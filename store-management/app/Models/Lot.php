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
        "assigned_at"
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


}
