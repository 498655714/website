<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use \Illuminate\Auth\Notifications\ResetPassword as Rp;

class ResetPassword extends Rp implements ShouldQueue
{
    use Queueable;
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('重置密码')
            ->line('您收到此电子邮件是因为我们收到了您帐户的密码重置请求。')
            ->action('重设密码', url($notifiable->getGuard().'/password/reset/'.$this->token))
//            ->action('重设密码', url(config('app.url').$notifiable->getGuard().'/password/reset/'.$this->token))
            ->line('如果您未请求重置密码，则无需操作。')
            ->level('green');
    }

}
