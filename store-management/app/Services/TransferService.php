<?php

namespace App\Services;

use App\Http\Resources\ExternalTransferResource;
use App\Repositories\Contracts\TransferRepositoryInterface;


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


     public function getVerifiedTransfers(array $data)
    {
        return $this->transferRepository->getVerifiedTransfers($data['search']??"");
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




}
