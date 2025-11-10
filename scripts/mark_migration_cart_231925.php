<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$migrationName = '2025_11_07_231925_create_cart_items_table';
$exists = DB::table('migrations')->where('migration', $migrationName)->exists();
if ($exists) {
    echo "Migration $migrationName already recorded.\n";
    exit(0);
}
$maxBatch = DB::table('migrations')->max('batch');
$batch = ($maxBatch === null) ? 1 : $maxBatch + 1;
DB::table('migrations')->insert(['migration' => $migrationName, 'batch' => $batch]);
echo "Inserted migration record: $migrationName with batch $batch\n";
