<?php

namespace ClassyPOS\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Password;

class TenantCreated extends Notification
{
    use Queueable;

    private $hostname;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $token = Password::broker()->createToken($notifiable);
        $resetUrl = "http://{$this->hostname->fqdn}/password/reset/{$token}";

        $app = config('app.name');

        return (new MailMessage)
            ->subject("{$app} Registration")
            ->greeting("Hello {$notifiable->name}")
            ->line("You have been registered to use {$app}!")
            ->line('Your password is ' . $notifiable->name . '@123')
            ->line('You should reset the password.')
            ->action('Reset password', $resetUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
