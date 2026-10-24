<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\MoneyReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoneyReceiptController extends Controller
{
    public function index(Request $request)
    {
        $query = MoneyReceipt::with(['customer', 'cheque']);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('receipt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('receipt_date', '<=', $request->to_date);
        }

        $receipts = $query->latest('receipt_date')->latest('id')->paginate(15)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        $stats = [
            'total_collected' => MoneyReceipt::sum('amount'),
            'today_collected' => MoneyReceipt::whereDate('receipt_date', today())->sum('amount'),
            'cash_collected' => MoneyReceipt::where('payment_method', 'cash')->sum('amount'),
            'cheque_collected' => MoneyReceipt::where('payment_method', 'cheque')->sum('amount'),
        ];

        return view('receipts.index', compact('receipts', 'customers', 'stats'));
    }

    public function create(Request $request)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $selectedCustomer = null;
        if ($request->filled('customer_id')) {
            $selectedCustomer = Customer::find($request->customer_id);
        }

        // Auto generate receipt number
        $year = date('Y');
        $lastReceipt = MoneyReceipt::latest('id')->first();
        $seq = $lastReceipt ? ($lastReceipt->id + 1) : 1;
        $receiptNo = 'MR-' . $year . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);

        return view('receipts.create', compact('customers', 'selectedCustomer', 'receiptNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receipt_no' => 'required|string|unique:money_receipts,receipt_no',
            'customer_id' => 'required|exists:customers,id',
            'receipt_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,cheque,bank_transfer,bkash,nagad,tt_po_dd',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            // Optional cheque details if method is cheque
            'cheque_no' => 'nullable|required_if:payment_method,cheque|string|max:100',
            'bank_name' => 'nullable|required_if:payment_method,cheque|string|max:255',
            'cheque_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $chequeId = null;

            if ($validated['payment_method'] === 'cheque') {
                $cheque = Cheque::create([
                    'cheque_no' => $request->cheque_no,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'cheque_date' => $request->cheque_date ?? $validated['receipt_date'],
                    'amount' => $validated['amount'],
                    'type' => 'received_from_customer',
                    'customer_id' => $validated['customer_id'],
                    'status' => 'in_hand',
                    'notes' => 'রিসিট নং ' . $validated['receipt_no'] . ' হতে প্রাপ্ত',
                ]);
                $chequeId = $cheque->id;
            }

            $receipt = MoneyReceipt::create([
                'receipt_no' => $validated['receipt_no'],
                'customer_id' => $validated['customer_id'],
                'receipt_date' => $validated['receipt_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'cheque_id' => $chequeId,
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Adjust Customer Balance
            $customer = Customer::find($validated['customer_id']);
            if ($customer) {
                $customer->decrement('current_balance', $validated['amount']);
            }
        });

        return redirect()->route('receipts.index')->with('success', 'মানি রিসিট সফলভাবে তৈরি এবং কাস্টমার একাউন্ট আপডেট হয়েছে!');
    }

    public function show(MoneyReceipt $receipt)
    {
        $receipt->load(['customer', 'cheque']);
        $company = CompanySetting::first();

        return view('receipts.show', compact('receipt', 'company'));
    }
}
