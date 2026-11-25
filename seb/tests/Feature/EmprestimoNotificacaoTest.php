<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\Turma;
use App\Models\User;
use App\Notifications\EmprestimoAtrasado;
use App\Notifications\EmprestimoDevolvido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmprestimoNotificacaoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: User, 1: Turma, 2: Aluno, 3: Livro}
     */
    private function cenario(): array
    {
        $user = User::create([
            'name' => 'Usuário Notificação',
            'email' => 'notificacao'.uniqid().'@example.com',
            'password' => 'senha-segura',
        ]);

        $user->markEmailAsVerified();

        $this->actingAs($user);

        $turma = Turma::create([
            'nome_turma' => 'Turma Notificação',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ]);

        $aluno = $turma->alunos()->create([
            'nome' => 'Aluno Notificação',
            'telefone' => '11999999999',
        ]);

        $livro = Livro::create([
            'titulo' => 'Livro Notificação',
            'autor' => 'Autor Notificação',
            'ano_publicacao' => 2024,
            'codigo' => '987654',
            'estoque' => 2,
        ]);

        return [$user, $turma, $aluno, $livro];
    }

    private function criarEmprestimo(Aluno $aluno, Livro $livro, array $atributos = []): Emprestimo
    {
        return Emprestimo::create(array_merge([
            'aluno_id' => $aluno->id,
            'livro_id' => $livro->id,
            'data_emprestimo' => today(),
            'data_devolucao_prevista' => today()->addDays(7),
        ], $atributos));
    }

    public function test_devolucao_envia_email_confirmando_a_devolucao(): void
    {
        Notification::fake();

        [$user, , $aluno, $livro] = $this->cenario();
        $emprestimo = $this->criarEmprestimo($aluno, $livro);

        $this->actingAs($user)
            ->put(route('emprestimo.devolver', $emprestimo))
            ->assertRedirect();

        Notification::assertSentTo(
            $user,
            EmprestimoDevolvido::class,
            fn (EmprestimoDevolvido $notification) => $notification->emprestimo->is($emprestimo)
        );

        $this->assertNotNull($emprestimo->fresh()->data_devolucao);
    }

    public function test_devolucao_ja_registrada_nao_envia_outro_email(): void
    {
        Notification::fake();

        [$user, , $aluno, $livro] = $this->cenario();
        $emprestimo = $this->criarEmprestimo($aluno, $livro);

        $this->actingAs($user)->put(route('emprestimo.devolver', $emprestimo));
        $this->actingAs($user)->put(route('emprestimo.devolver', $emprestimo));

        Notification::assertSentToTimes($user, EmprestimoDevolvido::class, 1);
    }

    public function test_emails_de_atraso_e_devolucao_sao_montados_corretamente(): void
    {
        [$user, , $aluno, $livro] = $this->cenario();

        $atrasado = $this->criarEmprestimo($aluno, $livro, [
            'data_devolucao_prevista' => today()->subDays(2),
        ]);

        $mailAtraso = (new EmprestimoAtrasado($atrasado))->toMail($user);
        $this->assertSame('Empréstimo em atraso', $mailAtraso->subject);
        $this->assertStringContainsString('Atraso de 2 dia(s)', $mailAtraso->introLines[2]);

        $atrasado->update(['data_devolucao' => today()]);
        $devolvido = $atrasado->fresh(['aluno', 'livro']);

        $mailDevolucao = (new EmprestimoDevolvido($devolvido))->toMail($user);
        $this->assertSame('Devolução registrada', $mailDevolucao->subject);
    }

    public function test_comando_notifica_emprestimos_atrasados_uma_vez_por_dia(): void
    {
        Notification::fake();

        [$user, , $aluno, $livro] = $this->cenario();

        $atrasado = $this->criarEmprestimo($aluno, $livro, [
            'data_devolucao_prevista' => today()->subDays(2),
        ]);

        // Já devolvido: não deve gerar aviso de atraso.
        $this->criarEmprestimo($aluno, $livro, [
            'data_devolucao_prevista' => today()->subDays(10),
            'data_devolucao' => today(),
        ]);

        $this->artisan('emprestimos:notificar-atrasados')->assertExitCode(0);

        Notification::assertSentToTimes($user, EmprestimoAtrasado::class, 1);
        $this->assertTrue($atrasado->fresh()->notificado_atraso_em->isToday());

        // Rodar novamente no mesmo dia não repete o e-mail.
        $this->artisan('emprestimos:notificar-atrasados')->assertExitCode(0);
        Notification::assertSentToTimes($user, EmprestimoAtrasado::class, 1);

        // No dia seguinte, enquanto continuar em atraso, avisa novamente.
        $this->travel(1)->days();

        $this->artisan('emprestimos:notificar-atrasados')->assertExitCode(0);
        Notification::assertSentToTimes($user, EmprestimoAtrasado::class, 2);
    }

    public function test_comando_nao_notifica_emprestimos_no_prazo(): void
    {
        Notification::fake();

        [$user, , $aluno, $livro] = $this->cenario();
        $this->criarEmprestimo($aluno, $livro);

        $this->artisan('emprestimos:notificar-atrasados')->assertExitCode(0);

        Notification::assertNothingSentTo($user);
    }
}
