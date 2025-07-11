<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Location;
use App\Models\Organization;
use App\Models\PickingDetailsModel;
use App\Models\PurchaseOrder;
use App\Models\StockCount;
use App\Models\Supplier;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;

class HomeController extends Controller
{
    protected $stock_onhand;
    protected $value_onhand;
    protected $stock_to_receive;
    protected $pending_value;
    protected $locationId = '0';

    protected $organization_id;
    protected $low_on_stock;
    protected $product_avialable;
    protected $product_not_avialable;
    protected $total_products;
    protected $active_products;
    protected $low_stock_products_list = [];
    protected $get_low_stock_products_list;
    protected $get_products_not_avaialable_list;
    protected $products_not_avaialable_list = [];
    protected $ordered_status_count;
    protected $partial_status_count;
    protected $in_cart_count;
    protected $purchase_order_stats;
    protected $recent_purchase_orders_list = [];
    protected $get_recent_purchase_orders_list;
    protected $get_location_orgs;
    protected $locations_list;
    protected $org_list;

    protected $supplier_list = [];

    protected $get_supplier_data;

    protected $top_picks;



    public function repDashboard()
    {
        return view('medical_rep.dashboard');
    }

    public function index()
    {
        $this->stockOnhand();
        $this->valueOnhand();
        $this->stockToReceive();
        $this->pendingValue();
        $this->low_on_stock();
        $this->product_avialable();
        $this->product_not_avialable();
        $this->total_products();
        $this->total_products();
        $this->get_low_stock_products_list();
        $this->get_products_not_avaialable_list();
        $this->get_purchase_order_stats();
        $this->get_recent_purchase_orders_list();
        $this->get_location_orgs();
        $this->get_supplier_data();
        $this->topPickups();


        return view("dashboard.index", [
            'stock_onhand' => $this->stock_onhand,
            'value_onhand' => $this->value_onhand,
            'stock_to_receive' => $this->stock_to_receive,
            'pending_value' => $this->pending_value,
            'low_on_stock' => $this->low_on_stock,
            'product_avialable' => $this->product_avialable,
            'product_not_avialable' => $this->product_not_avialable,
            'total_products' => $this->total_products,
            'active_products' => $this->active_products,
            'low_stock_products_list' => $this->low_stock_products_list,
            'products_not_avaialable_list' => $this->get_products_not_avaialable_list,
            'ordered_status_count' => $this->ordered_status_count,
            'partial_status_count' => $this->partial_status_count,
            'in_cart_count' => $this->in_cart_count,
            'recent_purchase_orders_list' => $this->recent_purchase_orders_list,
            'locations_list' => $this->locations_list,
            'org_list' => $this->org_list,
            'supplier_list' => $this->supplier_list,
            'top_picks' => $this->top_picks
        ]);
    }

    public function get_supplier_data()
    {
        $user = auth()->user();
        $query = Supplier::where('is_active', true)->leftJoin('purchase_orders', function ($join) use ($user) {
            $join->on('suppliers.id', '=', 'purchase_orders.supplier_id');
            if ($user->role_id == 1 && $this->organization_id > 0) {
                $join->where('purchase_orders.organization_id', $this->organization_id);
            } elseif ($user->role_id == 2) {
                $join->where('purchase_orders.organization_id', $user->organization_id);
                if ($this->locationId > 0) {
                    $join->where('purchase_orders.location_id', $this->locationId);
                }
            } elseif ($user->role_id == 3) {
                $join->where('purchase_orders.location_id', $user->location_id);
            }
        });

        $this->supplier_list = $query->select(
            'suppliers.id',
            'suppliers.supplier_name',
            DB::raw('COALESCE(SUM(purchase_orders.total), 0) as total_cost')
        )
            ->groupBy('suppliers.id', 'suppliers.supplier_name')
            ->get();
    }


    public function updateDashboard(Request $request)
    {
        $this->locationId = $request->location_id;
        $this->organization_id = $request->organization_id;

        $this->stockOnhand();
        $this->valueOnhand();
        $this->stockToReceive();
        $this->pendingValue();
        $this->low_on_stock();
        $this->product_avialable();
        $this->product_not_avialable();
        $this->total_products();
        $this->total_products();
        $this->get_low_stock_products_list();
        $this->get_products_not_avaialable_list();
        $this->get_purchase_order_stats();
        $this->get_recent_purchase_orders_list();
        $this->get_location_orgs();
        $this->get_supplier_data();

        return [
            'stock_onhand' => $this->stock_onhand,
            'value_onhand' => $this->value_onhand,
            'stock_to_receive' => $this->stock_to_receive,
            'pending_value' => $this->pending_value,
            'low_on_stock' => $this->low_on_stock,
            'product_avialable' => $this->product_avialable,
            'product_not_avialable' => $this->product_not_avialable,
            'total_products' => $this->total_products,
            'active_products' => $this->active_products,
            'low_stock_products_list' => $this->low_stock_products_list,
            'products_not_avaialable_list' => $this->get_products_not_avaialable_list,
            'ordered_status_count' => $this->ordered_status_count,
            'partial_status_count' => $this->partial_status_count,
            'in_cart_count' => $this->in_cart_count,
            'recent_purchase_orders_list' => $this->recent_purchase_orders_list,
            'locations_list' => $this->locations_list,
            'org_list' => $this->org_list,
            'supplier_list' => $this->supplier_list,
        ];
    }

    public function get_location_orgs()
    {
        $user = auth()->user();

        $query = Location::where('is_active', true);

        if ($user->role_id != 1) {
            $query->where('org_id', $user->organization_id);
        }
        $this->locations_list = $query->get();
        $this->org_list = Organization::where('is_active', true)->get();
    }


    public function get_recent_purchase_orders_list()
    {
        $user = auth()->user();

        $query = PurchaseOrder::query()->with([
            'purchaseSupplier',
            'purchaseLocation'
        ]);

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }
        if ($user->role_id != 1) {
            $query->where('organization_id', $user->organization_id);
        }

        // Fetch latest 5 records
        $this->recent_purchase_orders_list = $query->latest()->take(5)->where('status', 'completed')->get();
    }

    public function get_purchase_order_stats()
    {
        $user = auth()->user();
        $query = PurchaseOrder::query();

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }
        if ($user->role_id != 1) {
            $query->where('organization_id', $user->organization_id);
        }
        if ($user->role_id == 3) {
            $query->where('location_id', $user->location_id);
        }
        $this->ordered_status_count = (clone $query)->where('status', 'ordered')->count();
        $this->partial_status_count = (clone $query)->where('status', 'partial')->count();

        $carts = Cart::query();
        if ($user->role_id != 1) {
            $query->where('organization_id', $user->organization_id);
        }
        if ($this->locationId != '0') {
            $carts->where('location_id', $this->locationId);
        }

        $carts = $carts->get();
        $groupedBySupplier = $carts->groupBy(fn($cart) => optional($cart->product)->product_supplier_id);
        $this->in_cart_count = $groupedBySupplier->count();
    }

    public function stockOnhand()
    {
        $user = auth()->user();
        $query = StockCount::query();

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }
        if ($user->role_id != 1) {
            $query->where('organization_id', $user->organization_id);
        }
        if ($user->role_id == 3) {
            $query->where('location_id', $user->location_id);
        }

        $this->stock_onhand = $query->sum('on_hand_quantity');
    }

    public function valueOnhand()
    {
        $user = auth()->user();
        $query = StockCount::query();

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }
        if ($user->role_id != 1) {
            $query->where('organization_id', $user->organization_id);
        }

        $this->value_onhand = $query->with('product')
            ->get()
            ->sum(fn($row) => $row->on_hand_quantity * ($row->product->price ?? 0));
        logger($this->value_onhand);
    }

    public function stockToReceive()
    {
        $user = auth()->user();
        $query = PurchaseOrder::join('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
            ->where('purchase_orders.organization_id', $user->organization_id)
            ->whereNotIn('purchase_orders.status', ['completed', 'canceled']);

        if ($this->locationId != '0') {
            $query->where('purchase_orders.location_id', $this->locationId);
        }
        if ($user->role_id != 1) {
            $query->where('organization_id', $user->organization_id);
        }


        $this->stock_to_receive = $query->sum(\DB::raw('quantity - received_quantity'));
    }

    public function pendingValue()
    {
        $user = auth()->user();

        $query = PurchaseOrder::query()
            ->where('organization_id', $user->organization_id)
            ->whereNotIn('status', ['completed', 'canceled']);

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }

        // This condition is now redundant since we already filter by organization_id above
        // if ($user->role_id != 1) {
        //     $query->where('organization_id', $user->organization_id);
        // }

        $orders = $query->with('purchasedProducts.product')->get();

        $pendingValue = $orders->sum(function ($order) {
            return $order->purchasedProducts->sum(function ($product) {
                $price = $product->product->price ?? 0;
                return ($product->quantity - $product->received_quantity) * $price;
            });
        });
        $this->pending_value = number_format($pendingValue, 2, '.', '');
        logger($pendingValue);
    }


    public function low_on_stock()
    {
        $query = StockCount::query();

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }
        if (auth()->user()->role_id != 1) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        $this->low_on_stock = $query->where('on_hand_quantity', '<', 'alert_quantity')->where('on_hand_quantity', '>', '0')->count();
    }

    public function product_avialable()
    {
        $query = StockCount::query();

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }

        if (auth()->user()->role_id != 1) {
            $query->where('organization_id', auth()->user()->organization_id);
        }
        if (auth()->user()->role_id == 3) {
            $query->where('location_id', auth()->user()->location_id);
        }

        $this->product_avialable = $query->where('on_hand_quantity', '>', '0')->count();
    }

    public function product_not_avialable()
    {
        $query = StockCount::query();

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }
        if (auth()->user()->role_id != 1) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        if (auth()->user()->role_id == 3) {
            $query->where('location_id', auth()->user()->location_id);
        }

        $this->product_not_avialable = $query->where('on_hand_quantity', '<=', '0')->count();
    }

    public function total_products()
    {
        $query = StockCount::query();

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }
        if (auth()->user()->role_id != 1) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        if (auth()->user()->role_id == 3) {
            $query->where('location_id', auth()->user()->location_id);
        }

        $this->total_products = $query->count() == 0 ? 1 : $query->count();

        $this->active_products = ($this->product_avialable / $this->total_products) * 100;
    }

    public function get_low_stock_products_list()
    {
        $query = StockCount::query()
            ->whereNotNull('alert_quantity')
            ->whereRaw('CAST(on_hand_quantity AS DECIMAL(10,2)) <= CAST(alert_quantity AS DECIMAL(10,2))')
            ->with(['location', 'product.supplier'])
            ->orderBy('on_hand_quantity', 'desc');

        if (auth()->user()->role_id != 1) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }

        $this->low_stock_products_list = $query->take(5)->get();

    }

    public function get_products_not_avaialable_list()
    {
        $query = StockCount::query()
            ->where('on_hand_quantity', 0)
            ->whereHas('product')
            ->with('product');

        if (auth()->user()->role_id != 1) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        if ($this->locationId != '0') {
            $query->where('location_id', $this->locationId);
        }

        if (auth()->user()->role_id == 3) {
            $query->where('location_id', auth()->user()->location_id);
        }

        $this->products_not_avaialable_list = $query->get();
    }

    public function topPickups()
    {
        $query = PickingDetailsModel::select(
            'picking_details.product_id',
            'locations.name as location_name',
            'picking_details.picking_unit',
            DB::raw('SUM(picking_details.picking_quantity) as total_picked_qty')
        )
            ->join('pickings', 'picking_details.picking_id', '=', 'pickings.id')
            ->join('locations', 'pickings.location_id', '=', 'locations.id')
            ->with(['product.supplier'])
            ->groupBy('picking_details.product_id', 'locations.name')
            ->orderByDesc(DB::raw('SUM(picking_details.picking_quantity)'));

        // Apply org filter from `pickings` table
        if (auth()->user()->role_id != 1) {
            $query->where('pickings.organization_id', auth()->user()->organization_id);
        }

        // Apply location filter from `pickings` table
        if ($this->locationId != '0') {
            $query->where('pickings.location_id', $this->locationId);
        }

        $this->top_picks = $query->take(10)->get();
    }

    public function barCharData($organization_id, $location_id)
    {
        $currentDate = Carbon::now();
        $sixMonthsAgo = $currentDate->copy()->subMonths(5);
        $monthlyTotals = [];
        if (auth()->user()->role_id == 1) {
        } elseif (auth()->user()->role_id == 2) {
            $organization_id = auth()->user()->organization_id;
        } else {
            $organization_id = auth()->user()->organization_id;
            $location_id = auth()->user()->location_id;
        }

        for ($i = 5; $i >= 0; $i--) {
            $startDate = $sixMonthsAgo->copy()->addMonths($i)->startOfMonth();
            $endDate = $sixMonthsAgo->copy()->addMonths($i)->endOfMonth();

            $query = PurchaseOrder::whereBetween('created_at', [$startDate, $endDate]);

            if (auth()->user()->role_id == 1 && $organization_id == 0) {
            } else {
                $query->where('organization_id', $organization_id);
            }

            if ($location_id != 0) {
                $query->where('location_id', $location_id);
            }
            $totalPurchaseCost = $query->sum('total');
            $monthlyTotals[] = [
                'month' => $startDate->format('F'),
                'total_purchase_cost' => $totalPurchaseCost,
            ];
        }

        return $monthlyTotals;
    }


}
