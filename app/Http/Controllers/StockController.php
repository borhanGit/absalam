<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['product.category', 'product.brand', 'product.unit', 'warehouse']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $stocks = $query->paginate(20)->withQueryString();

        $warehouses = Warehouse::all();
        $categories = Category::all();

        $stats = [
            'total_items_in_stock' => Stock::sum('quantity'),
            'total_stock_value' => Stock::join('products', 'stocks.product_id', '=', 'products.id')
                ->selectRaw('SUM(stocks.quantity * products.purchase_price) as total_val')
                ->value('total_val') ?? 0,
            'low_stock_count' => Stock::join('products', 'stocks.product_id', '=', 'products.id')
                ->whereRaw('stocks.quantity <= products.min_stock_alert')
                ->count(),
        ];

        return view('stocks.index', compact('stocks', 'warehouses', 'categories', 'stats'));
    }

    public function transferForm()
    {
        $warehouses = Warehouse::all();
        $products = Product::where('is_active', true)->with('unit')->get();

        return view('stocks.transfer', compact('warehouses', 'products'));
    }

    public function processTransfer(Request $request)
    {
        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id|different:to_warehouse_id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $sourceStock = Stock::firstOrCreate(
                ['product_id' => $validated['product_id'], 'warehouse_id' => $validated['from_warehouse_id']],
                ['quantity' => 0]
            );

            if ($sourceStock->quantity < $validated['quantity']) {
                throw new \Exception("স্থানান্তরকারী ওয়্যারহাউসে পর্যাপ্ত স্টক নেই! বর্তমান স্টক: {$sourceStock->quantity}");
            }

            // Deduct from source
            $sourceStock->decrement('quantity', $validated['quantity']);

            // Add to destination
            $destStock = Stock::firstOrCreate(
                ['product_id' => $validated['product_id'], 'warehouse_id' => $validated['to_warehouse_id']],
                ['quantity' => 0]
            );
            $destStock->increment('quantity', $validated['quantity']);

            // Log transactions
            StockTransaction::create([
                'product_id' => $validated['product_id'],
                'warehouse_id' => $validated['from_warehouse_id'],
                'transaction_type' => 'adjustment',
                'quantity' => -$validated['quantity'],
                'balance_after' => $sourceStock->fresh()->quantity,
                'notes' => "ট্রান্সফার আউট -> ওয়্যারহাউস #{$validated['to_warehouse_id']}. " . ($validated['notes'] ?? ''),
            ]);

            StockTransaction::create([
                'product_id' => $validated['product_id'],
                'warehouse_id' => $validated['to_warehouse_id'],
                'transaction_type' => 'adjustment',
                'quantity' => $validated['quantity'],
                'balance_after' => $destStock->fresh()->quantity,
                'notes' => "ট্রান্সফার ইন <- ওয়্যারহাউস #{$validated['from_warehouse_id']}. " . ($validated['notes'] ?? ''),
            ]);
        });

        return redirect()->route('stocks.index')->with('success', 'পণ্য সফলভাবে এক ওয়্যারহাউস থেকে অন্য ওয়্যারহাউসে স্থানান্তর করা হয়েছে!');
    }
}
