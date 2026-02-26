<?php

namespace App\Http\Resources;

use App\Http\Resources\LabOrderItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $testType = $this->items->first()?->labTestType;

        return [
            'id' => $this->id,
            'lab_number' => $this->lab_number,
            'test_name' => $testType?->name ?? 'Multiple Tests',
            'test_code' => $this->lab_number,
            'test_type' => $testType ? [
                'id' => $testType->id,
                'name' => $testType->name,
                'department' => $testType->department,
            ] : null,
            'patient' => $this->patient ? [
                'id' => $this->patient->id,
                'name' => $this->patient->name,
                'cnic' => $this->patient->cnic,
                'emrn' => $this->patient->emrn,
            ] : null,
            'doctor' => $this->doctor ? [
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
            ] : null,
            'status' => $this->status,
            'priority' => $this->priority,
            'is_verified' => $this->is_verified,
            'verified_by' => $this->verifiedBy?->name,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
            'reporting_date' => $this->reporting_date,
            'items' => LabOrderItemResource::collection($this->whenLoaded('items')),
            'results_count' => $this->items->sum(fn($item) => $item->labResults->count()),
        ];
    }
}
