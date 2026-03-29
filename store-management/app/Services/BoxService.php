<?php

namespace App\Services;

use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Repositories\Contracts\TransferRepositoryInterface;
use Exception;

class BoxService
{
    public function __construct(
        protected TransferRepositoryInterface $transferRepository,
        protected BoxRepositoryInterface $boxRepository
    ) {}

    public function createBoxes(string $transferId, string $destination, int $numberOfBoxes, string $userId): array
    {
        $transfer = $this->transferRepository->findById($transferId);

        if (!$transfer) {
            throw new Exception('Transfer not found.');
        }

        if ($transfer->verification_status !== 'verified') {
            throw new Exception('Only verified transfers can be boxed.');
        }

        if (($transfer->boxing_status ?? 'not_boxed') === 'boxed') {
            throw new Exception('This transfer has already been boxed.');
        }

        if ($numberOfBoxes < 1) {
            throw new Exception('Number of boxes must be at least 1.');
        }

        $referenceNo = $transfer->reference_no;
        $boxesData = [];

        if ($numberOfBoxes === 1) {
            $boxesData[] = [
                'transfer_id' => $transfer->id,
                'box_code' => $referenceNo,
                'destination' => $destination,
                'box_number' => 1,
                'total_boxes' => 1,
                'status' => 'pending',
                'created_by' => $userId,
            ];
        } else {
            for ($i = 1; $i <= $numberOfBoxes; $i++) {
                $boxesData[] = [
                    'transfer_id' => $transfer->id,
                    'box_code' => $referenceNo . '-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'destination' => $destination,
                    'box_number' => $i,
                    'total_boxes' => $numberOfBoxes,
                    'status' => 'pending',
                    'created_by' => $userId,
                ];
            }
        }

        $boxes = $this->boxRepository->createMany($boxesData);

        $this->transferRepository->markAsBoxed($transfer->id);

        return $boxes;
    }


 public function getAllBoxes(string $locationCode)  {
    

    $boxes=$this->boxRepository->getBoxesByLocation($locationCode) ;


    return $boxes ;



 }


}