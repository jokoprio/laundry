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
        // 1. Membership Levels Table
        Schema::create('membership_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->integer('discount_percent')->default(0);
            $table->integer('min_points')->default(0); // Optional: for auto-upgrade
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Update Customers Table
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignUuid('membership_level_id')->nullable()->after('tenant_id')->constrained('membership_levels');
            $table->decimal('balance', 12, 2)->default(0)->after('points');
        });

        // 3. Customer Balance History Table
        Schema::create('customer_balance_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('customer_id')->constrained('customers')->onDelete('cascade');
            $table->decimal('amount', 12, 2); // Positive for top-up, negative for payment
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->enum('type', ['topup', 'payment', 'adjustment']);
            $table->string('description')->nullable();
            $table->foreignUuid('transaction_id')->nullable()->constrained('transactions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_balance_histories');

        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('membership_level_id');
            $table->dropColumn('balance');
        });

        Schema::dropIfExists('membership_levels');
    }
};
