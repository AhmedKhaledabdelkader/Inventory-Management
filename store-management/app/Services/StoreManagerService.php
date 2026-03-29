<?php

namespace App\Services;

use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Repositories\Contracts\LotRepositoryInterface;
use App\Repositories\Contracts\TransferRepositoryInterface;
use Exception;

class StoreManagerService
{
    
    

public function __construct(
        protected TransferRepositoryInterface $transferRepository,
        protected BoxRepositoryInterface $boxRepository,
        protected LotRepositoryInterface $lotRepository
    ) {}


    public function markLotReceivedByCode(string $lotCode, string $userId, ?string $notes = null): array
    {
        $lot = $this->lotRepository->findByCode($lotCode);

        if (!$lot) {
            throw new Exception('LOT not found.');
        }

        if ($lot->status !== 'delivered') {
            throw new Exception('Only delivered LOTs can be marked as received.');
        }

        $this->lotRepository->markReceived(
            $lot->id,
            $userId,
            $notes
        );

        return [
            'lot_id' => $lot->id,
            'lot_code' => $lot->lot_code,
            'status' => 'received',
        ];
    }

    public function confirmReceipt(string $lotId, string $userId, ?string $notes = null): bool
    {
        $lot = $this->lotRepository->findById($lotId);

        if (!$lot) {
            throw new Exception('LOT not found.');
        }

        if ($lot->status !== 'delivered') {
            throw new Exception('Only delivered LOTs can be confirmed.');
        }

        return $this->lotRepository->markReceived($lotId, $userId, $notes);
    }






    public function getDashboard(string $locationCode): array
    {
        return [
            'total_boxes' => $this->boxRepository->getBoxesCountByLocation($locationCode),
            'onhold_transfers' => $this->transferRepository->getStoreManagerOnHoldCount($locationCode),
            'in_transit' => $this->lotRepository->getInTransitLotsCountByLocation($locationCode),
            'delivered' => $this->lotRepository->getDeliveredLotsCountByLocation($locationCode),
        ];
    }

    public function getOnHoldTransfers(string $locationCode, ?string $search = null)
    {
        return $this->transferRepository->getOnHoldTransfersByLocation($locationCode, $search);
    }

    public function getVerifiedTransfers(string $locationCode, ?string $search = null)
    {
        return $this->transferRepository->getVerifiedTransfersByLocation($locationCode, $search);
    }

    public function getBoxes(string $locationCode)
    {
        return $this->boxRepository->getBoxesByLocation($locationCode);
    }

    public function getInTransitLots(string $locationCode)
    {
        return $this->lotRepository->getInTransitLotsByLocation($locationCode);
    }

    public function getDeliveredLots(string $locationCode)
    {
        return $this->lotRepository->getDeliveredLotsByLocation($locationCode);
    }


public function getAvailableBoxesForLot(
    string $locationCode,
    string $destination,
    ?string $search = null
) {
    return $this->boxRepository->getAvailableBoxesByLocationAndDestination(
        $locationCode,
        $destination??"",
        $search??""
    );
}


public function getLots( string $locationCode) {


 return $this->lotRepository->getLotsByLocation( $locationCode);

 
}





}












