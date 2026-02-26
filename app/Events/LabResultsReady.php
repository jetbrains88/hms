<?php

namespace App\Events;

use App\Models\LabOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LabResultsReady implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $labOrder;
    public $message;

    public function __construct(LabOrder $labOrder)
    {
        $this->labOrder = $labOrder;
        $this->message = "Lab results ready for patient {$labOrder->patient->name}";
    }

    public function broadcastOn()
    {
        return new Channel('doctor.' . $this->labOrder->doctor_id);
    }

    public function broadcastAs()
    {
        return 'lab.results.ready';
    }
}