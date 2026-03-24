<?php


namespace App\Console\Commands;

use App\Services\TransferService;
use Illuminate\Console\Command;

class SyncTransfersCommand extends Command
{
    protected $signature = 'transfers:sync';
    protected $description = 'Sync transfers from external API';

    public function handle(TransferService $transferSyncService): int
    {
        $result = $transferSyncService->syncHoldTransfersFromWarehouse();

        $this->info("Synced: {$result['synced_count']}, Missing: {$result['missing_count']}");

        return self::SUCCESS;
    }
}