<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('category_name')) {
            $query->where('category_name', $request->category_name);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        $expenses = $query->latest('expense_date')->latest('id')->paginate(15)->withQueryString();

        $stats = [
            'total_expense' => Expense::sum('amount'),
            'this_month' => Expense::whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->sum('amount'),
            'today' => Expense::whereDate('expense_date', today())->sum('amount'),
        ];

        // Categories list
        $categories = Expense::select('category_name')->distinct()->pluck('category_name');

        return view('expenses.index', compact('expenses', 'stats', 'categories'));
    }

    public function create()
    {
        $year = date('Y');
        $lastExp = Expense::latest('id')->first();
        $seq = $lastExp ? ($lastExp->id + 1) : 1;
        $expenseNo = 'EXP-' . $year . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);

        $defaultCategories = [
            'দোকান ভাড়া (Shop Rent)',
            'বিদ্যুৎ ও ইউটিলিটি বিল (Electricity Bill)',
            'স্টাফ ও কারিগর বেতন / খোরাকি (Staff & Labor)',
            'নাস্তা ও চা আপ্যায়ন (Tea & Snacks)',
            'ভ্যান ও পরিবহন ভাড়া (Transport & Coolie)',
            'কাটিং ব্লেড, ড্রিল বিট ও ওয়্যারশপ সরঞ্জাম (Tools & Consumables)',
            'জেনারেটর তেল ও জ্বালানি (Fuel)',
            'মোবাইল ও ইন্টারনেট বিল (Phone & Internet)',
            'অন্যান্য বিবিধ খরচ (Miscellaneous)',
        ];

        return view('expenses.create', compact('expenseNo', 'defaultCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_no' => 'required|string|unique:expenses,expense_no',
            'category_name' => 'required|string|max:255',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank,bkash',
            'description' => 'nullable|string',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'খরচ সফলভাবে এন্ট্রি করা হয়েছে!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'খরচের এন্ট্রি মুছে ফেলা হয়েছে!');
    }
}
