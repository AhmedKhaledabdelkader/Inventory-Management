<?php

namespace App\Services;

use App\Http\Resources\ExternalTransferResource;
use App\Repositories\Contracts\TransferRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Http;

class TransferService
{

    public $transferRepository ;

    public function __construct(TransferRepositoryInterface $transferRepository,protected AuthTokenService $authTokenService) {
        $this->transferRepository = $transferRepository;
    }



    public function fetchHoldTransfersFromWarehouse()
    {
        $token = $this->authTokenService->getToken();

        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.external_transfer_api.base_url') . '/Transfer', [
                '$filter' => "Status eq 'Hold' and FromWarehouse eq '99'",
                '$expand'=>"TransferLines"

            ]);

        $response->throw();

        return $response->json();
    }


      public function syncHoldTransfersFromWarehouse()
    {
        
        $response = $this->fetchHoldTransfersFromWarehouse() ;
        
        $rows = $response['value'] ?? $response;

        $externalIds = [];
        $syncedCount = 0;

        foreach ($rows as $row) {

            
            $mapped = (new ExternalTransferResource($row))->toArray(request());

          

            if (empty($mapped['external_id'])) {

                continue;
            }

        

            $this->transferRepository->upsertFromExternal($mapped);

          

            $externalIds[] = $mapped['external_id'];
            $syncedCount++;
        }

        if (!empty($externalIds)) {
            $this->transferRepository->resetMissing($externalIds);
            $missingCount = $this->transferRepository->markMissing($externalIds);
        } else {
            $missingCount = 0;
        }

        return [
            'synced_count' => $syncedCount,
            'missing_count' => $missingCount,
        ];

    }
    

    public function getHoldTransfers(string $locationCode,array $data)
    {
        return $this->transferRepository->getOnHoldTransfersByLocation($locationCode,$data['search']??"");
    }

    public function getPreparedTransfers(string $locationCode,array $data)
    {
        return $this->transferRepository->getPreparedTransfersByLocation($locationCode,$data['search']??"") ;
    }


    public function getDroppedTransfers(string $locationCode,array $data)
    {
        return $this->transferRepository->getDroppedTransfersByLocation($locationCode,$data['search']??"");
    }


     public function getVerifiedTransfers(string $locationCode,array $data)
    {
        return $this->transferRepository->getVerifiedTransfersByLocation($locationCode,$data['search']??"");
    }
    


 
    public function prepareTransfer(string $id,string $userId){


        return $this->transferRepository->markPrepared($id,$userId);
    }

     public function dropTransfer(string $id, string $reason){
        return $this->transferRepository->markDropped($id, $reason);

    }



    
     public function getSummary(string $locationCode): array
    {
        return $this->transferRepository->summary($locationCode);
     }


     public function getDroppedTransfersSummary(): array
{
    return $this->transferRepository->getDroppedTransfersSummary();
}


public function findTransfer(string $id){


    $transfer=$this->transferRepository->findById($id) ;

    return $transfer ;



}


 public function verifyTransferManually(string $transferId, ?string $notes = null): bool
    {
        return $this->transferRepository->markVerified($transferId, 'manual', $notes);
    }

   

    public function rejectTransfer(string $transferId, ?string $notes = null): bool
    {
        return $this->transferRepository->markRejected($transferId, $notes);
    }



    /*.................Scaning Barcode in Quality Control................*/


     public function scanBarcode(string $transferId, string $barcode): array
    {
        $transfer = $this->transferRepository->findById($transferId);

        if (!$transfer) {
            throw new Exception('Transfer not found.');
        }

        $items = collect($transfer->items ?? []);

        if ($items->isEmpty()) {
            throw new Exception('This transfer has no items.');
        }

        $matchedItem = $items->first(function ($item) use ($barcode) {
            return (string) ($item['barcode'] ?? '') === (string) $barcode;
        });

        if (!$matchedItem) {
            throw new Exception('Barcode does not belong to this transfer.');
        }

        $sku = $matchedItem['sku'];
        $requiredQty = (int) ($matchedItem['qty'] ?? 0);

        $progress = $transfer->verification_progress ?? [];

        if (!isset($progress[$sku])) {
            $progress[$sku] = [
                'sku' => $sku,
                'barcode' => $matchedItem['barcode'] ?? null,
                'name' => $matchedItem['name'] ?? null,
                'required_qty' => $requiredQty,
                'scanned_qty' => 0,
                'is_verified' => false,
            ];
        }

        $currentScanned = (int) $progress[$sku]['scanned_qty'];

        if ($currentScanned >= $requiredQty) {
            throw new Exception("SKU {$sku} is already fully verified.");
        }

        $progress[$sku]['scanned_qty'] = $currentScanned + 1;
        $progress[$sku]['is_verified'] = $progress[$sku]['scanned_qty'] >= $requiredQty;

        $this->transferRepository->updateVerificationProgress($transferId, $progress);

        $allVerified = $this->allItemsVerified($items, $progress);

        if ($allVerified) {
            $this->transferRepository->markVerified($transferId,'barcode_scan');
        }

        return [
            'sku' => $sku,
            'barcode' => $barcode,
            'required_qty' => $requiredQty,
            'scanned_qty' => $progress[$sku]['scanned_qty'],
            'is_verified' => $progress[$sku]['is_verified'],
            'transfer_verified' => $allVerified,
        ];
    }

    protected function allItemsVerified($items, array $progress): bool
    {
        foreach ($items as $item) {
            $sku = $item['sku'] ?? null;
            $requiredQty = (int) ($item['qty'] ?? 0);

            if (!$sku) {
                return false;
            }

            $scannedQty = (int) data_get($progress, "{$sku}.scanned_qty", 0);

            if ($scannedQty < $requiredQty) {
                return false;
            }
        }

        return true;
    }



    public function getErpStatus(): array
{
    return $this->transferRepository->getErpSyncStatus();
}


public function getQcDashboardSummary(string $locationCode): array
{
    return $this->transferRepository->getQualityControlSummary($locationCode);
}

public function getWarehousesSummary(): array
{
    return $this->transferRepository->getWarehousesSummary();
}



}
