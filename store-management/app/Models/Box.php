<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Box extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'transfer_id',
        'box_code',
        'destination',
        'box_number',
        'total_boxes',
        'status',
        'created_by',
        'lot_id'
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

    public function lot()
{
    return $this->belongsTo(Lot::class, 'lot_id');
}


}