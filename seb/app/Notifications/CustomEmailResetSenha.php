<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;


class CustomEmailResetSenha extends Notification
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
        $verificacaoURL = $this->verificacaoURL($notifiable);

        return (new MailMessage)
        ->subject('Redefinição de Senha')
        ->greeting('Ola')
        ->line('Para redefinir a sua senha, basta clicar no botão abaixo')
        ->action('Redefinir Senha', $verificacaoURL)
        ->line('Este link de Redefinição expira em ' . Config::get('auth.verification.expire', 5) . ' minutos.')
        ->line('Se você não criou nenhuma conta em nosso sistema, ignore essa mensagem')
        ->salutation('Atenciosamente, Equipe SEB ;)');
    }

    protected function verificacaoURL($notifiable): string
    {
       return URL::temporarySignedRoute(
            'reset.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 5)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

    }
}
