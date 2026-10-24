<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'warehouse', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purchase_no', 'like', "%{$search}%")
                  ->orWhere('supplier_invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $purchases = $query->latest()->paginate(15);
        $totalPurchases = Purchase::sum('total_amount');
        $totalPaid = Purchase::sum('paid_amount');
        $totalDue = Purchase::sum('due_amount');

        return view('purchases.index', compact('purchases', 'totalPurchases', 'totalPaid', 'totalDue'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::with('unit')->where('is_active', true)->get();

        return view('purchases.create', compact('suppliers', 'warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'purchase_date' => 'required|date',
            'supplier_invoice_no' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_weight_kg' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'carrying_cost' => 'nullable|numeric|min:0',
            'labor_cost' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $year = date('Y');
            $latest = Purchase::whereYear('created_at', $year)->latest('id')->first();
            $nextNumber = $latest ? ((int) substr($latest->purchase_no, -4)) + 1 : 1;
            $purchaseNo = 'PUR-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $subtotal = $validated['subtotal'];
            $carrying = $validated['carrying_cost'] ?? 0;
            $labor = $validated['labor_cost'] ?? 0;
            $discount = $validated['discount_amount'] ?? 0;

            $totalAmount = max(0, $subtotal + $carrying + $labor - $discount);
            $paidAmount = $validated['paid_amount'];
            $dueAmount = max(0, $totalAmount - $paidAmount);

            $paymentStatus = 'due';
            if ($paidAmount >= $totalAmount) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            }

            $purchase = Purchase::create([
                'purchase_no' => $purchaseNo,
                'supplier_id' => $validated['supplier_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'purchase_date' => $validated['purchase_date'],
                'supplier_invoice_no' => $validated['supplier_invoice_no'] ?? null,
                'subtotal' => $subtotal,
                'carrying_cost' => $carrying,
                'labor_cost' => $labor,
                'discount_amount' => $discount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'approval_status' => 'approved',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $qty = $item['quantity'];
                $price = $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'unit_id' => $product->unit_id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_weight_kg' => $item['total_weight_kg'] ?? null,
                    'total_price' => $qty * $price,
                ]);

                // Increment Stock
                $stock = Stock::firstOrCreate(
                    ['product_id' => $product->id, 'warehouse_id' => $validated['warehouse_id']],
                    ['quantity' => 0]
                );
                $stock->increment('quantity', $qty);

                // Stock transaction audit
                StockTransaction::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $validated['warehouse_id'],
                    'transaction_type' => 'purchase',
                    'quantity' => $qty,
                    'balance_after' => $stock->fresh()->quantity,
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'notes' => "Purchase Bill: {$purchaseNo}",
                ]);
            }

            // Update Supplier Balance
            $supplier = Supplier::find($validated['supplier_id']);
            $supplier->increment('current_balance', $dueAmount);

            return redirect()->route('purchases.index')->with('success', "পারচেজ চালান #{$purchaseNo} সফলভাবে যুক্ত হয়েছে এবং স্টক বৃদ্ধি পেয়েছে!");
        });
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'warehouse', 'items.product.unit']);
        return view('purchases.show', compact('purchase'));
    }
}
