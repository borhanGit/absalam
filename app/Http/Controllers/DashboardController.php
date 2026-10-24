<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\JobQuotation;
use App\Models\OffCutItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $todaySales = Sale::whereDate('sale_date', $today)->sum('grand_total');
        $todayCollections = Sale::whereDate('sale_date', $today)->sum('paid_amount');
        $todayPurchases = Purchase::whereDate('purchase_date', $today)->sum('total_amount');
        $todayExpenses = Expense::whereDate('expense_date', $today)->sum('amount');
        
        $totalCustomerDue = Customer::sum('current_balance');
        $totalSupplierPayable = Supplier::sum('current_balance');
        
        $chequesInHand = Cheque::where('status', 'in_hand')->sum('amount');
        $chequesInHandCount = Cheque::where('status', 'in_hand')->count();
        
        $totalStockQty = Stock::sum('quantity');
        $lowStockProducts = Product::with('stocks')
            ->whereHas('stocks')
            ->take(5)
            ->get();
            
        $recentSales = Sale::with('customer')->latest()->take(6)->get();
        $recentQuotations = JobQuotation::with('customer')->latest()->take(5)->get();
        $recentOffCuts = OffCutItem::with('product')->where('status', 'available')->latest()->take(5)->get();
        
        $warehouses = Warehouse::withCount('stocks')->get();

        return view('dashboard', compact(
            'todaySales',
            'todayCollections',
            'todayPurchases',
            'todayExpenses',
            'totalCustomerDue',
            'totalSupplierPayable',
            'chequesInHand',
            'chequesInHandCount',
            'totalStockQty',
            'lowStockProducts',
            'recentSales',
            'recentQuotations',
            'recentOffCuts',
            'warehouses'
        ));
    }
}
