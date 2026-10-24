<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sales Master (POS & Invoicing)
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique(); // e.g. INV-2026-0001
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Salesperson / Cashier
            
            // Contractor / Thai Mistri tagging for commission calculation
            $table->foreignId('contractor_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->decimal('contractor_commission_total', 10, 2)->default(0.00);
            
            $table->date('sale_date');
            $table->string('sale_type')->default('pos_retail'); // pos_retail, contractor_job, wholesale, quotation_converted
            
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('vat_tax_amount', 12, 2)->default(0.00);
            
            // Transport & Fitting/Labor charges
            $table->decimal('carrying_cost', 12, 2)->default(0.00); // Van / Pickup delivery fee
            $table->decimal('labor_fitting_cost', 12, 2)->default(0.00); // Cutting or Fitting charge
            
            $table->decimal('grand_total', 12, 2)->default(0.00); // Subtotal - Discount + Tax + Carrying + Labor
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('due_amount', 12, 2)->default(0.00);
            
            $table->string('payment_status')->default('due'); // paid, partial, due
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Sale Line Items with Dimensional Measurement Support
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            
            // Dimensional inputs for Glass & Custom Cuts
            $table->decimal('height_inch', 8, 2)->nullable(); // e.g. 54.5 inch
            $table->decimal('width_inch', 8, 2)->nullable(); // e.g. 36.0 inch
            $table->decimal('total_sqft', 10, 2)->nullable(); // Calculated: (H * W) / 144
            
            // Dimensional inputs for Profiles (RFT or Weight)
            $table->decimal('length_feet', 8, 2)->nullable(); // Cut length in feet
            $table->decimal('total_rft', 10, 2)->nullable(); // Total Running Feet
            $table->decimal('total_weight_kg', 10, 2)->nullable(); // If billed by weight
            
            $table->decimal('quantity', 12, 2); // Bars, Sheets, Pcs, or SFT
            $table->decimal('unit_price', 12, 2); // Price per SFT, per Kg, per Bar, or per Pc
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Sales Returns
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_no')->unique(); // e.g. SR-2026-0001
            $table->foreignId('sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            
            $table->date('return_date');
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        // Sales Return Items
        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_id')->constrained('sales_returns')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_items');
        Schema::dropIfExists('sales_returns');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
