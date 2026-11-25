<?php

namespace Tests\Feature;

use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlunoEmprestimoStatusTest extends TestCase
{
    use RefreshDatabase;

    private function usuario(): User
    {
        $user = User::create([
            'name' => 'Usuário de Teste',
            'email' => 'teste'.uniqid().'@example.com',
            'password' => 'senha-segura',
        ]);

        $user->markEmailAsVerified();

        return $user;
    }

    private function cenario(): array
    {
        $user = $this->usuario();
        $this->actingAs($user);

        $turma = Turma::create([
            'nome_turma' => 'Turma Empréstimo',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ]);

        $aluno = $turma->alunos()->create([
            'nome' => 'Aluno Empréstimo',
            'telefone' => '11999999999',
        ]);

        $livro = Livro::create([
            'titulo' => 'Livro de Teste',
            'autor' => 'Autor de Teste',
            'ano_publicacao' => 2024,
            'codigo' => '654321',
            'estoque' => 2,
        ]);

        return [$user, $turma, $aluno, $livro];
    }

    private function emprestar(Turma $turma, $aluno, Livro $livro, int $diasPrazo = 7): Emprestimo
    {
        $this->actingAs($turma->user)->post('/emprestimo', [
            'aluno_id' => $aluno->id,
            'livro_id' => $livro->id,
            'dias_prazo' => $diasPrazo,
        ])->assertRedirect(route('emprestimo.index'));

        return $aluno->emprestimoAtivo()->firstOrFail();
    }

    public function test_aluno_sem_emprestimo_usa_o_rotulo_sem_emprestimo(): void
    {
        [, , $aluno] = $this->cenario();

        $this->assertSame('Sem emprestimo', $aluno->status);
        $this->assertSame('Sem empréstimo', $aluno->statusLabel);
    }

    public function test_aluno_com_emprestimo_no_prazo_usa_o_rotulo_emprestimo_ativo(): void
    {
        [$user, $turma, $aluno, $livro] = $this->cenario();

        $this->actingAs($user);
        $this->emprestar($turma, $aluno, $livro);

        $this->assertSame('No prazo', $aluno->fresh()->status);
        $this->assertSame('No prazo', $aluno->fresh()->statusLabel);
    }

    public function test_aluno_com_emprestimo_vencido_fica_atrasado(): void
    {
        [$user, $turma, $aluno, $livro] = $this->cenario();

        $this->actingAs($user);
        $emprestimo = $this->emprestar($turma, $aluno, $livro);

        $emprestimo->update([
            'data_devolucao_prevista' => today()->subDays(3),
        ]);

        $this->assertSame('Atrasado', $aluno->fresh()->status);
        $this->assertSame('Em atraso', $aluno->fresh()->statusLabel);
    }

    public function test_consultar_alunos_mostra_status_e_botao_de_emprestimo_apenas_sem_emprestimo_ativo(): void
    {
        [$user, $turma, $aluno, $livro] = $this->cenario();

        $this->actingAs($user)
            ->get(route('aluno.index'))
            ->assertOk()
            ->assertSee('Sem empréstimo')
            ->assertSee("abrir-add-emprestimo', { aluno_id: {$aluno->id} })", false);

        $this->emprestar($turma, $aluno, $livro);

        $this->actingAs($user)
            ->get(route('aluno.index'))
            ->assertOk()
            ->assertSee('No prazo')
            ->assertDontSee("abrir-add-emprestimo', { aluno_id: {$aluno->id} })", false);
    }

    public function test_detalhes_da_turma_mostram_status_e_botao_de_emprestimo_apenas_sem_emprestimo_ativo(): void
    {
        [$user, $turma, $aluno, $livro] = $this->cenario();

        $this->actingAs($user)
            ->get(route('turma.show', $turma))
            ->assertOk()
            ->assertSee('Sem empréstimo')
            ->assertSee("abrir-add-emprestimo', { aluno_id: {$aluno->id} })", false);

        $this->emprestar($turma, $aluno, $livro);

        $this->actingAs($user)
            ->get(route('turma.show', $turma))
            ->assertOk()
            ->assertSee('No prazo')
            ->assertDontSee("abrir-add-emprestimo', { aluno_id: {$aluno->id} })", false);
    }

    public function test_relatorio_de_alunos_mostra_o_rotulo_do_status(): void
    {
        [$user, $turma, $aluno, $livro] = $this->cenario();

        $html = view('aluno.pdf', [
            'alunos' => collect([$aluno]),
            'turma' => $turma,
        ])->render();

        $this->assertStringContainsString('Sem empréstimo', $html);

        $this->actingAs($user);
        $this->emprestar($turma, $aluno, $livro);

        $html = view('aluno.pdf', [
            'alunos' => collect([$aluno->fresh()]),
            'turma' => $turma,
        ])->render();

        $this->assertStringContainsString('No prazo', $html);
    }
}
