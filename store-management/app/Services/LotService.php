<?php

namespace App\Services;

use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Repositories\Contracts\LotRepositoryInterface;
use Illuminate\Support\Str;
use Exception;

class LotService
{
    public function __construct(
        protected LotRepositoryInterface $lotRepository,
        protected BoxRepositoryInterface $boxRepository
    ) {}

    public function createLot(array $boxIds, string $destination, string $userId)
    {
        if (empty($boxIds)) {
            throw new Exception('No boxes selected.');
        }

        $boxes = $this->boxRepository->findByIds($boxIds);

        if ($boxes->count() !== count($boxIds)) {
            throw new Exception('One or more boxes were not found.');
        }

        foreach ($boxes as $box) {
            if ($box->destination !== $destination) {
                throw new Exception('All selected boxes must have the same destination.');
            }

            if (!is_null($box->lot_id)) {
                throw new Exception("Box {$box->box_code} is already assigned to another LOT.");
            }

            if ($box->status !== 'pending') {
                throw new Exception("Box {$box->box_code} is not available for LOT creation.");
            }
        }

        $lotCode = $this->generateUniqueLotCode();

        $totalBoxes = $boxes->count();
        $totalItems = $boxes->sum(function ($box) {
            return $box->transfer->qty ?? 0;
        });

        $lot = $this->lotRepository->create([
            'lot_code' => $lotCode,
            'destination' => $destination,
            'total_boxes' => $totalBoxes,
            'total_items' => $totalItems,
            'status' => 'pending',
            'created_by' => $userId,
        ]);

        foreach ($boxes as $box) {
            $this->boxRepository->assignToLot($box->id, $lot->id);
        }

        return $lot->load('boxes.transfer');
    }

    protected function generateUniqueLotCode(): string
    {
        do {
            $code = 'LOT-' . random_int(1000000000, 9999999999);
        } while (\App\Models\Lot::where('lot_code', $code)->exists());

        return $code;
    }


    public function getAllLots(){


        $lots=$this->lotRepository->getAll() ;

        return $lots ;



    }


    
     public function getAvaliableBoxes(array $data)
    {
        return $this->boxRepository->getAvailableBoxes($data['destination']??"",$data['search']??"");
    }
    


}