<?php

namespace App\Services;

use App\Repositories\Contracts\LotRepositoryInterface;
use App\Traits\HandlesFileUpload;
use Exception;

class RunnerService
{

    use HandlesFileUpload ;

    public function __construct(protected LotRepositoryInterface $lotRepository,protected ImageConverterService $imageConverterService){}


public function pickupByCode(string $runnerId, string $lotCode): array
    {
        $lot = $this->lotRepository->findByCode($lotCode);

        if (!$lot) {
            throw new Exception('LOT not found.');
        }

        if ($lot->runner_id !== $runnerId) {
            throw new Exception('This LOT is not assigned to you.');
        }

        if ($lot->status !== 'ready_to_go') {
            throw new Exception('Only ready-to-go LOTs can be picked up.');
        }

        $this->lotRepository->updateStatus($lot->id, [
            'status' => 'in_transit',
            'in_transit_at' => now(),
        ]);

        return [
            'lot_id' => $lot->id,
            'lot_code' => $lot->lot_code,
            'status' => 'in_transit',
        ];
    }

    public function updateStatusManually(string $runnerId, string $lotId, string $status): bool
    {
        $lot = $this->lotRepository->findById($lotId);

        if (!$lot) {
            throw new Exception('LOT not found.');
        }

        if ($lot->runner_id !== $runnerId) {
            throw new Exception('This LOT is not assigned to you.');
        }

        if (!in_array($status, ['ready_to_go', 'in_transit'])) {
            throw new Exception('Invalid manual status.');
        }

        if ($status === 'in_transit' && $lot->status !== 'ready_to_go') {
            throw new Exception('LOT must be ready_to_go before moving to in_transit.');
        }

        return $this->lotRepository->updateStatus($lotId, [
            'status' => $status,
            'in_transit_at' => $status === 'in_transit' ? now() : $lot->in_transit_at,
        ]);
    }

    public function markAsDelivered(array $data,string $runnerId, string $lotId): bool
    {
        $lot = $this->lotRepository->findById($lotId);

        if (!$lot) {
            throw new Exception('LOT not found.');
        }

        if ($lot->runner_id !== $runnerId) {
            throw new Exception('This LOT is not assigned to you.');
        }

        if ($lot->status !== 'in_transit') {
            throw new Exception('Only in-transit LOTs can be marked as delivered.');
        }

         $data["delivery_photo"] = $this->uploadFile($data['delivery_photo'] ?? null, 
         'deliveries', $this->imageConverterService);

        return $this->lotRepository->updateStatus($lotId, [
            'status' => 'delivered',
            'delivered_at' => now(),
            'delivery_notes' => $data["delivery_notes"],
            'delivery_photo_path' => $data["delivery_photo"]??null ,
        ]);
    }

    public function getDashboard(string $runnerId): array
    {
        return $this->lotRepository->getRunnerDashboardStats($runnerId);
    }

    public function getRunnerLots(string $runnerId, ?string $status = null)
    {
        return $this->lotRepository->getRunnerLots($runnerId, $status);
    }
}














    

