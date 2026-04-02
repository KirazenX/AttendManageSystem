<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkScheduleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'effective_date' => $this->effective_date?->format('Y-m-d'),
            'end_date'       => $this->end_date?->format('Y-m-d'),
            'is_active'      => $this->is_active,
            'notes'          => $this->notes,
            'user'           => $this->whenLoaded('user', fn() => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]),
            'work_shift' => $this->whenLoaded('workShift', fn() =>
                new WorkShiftResource($this->workShift)
            ),
            'created_at' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
