<?php

namespace App\Events;

use App\Models\Visit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PatientWaiting implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visit;
    public $message;

    public function __construct(Visit $visit)
    {
        $this->visit = $visit;
        $this->message = "Patient {$visit->patient->name} is waiting";
    }

    public function broadcastOn()
    {
        return new Channel('doctor.' . $this->visit->doctor_id);
    }

    public function broadcastAs()
    {
        return 'patient.waiting';
    }
}