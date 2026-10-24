<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest()->paginate(15);
        $totalPayable = Supplier::sum('current_balance');

        return view('suppliers.index', compact('suppliers', 'totalPayable'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'phone_alternate' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $opening = $validated['opening_balance'] ?? 0;
        $validated['current_balance'] = $opening;

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'নতুন মিল / সাপ্লায়ার সফলভাবে নিবন্ধিত হয়েছে!');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load([
            'purchases' => fn($q) => $q->latest()->take(15),
            'payments' => fn($q) => $q->latest()->take(15),
        ]);

        return view('suppliers.show', compact('supplier'));
    }
}
