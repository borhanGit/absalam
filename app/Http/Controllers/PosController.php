<?php

namespace App\Http\Controllers;

use App\Models\ContractorCommission;
use App\Models\Customer;
use App\Models\OffCutItem;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function create()
    {
        $products = Product::with(['unit', 'category', 'stocks', 'thickness', 'color'])
            ->where('is_active', true)
            ->get();
            
        $customers = Customer::where('is_active', true)->get();
        $mistris = Customer::where('customer_type', 'contractor_mistri')->where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $defaultWarehouse = Warehouse::where('is_primary', true)->first() ?? $warehouses->first();

        return view('pos.create', compact('products', 'customers', 'mistris', 'warehouses', 'defaultWarehouse'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'contractor_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.height_inch' => 'nullable|numeric|min:0',
            'items.*.width_inch' => 'nullable|numeric|min:0',
            'items.*.total_sqft' => 'nullable|numeric|min:0',
            'items.*.cut_length_feet' => 'nullable|numeric|min:0',
            'items.*.save_off_cut' => 'nullable|boolean',
            'subtotal' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'vat_tax_amount' => 'nullable|numeric|min:0',
            'carrying_cost' => 'nullable|numeric|min:0',
            'labor_fitting_cost' => 'nullable|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'contractor_commission_total' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // Generate Invoice No
            $year = date('Y');
            $latestSale = Sale::whereYear('created_at', $year)->latest('id')->first();
            $nextNumber = $latestSale ? ((int) substr($latestSale->invoice_no, -5)) + 1 : 1;
            $invoiceNo = 'INV-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $grandTotal = $validated['grand_total'];
            $paidAmount = $validated['paid_amount'];
            $dueAmount = max(0, $grandTotal - $paidAmount);
            
            $paymentStatus = 'due';
            if ($paidAmount >= $grandTotal) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            }

            // Create Sale Record
            $sale = Sale::create([
                'invoice_no' => $invoiceNo,
                'customer_id' => $validated['customer_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'user_id' => auth()->id() ?? null,
                'contractor_id' => $validated['contractor_id'] ?? null,
                'contractor_commission_total' => $validated['contractor_commission_total'] ?? 0,
                'sale_date' => $validated['sale_date'],
                'sale_type' => 'pos_retail',
                'subtotal' => $validated['subtotal'],
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'vat_tax_amount' => $validated['vat_tax_amount'] ?? 0,
                'carrying_cost' => $validated['carrying_cost'] ?? 0,
                'labor_fitting_cost' => $validated['labor_fitting_cost'] ?? 0,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create Sale Items and update inventory
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                $qty = $itemData['quantity'];
                $price = $itemData['unit_price'];
                $itemSubtotal = $qty * $price;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'unit_id' => $product->unit_id,
                    'height_inch' => $itemData['height_inch'] ?? null,
                    'width_inch' => $itemData['width_inch'] ?? null,
                    'total_sqft' => $itemData['total_sqft'] ?? null,
                    'length_feet' => $itemData['cut_length_feet'] ?? null,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $itemSubtotal,
                ]);

                // Deduct stock from the selected warehouse
                $stock = Stock::firstOrCreate(
                    ['product_id' => $product->id, 'warehouse_id' => $validated['warehouse_id']],
                    ['quantity' => 0]
                );
                $stock->decrement('quantity', $qty);

                // Record stock audit transaction
                StockTransaction::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $validated['warehouse_id'],
                    'transaction_type' => 'sale',
                    'quantity' => -$qty,
                    'balance_after' => $stock->fresh()->quantity,
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'notes' => "Sale Invoice: {$invoiceNo}",
                ]);

                // If this is a profile with cut length < 16 ft and save_off_cut is checked, store off-cut!
                if (!empty($itemData['cut_length_feet']) && $itemData['cut_length_feet'] < 16 && !empty($itemData['save_off_cut'])) {
                    $leftoverFeet = 16 - $itemData['cut_length_feet'];
                    if ($leftoverFeet >= 1) {
                        OffCutItem::create([
                            'product_id' => $product->id,
                            'warehouse_id' => $validated['warehouse_id'],
                            'length_feet' => $leftoverFeet,
                            'length_inch' => $leftoverFeet * 12,
                            'quantity' => $qty,
                            'rate' => $price,
                            'status' => 'available',
                            'notes' => "Generated from Invoice {$invoiceNo}",
                        ]);
                    }
                }
            }

            // Update customer balance
            $customer = Customer::find($validated['customer_id']);
            $customer->increment('current_balance', $dueAmount);

            // Record Mistri commission if applicable
            if (!empty($validated['contractor_id']) && !empty($validated['contractor_commission_total']) && $validated['contractor_commission_total'] > 0) {
                ContractorCommission::create([
                    'contractor_id' => $validated['contractor_id'],
                    'sale_id' => $sale->id,
                    'date' => $validated['sale_date'],
                    'commission_amount' => $validated['contractor_commission_total'],
                    'status' => 'due',
                    'notes' => "Commission for Invoice {$invoiceNo}",
                ]);
            }

            return redirect()->route('sales.show', $sale->id)->with('success', "চালান #{$invoiceNo} সফলভাবে তৈরি হয়েছে!");
        });
    }
}
