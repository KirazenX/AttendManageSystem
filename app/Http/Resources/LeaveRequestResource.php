<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'leave_type'       => $this->whenLoaded('leaveType', fn() => [
                'id'   => $this->leaveType->id,
                'name' => $this->leaveType->name,
                'code' => $this->leaveType->code,
            ]),
            'start_date'       => $this->start_date?->format('Y-m-d'),
            'end_date'         => $this->end_date?->format('Y-m-d'),
            'total_days'       => $this->total_days,
            'reason'           => $this->reason,
            'attachment'       => $this->attachment
                ? asset('storage/' . $this->attachment)
                : null,
            'status'           => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'approved_by'      => $this->whenLoaded('approvedBy', fn() =>
                $this->approvedBy?->name
            ),
            'approved_at'      => $this->approved_at?->format('Y-m-d H:i:s'),
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
