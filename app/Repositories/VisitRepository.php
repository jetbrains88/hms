<?php

namespace App\Repositories;

use App\Models\Visit;

class VisitRepository
{
    public function startConsultation(int $visitId, int $doctorId): bool
    {
        $visit = Visit::findOrFail($visitId);

        if ($visit->status === 'waiting') {
            $visit->update([
                'status' => 'in_progress',
                'doctor_id' => $doctorId,
            ]);
            return true;
        }

        return false;
    }

    public function completeConsultation(int $visitId): bool
    {
        $visit = Visit::findOrFail($visitId);

        if ($visit->status === 'in_progress' && $visit->diagnoses()->exists()) {
            $visit->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            return true;
        }

        return false;
    }

    public function cancelConsultation(int $visitId, string $reason): bool
    {
        $visit = Visit::findOrFail($visitId);

        if (in_array($visit->status, ['waiting', 'in_progress'])) {
            $visit->update([
                'status' => 'cancelled',
                'notes' => $reason,
            ]);
            return true;
        }

        return false;
    }
}
