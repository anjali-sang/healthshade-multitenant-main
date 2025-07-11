<?php

namespace App\Services;

use App\Mail\PurchaseOrderFailedMail;
use App\Models\Organization;
use App\Models\PurchaseOrder;
use App\Notifications\PurchaseOrderFailedNotification;
use App\Notifications\SystemNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Mail\PurchaseOrderMail;
use App\Models\BillToLocation;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\PurchaseOrderDetail;
use App\Models\ShipToLocation;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use \Mysqli;
use phpseclib3\Net\SFTP;

class EdiUploadService
{
    public function uploadPendingEdiFiles()
    {
        // Example: Only pick Henry Schein orders that are not yet uploaded
        $pos = PurchaseOrder::where('is_order_placed', false)
            ->whereHas('purchaseSupplier', fn($q) => $q->where('supplier_slug', 'henryschien'))
            ->get();

        foreach ($pos as $po) {
            $this->uploadEdiFile($po);
        }
    }

    public function uploadEdiFile(PurchaseOrder $po)
    {
        try {
            $response = $this->getPo850($po->purchase_oder_number);
            $responseData = json_decode($response->getContent(), true);
            if ($responseData['status'] == false) {
                $po->note = "Order is not Placed. Contact Support.";
                $po->is_order_placed = false;
            } else {
                $po->note = "Order placed Successful.";
                $po->is_order_placed = true;
            }
            $po->save();

        } catch (\Throwable $e) {
            Log::error("EDI Upload failed for PO: {$po->purchase_oder_number} — " . $e->getMessage());
        }
    }
    public function getPo850($reference_no)
    {
        Log::info('Entered Henry schein fnction....');
        $connection = new mysqli(
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            config('database.connections.mysql.port')
        );
        $query = "SELECT purchase_orders.id, purchase_orders.purchase_oder_number, purchase_orders.ship_to_location_id, purchase_orders.ship_to_number,purchase_orders.bill_to_number,purchase_orders.supplier_id, locations.name as warehouse_name, locations.address as warehouse_address, locations.state as warehouse_state, locations.city as warehouse_city, locations.pin as warehouse_postal_code, suppliers.supplier_phone as phone_number, units.unit_code, products.product_code as product_code, products.cost as product_cost, products.product_name as product_name, purchase_orders.created_at, purchase_order_details.quantity as qty FROM purchase_orders INNER JOIN locations ON purchase_orders.ship_to_location_id = locations.id INNER JOIN suppliers ON purchase_orders.supplier_id = suppliers.id INNER JOIN purchase_order_details ON purchase_orders.id = purchase_order_details.purchase_order_id INNER JOIN products ON purchase_order_details.product_id = products.id INNER JOIN units ON purchase_order_details.unit_id = units.id WHERE purchase_orders.purchase_oder_number = ?";

        $statement = $connection->prepare($query);
        $statement->bind_param("s", $reference_no);

        try {

            Log::info('Entered Try Block...');
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $isaSenderID = env('HEALTHSHADE_ISA_HEADER', '8179650267');
                $isaReceiverID = env('HS_ISA_HEADER', '012430880');
                $isaDateFormat = "ymd";
                $isaTimeFormat = "Hi";
                $segmentCount = 11;

                $isaHeader = "ISA*" . str_pad("00", 2, "0") . "*" . str_pad("", 10) . "*" . str_pad("00", 2, "0") . "*" . str_pad("", 10) . "*" . str_pad("12", 2) . "*" . str_pad($isaSenderID, 15) . "*" . str_pad("01", 2) . "*" . str_pad($isaReceiverID, 15) . "*" . Carbon::now()->format($isaDateFormat) . "*" . Carbon::now()->format($isaTimeFormat) . "*U*00401*000000001*0*P*<~\n";
                $ediBuilder = $isaHeader;
                $ediBuilder .= "GS*PO*" . $isaSenderID . "*" . $isaReceiverID . "*" . Carbon::now()->format("Ymd") . "*" . Carbon::now()->format("Hi") . "*1421*X*004010~\n";
                $ediBuilder .= "ST*850*0001~\n";

                while ($row = $result->fetch_assoc()) {
                    $createdAt = Carbon::parse($row["created_at"] ?? "");
                    $formattedDate = $createdAt->format("Ymd");

                    $ediBuilder .= "BEG*00*SA*" . $row["purchase_oder_number"] . "**" . $formattedDate . "~\n";
                    // $ediBuilder .= "REF*IT**~\r\n";
                    $ediBuilder .= "PER*OC**TE*" . $row["phone_number"] . "~\n";
                    $ediBuilder .= "N1*ST*" . $row["warehouse_name"] . "*91*" . $row["ship_to_number"] . "~\n";
                    $ediBuilder .= "N3*" . $row["warehouse_address"] . "*" . $row["warehouse_state"] . "~\n";
                    $ediBuilder .= "N4*" . $row["warehouse_city"] . "**" . $row["warehouse_postal_code"] . "~\n";
                    $ediBuilder .= "N1*BY*" . $row["warehouse_name"] . "*91*" . $row["bill_to_number"] . "~\n";
                    $ediBuilder .= "N3*" . $row["warehouse_address"] . "*" . $row["warehouse_state"] . "~\n";
                    $ediBuilder .= "N4*" . $row["warehouse_city"] . "**" . $row["warehouse_postal_code"] . "~\n";

                    $lineNum = 1;
                    do {
                        $ediBuilder .= "PO1*" . $lineNum . "*" . $row["qty"] . "*" . $row["unit_code"] . "*" . $row["product_cost"] . "**VC*" . $row["product_code"] . "~\n";
                        $ediBuilder .= "PID*F****" . $row["product_name"] . "~\n";
                        $lineNum++;
                        $segmentCount += 2;
                    } while ($row = $result->fetch_assoc());

                    $value = $lineNum - 1;
                    $ediBuilder .= "CTT*" . $value . "~\n";
                    $ediBuilder .= "SE*" . $segmentCount . "*0001~\n";
                    $ediBuilder .= "GE*1*1421~\n";
                    $ediBuilder .= "IEA*1*000000001~\n";

                    $ediString = $ediBuilder;

                    Log::info('EDI String is prepared...');
                    Log::info($ediString);
                    Log::info('Function call to upload850 made...');
                    $response = $this->upload850($ediString, $reference_no);
                    $responseData = json_decode($response->getContent(), true);
                    if ($responseData['status'] == false) {
                        return response()->json([
                            'status' => false,
                            'message' => $responseData['message']
                        ]);
                    } else {
                        return response()->json([
                            'status' => true,
                            'message' => $responseData['message']
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'message' => "No record found with custom ID: " . $reference_no,
                    'status' => false
                ]);
            }
        } catch (Exception $ex) {
            $notificationEmails = config('app.email');
            $message = "An error occurred while generating EDI file of Ref no: " . $reference_no . "  Error - " . $ex->getMessage();

            $purchaseOrder = PurchaseOrder::where('purchase_oder_number', $reference_no)->first();
            $organization = $purchaseOrder ? $purchaseOrder->organization : null;

            $notifier = new SystemNotifier($notificationEmails);

            if ($organization) {
                $notifier->notify(new PurchaseOrderFailedNotification(
                    $organization,
                    $message
                ));
            }

            return response()->json([
                'message' => $message,
                'status' => false
            ]);
        }
    }
    public function upload850($ediString, $reference_no)
    {
        try {
            Log::info('Entwred to upload850 made...');
            // Upload file to SFTP server
            $sftp = new SFTP(
                config('services.sftp.host'),
                config('services.sftp.port')
            );
            // SFTP server details
            if (
                $sftp->login(
                    config('services.sftp.username'),
                    config('services.sftp.password')
                )
            ) {
                $remotePath = "/inbound/EDI_" . $reference_no . ".850";
                Log::info($remotePath);
                if ($sftp->put($remotePath, $ediString)) {
                    // File uploaded successfully
                    return response()->json([
                        'message' => 'File uploaded successfully',
                        'status' => true
                    ]);
                } else {
                    // File upload failed
                    Log::info('File upload failed');
                    throw new Exception('File upload failed');
                }
            } else {
                // SFTP login failed
                Log::info('SFTP login failed');
                throw new Exception('SFTP login failed');
            }
        } catch (Exception $e) {
            Log::error('Error in upload850: ' . $e->getMessage() . $e->getLine());
            $notificationEmails = config('app.email');
            $message = "An error occurred while uploading EDI file of Ref no: " . $reference_no . "  Error - " . $e->getMessage();
            $purchaseOrder = PurchaseOrder::where('purchase_oder_number', $reference_no)->first();
            $organization = $purchaseOrder ? $purchaseOrder->organization : null;

            $notifier = new SystemNotifier($notificationEmails);

            if ($organization) {
                $notifier->notify(new PurchaseOrderFailedNotification(
                    $organization,
                    $message
                ));
            }

            return response()->json([
                'message' => $message,
                'status' => false
            ]);
        }
    }
}
