<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'gender' => $this->user->gender,
                'phone' => $this->user->phone,
            ],
            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
            ],
            'hospital' => [
                'id' => $this->hospital?->id,
                'name' => $this->hospital?->name,
            ],
            'examination_date' => $this->examination_date,
            'diagnosis' => $this->diagnosis,
            'prescription' => $this->prescription,
            'additional_notes' => $this->additional_notes,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
