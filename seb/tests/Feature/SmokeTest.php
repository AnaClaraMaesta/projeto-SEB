<?php

namespace Tests\Feature;

use App\Models\Livro;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    private function usuario(bool $verificado = true): User
    {
        $user = User::create([
            'name' => 'Usuário de Teste',
            'email' => 'teste'.uniqid().'@example.com',
            'password' => 'senha-segura',
        ]);

        if ($verificado) {
            $user->markEmailAsVerified();
        }

        return $user;
    }

    public function test_visitante_e_redirecionado_para_o_login(): void
    {
        $this->get('/turmas')->assertRedirect(route('login'));
    }

    public function test_pagina_de_login_esta_disponivel(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_usuario_verificado_acessa_as_paginas_principais(): void
    {
        $user = $this->usuario();

        $this->actingAs($user)->get('/turmas')->assertOk();
        $this->actingAs($user)->get('/alunos')->assertOk();
        $this->actingAs($user)->get('/livros')->assertOk();
        $this->actingAs($user)->get('/emprestimo')->assertOk();
    }

    public function test_usuario_nao_verificado_vai_para_o_aviso_de_verificacao(): void
    {
        $user = $this->usuario(verificado: false);

        $this->actingAs($user)
            ->get('/turmas')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_turma_pode_ser_criada_e_exibida(): void
    {
        $user = $this->usuario();

        $this->actingAs($user)->post('/turmas', [
            'nome_turma' => 'Turma A',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ])->assertRedirect(route('turma.index'));

        $turma = Turma::firstOrFail();

        $this->assertSame($user->id, $turma->user_id);
        $this->actingAs($user)->get(route('turma.show', $turma))->assertOk();
    }

    public function test_relatorios_pdf_de_alunos_sao_gerados(): void
    {
        $user = $this->usuario();
        $this->actingAs($user);

        $turma = Turma::create([
            'nome_turma' => 'Turma PDF',
            'turno_turma' => 'tarde',
            'ano_turma' => 'em',
        ]);

        $turma->alunos()->create([
            'nome' => 'Aluno Teste',
            'telefone' => '11999999999',
        ]);

        $this->actingAs($user)->get(route('aluno.pdf', $turma))->assertOk();
        $this->actingAs($user)->get(route('aluno.pdfGeral'))->assertOk();
    }

    public function test_login_com_credenciais_invalidas_retorna_erro(): void
    {
        $this->post('/login', [
            'email' => 'naoexiste@example.com',
            'password' => 'errada',
        ])->assertSessionHasErrors('email');
    }

    public function test_turma_pode_ser_criada_via_json_pelo_atalho_do_aluno(): void
    {
        $user = $this->usuario();
        $this->actingAs($user);

        $resposta = $this->postJson('/turmas', [
            'nome_turma' => 'Turma Atalho',
            'turno_turma' => 'noite',
            'ano_turma' => 'em',
        ]);

        $resposta->assertCreated()
            ->assertJsonPath('nome_turma', 'Turma Atalho')
            ->assertJsonStructure(['id', 'nome_turma', 'turno_turma', 'ano_turma']);

        $this->assertDatabaseHas('turmas', [
            'nome_turma' => 'Turma Atalho',
            'user_id' => $user->id,
        ]);
    }

    public function test_emprestimo_ativo_define_status_e_baixa_o_estoque(): void
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
            'telefone' => '11988887777',
        ]);

        $livro = Livro::create([
            'titulo' => 'Livro de Teste',
            'autor' => 'Autor de Teste',
            'ano_publicacao' => 2024,
            'codigo' => '123456',
            'estoque' => 2,
        ]);

        $this->post('/emprestimo', [
            'aluno_id' => $aluno->id,
            'livro_id' => $livro->id,
            'dias_prazo' => 7,
        ])->assertRedirect(route('emprestimo.index'));

        $aluno->refresh();

        $this->assertSame('No prazo', $aluno->status);
        $this->assertNotNull($aluno->emprestimoAtivo);
        $this->assertSame(1, $livro->fresh()->estoque);

        // O aluno com empréstimo em aberto não pode ser excluído.
        $this->delete(route('aluno.destroy', [$turma, $aluno]))
            ->assertRedirect(route('turma.show', $turma))
            ->assertSessionHas('error');

        // O livro com empréstimo ativo também não pode ser removido.
        $this->delete(route('livro.destroy', $livro))
            ->assertRedirect(route('livro.index'))
            ->assertSessionHas('error');

        $this->get(route('aluno.pdf', $turma))->assertOk();
    }
}
