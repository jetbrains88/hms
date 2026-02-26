<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $parameter = $this->labTestParameter;

        return [
            'id' => $this->id,
            'value_type' => $this->value_type,
            'numeric_value' => $this->numeric_value,
            'text_value' => $this->text_value,
            'boolean_value' => $this->boolean_value,
            'display_value' => $this->getDisplayValueAttribute(),
            'is_abnormal' => $this->is_abnormal,
            'remarks' => $this->remarks,
            'parameter' => $parameter ? [
                'id' => $parameter->id,
                'name' => $parameter->name,
                'unit' => $parameter->unit,
                'reference_range' => $parameter->reference_range,
                'min_range' => $parameter->min_range,
                'max_range' => $parameter->max_range,
                'group_name' => $parameter->group_name,
            ] : null,
            'created_at' => $this->created_at,
        ];
    }
}
