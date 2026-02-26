<?php

namespace App\Events;

use App\Models\StockAlert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAlertGenerated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alert;
    public $message;

    public function __construct(StockAlert $alert)
    {
        $this->alert = $alert;
        $this->message = $alert->message;
    }

    public function broadcastOn()
    {
        return new Channel('pharmacy.' . $this->alert->branch_id);
    }

    public function broadcastAs()
    {
        return 'stock.alert';
    }
}