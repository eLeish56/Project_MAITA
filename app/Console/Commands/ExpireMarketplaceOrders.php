<?php

namespace App\Console\Commands;

use App\Services\MarketplaceOrderService;
use Illuminate\Console\Command;

class ExpireMarketplaceOrders extends Command
{
    protected $signature = 'marketplace:expire-orders';

    protected $description = 'Auto-cancel pesanan marketplace yang telah melewati 24 jam';

    public function handle(MarketplaceOrderService $orderService): int
    {
        $this->info('Checking for expired marketplace orders...');

        try {
            $count = $orderService->autoExpireOrders();
            
            $this->info("✓ {$count} pesanan berhasil dibatalkan otomatis.");
            $this->line("Stok barang telah dikembalikan ke inventory.");
            
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
