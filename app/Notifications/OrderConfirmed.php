<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderConfirmed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Order $order
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $fullName = trim(($notifiable->first_name ?? '') . ' ' . ($notifiable->last_name ?? ''));
        if (empty($fullName)) {
            $fullName = $notifiable->email ?? 'Customer';
        }

        return (new MailMessage)
                    ->subject('Order Confirmed - Order #' . $this->order->id)
                    ->greeting('Hello ' . $fullName . ',')
                    ->line('Your order has been confirmed!')
                    ->line('Reminder: You have 24 hours to pay and pick up your order before it’s canceled.')
                    ->line('Order Number: #' . $this->order->id)
                    ->line('Total Amount: ₱' . number_format($this->order->total_price, 2))
                    ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
