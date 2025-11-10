<?php
// Simple script to inspect DB from Laravel app context
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $res = DB::select('SHOW CREATE TABLE inventory_movements');
    echo "SHOW CREATE TABLE inventory_movements result:\n";
    var_export($res);
    echo "\n\n";

    $m = DB::table('migrations')->where('migration','like','%000002_create_inventory_movements%')->get()->toArray();
    echo "migrations row for 000002_create_inventory_movements:\n";
    var_export($m);
    echo "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
