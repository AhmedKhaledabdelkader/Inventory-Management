<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
        'total_users' => $this['total_users'],
        'active_users' => $this['active_users'],
        'blocked_users' => $this['blocked_users'],
        'administrators' => $this['administrators'],
    ];
       
    }
}
