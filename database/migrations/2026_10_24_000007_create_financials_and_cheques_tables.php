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
        // Cheque Management (Received from customers / Issued to suppliers)
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_no');
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->date('cheque_date'); // Post-dated / Current dated
            $table->decimal('amount', 12, 2);
            
            $table->string('type'); // received_from_customer, issued_to_supplier
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            
            $table->string('status')->default('in_hand'); 
            // in_hand, deposited, honored (cleared), bounced (dishonored), returned
            
            $table->date('deposit_date')->nullable();
            $table->date('clearance_date')->nullable();
            $table->string('bounce_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Money Receipts / Customer Collections
        Schema::create('money_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique(); // e.g. MR-2026-0001
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('receipt_date');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('cash'); // cash, cheque, bank_transfer, bkash, nagad, tt_po_dd
            $table->foreignId('cheque_id')->nullable()->constrained('cheques')->nullOnDelete();
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Supplier Payments
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no')->unique(); // e.g. PAY-2026-0001
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('cash'); // cash, cheque, bank_transfer, bkash, nagad, tt_po_dd
            $table->foreignId('cheque_id')->nullable()->constrained('cheques')->nullOnDelete();
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Chart of Accounts
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. 1001, 1002, 2001, 4001, 5001
            $table->string('name'); // e.g. Cash in Hand, Bank Accounts, Accounts Receivable, Sales Revenue
            $table->string('account_type'); // asset, liability, equity, revenue, expense
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->boolean('is_system_locked')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Journal Entries / Vouchers
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique(); // e.g. JV-2026-0001, CP-2026-0001, CR-2026-0001
            $table->string('voucher_type')->default('journal'); // cash_payment, cash_receipt, bank_payment, bank_receipt, journal
            $table->date('entry_date');
            $table->decimal('total_amount', 12, 2);
            $table->text('narration')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Journal Entry Items (Double Entry Debit/Credit Lines)
        Schema::create('journal_entry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('chart_of_accounts')->cascadeOnDelete();
            $table->decimal('debit', 12, 2)->default(0.00);
            $table->decimal('credit', 12, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Daily Operating Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_no')->unique(); // e.g. EXP-2026-0001
            $table->foreignId('account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete(); // Linked expense account
            $table->string('category_name'); // e.g. Shop Rent, Electricity Bill, Staff Salary, Tea & Snacks, Van Fare
            $table->date('expense_date');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('cash'); // cash, bank, bkash
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('journal_entry_items');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('supplier_payments');
        Schema::dropIfExists('money_receipts');
        Schema::dropIfExists('cheques');
    }
};
