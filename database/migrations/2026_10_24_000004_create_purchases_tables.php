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
        // Purchase Master
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no')->unique(); // e.g. PUR-2026-0001
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            
            $table->date('purchase_date');
            $table->string('supplier_invoice_no')->nullable(); // Supplier's challan/bill number
            
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            
            // Transport / Carrying & Labor costs (Crucial for BD mill deliveries)
            $table->decimal('carrying_cost', 12, 2)->default(0.00); // Truck / Pickup rent
            $table->decimal('labor_cost', 12, 2)->default(0.00); // Unloading labor / Coolie charge
            $table->decimal('other_cost', 12, 2)->default(0.00);
            
            $table->decimal('total_amount', 12, 2)->default(0.00); // Subtotal - Discount + Tax + Carrying + Labor
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('due_amount', 12, 2)->default(0.00);
            
            $table->string('payment_status')->default('due'); // paid, partial, due
            $table->string('approval_status')->default('approved'); // draft, approved, cancelled
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Purchase Items
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            
            $table->decimal('quantity', 12, 2); // Bars, Sheets, or Kg
            $table->decimal('total_weight_kg', 10, 2)->nullable(); // Total weight if bought by weight
            $table->decimal('unit_price', 12, 2); // Price per bar, kg, or sheet
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });

        // Purchase Returns
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_no')->unique(); // e.g. PR-2026-0001
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->nullOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            
            $table->date('return_date');
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->string('approval_status')->default('approved');
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        // Purchase Return Items
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_return_id')->constrained('purchase_returns')->cascadeOnDelete();
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
        Schema::dropIfExists('purchase_return_items');
        Schema::dropIfExists('purchase_returns');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
    }
};
