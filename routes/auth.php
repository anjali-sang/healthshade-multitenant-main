<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryManagementController;
use App\Http\Controllers\MedicalRepOrganizationController;
use App\Http\Controllers\MedicalRepSalesController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PickingController;
use App\Http\Controllers\PotentialUsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UnitController;
use App\Models\MedicalRep;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController as StripeWebhookController;





Route::post('/stripe/webhook', [SubscriptionController::class, 'handleWebhook']);


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Create shipment
    Route::post('/shipping/create', [ShippingController::class, 'createShipment'])->name('shipping.create');
    
    // Get shipment details
    Route::get('/shipping/sale/{saleId}', [ShippingController::class, 'getShipment'])->name('shipping.get');
    
    // Track shipment
    Route::post('/shipping/track', [ShippingController::class, 'trackShipment'])->name('shipping.track');
    
    // Get all shipments
    Route::get('/shipping/all', [ShippingController::class, 'getAllShipments'])->name('shipping.all');
    
    // Download shipping label
    Route::get('/shipping/label/{shipmentId}', [ShippingController::class, 'downloadLabel'])->name('shipping.label');
});

Route::middleware('guest:web')->group(function () {
    // Registration Routes
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('check-email', [RegisteredUserController::class, 'checkEmail'])->name('check-email');
    Route::post('verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('verify-otp');

    // Login Routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password Reset Routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});



Route::middleware(['auth:web'])->group(callback: function () {
    Route::get('/pricing', [SubscriptionController::class, 'showPricing'])->name('pricing');
    Route::post('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('billing.portal');
});


Route::middleware(['auth:web', 'check.organization', 'check.subscription'])->group(function () {

    Route::get('/potential-users', [PotentialUsersController::class, 'index'])->name('potential-users.index');
    /*
    |--------------------------------------------------------------------------
    | Email Verification Routes
    |--------------------------------------------------------------------------
    */
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    /*
    |--------------------------------------------------------------------------
    | Password & Authentication Management Routes
    |--------------------------------------------------------------------------
    */
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard Routes
    |--------------------------------------------------------------------------
    */
    // Route::get('/dashboard', [HomeController::class, 'index'])
    //     ->middleware(['verified'])
    //     ->name('dashboard');
    // Route::get('/apex-bar-chart/{organization_id}/{location_id}', [HomeController::class, 'barCharData']);
    // Route::get('/update_dashboard/{organization_id}/{location_id}', [HomeController::class, 'updateDashboard']);

    /*
    |--------------------------------------------------------------------------
    | Organization & Location Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('organization')->group(function () {
        Route::get('/create', [OrganizationController::class, 'create'])->name('organization.create');
        Route::post('/products', [ProductController::class, 'importProducts'])->name('import.products');
        Route::post('/import/catalog', [ProductController::class, 'importCatalog'])->name('import.catalog');
    });

    Route::get('/get-locations/{organizationId}', [OrganizationController::class, 'get_locations'])->name('get-locations');

    /*
    |--------------------------------------------------------------------------
    | Product Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('organization')->group(function () {

        Route::get('/inventory', [ProductController::class, 'OrganizationInventory'])->name('organization.inventory');
        Route::get('/catalog', [ProductController::class, 'OrganizationCatalog'])->name('organization.catalog');
    });
    Route::get('admin/products', [ProductController::class, 'adminProducts'])->name('admin.products.index');

    /*
    |--------------------------------------------------------------------------
    | Inventory & Operations Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/picking', [PickingController::class, 'index'])->name('picking.index');



    /*
    |--------------------------------------------------------------------------
    | E-commerce Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::get('/billing/{organization_id}', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/{organization_id}', [BillingController::class, 'billingUpdate'])->name('billing.update');
    Route::post('/shipping/{organization_id}', [BillingController::class, 'shippingUpdate'])->name('shipping.update');

    Route::get('/fedex-shipping', [ShippingController::class, 'createShipment'])->name('fedex.shipping');

    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.index');

    Route::post('/purchase-orders/{id}/reorder', [PurchaseController::class, 'reorder'])->name('purchase-orders.reorder');


    Route::get('user/tickets', [TicketController::class, 'index'])->name('ticket.index');

    // // Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['verified'])->name('dashboard');
    // Route::get('/medical_rep/dashboard', [HomeController::class, 'repDashboard'])->middleware(['verified'])->name('medical_rep.dashboard');
    // Route::get('/medical_rep/organizations', [MedicalRepOrganizationController::class, 'index'])->middleware(['verified'])->name('medical_rep.organizations');

    // Route::post('/medical-rep/organization/{org}/request-access', [MedicalRepOrganizationController::class, 'requestAccess'])
    //     ->name('medical_rep.organization.request_access');

    // Route::get('/medical-rep/organization/{id}', [MedicalRepOrganizationController::class, 'viewOrganization'])->name('medical_rep.organization.view');

    // Route::get('/medical-rep/sales', [MedicalRepSalesController::class, 'index'])->name('medical_rep.sales');

    // Route::post('/medical-rep/request/{id}/approve', [MedicalRepOrganizationController::class, 'approveRequest'])
    //     ->name('medical_rep.organization.request.approve');

    // Route::post('/medical-rep/request/{id}/reject', [MedicalRepOrganizationController::class, 'rejectRequest'])
    //     ->name('medical_rep.organization.request.reject');


    // Route::get('/my-requests', [MedicalRepOrganizationController::class, 'myRequests'])
    //     ->name('medical_rep.requests')
    //     ->middleware('auth');


    Route::get('/apex-bar-chart/{organization_id}/{location_id}', [HomeController::class, 'barCharData']);


    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.index');

    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');
    Route::post('delivery/{id}/calculate/shipment', [ShippingController::class, 'calculateShipment']);

    Route::get('/purchase-order/{id}/download-invoice', [PurchaseController::class, 'downloadInvoice'])
        ->name('download.invoice');

    Route::get('/purchase-order/{id}/download-acknowledgment', [PurchaseController::class, 'downloadAcknowledgment'])
        ->name('download.acknowledgment');

    Route::get('/purchase-order/{id}/preview-invoice', [PurchaseController::class, 'previewInvoice'])
        ->name('preview.invoice');

    Route::get('/purchase-order/{id}/preview-acknowledgment', [PurchaseController::class, 'previewAcknowledgment'])
        ->name('preview.acknowledgment');
    /*
    |--------------------------------------------------------------------------
    | Profile Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    /*
    |--------------------------------------------------------------------------
    | Superadmin routes
    |--------------------------------------------------------------------------
    */
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/units', [UnitController::class, 'index'])->name('unit.index');
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/admin/organization', [OrganizationController::class, 'adminindex'])->name('admin-organization.index');
    Route::get('/users', [RegisteredUserController::class, 'usersindex'])->name('users.index');
    Route::post('/users', [RegisteredUserController::class, 'importUsers'])->name('import.users');
    Route::get('/billing_shipping', [BillingController::class, 'billingShipping'])->name('billing_shipping.index');
    Route::get('/admin/purchase', [PurchaseController::class, 'adminPurchase'])->name('admin.purchase.index');
    /*
    |--------------------------------------------------------------------------
    | Reporting Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('report.index');
        Route::get('/purchase_order', [ReportController::class, 'purchaseOrderReport'])->name('report.purchase_order');
        Route::get('/picking', [ReportController::class, 'PickingReport'])->name('report.picking');
        Route::get('/lot-picking', [ReportController::class, 'lotPickingReport'])->name('report.lot_picking');
        Route::get('/audit', [ReportController::class, 'AuditReport'])->name('report.audit');
        Route::get('/inventory_adjust', [ReportController::class, 'inventoryAdjust'])->name('report.inventoryAdjust');
        Route::get('/inventory_transfer', [ReportController::class, 'inventoryTransfer'])->name('report.inventoryTransfers');
        Route::get('/product', [ReportController::class, 'productReport'])->name('report.product');
    });
    /*
    |--------------------------------------------------------------------------
    | Barcode Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');


    /*
    |--------------------------------------------------------------------------
    | Organization Setting Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('organization')->name('organization.')->group(function () {
        Route::get('/settings', [SettingController::class, 'settings'])->name('settings');
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/categories', [SettingController::class, 'categories'])->name('categories');
            Route::get('/inventory-adjustment', [SettingController::class, 'inventory_adjustment'])->name('inventory_adjust');
            Route::get('/inventory-transfer', [SettingController::class, 'inventory_transfer'])->name('inventory_transfer');
            Route::get('/organization', [SettingController::class, 'organization_settings'])->name('organization_settings');
            Route::get('/manufacturer', [SettingController::class, 'manufacturer'])->name('manufacturer');
            Route::get('/roles', [SettingController::class, 'roles'])->name('roles');
            Route::get('/general', [SettingController::class, 'general_settings'])->name('general_settings');
            Route::put('/general', [SettingController::class, 'general_settings_update'])->name('general_settings.update');
            Route::get('/customer', [SettingController::class, 'customer_settings'])->name('customer');
        });
    });

    Route::get('/patients', [PatientController::class, 'index'])->name('patient.index');
    Route::post('/patients', [PatientController::class, 'importPatients'])->name('import.patients');


});
