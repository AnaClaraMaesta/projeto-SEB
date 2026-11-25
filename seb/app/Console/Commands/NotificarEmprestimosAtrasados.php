<?php

namespace App\Console\Commands;

use App\Models\Emprestimo;
use App\Notifications\EmprestimoAtrasado;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificarEmprestimosAtrasados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emprestimos:notificar-atrasados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia e-mail aos usuários sobre empréstimos em atraso (no máximo uma vez por dia por empréstimo)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $emprestimos = Emprestimo::query()
            ->atrasados()
            ->where(function ($query) {
                $query->whereNull('notificado_atraso_em')
                    ->orWhereDate('notificado_atraso_em', '<', today());
            })
            ->with(['aluno.turma.user', 'livro'])
            ->get();

        $enviados = 0;

        foreach ($emprestimos as $emprestimo) {
            $usuario = $emprestimo->usuario();

            if (! $usuario) {
                continue;
            }

            try {
                $usuario->notify(new EmprestimoAtrasado($emprestimo));
            } catch (Throwable $e) {
                Log::warning('Falha ao enviar notificação de atraso do empréstimo.', [
                    'emprestimo_id' => $emprestimo->id,
                    'erro' => $e->getMessage(),
                ]);

                continue;
            }

            $emprestimo->forceFill(['notificado_atraso_em' => today()])->save();
            $enviados++;
        }

        $this->info("Notificações de atraso enviadas: {$enviados}.");

        return self::SUCCESS;
    }
}
