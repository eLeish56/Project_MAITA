<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$migrationName = '0001_01_01_000002_create_inventory_movements_table';
$exists = DB::table('migrations')->where('migration', $migrationName)->exists();
if ($exists) {
    echo "Migration $migrationName already recorded in migrations table.\n";
    exit(0);
}
$maxBatch = DB::table('migrations')->max('batch');
$batch = ($maxBatch === null) ? 1 : $maxBatch + 1;
DB::table('migrations')->insert(['migration' => $migrationName, 'batch' => $batch]);
echo "Inserted migration record: $migrationName with batch $batch\n";
