<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\CompanySetting;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierPayment::with(['supplier', 'cheque']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $query->latest('payment_date')->latest('id')->paginate(15)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        $stats = [
            'total_paid' => SupplierPayment::sum('amount'),
            'today_paid' => SupplierPayment::whereDate('payment_date', today())->sum('amount'),
            'cash_paid' => SupplierPayment::where('payment_method', 'cash')->sum('amount'),
            'cheque_paid' => SupplierPayment::where('payment_method', 'cheque')->sum('amount'),
        ];

        return view('payments.index', compact('payments', 'suppliers', 'stats'));
    }

    public function create(Request $request)
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $selectedSupplier = null;
        if ($request->filled('supplier_id')) {
            $selectedSupplier = Supplier::find($request->supplier_id);
        }

        $year = date('Y');
        $lastPayment = SupplierPayment::latest('id')->first();
        $seq = $lastPayment ? ($lastPayment->id + 1) : 1;
        $paymentNo = 'PAY-' . $year . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);

        return view('payments.create', compact('suppliers', 'selectedSupplier', 'paymentNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_no' => 'required|string|unique:supplier_payments,payment_no',
            'supplier_id' => 'required|exists:suppliers,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,cheque,bank_transfer,bkash,nagad,tt_po_dd',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            // Cheque info if payment method is cheque
            'cheque_no' => 'nullable|required_if:payment_method,cheque|string|max:100',
            'bank_name' => 'nullable|required_if:payment_method,cheque|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $chequeId = null;

            if ($validated['payment_method'] === 'cheque') {
                $cheque = Cheque::create([
                    'cheque_no' => $request->cheque_no,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'cheque_date' => $request->cheque_date ?? $validated['payment_date'],
                    'amount' => $validated['amount'],
                    'type' => 'issued_to_supplier',
                    'supplier_id' => $validated['supplier_id'],
                    'status' => 'in_hand',
                    'notes' => 'পেমেন্ট ভাউচার #' . $validated['payment_no'] . ' হতে ইস্যুকৃত',
                ]);
                $chequeId = $cheque->id;
            }

            $payment = SupplierPayment::create([
                'payment_no' => $validated['payment_no'],
                'supplier_id' => $validated['supplier_id'],
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'cheque_id' => $chequeId,
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Decrement Supplier balance (they were owed money, now paid)
            $supplier = Supplier::find($validated['supplier_id']);
            if ($supplier) {
                $supplier->decrement('current_balance', $validated['amount']);
            }
        });

        return redirect()->route('payments.index')->with('success', 'সাপ্লায়ার পেমেন্ট সফলভাবে এন্ট্রি হয়েছে ও ব্যালেন্স সমন্বয় করা হয়েছে!');
    }

    public function show(SupplierPayment $payment)
    {
        $payment->load(['supplier', 'cheque']);
        $company = CompanySetting::first();

        return view('payments.show', compact('payment', 'company'));
    }
}
