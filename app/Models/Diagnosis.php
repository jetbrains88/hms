<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Prescription;
use App\Models\User;
use App\Models\Visit;
use App\Traits\Auditable;
use App\Traits\HasUUID;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use SoftDeletes, HasUUID, MultiTenant, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'uuid',
        'branch_id',
        'visit_id',
        'doctor_id',
        'symptoms',
        'diagnosis',
        'doctor_notes',
        'recommendations',
        'followup_date',
        'is_chronic',
        'is_urgent',
        'severity',
        'has_prescription'
    ];

    protected $casts = [
        'has_prescription' => 'boolean',
        'is_chronic' => 'boolean',
        'is_urgent' => 'boolean',
        'follow_up_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the visit associated with the diagnosis.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the doctor who made the diagnosis.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the prescriptions for the diagnosis.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Check if diagnosis requires follow-up.
     */
    public function requiresFollowUp()
    {
        return !empty($this->follow_up_date);
    }

    /**
     * Get severity badge color.
     */
    public function getSeverityBadgeAttribute()
    {
        $colors = [
            'mild' => 'bg-green-100 text-green-800',
            'moderate' => 'bg-yellow-100 text-yellow-800',
            'severe' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
        ];

        $color = $colors[$this->severity] ?? 'bg-gray-100 text-gray-800';

        return '<span class="px-2 py-1 text-xs rounded-full ' . $color . '">' .
            ucfirst($this->severity) .
            '</span>';
    }
}
