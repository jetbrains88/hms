<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LabResultReady extends Notification implements ShouldQueue
{
    use Queueable;

    public $labOrder;
    public $verifierName;

    /**
     * Create a new notification instance.
     */
    public function __construct($labOrder, $verifierName)
    {
        $this->labOrder = $labOrder;
        $this->verifierName = $verifierName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Lab results are ready for ' . $this->labOrder->patient->name)
            ->action('View Report', route('lab.reports.show', $this->labOrder->id))
            ->line('Verified by: ' . $this->verifierName);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'lab_result_ready',
            'order_id' => $this->labOrder->id,
            'lab_number' => $this->labOrder->lab_number,
            'patient_name' => $this->labOrder->patient->name,
            'test_name' => $this->labOrder->test_name ?? $this->labOrder->testType->name ?? 'Unknown Test',
            'verified_by' => $this->verifierName,
            'message' => "Lab results ready for {$this->labOrder->patient->name} ({$this->labOrder->lab_number})",
        ];
    }
}
