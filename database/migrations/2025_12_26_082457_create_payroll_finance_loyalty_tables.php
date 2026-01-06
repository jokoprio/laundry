<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Payroll Module
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('salary_type', ['daily', 'monthly', 'borongan_item', 'borongan_kg'])->default('daily');
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('rate_per_unit', 12, 2)->default(0);
        });

        Schema::create('payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('employee_id')->constrained('employees');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('base_amount', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['draft', 'paid'])->default('draft');
            $table->json('details')->nullable(); // Breakdown of attendance or production
            $table->timestamps();
        });

        Schema::create('production_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('transaction_id')->constrained('transactions');
            $table->foreignUuid('employee_id')->constrained('employees');
            $table->enum('task_type', ['washing', 'drying', 'ironing', 'packing', 'delivery']);
            $table->decimal('qty', 10, 2);
            $table->decimal('rate_snapshot', 12, 2); // Cost per unit at time of assignment
            $table->timestamps();
        });

        // 2. Finance Module
        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('category'); // electricity, rent, maintenance, etc.
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending')->after('status');
            $table->decimal('amount_paid', 12, 2)->default(0)->after('total_price');
        });

        // 3. Loyalty Module
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('address');
        });

        Schema::create('points_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('customer_id')->constrained('customers');
            $table->foreignUuid('transaction_id')->nullable()->constrained('transactions');
            $table->integer('points');
            $table->enum('type', ['earned', 'redeemed', 'adjustment']);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('customer_id')->constrained('customers');
            $table->foreignUuid('coupon_id')->constrained('coupons');
            $table->string('code')->nullable(); // Unique code for this specific issuance if needed
            $table->enum('status', ['active', 'used', 'expired'])->default('active');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_coupons');
        Schema::dropIfExists('points_history');
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['points']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'amount_paid']);
        });
        Schema::dropIfExists('expenses');

        Schema::dropIfExists('production_assignments');
        Schema::dropIfExists('payrolls');
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['salary_type', 'base_salary', 'rate_per_unit']);
        });
    }
};
