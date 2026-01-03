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
        // Services (e.g., Cuci Kiloan, Dry Clean)
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('unit'); // kg, pc
            $table->timestamps();
        });

        // Service Materials (BOM / Recipe)
        Schema::create('service_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignUuid('inventory_item_id')->constrained('inventory_items');
            $table->decimal('quantity', 10, 2); // Amount needed per service unit
            $table->timestamps();
        });

        // Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('service_id')->constrained('services');
            $table->foreignUuid('customer_id')->nullable()->constrained('customers');
            $table->decimal('qty', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->decimal('total_cogs', 12, 2)->default(0); // Snapshot cost
            $table->enum('status', ['pending', 'processing', 'done', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('service_materials');
        Schema::dropIfExists('services');
    }
};
