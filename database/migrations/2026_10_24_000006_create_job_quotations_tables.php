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
        // Job Quotations Master (Complete Window / Door / Partition Estimates)
        Schema::create('job_quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_no')->unique(); // e.g. QTN-2026-0001
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('project_name')->nullable(); // e.g. "Residential 5-Storied Building - Mirpur DOHS"
            $table->date('quotation_date');
            $table->date('valid_until')->nullable();
            
            $table->integer('total_units')->default(0); // Total number of windows/doors
            $table->decimal('total_sqft', 10, 2)->default(0.00); // Total project square footage
            
            // Cost breakdown
            $table->decimal('profile_cost', 12, 2)->default(0.00);
            $table->decimal('glass_cost', 12, 2)->default(0.00);
            $table->decimal('hardware_cost', 12, 2)->default(0.00);
            $table->decimal('labor_fabrication_cost', 12, 2)->default(0.00);
            $table->decimal('fitting_installation_cost', 12, 2)->default(0.00);
            $table->decimal('transport_cost', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2)->default(0.00);
            
            $table->string('status')->default('draft'); // draft, sent, approved, converted_to_sale, rejected
            $table->foreignId('converted_sale_id')->nullable()->constrained('sales')->nullOnDelete();
            
            $table->text('terms_and_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Individual Window / Door / Partition Specifications
        Schema::create('job_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_quotation_id')->constrained('job_quotations')->cascadeOnDelete();
            
            $table->string('structure_type')->default('sliding_window_2_track'); 
            // sliding_window_2_track, sliding_window_3_track, casement_window, swing_door, sliding_door, fixed_partition, louvers
            $table->string('location_tag')->nullable(); // e.g. "Master Bed Window W-1", "Drawing Partition P-1"
            
            // Dimensions
            $table->decimal('width_inch', 8, 2);
            $table->decimal('height_inch', 8, 2);
            $table->decimal('unit_sqft', 8, 2); // (W * H) / 144
            $table->integer('quantity')->default(1);
            $table->decimal('total_sqft', 10, 2); // unit_sqft * quantity
            
            // Selected Materials
            $table->foreignId('profile_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('glass_product_id')->nullable()->constrained('products')->nullOnDelete();
            
            // Pricing
            $table->decimal('rate_per_sqft', 10, 2); // Quoted all-inclusive or material rate per SFT
            $table->decimal('total_amount', 12, 2); // total_sqft * rate_per_sqft
            $table->text('special_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_quotation_items');
        Schema::dropIfExists('job_quotations');
    }
};
