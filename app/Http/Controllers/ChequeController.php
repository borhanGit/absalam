<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ChequeController extends Controller
{
    public function index(Request $request)
    {
        $query = Cheque::with(['customer', 'supplier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $cheques = $query->latest('cheque_date')->paginate(15)->withQueryString();
        
        $stats = [
            'in_hand' => Cheque::where('status', 'in_hand')->sum('amount'),
            'in_hand_count' => Cheque::where('status', 'in_hand')->count(),
            'deposited' => Cheque::where('status', 'deposited')->sum('amount'),
            'honored' => Cheque::where('status', 'honored')->sum('amount'),
            'bounced' => Cheque::where('status', 'bounced')->sum('amount'),
            'bounced_count' => Cheque::where('status', 'bounced')->count(),
        ];

        return view('cheques.index', compact('cheques', 'stats'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('cheques.create', compact('customers', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cheque_no' => 'required|string|max:100',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'cheque_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:received_from_customer,issued_to_supplier',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'in_hand';
        Cheque::create($validated);

        return redirect()->route('cheques.index')->with('success', 'নতুন চেক সফলভাবে এন্ট্রি করা হয়েছে!');
    }

    public function updateStatus(Request $request, Cheque $cheque)
    {
        $validated = $request->validate([
            'status' => 'required|in:in_hand,deposited,honored,bounced,returned',
            'deposit_date' => 'nullable|date',
            'clearance_date' => 'nullable|date',
            'bounce_reason' => 'nullable|string',
        ]);

        $cheque->update($validated);

        return back()->with('success', "চেক #{$cheque->cheque_no}-এর স্ট্যাটাস সফলভাবে আপডেট হয়েছে!");
    }
}
