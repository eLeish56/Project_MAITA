<?php
// Boot Laravel and print app timezone + current date for verification
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "config('app.timezone') = " . config('app.timezone') . PHP_EOL;
echo "PHP date() = " . date('Y-m-d H:i:s T') . PHP_EOL;
if (class_exists(\Carbon\Carbon::class)) {
    echo "Carbon now (toDateTimeString) = " . \Carbon\Carbon::now()->toDateTimeString() . PHP_EOL;
    echo "Carbon now (translatedFormat) = " . \Carbon\Carbon::now()->translatedFormat('l, j F Y H:i') . PHP_EOL;
}

try {
    $dbNow = DB::selectOne('SELECT NOW() as now');
    echo "DB NOW() = " . ($dbNow->now ?? '<null>') . PHP_EOL;
    $tzs = DB::selectOne("SELECT @@global.time_zone as g, @@session.time_zone as s");
    echo "DB global.time_zone = " . ($tzs->g ?? '<null>') . ", session.time_zone = " . ($tzs->s ?? '<null>') . PHP_EOL;
} catch (\Exception $e) {
    echo "DB check failed: " . $e->getMessage() . PHP_EOL;
}

echo "ini_get('date.timezone') = " . ini_get('date.timezone') . PHP_EOL;
