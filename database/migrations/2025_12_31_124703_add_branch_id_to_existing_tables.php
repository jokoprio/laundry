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
        $tables = [
            'users',
            'employees',
            'inventory_items',
            'purchase_orders',
            'transactions',
            'expenses',
            'payrolls',
            'production_assignments',
            'services',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // branch_id is nullable because some data might belong to 'Pusat' (no branch)
                $table->foreignUuid('branch_id')->nullable()->after('tenant_id')->constrained('branches')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'employees',
            'inventory_items',
            'purchase_orders',
            'transactions',
            'expenses',
            'payrolls',
            'production_assignments',
            'services',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropForeign([$tableName . '_branch_id_foreign']);
                $table->dropColumn('branch_id');
            });
        }
    }
};
