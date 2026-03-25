<?php

namespace App\Notifications;

use App\Models\Transfer;
use App\Models\TransferIssue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransferDroppedByQcNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Transfer $transfer,
        protected TransferIssue $issue
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'transfer_dropped_by_qc',
            'transfer_id' => $this->transfer->id,
            'reference_no' => $this->transfer->reference_no,
            'issue_type' => $this->issue->issue_type,
            'description' => $this->issue->description,
            'message' => 'QC dropped transfer ' . $this->transfer->reference_no . ' بسبب مشكلة في التحقق',
            'reported_at' => $this->issue->reported_at?->toISOString(),
        ];
    }
}