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
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->foreignUuid('from_branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignUuid('to_branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->decimal('quantity', 10, 2);
            $table->enum('type', ['mutation', 'adjustment', 'purchase'])->default('mutation');
            $table->string('reference_type')->nullable(); // PO, Transaction, etc.
            $table->string('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
