<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\MoneyReceipt;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = ChartOfAccount::with('children')->orderBy('code')->get();

        $stats = [
            'total_sales' => Sale::sum('grand_total'),
            'total_received' => MoneyReceipt::sum('amount') + Sale::sum('paid_amount'),
            'total_purchases' => Purchase::sum('total_amount'),
            'total_paid' => SupplierPayment::sum('amount') + Purchase::sum('paid_amount'),
            'total_expenses' => Expense::sum('amount'),
            'customer_receivables' => Customer::sum('current_balance'),
            'supplier_payables' => Supplier::sum('current_balance'),
            'stock_valuation' => Stock::join('products', 'stocks.product_id', '=', 'products.id')
                ->selectRaw('SUM(stocks.quantity * products.purchase_price) as val')
                ->value('val') ?? 0,
        ];

        return view('accounts.index', compact('accounts', 'stats'));
    }

    public function cashbook(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();

        // Cash Inflows for the date
        $cashSales = Sale::whereDate('sale_date', $date)
            ->where('paid_amount', '>', 0)
            ->get()
            ->map(function ($s) {
                return [
                    'time' => $s->created_at->format('h:i A'),
                    'type' => 'ক্যাশ সেলস (POS/বিক্রয়)',
                    'ref' => $s->invoice_no,
                    'party' => $s->customer->name ?? 'খুচরা গ্রাহক',
                    'inflow' => $s->paid_amount,
                    'outflow' => 0,
                ];
            });

        $cashReceipts = MoneyReceipt::whereDate('receipt_date', $date)
            ->where('payment_method', 'cash')
            ->with('customer')
            ->get()
            ->map(function ($r) {
                return [
                    'time' => $r->created_at->format('h:i A'),
                    'type' => 'কালেকশন মানি রিসিট',
                    'ref' => $r->receipt_no,
                    'party' => $r->customer->name ?? '-',
                    'inflow' => $r->amount,
                    'outflow' => 0,
                ];
            });

        // Cash Outflows for the date
        $cashPurchases = Purchase::whereDate('purchase_date', $date)
            ->where('paid_amount', '>', 0)
            ->with('supplier')
            ->get()
            ->map(function ($p) {
                return [
                    'time' => $p->created_at->format('h:i A'),
                    'type' => 'নগদ ক্রয় (Cash Purchase)',
                    'ref' => $p->purchase_no,
                    'party' => $p->supplier->name ?? '-',
                    'inflow' => 0,
                    'outflow' => $p->paid_amount,
                ];
            });

        $cashPayments = SupplierPayment::whereDate('payment_date', $date)
            ->where('payment_method', 'cash')
            ->with('supplier')
            ->get()
            ->map(function ($sp) {
                return [
                    'time' => $sp->created_at->format('h:i A'),
                    'type' => 'সাপ্লায়ার বিল পরিশোধ',
                    'ref' => $sp->payment_no,
                    'party' => $sp->supplier->name ?? '-',
                    'inflow' => 0,
                    'outflow' => $sp->amount,
                ];
            });

        $cashExpenses = Expense::whereDate('expense_date', $date)
            ->where('payment_method', 'cash')
            ->get()
            ->map(function ($e) {
                return [
                    'time' => $e->created_at->format('h:i A'),
                    'type' => 'দোকান খরচ: ' . $e->category_name,
                    'ref' => $e->expense_no,
                    'party' => $e->description ?? 'দোকান ব্যয়',
                    'inflow' => 0,
                    'outflow' => $e->amount,
                ];
            });

        $transactions = $cashSales->concat($cashReceipts)
            ->concat($cashPurchases)
            ->concat($cashPayments)
            ->concat($cashExpenses)
            ->sortBy('time')
            ->values();

        $totalInflow = $transactions->sum('inflow');
        $totalOutflow = $transactions->sum('outflow');
        $netCashToday = $totalInflow - $totalOutflow;

        return view('accounts.cashbook', compact('transactions', 'date', 'totalInflow', 'totalOutflow', 'netCashToday'));
    }

    public function profitLoss(Request $request)
    {
        $fromDate = $request->filled('from_date') ? Carbon::parse($request->from_date) : now()->startOfMonth();
        $toDate = $request->filled('to_date') ? Carbon::parse($request->to_date) : now()->endOfMonth();

        // 1. Total Revenue
        $totalRevenue = Sale::whereBetween('sale_date', [$fromDate, $toDate])->sum('grand_total');
        $salesCount = Sale::whereBetween('sale_date', [$fromDate, $toDate])->count();

        // 2. Cost of Goods Purchased in this period
        $totalPurchases = Purchase::whereBetween('purchase_date', [$fromDate, $toDate])->sum('total_amount');

        // 3. Gross Profit Approximation (Sales - Material Cost)
        $grossProfit = $totalRevenue - $totalPurchases;

        // 4. Operating Expenses
        $expenses = Expense::whereBetween('expense_date', [$fromDate, $toDate])
            ->selectRaw('category_name, SUM(amount) as total_cat_amount')
            ->groupBy('category_name')
            ->get();

        $totalExpenses = $expenses->sum('total_cat_amount');

        // 5. Net Profit
        $netProfit = $grossProfit - $totalExpenses;

        return view('accounts.profit_loss', compact(
            'fromDate',
            'toDate',
            'totalRevenue',
            'salesCount',
            'totalPurchases',
            'grossProfit',
            'expenses',
            'totalExpenses',
            'netProfit'
        ));
    }
}
