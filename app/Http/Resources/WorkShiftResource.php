<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkShiftResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                               => $this->id,
            'name'                             => $this->name,
            'start_time'                       => $this->start_time,
            'end_time'                         => $this->end_time,
            'crosses_midnight'                 => $this->crosses_midnight,
            'late_tolerance_minutes'           => $this->late_tolerance_minutes,
            'early_checkout_tolerance_minutes' => $this->early_checkout_tolerance_minutes,
            'working_days'                     => $this->working_days,
            'working_days_label'               => $this->workingDaysLabel(),
            'is_active'                        => $this->is_active,
            'description'                      => $this->description,
            'created_at'                       => $this->created_at?->format('Y-m-d'),
        ];
    }

    private function workingDaysLabel(): string
    {
        $map = [0=>'Sun',1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat'];
        return implode(', ', array_map(fn($d) => $map[$d] ?? $d, $this->working_days ?? []));
    }
}
