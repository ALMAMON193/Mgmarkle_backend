<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRescheduledNotification extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Rescheduled')
            ->line('Your booking for the event "'.$this->booking->event->title.'" has been rescheduled by the spiritual guide.')
            ->line('New Date: '.$this->booking->starts_at->format('M d, Y'))
            ->line('New Time: '.$this->booking->starts_at->format('h:i A').' - '.$this->booking->ends_at->format('h:i A'))
            ->action('View Booking', url('/seeker/appointments'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'event_title' => $this->booking->event->title,
            'message' => 'Your booking for "'.$this->booking->event->title.'" has been rescheduled to '.$this->booking->starts_at->format('M d, Y h:i A'),
            'type' => 'booking_rescheduled',
        ];
    }
}
