<?php

namespace App\Notifications;

use App\Models\Emprestimo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmprestimoDevolvido extends Notification
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
        $devolucao = $this->emprestimo->data_devolucao;

        $mensagem = (new MailMessage)
            ->subject('Devolução registrada')
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line("A devolução do livro \"{$livro}\" do aluno {$aluno} foi registrada em ".$devolucao?->format('d/m/Y').'.')
            ->line('O exemplar já está disponível novamente no acervo.');

        if ($prevista && $devolucao && $prevista->lt($devolucao)) {
            $diasAtraso = max(1, (int) abs($prevista->startOfDay()->diffInDays($devolucao->startOfDay())));

            $mensagem->line("A devolução ocorreu com {$diasAtraso} dia(s) de atraso. A data prevista era ".$prevista->format('d/m/Y').'.');
        }

        return $mensagem
            ->action('Ver empréstimos', route('emprestimo.index'))
            ->salutation('Atenciosamente, Equipe SEB ;)');
    }
}
