<?php

namespace App\Notifications;

use App\Models\Emprestimo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmprestimoAtrasado extends Notification
{
    use Queueable;

    public function __construct(public Emprestimo $emprestimo)
    {
        $this->emprestimo->loadMissing(['aluno', 'livro']);
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
        $livro = $this->emprestimo->livro?->titulo ?? 'Livro removido do acervo';
        $aluno = $this->emprestimo->aluno?->nome ?? 'Aluno';
        $prevista = $this->emprestimo->data_devolucao_prevista;
        $diasAtraso = $prevista
            ? max(1, (int) abs($prevista->startOfDay()->diffInDays(today()->startOfDay())))
            : 0;

        return (new MailMessage)
            ->subject('Empréstimo em atraso')
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line("O empréstimo do livro \"{$livro}\" para o aluno {$aluno} está em atraso.")
            ->line('A devolução estava prevista para '.$prevista?->format('d/m/Y').'.')
            ->line("Atraso de {$diasAtraso} dia(s).")
            ->action('Ver empréstimos', route('emprestimo.index'))
            ->line('Assim que o livro for devolvido, registre a devolução no sistema.')
            ->salutation('Atenciosamente, Equipe SEB ;)');
    }
}
