<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\TransferDroppedByQcNotification;
use App\Repositories\Contracts\TransferIssueRepositoryInterface;
use App\Repositories\Contracts\TransferRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;

class TransferIssueService
{
    public function __construct(
        protected TransferRepositoryInterface $transferRepository,
        protected TransferIssueRepositoryInterface $transferIssueRepository,
        protected UserRepositoryInterface $userRepository
    ) {}

    public function reportIssueAndDrop(string $transferId, array $data, string $qcUserId)
    {
        $transfer = $this->transferRepository->findById($transferId);

        if (!$transfer) {
      
            return null ;
           // return response()->json(['status'=>'error','message'=>'Transfer not found']);
        }

        if ($transfer->verification_status === 'verified') {
           // throw new Exception('Verified transfer cannot be dropped.');
           return 'verify' ;
        }

        $issue = $this->transferIssueRepository->create([
            'transfer_id' => $transfer->id,
            'issue_type' => $data['issue_type'],
            'description' => $data['description'],
            'status' => 'open',
            'reported_at' => now(),
            'reported_by' => $qcUserId,
            'notified_user_id' => $transfer->prepared_by,
        ]);

        $this->transferRepository->markDroppedFromQc(
            $transfer->id,
            $data['description']
        );

        $this->notifyPicker($transfer->prepared_by, $transfer, $issue);

        return [
            'issue_id' => $issue->id,
            'transfer_id' => $transfer->id,
            'transfer_dropped' => true,
        ];
    }

    protected function notifyPicker(?string $pickerId, $transfer, $issue): void
    {
        if (!$pickerId) {
            return;
        }

        $picker = $this->userRepository->findById($pickerId);

        if (!$picker) {
            return;
        }

        $picker->notify(new TransferDroppedByQcNotification($transfer, $issue));
    }
}