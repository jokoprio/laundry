<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaction;
use App\Models\Tenant;

$tenant = Tenant::first();
if (!$tenant) {
    die("No tenant found\n");
}

try {
    $t = Transaction::create([
        'tenant_id' => $tenant->id,
        'total_price' => 12345,
        'total_cogs' => 6789,
        'status' => 'pending',
        'payment_status' => 'pending',
        'amount_paid' => 0,
    ]);
    echo "SUCCESS: Transaction ID " . $t->id . "\n";

    $count = Transaction::where('id', $t->id)->count();
    echo "VERIFICATION: Count for ID " . $t->id . " is " . $count . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
