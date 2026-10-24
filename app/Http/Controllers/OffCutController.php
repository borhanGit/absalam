<?php

namespace App\Http\Controllers;

use App\Models\OffCutItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class OffCutController extends Controller
{
    public function index()
    {
        $offCuts = OffCutItem::with(['product', 'warehouse'])
            ->where('status', 'available')
            ->latest()
            ->paginate(15);

        $scrapProduct = Product::where('product_type', 'scrap')->first();
        $scrapStock = $scrapProduct ? Stock::where('product_id', $scrapProduct->id)->sum('quantity') : 0;
        
        $products = Product::whereIn('product_type', ['profile', 'glass'])->get();
        $warehouses = Warehouse::all();

        return view('offcuts.index', compact('offCuts', 'scrapStock', 'scrapProduct', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'length_feet' => 'required|numeric|min:0.5',
            'width_inch' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'rate' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $sqft = null;
        if (!empty($validated['width_inch'])) {
            $sqft = (($validated['length_feet'] * 12) * $validated['width_inch']) / 144;
        }

        OffCutItem::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'length_feet' => $validated['length_feet'],
            'length_inch' => $validated['length_feet'] * 12,
            'width_inch' => $validated['width_inch'] ?? null,
            'sqft' => $sqft,
            'quantity' => $validated['quantity'],
            'rate' => $validated['rate'] ?? 0,
            'status' => 'available',
            'notes' => $validated['notes'] ?? 'Manual workshop cutting entry',
        ]);

        return redirect()->route('offcuts.index')->with('success', 'কাটিং অফ-কাট টুকরো সফলভাবে স্টকে যোগ করা হয়েছে!');
    }

    public function convertToScrap(OffCutItem $offcut)
    {
        $product = $offcut->product;
        // Approximate weight of this offcut
        $weightPerFoot = $product->weight_per_unit_kg ? ($product->weight_per_unit_kg / 16) : 0.25;
        $totalWeight = $offcut->length_feet * $weightPerFoot * $offcut->quantity;

        // Add to scrap product stock
        $scrapProduct = Product::where('product_type', 'scrap')->first();
        if ($scrapProduct) {
            $stock = Stock::firstOrCreate(
                ['product_id' => $scrapProduct->id, 'warehouse_id' => $offcut->warehouse_id],
                ['quantity' => 0]
            );
            $stock->increment('quantity', $totalWeight);
        }

        $offcut->update(['status' => 'scrapped']);

        return redirect()->route('offcuts.index')->with('success', "টুকরোটি সফলভাবে বাতিল করে {$totalWeight} কেজি ভাঙারি স্টকে যুক্ত করা হয়েছে!");
    }
}
