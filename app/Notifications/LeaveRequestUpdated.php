<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly LeaveRequest $leaveRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status    = ucfirst($this->leaveRequest->status);
        $leaveType = $this->leaveRequest->leaveType->name;
        $startDate = $this->leaveRequest->start_date->format('d M Y');
        $endDate   = $this->leaveRequest->end_date->format('d M Y');

        $mail = (new MailMessage)
            ->subject("Leave Request {$status} — {$leaveType}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your leave request has been **{$status}**.")
            ->line("**Leave Type:** {$leaveType}")
            ->line("**Period:** {$startDate} to {$endDate} ({$this->leaveRequest->total_days} days)");

        if ($this->leaveRequest->rejection_reason) {
            $mail->line("**Reason:** {$this->leaveRequest->rejection_reason}");
        }

        return $mail->line('Thank you.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'             => 'leave_request_updated',
            'leave_request_id' => $this->leaveRequest->id,
            'status'           => $this->leaveRequest->status,
            'leave_type'       => $this->leaveRequest->leaveType->name,
        ];
    }
}
