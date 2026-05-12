<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure PHP default timezone follows config('app.timezone') so
        // date(), DateTime and Carbon use the correct zone (WIB = Asia/Jakarta).
        $tz = config('app.timezone', 'Asia/Jakarta');
        date_default_timezone_set($tz);

        // Set Carbon locale for translated formatting (e.g. translatedFormat())
        if (class_exists(Carbon::class)) {
            Carbon::setLocale(config('app.locale', 'id'));
        }

        // Try to set system locale for strftime and localized month/day names.
        // Use several common Indonesian locale names (Linux/Windows).
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian_Indonesia.1252', 'id_ID.utf8');

        // Set DB session timezone so MySQL NOW() and TIMESTAMP behaviour are consistent.
        // Prefer storing timestamps in UTC in DB; if your DB uses local time, set the session zone.
        try {
            // set session timezone to +07:00 (WIB)
            DB::statement("SET time_zone = '+07:00'");
        } catch (\Exception $e) {
            // ignore if DB does not allow changing time_zone or running in some environments
        }

        // Enable query logging for debugging (kept as before)
        DB::enableQueryLog();

        // Set up pagination to use Bootstrap 5
        Paginator::useBootstrapFive();

        Blade::directive('indo_currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });
    }
}
