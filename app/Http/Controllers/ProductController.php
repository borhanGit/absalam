<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Thickness;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'unit', 'color', 'thickness', 'stocks']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $colors = Color::all();
        $thicknesses = Thickness::all();
        $warehouses = Warehouse::all();

        return view('products.create', compact('categories', 'brands', 'units', 'colors', 'thicknesses', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:products,code',
            'barcode' => 'nullable|string|max:100',
            'product_type' => 'required|string|in:profile,glass,accessory,board_ceiling,scrap',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'secondary_unit_id' => 'nullable|exists:units,id',
            'conversion_ratio' => 'nullable|numeric|min:0.0001',
            'color_id' => 'nullable|exists:colors,id',
            'thickness_id' => 'nullable|exists:thicknesses,id',
            'length_feet' => 'nullable|numeric|min:0',
            'weight_per_unit_kg' => 'nullable|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price_retail' => 'required|numeric|min:0',
            'selling_price_wholesale' => 'nullable|numeric|min:0',
            'selling_price_contractor' => 'nullable|numeric|min:0',
            'min_stock_alert' => 'nullable|numeric|min:0',
            'initial_stock' => 'nullable|numeric|min:0',
            'warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'barcode' => $validated['barcode'] ?? null,
            'product_type' => $validated['product_type'],
            'category_id' => $validated['category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'unit_id' => $validated['unit_id'],
            'secondary_unit_id' => $validated['secondary_unit_id'] ?? null,
            'conversion_ratio' => $validated['conversion_ratio'] ?? 1.0,
            'color_id' => $validated['color_id'] ?? null,
            'thickness_id' => $validated['thickness_id'] ?? null,
            'length_feet' => $validated['length_feet'] ?? null,
            'weight_per_unit_kg' => $validated['weight_per_unit_kg'] ?? null,
            'purchase_price' => $validated['purchase_price'],
            'selling_price_retail' => $validated['selling_price_retail'],
            'selling_price_wholesale' => $validated['selling_price_wholesale'] ?? $validated['selling_price_retail'],
            'selling_price_contractor' => $validated['selling_price_contractor'] ?? $validated['selling_price_retail'],
            'min_stock_alert' => $validated['min_stock_alert'] ?? 5,
        ]);

        // Add initial stock if provided
        if (!empty($validated['initial_stock']) && $validated['initial_stock'] > 0) {
            $whId = $validated['warehouse_id'] ?? Warehouse::first()->id;
            Stock::create([
                'product_id' => $product->id,
                'warehouse_id' => $whId,
                'quantity' => $validated['initial_stock'],
                'quantity_secondary' => $validated['initial_stock'] * ($validated['conversion_ratio'] ?? 1),
            ]);
        }

        return redirect()->route('products.index')->with('success', 'নতুন পণ্য সফলভাবে যুক্ত করা হয়েছে!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $colors = Color::all();
        $thicknesses = Thickness::all();

        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'colors', 'thicknesses'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:products,code,' . $product->id,
            'barcode' => 'nullable|string|max:100',
            'product_type' => 'required|string|in:profile,glass,accessory,board_ceiling,scrap',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'color_id' => 'nullable|exists:colors,id',
            'thickness_id' => 'nullable|exists:thicknesses,id',
            'length_feet' => 'nullable|numeric|min:0',
            'weight_per_unit_kg' => 'nullable|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price_retail' => 'required|numeric|min:0',
            'selling_price_wholesale' => 'nullable|numeric|min:0',
            'selling_price_contractor' => 'nullable|numeric|min:0',
            'min_stock_alert' => 'nullable|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'পণ্য তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'পণ্য সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
