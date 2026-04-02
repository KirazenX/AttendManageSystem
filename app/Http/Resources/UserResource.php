<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'employee_id' => $this->employee_id,
            'phone'       => $this->phone,
            'avatar'      => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'gender'      => $this->gender,
            'join_date'   => $this->join_date?->format('Y-m-d'),
            'is_active'   => $this->is_active,
            'department'  => $this->whenLoaded('department', fn() => [
                'id'   => $this->department->id,
                'name' => $this->department->name,
                'code' => $this->department->code,
            ]),
            'roles'       => $this->whenLoaded('roles', fn() =>
                $this->roles->pluck('name')
            ),
            'permissions' => $this->whenLoaded('permissions', fn() =>
                $this->getAllPermissions()->pluck('name')
            ),
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
