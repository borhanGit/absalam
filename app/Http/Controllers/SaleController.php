<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'warehouse', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date')) {
            $query->whereDate('sale_date', $request->date);
        }

        $sales = $query->latest()->paginate(15)->withQueryString();
        $totalSalesAmount = Sale::sum('grand_total');
        $totalPaidAmount = Sale::sum('paid_amount');
        $totalDueAmount = Sale::sum('due_amount');

        return view('sales.index', compact('sales', 'totalSalesAmount', 'totalPaidAmount', 'totalDueAmount'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'contractor', 'warehouse', 'items.product.unit', 'items.unit']);
        $company = CompanySetting::first();

        return view('sales.show', compact('sale', 'company'));
    }
}
