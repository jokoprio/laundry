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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignUuid('branch_id')->nullable()->after('tenant_id')->constrained('branches')->onDelete('set null');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignUuid('branch_id')->nullable()->after('tenant_id')->constrained('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['customers_branch_id_foreign']);
            $table->dropColumn('branch_id');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['suppliers_branch_id_foreign']);
            $table->dropColumn('branch_id');
        });
    }
};
