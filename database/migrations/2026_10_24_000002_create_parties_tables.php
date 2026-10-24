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
        // Suppliers (Mills, Glass factories, Hardware importers)
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('phone');
            $table->string('phone_alternate')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0.00); // Positive = we owe supplier; Negative = advance given
            $table->decimal('current_balance', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Customers (Retail, Thai Mistri / Fabricator / Contractor, Wholesale, Builder)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('customer_type')->default('retail'); // retail, contractor_mistri, wholesale, builder
            $table->string('phone');
            $table->string('phone_alternate')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('zone_area')->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0.00); // Positive = customer owes us; Negative = customer advance
            $table->decimal('current_balance', 12, 2)->default(0.00);
            $table->decimal('credit_limit', 12, 2)->default(0.00);
            $table->decimal('default_commission_rate', 5, 2)->default(0.00); // Special for mistri/contractor (e.g. % or per SFT)
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Contractor / Thai Mistri Commission Ledger
        Schema::create('contractor_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('customers')->cascadeOnDelete();
            $table->unsignedBigInteger('sale_id')->nullable(); // Tagged sale/invoice
            $table->date('date');
            $table->decimal('total_sqft', 10, 2)->default(0.00);
            $table->decimal('rate_per_sqft', 8, 2)->default(0.00);
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->string('status')->default('due'); // due, paid, adjusted_with_due
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_commissions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('suppliers');
    }
};
