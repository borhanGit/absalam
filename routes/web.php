<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;

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
