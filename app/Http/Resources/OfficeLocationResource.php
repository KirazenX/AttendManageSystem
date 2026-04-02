<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfficeLocationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'address'       => $this->address,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'radius_meters' => $this->radius_meters,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at?->format('Y-m-d'),
        ];
    }
}
