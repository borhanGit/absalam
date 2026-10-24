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
        // 1. Company / Shop Settings
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Thai & Aluminium POS');
            $table->string('proprietor_name')->nullable();
            $table->string('phone');
            $table->string('phone_alternate')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('bin_vat_number')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('currency_symbol')->default('৳');
            $table->decimal('default_vat_percentage', 5, 2)->default(0.00);
            $table->string('logo_path')->nullable();
            $table->text('invoice_footer_note')->nullable();
            $table->timestamps();
        });

        // 2. Warehouses / Branches / Locations (e.g. Main Shop, Godown, Workshop)
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Main Shop, Godown 1, Cutting Workshop
            $table->string('code')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Product Categories (Aluminium Profile, Glass, Hardware & Accessories, Board / Ceiling, Scrap / Bhangari)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Aluminium Profile, Glass, Hardware, Gypsum / Ceiling
            $table->string('slug')->unique();
            $table->string('type')->default('standard'); // standard, profile, glass, accessory, scrap
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Brands / Mills (e.g. Kai, Dhaka Thai, Chung Hua, PHP Glass, Nasir Glass, Al-Aqsa)
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('origin_country')->default('Bangladesh');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Units of Measurement (Bar/Pcs, Kg, Rft, Sqft, Box, Tube, Roll, Packet)
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Piece / Bar, Kilogram, Running Foot, Square Foot
            $table->string('code')->unique(); // e.g. pcs, bar, kg, rft, sqft, box, tube, roll
            $table->boolean('allow_decimal')->default(false); // e.g. true for kg, sqft, rft; false for bar, box
            $table->timestamps();
        });

        // 6. Color / Finishes (Silver/Mill, Bronze/Champagne, SS, White Powder, Black Powder, Wood Grain, Clear, Tinted Black, etc.)
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Silver (Mill finish), Bronze, SS Finish, Powder Coated White, Powder Coated Black
            $table->string('color_code')->nullable(); // Hex code for UI representation
            $table->string('applicable_for')->default('both'); // profile, glass, or both
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Thickness / Gauges (1.0mm, 1.2mm, 1.5mm, 1.8mm, 2.0mm, 5mm, 6mm, 8mm, 10mm, 12mm)
        Schema::create('thicknesses', function (Blueprint $table) {
            $table->id();
            $table->string('value'); // e.g. "1.2mm", "1.5mm", "5mm", "10mm"
            $table->decimal('numeric_value_mm', 5, 2); // 1.20, 1.50, 5.00, 10.00
            $table->string('applicable_for')->default('both'); // profile, glass, or both
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thicknesses');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('units');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('company_settings');
    }
};
