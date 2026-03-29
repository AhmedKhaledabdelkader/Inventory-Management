<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lot extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'lot_code',
        'destination',
        'total_boxes',
        'total_items',
        'status',
        'created_by',
        "runner_id",
        "assigned_at",
    'in_transit_at',
    'delivered_at',
    'delivery_notes',
    'delivery_photo_path',
    'received_at',
    'received_by',
    'receipt_notes',
    ];

    public function boxes()
    {
        return $this->hasMany(Box::class, 'lot_id');
    }


    
     protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

      public function user()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function runner()
{
    return $this->belongsTo(User::class, 'runner_id');
}

public function receiver()
{
    return $this->belongsTo(User::class, 'received_by');
}

protected $casts = [
    'assigned_at' => 'datetime',
    'in_transit_at' => 'datetime',
    'delivered_at' => 'datetime',
     'received_at' => 'datetime',
];


}
