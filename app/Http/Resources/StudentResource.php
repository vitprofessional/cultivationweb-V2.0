<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name ?? $this->fullName ?? null,
            'roll'        => $this->roll ?? $this->rollNumber ?? null,
            'email'       => $this->email ?? null,
            'mobile'      => $this->mobile ?? null,
            'session'     => $this->session ?? $this->sessionYear ?? null,
            'department'  => $this->department ?? null,
            'created_at'  => optional($this->created_at)->toIso8601String(),
            'updated_at'  => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
