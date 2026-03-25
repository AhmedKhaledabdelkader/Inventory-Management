<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Transfer extends Model
{

    
     public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'external_id',
        'reference_no',
        'external_status',
        'from_warehouse',
        'to_warehouse',
        'qty',
        'sku_count',
        'title',
        'items_names',
        'items',
        'current_action',
        'is_missing_from_api',
        'payload',
        'drop_reason',
        'verification_status',
        'verified_at',
        'verification_method',
        'verification_notes',
        'external_updated_at',
        'last_synced_at',
        'dropped_at',
        'prepared_at',
        'prepared_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'items' => 'array',
        'external_updated_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'is_missing_from_api' => 'boolean',
        'dropped_at' => 'datetime',
        'prepared_at' => 'datetime',
        'verified_at' => 'datetime',
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


    public function preparedBy()
{
    return $this->belongsTo(User::class, 'prepared_by');
}

public function issues()
{
    return $this->hasMany(TransferIssue::class, 'transfer_id');
}

    
}
