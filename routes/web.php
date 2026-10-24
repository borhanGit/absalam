<?php

use App\Http\Controllers\AccountController;
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
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;

// Welcome & Public Showcase
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

// 1. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 2. POS Counter & Sales
Route::get('/pos', [PosController::class, 'create'])->name('pos.create');
Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
Route::resource('sales', SaleController::class)->only(['index', 'show']);

// 3. Thai Window/Door Quotation Estimator
Route::post('quotations/{quotation}/convert-to-sale', [JobQuotationController::class, 'convertToSale'])->name('quotations.convert-to-sale');
Route::resource('quotations', JobQuotationController::class);

// 4. Purchases & Factory Invoices
Route::resource('purchases', PurchaseController::class);

// 5. Products & Inventory Master
Route::resource('products', ProductController::class);

// 6. Stocks & Warehouse Transfers
Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
Route::get('stocks/transfer', [StockController::class, 'transferForm'])->name('stocks.transfer');
Route::post('stocks/transfer', [StockController::class, 'processTransfer'])->name('stocks.process-transfer');

// 7. Off-Cut & Scrap Management
Route::get('offcuts', [OffCutController::class, 'index'])->name('offcuts.index');
Route::post('offcuts', [OffCutController::class, 'store'])->name('offcuts.store');
Route::post('offcuts/{offcut}/scrap', [OffCutController::class, 'convertToScrap'])->name('offcuts.scrap');

// 8. CRM: Customers & Thai Mistri
Route::resource('customers', CustomerController::class);

// 9. CRM: Suppliers & Mills
Route::resource('suppliers', SupplierController::class);

// 10. Cheque Management
Route::patch('cheques/{cheque}/status', [ChequeController::class, 'updateStatus'])->name('cheques.update-status');
Route::resource('cheques', ChequeController::class);

// 11. Collections & Money Receipts
Route::resource('receipts', MoneyReceiptController::class);

// 12. Supplier Payments
Route::resource('payments', SupplierPaymentController::class);

// 13. Daily Shop Expenses
Route::resource('expenses', ExpenseController::class)->except(['edit', 'update']);

// 14. Accounts, Cash Book & Profit & Loss
Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
Route::get('accounts/cashbook', [AccountController::class, 'cashbook'])->name('accounts.cashbook');
Route::get('accounts/profit-loss', [AccountController::class, 'profitLoss'])->name('accounts.profit-loss');
