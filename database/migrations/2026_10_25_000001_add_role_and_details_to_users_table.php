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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('cashier')->after('email'); // admin, manager, cashier, storekeeper
            $table->string('phone')->nullable()->after('role');
            $table->foreignId('warehouse_id')->nullable()->after('phone')->constrained('warehouses')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('warehouse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['role', 'phone', 'warehouse_id', 'is_active']);
        });
    }
};
