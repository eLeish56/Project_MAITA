<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

try {
    $res = DB::select('SHOW CREATE TABLE goods_receipt_items');
    echo "SHOW CREATE TABLE goods_receipt_items result:\n";
    var_export($res);
    echo "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
