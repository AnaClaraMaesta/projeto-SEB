<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class CustomVerifyEmail extends Notification
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
        ->subject('Verificação de email')
        ->greeting('Ola,' . $notifiable -> name)
        ->line('Por favor verifique o seu email é muito importante, basta clicar no botão abaixo')
        ->action('Verificar Email', $verificacaoURL)
        ->line('Este link de verificação expira em ' . Config::get('auth.verification.expire', 1) . ' minutos.')
        ->line('Se você não criou nenhuma conta em nosso sistema, ignore essa mensagem')
        ->salutation('Atenciosamente, Equipe SEB ;)');
    }

    protected function verificacaoURL($notifiable): string
    {
       return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 1)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

    }

}



