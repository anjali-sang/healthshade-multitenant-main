<?php

namespace App\Livewire\Organization;

use App\Mail\PurchaseOrderMail;
use App\Models\BillToLocation;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\ShipToLocation;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Log;
use \Mysqli;
use phpseclib3\Net\SFTP;

class CartComponent extends Component
{
    public $cartItems = [];

    public $organization_id = null;

    public $user = null;
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;
    public $units = [];

    public $billingLocations = [];
    public $selectedBillingLocation = null;
    public $shippingLocations = [];
    public $selectedShippingLocation = null;

    public $locations = [];
    public $selectedLocation = null;

    public $notifications = [];

    public $selectedLocationName = null;

    public $added_by = '';
    public $unit_id = [];
    public $quantity;

    protected $listeners = ['load-cart' => 'loadCart'];

    public function mount()
    {
        $this->user = auth()->user();
        $this->organization_id = $this->user->organization_id;
        $this->billingLocations = Location::where('org_id', $this->organization_id)->where('is_active', true)->get();
        $this->shippingLocations = Location::where('org_id', $this->organization_id)->where('is_active', true)->get();
        $this->loadLocations();
        $this->selectedBillingLocation = Location::where('org_id', $this->organization_id)
            ->where('is_default', true)->where('is_active', true)->first();

    }

    public function loadLocations()
    {
        $user = auth()->user();
        $role = $user->role;
        if ($role?->hasPermission('all_location_cart') || $user->role_id <= 2 ) {
            $this->locations = Location::where('org_id', $user->organization_id)->where('is_active', true)->get();
            $this->selectedLocation = $user->location_id ?? $this->locations->first()->id;
            $this->selectedLocationName = $this->locations->firstWhere('id', $this->selectedLocation)->name ?? null;
        }else{
            $this->locations = Location::where('id', $user->location_id)->where('is_active', true)->get();
            $this->selectedLocation = $user->location_id ?? $this->locations->first()->id;
            $this->selectedLocationName = $this->locations->firstWhere('id', $this->selectedLocation)->name ?? null;
        }
        $this->loadCart();
    }

    public function updateLocation()
    {
        $this->selectedShippingLocation = $this->selectedLocation;
        $this->selectedLocationName = $this->locations->firstWhere('id', $this->selectedLocation)->name ?? null;
        $this->loadCart();
    }

    public function loadCart()
    {
        $query = Cart::with(['product.supplier', 'product.units.unit'])
            ->where('organization_id', auth()->user()->organization_id);

        // Add location filter if a location is selected
        if ($this->selectedLocation) {
            $query->where('location_id', $this->selectedLocation);
        }

        $this->cartItems = $query->get()
            ->map(function ($cartItem) {
                return [
                    'id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'unit_id' => $cartItem->unit_id,
                    'product' => [
                        'id' => $cartItem->product->id,
                        'name' => $cartItem->product->product_name,
                        'code' => $cartItem->product->product_code,
                        'base_price' => $cartItem->product->product_price,
                        'image' => $cartItem->product->image,
                        'supplier' => [
                            'id' => $cartItem->product->supplier->id,
                            'name' => $cartItem->product->supplier->supplier_name
                        ],
                        'units' => $cartItem->product->units->map(function ($unit) {
                            return [
                                'unit_id' => $unit->unit_id,
                                'unit_name' => $unit->unit->unit_name,
                                'unit_code' => $unit->unit->unit_code,
                                'is_base_unit' => $unit->is_base_unit,
                                'operator' => $unit->operator,
                                'conversion_factor' => $unit->conversion_factor
                            ];
                        })->values()->all()
                    ]
                ];
            })->values()->all();

        foreach ($this->cartItems as $item) {
            $this->unit_id[$item['id']] = $item['unit_id'];
        }
        $this->calculateTotals();
    }
    public function updateUnitPrice($cartItemId, $productId, $unitId)
    {
        $cartItem = Cart::find($cartItemId);
        $productUnits = ProductUnit::where('product_id', $productId)->get();
        // Find the current unit and the target unit
        $targetUnit = $productUnits->firstWhere('unit_id', $unitId);
        if (!$targetUnit) {
            return;
        }
        // Get base price
        $basePrice = $cartItem->product->cost;
        $selectedUnitPrice = $targetUnit->operator === 'multiply'
            ? $basePrice * $targetUnit->conversion_factor
            : $basePrice / $targetUnit->conversion_factor;
        // Update cart item with new unit and price
        $cartItem->update([
            'unit_id' => $unitId,
            'price' => $selectedUnitPrice * $cartItem->quantity
        ]);
        $this->loadCart();
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if ($quantity > 0) {
            $cartItem = Cart::find($cartItemId);

            if ($cartItem) {
                $oldQuantity = $cartItem->quantity;
                $oldPrice = $cartItem->price;

                // Calculate new price using (old price / old quantity) * new quantity
                $newPrice = ($oldPrice / $oldQuantity) * $quantity;

                $cartItem->update([
                    'quantity' => $quantity,
                    'price' => $newPrice
                ]);

                $this->loadCart();
            }
        }
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->cartItems)->sum(function ($item) {
            return $item['price'];
        });

        $this->tax = 0;
        $this->total = $this->subtotal + $this->tax;
    }

    public function removeItem($cartItemId)
    {
        $event = 'Removed';
        $message = 'Following Product is removed from cart';
        $auditService = app(\App\Services\InventoryAuditService::class);
        $auditService->logCartChanges(
            $cartItemId,
            $event,
            $message
        );

        Cart::find($cartItemId)->delete();

        $this->loadCart();
    }

    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type,
        ]);
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(
            array_filter($this->notifications, function ($notification) use ($id) {
                return $notification['id'] !== $id;
            }),
        );
    }

    public function createPurchaseOrder()
    {

        $user = auth()->user();
        $role = $user->role;
        // Case 1: User has 'approve_all_cart' permission OR is an admin-level role (role_id <= 2)
        $hasApproveAllPermission = $role?->hasPermission('approve_all_cart') || $user->role_id <= 2;
        // Case 2: User has 'approve_own_cart' permission AND is at the matching location
        $hasApproveOwnPermission = $role?->hasPermission('approve_own_cart') && $user->location_id == $this->selectedLocation;
        // If neither permission condition is met, deny access
        if (!$hasApproveAllPermission && !$hasApproveOwnPermission) {
            $this->addNotification('You do not have permission to create a purchase order.', 'danger');
            return;
        }

        if ($this->selectedShippingLocation == null || $this->selectedShippingLocation == '0') {
            $this->addNotification('Shipping location is not provided.', 'danger');
            return;
        }

        if ($this->selectedBillingLocation == null || $this->selectedBillingLocation == '0') {
            $this->addNotification('Billing location is not provided. Contact support!', 'danger');
            return;
        }
        // Get all products from the cart for the current organization and location
        $carts = Cart::where('organization_id', $this->organization_id)
            ->where('location_id', $this->selectedLocation)
            ->get();
        if ($carts->isEmpty()) {
            $this->addNotification('Cart is empty. Cannot create a purchase order.', 'danger');
            return;
        }

        // Group cart items by supplier
        $groupedBySupplier = $carts->groupBy(fn($cart) => $cart->product->product_supplier_id ?? null);

        foreach ($groupedBySupplier as $supplierId => $cartItems) {
            if (!$supplierId) {
                $this->addNotification('Some products do not have a supplier assigned.', 'danger');
                continue;
            }
            $supplier = Supplier::where('id', $supplierId)
                ->where('is_active', true) // Ensure supplier is active
                ->first();

            if (!$supplier) {
                $this->addNotification("Supplier with ID {$supplierId} is either inactive or not found.", 'danger');
                continue;
            }

            // Check for billing location
            $billToLocation = $this->selectedBillingLocation;
            $billToLocationSupplier = BillToLocation::where('location_id', $billToLocation->id)
                ->where('supplier_id', $supplier->id)
                ->first();

            if (!$billToLocationSupplier) {
                $this->addNotification("Billing information is missing for {$supplier->supplier_name}. Contact Support !", 'danger');
                continue;
            }

            // Check for shipping location
            $shipToLocation = Location::where('id', $this->selectedShippingLocation)
                ->first();


            $shipToLocationSupplier = ShipToLocation::where('location_id', $shipToLocation->id)
                ->where('supplier_id', $supplier->id)
                ->first();
            if (!$shipToLocationSupplier) {
                $this->addNotification("Shipping information is missing for supplier {$supplier->supplier_name}.", 'danger');
                continue;
            }
            try {
                // Start a database transaction for this supplier
                DB::beginTransaction();
                $poNumber = PurchaseOrder::generatePurchaseOrderNumber();
                // Create a new purchase order for this supplier
                $purchaseOrder = PurchaseOrder::create([
                    'purchase_oder_number' => $poNumber,
                    'supplier_id' => $supplierId,
                    'organization_id' => $this->organization_id,
                    'location_id' => $this->selectedLocation,
                    'bill_to_location_id' => $billToLocation->id,
                    'ship_to_location_id' => $shipToLocation->id,
                    'created_by' => $this->user->id,
                    'updated_by' => $this->user->id,
                    'status' => 'ordered',
                    'total' => $cartItems->sum('price'),
                    'note' => 'Waiting for Supplier\'s confirmation',
                    'bill_to_number' => $billToLocationSupplier->bill_to,
                    'ship_to_number' => $shipToLocationSupplier->ship_to
                ]);
                $auditService = app(\App\Services\PurchaseOrderAuditService::class);
                // Attach products to the purchase order
                foreach ($cartItems as $cart) {
                    PurchaseOrderDetail::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $cart->product->id,
                        'quantity' => $cart->quantity,
                        'sub_total' => $cart->price,
                        'unit_id' => $cart->unit_id,
                    ]);
                    $auditService->logPurchaseOrderCreation($purchaseOrder, [
                        'product_id' => $cart->product->id,
                        'quantity' => $cart->quantity,
                        'sub_total' => $cart->price,
                        'unit_id' => $cart->unit_id,
                    ]);
                }

                // Clear cart for this supplier after PO is successfully created
                Cart::where('organization_id', $this->organization_id)
                    ->where('location_id', $this->selectedLocation)
                    ->whereIn('id', $cartItems->pluck('id'))
                    ->delete();

                // Commit transaction for this supplier
                DB::commit();

                $supplier = Supplier::find($supplierId);

            } catch (\Throwable $e) {
                Log::error($e->getMessage());
                DB::rollBack();
                if (isset($purchaseOrder)) {
                    if (PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)->exists()) {
                        PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)->delete();
                    }
                    $purchaseOrder->delete();
                }
                $this->addNotification("Failed to create Purchase Order for supplier {$supplier->supplier_name}", 'danger');
            }
            if (auth()->user()->organization->subscription->name == 'free trial') {
                $this->addNotification("You purchase orders will not go to suppliers in free trial subscription.", 'danger');
                $purchaseOrder->note = "Your Current plan do not support EDI/EMAIL integration with {$supplier->supplier_name}";
                $purchaseOrder->is_order_placed = true;
                $purchaseOrder->save();
            } else if ($supplier && $supplier->supplier_email && $supplier->supplier_slug != 'henryschien') {
                try {
                    // $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_id', $purchaseOrder->id)
                    //     ->with('product')
                    //     ->get();
                    // $ccUsers = User::where('organization_id', auth()->user()->organization_id)
                    //     ->where('role_id', 2)
                    //     ->whereNotNull('email')
                    //     ->pluck('email')
                    //     ->toArray();
                    // $ccUsers[] = 'support@healthshade.com';
                    // Mail::to($supplier->supplier_email)
                    //     ->cc($ccUsers)
                    //     ->send(new PurchaseOrderMail($purchaseOrder, $supplier, $purchaseOrderDetails));
                    // $purchaseOrder->is_order_placed = true;
                    $purchaseOrder->note = "Order is being processed and will be placed shortly.";
                } catch (Exception $e) {
                    logger("Email sending failed: " . $e->getMessage());
                    $purchaseOrder->is_order_placed = false;
                    $purchaseOrder->note = "Order is not placed to supplier. Please contact support.";
                }
                $purchaseOrder->save();
            } elseif ($supplier && $supplier->supplier_slug == 'henryschien') {
                // logger("Supplier is henry schein");
                // $response = $this->getPo850($poNumber);
                // $responseData = json_decode($response->getContent(), true);
                // if ($responseData['status'] == false) {
                //     $purchaseOrder->note = "Order is not placed to supplier. Please contact support.";
                // } else {
                //     $purchaseOrder->note = "Order is placed to supplier. Waiting for Acknowledgement.";
                // }
                $purchaseOrder->note = "Order is being processed and will be placed shortly.";
                $purchaseOrder->save();
            }
            $this->addNotification("Purchase Order created successfully for supplier {$supplier->supplier_name}.", 'success');
        }
        $carts = Cart::where('organization_id', $this->organization_id)
            ->where('location_id', $this->selectedLocation)
            ->get();
        if ($carts->isEmpty()) {
            return redirect('/purchase');
        }

    }

    // public function getPo850($reference_no)
    // {
    //     Log::info('Entered Henry schein fnction....');
    //     $connection = new mysqli(
    //         config('database.connections.mysql.host'),
    //         config('database.connections.mysql.username'),
    //         config('database.connections.mysql.password'),
    //         config('database.connections.mysql.database'),
    //         config('database.connections.mysql.port')
    //     );
    //     $query = "SELECT purchase_orders.id, purchase_orders.purchase_oder_number, purchase_orders.ship_to_location_id, purchase_orders.ship_to_number,purchase_orders.bill_to_number,purchase_orders.supplier_id, locations.name as warehouse_name, locations.address as warehouse_address, locations.state as warehouse_state, locations.city as warehouse_city, locations.pin as warehouse_postal_code, suppliers.supplier_phone as phone_number, units.unit_code, products.product_code as product_code, products.cost as product_cost, products.product_name as product_name, purchase_orders.created_at, purchase_order_details.quantity as qty FROM purchase_orders INNER JOIN locations ON purchase_orders.ship_to_location_id = locations.id INNER JOIN suppliers ON purchase_orders.supplier_id = suppliers.id INNER JOIN purchase_order_details ON purchase_orders.id = purchase_order_details.purchase_order_id INNER JOIN products ON purchase_order_details.product_id = products.id INNER JOIN units ON purchase_order_details.unit_id = units.id WHERE purchase_orders.purchase_oder_number = ?";

    //     $statement = $connection->prepare($query);
    //     $statement->bind_param("s", $reference_no);

    //     try {

    //         Log::info('Entered Try Block...');
    //         $statement->execute();
    //         $result = $statement->get_result();

    //         if ($result->num_rows > 0) {
    //             $isaSenderID = env('HEALTHSHADE_ISA_HEADER', '8179650267');
    //             $isaReceiverID = env('HS_ISA_HEADER', '012430880');
    //             $isaDateFormat = "ymd";
    //             $isaTimeFormat = "Hi";
    //             $segmentCount = 11;

    //             $isaHeader = "ISA*" . str_pad("00", 2, "0") . "*" . str_pad("", 10) . "*" . str_pad("00", 2, "0") . "*" . str_pad("", 10) . "*" . str_pad("12", 2) . "*" . str_pad($isaSenderID, 15) . "*" . str_pad("01", 2) . "*" . str_pad($isaReceiverID, 15) . "*" . Carbon::now()->format($isaDateFormat) . "*" . Carbon::now()->format($isaTimeFormat) . "*U*00401*000000001*0*P*<~\n";
    //             $ediBuilder = $isaHeader;
    //             $ediBuilder .= "GS*PO*" . $isaSenderID . "*" . $isaReceiverID . "*" . Carbon::now()->format("Ymd") . "*" . Carbon::now()->format("Hi") . "*1421*X*004010~\n";
    //             $ediBuilder .= "ST*850*0001~\n";

    //             while ($row = $result->fetch_assoc()) {
    //                 $createdAt = Carbon::parse($row["created_at"] ?? "");
    //                 $formattedDate = $createdAt->format("Ymd");

    //                 $ediBuilder .= "BEG*00*SA*" . $row["purchase_oder_number"] . "**" . $formattedDate . "~\n";
    //                 // $ediBuilder .= "REF*IT**~\r\n";
    //                 $ediBuilder .= "PER*OC**TE*" . $row["phone_number"] . "~\n";
    //                 $ediBuilder .= "N1*ST*" . $row["warehouse_name"] . "*91*" . $row["ship_to_number"] . "~\n";
    //                 $ediBuilder .= "N3*" . $row["warehouse_address"] . "*" . $row["warehouse_state"] . "~\n";
    //                 $ediBuilder .= "N4*" . $row["warehouse_city"] . "**" . $row["warehouse_postal_code"] . "~\n";
    //                 $ediBuilder .= "N1*BY*" . $row["warehouse_name"] . "*91*" . $row["bill_to_number"] . "~\n";
    //                 $ediBuilder .= "N3*" . $row["warehouse_address"] . "*" . $row["warehouse_state"] . "~\n";
    //                 $ediBuilder .= "N4*" . $row["warehouse_city"] . "**" . $row["warehouse_postal_code"] . "~\n";

    //                 $lineNum = 1;
    //                 do {
    //                     $ediBuilder .= "PO1*" . $lineNum . "*" . $row["qty"] . "*" . $row["unit_code"] . "*" . $row["product_cost"] . "**VC*" . $row["product_code"] . "~\n";
    //                     $ediBuilder .= "PID*F****" . $row["product_name"] . "~\n";
    //                     $lineNum++;
    //                     $segmentCount += 2;
    //                 } while ($row = $result->fetch_assoc());

    //                 $value = $lineNum - 1;
    //                 $ediBuilder .= "CTT*" . $value . "~\n";
    //                 $ediBuilder .= "SE*" . $segmentCount . "*0001~\n";
    //                 $ediBuilder .= "GE*1*1421~\n";
    //                 $ediBuilder .= "IEA*1*000000001~\n";

    //                 $ediString = $ediBuilder;

    //                 Log::info('EDI String is prepared...');
    //                 Log::info($ediString);
    //                 Log::info('Function call to upload850 made...');
    //                 $response = $this->upload850($ediString, $reference_no);
    //                 $responseData = json_decode($response->getContent(), true);
    //                 if ($responseData['status'] == false) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => $responseData['message']
    //                     ]);
    //                 } else {
    //                     return response()->json([
    //                         'status' => true,
    //                         'message' => $responseData['message']
    //                     ]);
    //                 }
    //             }
    //         } else {
    //             return response()->json([
    //                 'message' => "No record found with custom ID: " . $reference_no,
    //                 'status' => false
    //             ]);
    //         }
    //     } catch (Exception $ex) {
    //         return response()->json([
    //             'message' => "An error occurred while generating EDI file of Ref no: " . $reference_no . "  Error - " . $ex->getMessage(),
    //             'status' => false
    //         ]);
    //     }
    // }

    // public function upload850($ediString, $reference_no)
    // {
    //     try {
    //         Log::info('Entwred to upload850 made...');
    //         // Upload file to SFTP server
    //         $sftp = new SFTP(
    //             env('HS_SFTP_EMAIL'),
    //             env('HS_SFTP_PORT')
    //         );
    //         // SFTP server details
    //         if (
    //             $sftp->login(
    //                 env('HS_USERNAME'),
    //                 env('HS_PASSWORD')
    //             )
    //         ) {
    //             $remotePath = "/inbound/EDI_" . $reference_no . ".850";
    //             Log::info($remotePath);
    //             if ($sftp->put($remotePath, $ediString)) {
    //                 // File uploaded successfully
    //                 return response()->json([
    //                     'message' => 'File uploaded successfully',
    //                     'status' => true
    //                 ]);
    //             } else {
    //                 // File upload failed
    //                 Log::info('File upload failed');
    //                 throw new Exception('File upload failed');
    //             }
    //         } else {
    //             // SFTP login failed
    //             Log::info('SFTP login failed');
    //             throw new Exception('SFTP login failed');
    //         }
    //     } catch (Exception $e) {
    //         Log::error('Error in upload850: ' . $e->getMessage() . $e->getLine());
    //         return response()->json([
    //             'message' => $e->getMessage(),
    //             'status' => false
    //         ], 500);
    //     }
    // }

    public function render()
    {
        return view('livewire.organization.cart-component');
    }
}