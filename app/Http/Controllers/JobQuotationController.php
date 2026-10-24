<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\JobQuotation;
use App\Models\JobQuotationItem;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobQuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobQuotation::with(['customer', 'items']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_no', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $quotations = $query->latest()->paginate(15);
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $profileProducts = Product::where('product_type', 'profile')->get();
        $glassProducts = Product::where('product_type', 'glass')->get();

        return view('quotations.create', compact('customers', 'profileProducts', 'glassProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'project_name' => 'nullable|string|max:255',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.structure_type' => 'required|string',
            'items.*.location_tag' => 'nullable|string',
            'items.*.width_inch' => 'required|numeric|min:1',
            'items.*.height_inch' => 'required|numeric|min:1',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.profile_product_id' => 'nullable|exists:products,id',
            'items.*.glass_product_id' => 'nullable|exists:products,id',
            'items.*.rate_per_sqft' => 'required|numeric|min:0',
            'fitting_installation_cost' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'terms_and_conditions' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $year = date('Y');
            $latest = JobQuotation::whereYear('created_at', $year)->latest('id')->first();
            $nextNumber = $latest ? ((int) substr($latest->quotation_no, -4)) + 1 : 1;
            $qtnNo = 'QTN-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $totalSqft = 0;
            $totalUnits = 0;
            $itemsTotal = 0;

            foreach ($validated['items'] as $item) {
                $w = $item['width_inch'];
                $h = $item['height_inch'];
                $qty = $item['quantity'];
                $rate = $item['rate_per_sqft'];

                $unitSqft = ($w * $h) / 144;
                $lineSqft = $unitSqft * $qty;
                $lineAmount = $lineSqft * $rate;

                $totalSqft += $lineSqft;
                $totalUnits += $qty;
                $itemsTotal += $lineAmount;
            }

            $fitting = $validated['fitting_installation_cost'] ?? 0;
            $transport = $validated['transport_cost'] ?? 0;
            $discount = $validated['discount_amount'] ?? 0;
            $grandTotal = max(0, $itemsTotal + $fitting + $transport - $discount);

            $quotation = JobQuotation::create([
                'quotation_no' => $qtnNo,
                'customer_id' => $validated['customer_id'],
                'project_name' => $validated['project_name'] ?? 'Residential Window Project',
                'quotation_date' => $validated['quotation_date'],
                'valid_until' => $validated['valid_until'] ?? null,
                'total_units' => $totalUnits,
                'total_sqft' => $totalSqft,
                'fitting_installation_cost' => $fitting,
                'transport_cost' => $transport,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'status' => 'draft',
                'terms_and_conditions' => $validated['terms_and_conditions'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $unitSqft = ($item['width_inch'] * $item['height_inch']) / 144;
                $lineSqft = $unitSqft * $item['quantity'];
                $lineAmount = $lineSqft * $item['rate_per_sqft'];

                JobQuotationItem::create([
                    'job_quotation_id' => $quotation->id,
                    'structure_type' => $item['structure_type'],
                    'location_tag' => $item['location_tag'] ?? null,
                    'width_inch' => $item['width_inch'],
                    'height_inch' => $item['height_inch'],
                    'unit_sqft' => $unitSqft,
                    'quantity' => $item['quantity'],
                    'total_sqft' => $lineSqft,
                    'profile_product_id' => $item['profile_product_id'] ?? null,
                    'glass_product_id' => $item['glass_product_id'] ?? null,
                    'rate_per_sqft' => $item['rate_per_sqft'],
                    'total_amount' => $lineAmount,
                ]);
            }

            return redirect()->route('quotations.show', $quotation->id)->with('success', "কোটেশন #{$qtnNo} তৈরি সম্পন্ন হয়েছে!");
        });
    }

    public function show(JobQuotation $quotation)
    {
        $quotation->load(['customer', 'items.profileProduct', 'items.glassProduct']);
        $company = CompanySetting::first();

        return view('quotations.show', compact('quotation', 'company'));
    }

    public function convertToSale(JobQuotation $quotation)
    {
        if ($quotation->status === 'converted_to_sale') {
            return back()->with('error', 'এই কোটেশনটি ইতিমধ্যে সেলস চালানে রূপান্তর করা হয়েছে!');
        }

        return DB::transaction(function () use ($quotation) {
            $year = date('Y');
            $latestSale = Sale::whereYear('created_at', $year)->latest('id')->first();
            $nextNumber = $latestSale ? ((int) substr($latestSale->invoice_no, -5)) + 1 : 1;
            $invoiceNo = 'INV-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $defaultWarehouse = Warehouse::first();

            $sale = Sale::create([
                'invoice_no' => $invoiceNo,
                'customer_id' => $quotation->customer_id,
                'warehouse_id' => $defaultWarehouse->id,
                'sale_date' => date('Y-m-d'),
                'sale_type' => 'quotation_converted',
                'subtotal' => $quotation->grand_total,
                'grand_total' => $quotation->grand_total,
                'paid_amount' => 0,
                'due_amount' => $quotation->grand_total,
                'payment_status' => 'due',
                'notes' => "Converted from Quotation: {$quotation->quotation_no}",
            ]);

            // Add quotation items as sale lines
            foreach ($quotation->items as $item) {
                $product = $item->profileProduct ?? Product::first();
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'unit_id' => $product->unit_id,
                    'height_inch' => $item->height_inch,
                    'width_inch' => $item->width_inch,
                    'total_sqft' => $item->total_sqft,
                    'quantity' => $item->total_sqft,
                    'unit_price' => $item->rate_per_sqft,
                    'subtotal' => $item->total_amount,
                ]);
            }

            // Update customer balance
            $quotation->customer->increment('current_balance', $quotation->grand_total);

            // Update quotation status
            $quotation->update([
                'status' => 'converted_to_sale',
                'converted_sale_id' => $sale->id
            ]);

            return redirect()->route('sales.show', $sale->id)->with('success', "কোটেশনটি সফলভাবে সেলস চালান #{$invoiceNo}-এ রূপান্তর করা হয়েছে!");
        });
    }
}
