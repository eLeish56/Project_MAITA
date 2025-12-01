<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Permintaan Reset Password - SMK Muhammadiyah 1 Palembang')
            ->view('emails.reset-password', [
                'notifiable' => $notifiable,
                'token' => $this->token,
            ]);
    }
}
