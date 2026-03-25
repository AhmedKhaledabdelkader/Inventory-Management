<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class TransferIssue extends Model
{
      public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'transfer_id',
        'issue_type',
        'description',
        'status',
        'reported_at',
        'reported_by',
        'notified_user_id',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }
}
