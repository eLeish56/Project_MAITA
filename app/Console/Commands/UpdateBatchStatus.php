<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GoodsReceiptItem;
use Carbon\Carbon;

class UpdateBatchStatus extends Command
{
    protected $signature = 'batch:update-status';
    protected $description = 'Update status kadaluarsa untuk semua batch';

    public function handle()
    {
        $batches = GoodsReceiptItem::whereNotNull('expiry_date')->get();
        $today = Carbon::today();
        $updated = 0;

        foreach ($batches as $batch) {
            $daysUntilExpiry = $today->diffInDays($batch->expiry_date, false);
            $oldStatus = $batch->expiry_status;
            
            if ($daysUntilExpiry < 0) {
                $batch->expiry_status = 'expired';
            } elseif ($daysUntilExpiry <= 30) {
                $batch->expiry_status = 'warning';
            } else {
                $batch->expiry_status = 'safe';
            }
            
            if ($oldStatus !== $batch->expiry_status) {
                $batch->save();
                $updated++;
            }
        }

        $this->info("Berhasil memperbarui status {$updated} batch");
        return Command::SUCCESS;
    }
}