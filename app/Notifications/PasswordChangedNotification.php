<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Konfirmasi Perubahan Password')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Password akun Anda baru saja diubah.')
            ->line('Jika Anda tidak melakukan perubahan ini, segera hubungi tim support kami.')
            ->action('Masuk ke Akun', url('/login'))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }
}
