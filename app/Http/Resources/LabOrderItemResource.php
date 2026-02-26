<?php

namespace App\Http\Resources;

use App\Http\Resources\LabResultResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabOrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'lab_test_type' => $this->labTestType ? [
                'id' => $this->labTestType->id,
                'name' => $this->labTestType->name,
                'department' => $this->labTestType->department,
                'sample_type' => $this->labTestType->sample_type,
            ] : null,
            'technician' => $this->technician ? [
                'id' => $this->technician->id,
                'name' => $this->technician->name,
            ] : null,
            'lab_results' => LabResultResource::collection($this->whenLoaded('labResults')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
