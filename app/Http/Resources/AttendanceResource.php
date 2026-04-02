<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'date'          => $this->attendance_date?->format('Y-m-d'),
            'status'        => $this->status,
            'late_minutes'  => $this->late_minutes,
            'working_hours' => $this->working_minutes
                ? round($this->working_minutes / 60, 2)
                : null,
            'check_in' => [
                'time'       => $this->check_in_time?->format('H:i:s'),
                'latitude'   => $this->check_in_latitude,
                'longitude'  => $this->check_in_longitude,
                'photo'      => $this->check_in_photo
                    ? asset('storage/' . $this->check_in_photo)
                    : null,
                'gps_valid'  => $this->check_in_gps_valid,
                'distance_m' => $this->check_in_distance_meters,
            ],
            'check_out' => [
                'time'      => $this->check_out_time?->format('H:i:s'),
                'latitude'  => $this->check_out_latitude,
                'longitude' => $this->check_out_longitude,
                'photo'     => $this->check_out_photo
                    ? asset('storage/' . $this->check_out_photo)
                    : null,
                'gps_valid' => $this->check_out_gps_valid,
            ],
            'shift' => $this->whenLoaded('workShift', fn() => [
                'id'         => $this->workShift->id,
                'name'       => $this->workShift->name,
                'start_time' => $this->workShift->start_time,
                'end_time'   => $this->workShift->end_time,
            ]),
            'notes'      => $this->notes,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
