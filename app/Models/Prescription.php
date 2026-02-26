<?php

namespace App\Models;

use App\Models\Diagnosis;
use App\Models\Medicine;
use App\Models\User;
use App\Traits\Auditable;
use App\Traits\HasUUID;
use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasUUID, MultiTenant, Auditable;

    protected $fillable = [
        'uuid',
        'branch_id',
        'diagnosis_id',
        'medicine_id',
        'prescribed_by',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'status',
        'instructions'
    ];

    protected $casts = [
        'status' => 'string',
        'frequency' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * Get the diagnosis associated with the prescription.
     */
    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }


    /**
     * Get the doctor who prescribed the medicine.
     */
    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }

    /**
     * Get the pharmacist who dispensed.
     */
    public function dispenser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    /**
     * Scope for pending prescriptions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for dispensed prescriptions.
     */
    public function scopeDispensed($query)
    {
        return $query->where('status', 'dispensed');
    }

    /**
     * Scope for cancelled prescriptions.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Get remaining refills.
     */
    public function getRemainingRefillsAttribute(): int
    {
        return max(0, $this->refills_allowed - $this->refills_used);
    }

    /**
     * Check if prescription is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if prescription is dispensed.
     */
    public function isDispensed(): bool
    {
        return $this->status === 'dispensed';
    }

    /**
     * Check if prescription can be dispensed.
     */
    public function canBeDispensed(): bool
    {
        return $this->isPending()
            && $this->medicine
            && $this->medicine->stock >= $this->quantity;
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }



    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function prescribedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }

    public function dispensations(): HasMany
    {
        return $this->hasMany(PrescriptionDispensation::class);
    }

    public function getDispensedQuantityAttribute(): int
    {
        return $this->dispensations()->sum('quantity_dispensed');
    }

    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity - $this->dispensed_quantity;
    }

    public function getIsFullyDispensedAttribute(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    public function getDispensedAtAttribute()
    {
        $lastDispensation = $this->dispensations()->latest('dispensed_at')->first();
        return $lastDispensation ? $lastDispensation->dispensed_at : $this->updated_at;
    }
}
