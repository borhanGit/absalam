<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        $customers = $query->latest()->paginate(15);
        $totalDue = Customer::sum('current_balance');
        $mistriCount = Customer::where('customer_type', 'contractor_mistri')->count();

        return view('customers.index', compact('customers', 'totalDue', 'mistriCount'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_type' => 'required|in:retail,contractor_mistri,wholesale,builder',
            'phone' => 'required|string|max:50',
            'phone_alternate' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'opening_balance' => 'nullable|numeric',
            'credit_limit' => 'nullable|numeric|min:0',
            'default_commission_rate' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $opening = $validated['opening_balance'] ?? 0;
        $validated['current_balance'] = $opening;

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'নতুন কাস্টমার / থাই মিস্ত্রি সফলভাবে যুক্ত করা হয়েছে!');
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'sales' => fn($q) => $q->latest()->take(15),
            'receipts' => fn($q) => $q->latest()->take(15),
            'commissions' => fn($q) => $q->latest()->take(15),
        ]);

        return view('customers.show', compact('customer'));
    }
}
