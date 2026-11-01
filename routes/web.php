<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\JobQuotationController;
use App\Http\Controllers\MoneyReceiptController;
use App\Http\Controllers\OffCutController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\UserController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;

// Public Showcase / Landing
Route::get('/', function () {
    $company = CompanySetting::first() ?? new CompanySetting([
        'company_name' => 'Dhaka Thai & Aluminium Glass House',
        'phone' => '01712-345678',
        'address' => 'Mirpur-10, Dhaka, Bangladesh',
        'currency_symbol' => '৳',
    ]);

    $warehouses = Warehouse::withCount('stocks')->get();
    $categories = Category::withCount('products')->get();
    $brands = Brand::withCount('products')->get();
    $products = Product::with(['category', 'brand', 'unit', 'secondaryUnit', 'color', 'thickness', 'stocks'])->get();
    $customers = Customer::latest()->take(5)->get();
    $suppliers = Supplier::latest()->take(5)->get();

    $stats = [
        'total_products' => Product::count(),
        'profile_count' => Product::where('product_type', 'profile')->count(),
        'glass_count' => Product::where('product_type', 'glass')->count(),
        'total_stock_qty' => Stock::sum('quantity'),
        'total_customers' => Customer::count(),
        'total_mistri' => Customer::where('customer_type', 'contractor_mistri')->count(),
        'total_suppliers' => Supplier::count(),
        'customer_receivables' => Customer::sum('current_balance'),
        'supplier_payables' => Supplier::sum('current_balance'),
    ];

    return view('welcome', compact(
        'company',
        'warehouses',
        'categories',
        'brands',
        'products',
        'customers',
        'suppliers',
        'stats'
    ));
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // 1. Dashboard (All staff)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. POS Counter & Sales (Cashier, Manager, Admin)
    Route::middleware('role:admin,manager,cashier')->group(function () {
        Route::get('/pos', [PosController::class, 'create'])->name('pos.create');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
        Route::resource('sales', SaleController::class)->only(['index', 'show']);

        // Thai Window/Door Quotation Estimator
        Route::post('quotations/{quotation}/convert', [JobQuotationController::class, 'convertToSale'])->name('quotations.convert');
        Route::post('quotations/{quotation}/convert-to-sale', [JobQuotationController::class, 'convertToSale'])->name('quotations.convert-to-sale');
        Route::resource('quotations', JobQuotationController::class);

        // Cheques & Collections
        Route::patch('cheques/{cheque}/status', [ChequeController::class, 'updateStatus'])->name('cheques.update-status');
        Route::resource('cheques', ChequeController::class);
        Route::resource('receipts', MoneyReceiptController::class);
    });

    // 3. Purchases & Factory Invoices (Storekeeper, Manager, Admin)
    Route::middleware('role:admin,manager,storekeeper')->group(function () {
        Route::resource('purchases', PurchaseController::class);
    });

    // 4. Inventory, Products, Stocks & Off-cuts (All staff)
    Route::resource('products', ProductController::class);
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('stocks/transfer', [StockController::class, 'transferForm'])->name('stocks.transfer');
    Route::post('stocks/transfer', [StockController::class, 'processTransfer'])->name('stocks.process-transfer');

    Route::get('offcuts', [OffCutController::class, 'index'])->name('offcuts.index');
    Route::post('offcuts', [OffCutController::class, 'store'])->name('offcuts.store');
    Route::post('offcuts/{offcut}/scrap', [OffCutController::class, 'convertToScrap'])->name('offcuts.scrap');

    // 5. CRM: Customers & Mistri (All staff)
    Route::resource('customers', CustomerController::class);

    // 6. CRM: Suppliers, Payments, Expenses (Manager, Admin)
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('suppliers', SupplierController::class);
        Route::resource('payments', SupplierPaymentController::class);
        Route::resource('expenses', ExpenseController::class)->except(['edit', 'update']);
    });

    // 7. Accounts & Financial Reports (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('accounts/cashbook', [AccountController::class, 'cashbook'])->name('accounts.cashbook');
        Route::get('accounts/profit-loss', [AccountController::class, 'profitLoss'])->name('accounts.profit-loss');

        // 8. User Management Module (Admin Only)
        Route::resource('users', UserController::class);
    });
});
