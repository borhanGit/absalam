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
        // Products Master (Handles Profiles, Glass, Accessories, Sheet/Board, Scrap)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "4\" Sliding Bottom (1.2mm SS Finish)", "5mm Clear Glass", "Sliding Roller Bearing Double"
            $table->string('code')->unique();
            $table->string('barcode')->nullable()->index();
            $table->string('product_type')->default('profile'); // profile, glass, accessory, board_ceiling, scrap
            
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            
            // Primary & Secondary measurement units
            $table->foreignId('unit_id')->constrained('units'); // e.g. Bar / Pcs, SFT, Kg, Box, Tube
            $table->foreignId('secondary_unit_id')->nullable()->constrained('units'); // e.g. Kg or Rft
            $table->decimal('conversion_ratio', 10, 4)->default(1.0000); // 1 Bar = 16 Rft or 4.25 Kg
            
            // Specifications for Aluminium Profiles & Glass
            $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('thickness_id')->nullable()->constrained('thicknesses')->nullOnDelete();
            $table->decimal('length_feet', 8, 2)->nullable(); // e.g. 16.00 ft, 18.00 ft
            $table->decimal('weight_per_unit_kg', 8, 3)->nullable(); // e.g. 4.350 kg per bar (used for kg-based buying/selling)
            $table->decimal('standard_sqft_per_sheet', 8, 2)->nullable(); // for large glass sheets
            
            // Pricing tiers
            $table->decimal('purchase_price', 12, 2)->default(0.00);
            $table->decimal('selling_price_retail', 12, 2)->default(0.00);
            $table->decimal('selling_price_wholesale', 12, 2)->default(0.00);
            $table->decimal('selling_price_contractor', 12, 2)->default(0.00);
            
            $table->decimal('min_stock_alert', 10, 2)->default(5.00);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Current Inventory per Warehouse
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->decimal('quantity', 12, 2)->default(0.00); // Primary unit qty
            $table->decimal('quantity_secondary', 12, 2)->default(0.00); // Secondary unit qty (e.g. total Kg or total Rft)
            $table->timestamps();

            $table->unique(['product_id', 'warehouse_id']);
        });

        // Leftover Cut Pieces (Off-cuts) for Aluminium Sections & Glass
        Schema::create('off_cut_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            
            // Dimensions of the cut piece
            $table->decimal('length_feet', 8, 2)->nullable(); // e.g. 6.50 ft
            $table->decimal('length_inch', 8, 2)->nullable(); // e.g. 78.00 inch
            $table->decimal('width_inch', 8, 2)->nullable(); // For glass cut piece e.g. 24.00 inch
            $table->decimal('sqft', 8, 2)->nullable(); // Total sqft if glass
            
            $table->integer('quantity')->default(1); // Number of identical cut pieces
            $table->decimal('rate', 10, 2)->default(0.00); // Negotiated rate per piece or per rft/sqft
            $table->string('status')->default('available'); // available, sold, scrapped
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Stock Transactions / Audit Ledger
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('transaction_type'); // purchase, sale, purchase_return, sales_return, off_cut_generation, scrap_bhangari, adjustment
            $table->decimal('quantity', 12, 2); // Positive = IN, Negative = OUT
            $table->decimal('balance_after', 12, 2);
            $table->string('reference_type')->nullable(); // Model class e.g. App\Models\Purchase, App\Models\Sale
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
        Schema::dropIfExists('off_cut_items');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('products');
    }
};
