<?php

namespace Tests\Feature;

use App\Models\Professor;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfessorTest extends TestCase
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

    private function turma(User $user, string $nome = 'Turma A', ?int $professorId = null): Turma
    {
        return Turma::create([
            'user_id' => $user->id,
            'professor_id' => $professorId,
            'nome_turma' => $nome,
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ]);
    }

    public function test_professor_pode_ser_cadastrado(): void
    {
        $user = $this->usuario();

        $this->actingAs($user)->post(route('professor.store'), [
            'nome' => 'Maria Professora',
            'email' => 'maria@example.com',
            'telefone' => '11999999999',
        ])->assertRedirect(route('professor.index'))
            ->assertSessionHas('success');

        $professor = Professor::firstOrFail();

        $this->assertSame('Maria Professora', $professor->nome);
        $this->assertSame('maria@example.com', $professor->email);
        $this->assertSame($user->id, $professor->user_id);
    }

    public function test_email_do_professor_precisa_ser_unico(): void
    {
        $user = $this->usuario();

        Professor::create([
            'user_id' => $user->id,
            'nome' => 'Professor Existente',
            'email' => 'repetido@example.com',
            'telefone' => '11999999999',
        ]);

        $this->actingAs($user)->post(route('professor.store'), [
            'nome' => 'Outro Professor',
            'email' => 'repetido@example.com',
            'telefone' => '11988888888',
        ])->assertSessionHasErrors('email');

        $this->assertSame(1, Professor::count());
    }

    public function test_email_unico_vale_entre_usuarios_diferentes(): void
    {
        $dono = $this->usuario();
        $outro = $this->usuario();

        Professor::create([
            'user_id' => $dono->id,
            'nome' => 'Professor do Dono',
            'email' => 'compartilhado@example.com',
            'telefone' => '11999999999',
        ]);

        $this->actingAs($outro)->post(route('professor.store'), [
            'nome' => 'Professor do Outro',
            'email' => 'compartilhado@example.com',
            'telefone' => '11988888888',
        ])->assertSessionHasErrors('email');
    }

    public function test_professor_pode_ser_atualizado_e_removido(): void
    {
        $user = $this->usuario();

        $professor = Professor::create([
            'user_id' => $user->id,
            'nome' => 'Nome Antigo',
            'email' => 'antigo@example.com',
            'telefone' => '11999999999',
        ]);

        $this->actingAs($user)->put(route('professor.update', $professor), [
            'nome' => 'Nome Atualizado',
            'email' => 'antigo@example.com',
            'telefone' => '11988888888',
        ])->assertRedirect(route('professor.index'));

        $professor->refresh();

        $this->assertSame('Nome Atualizado', $professor->nome);
        $this->assertSame('11988888888', $professor->telefone);

        $this->actingAs($user)
            ->delete(route('professor.destroy', $professor))
            ->assertRedirect(route('professor.index'));

        $this->assertSoftDeleted('professores', ['id' => $professor->id]);
    }

    public function test_remover_professor_deixa_as_turmas_sem_responsavel(): void
    {
        $user = $this->usuario();

        $professor = Professor::create([
            'user_id' => $user->id,
            'nome' => 'Professor Removido',
            'email' => 'removido@example.com',
            'telefone' => '11999999999',
        ]);

        $turma = $this->turma($user, 'Turma do Professor', $professor->id);

        $this->actingAs($user)->delete(route('professor.destroy', $professor));

        $this->assertNull($turma->fresh()->professor_id);
        $this->assertNotSoftDeleted('turmas', ['id' => $turma->id]);
    }

    public function test_listagem_mostra_professor_e_suas_turmas(): void
    {
        $user = $this->usuario();

        $professor = Professor::create([
            'user_id' => $user->id,
            'nome' => 'Professor Listado',
            'email' => 'listado@example.com',
            'telefone' => '11999999999',
        ]);

        $this->turma($user, 'Turma Um', $professor->id);
        $this->turma($user, 'Turma Dois', $professor->id);

        $this->actingAs($user)
            ->get(route('professor.index'))
            ->assertOk()
            ->assertSee('Professor Listado')
            ->assertSee('listado@example.com')
            ->assertSee('Turma Um')
            ->assertSee('Turma Dois');
    }

    public function test_professor_de_outro_usuario_nao_e_acessivel(): void
    {
        $dono = $this->usuario();
        $outro = $this->usuario();

        $professor = Professor::create([
            'user_id' => $dono->id,
            'nome' => 'Professor Privado',
            'email' => 'privado@example.com',
            'telefone' => '11999999999',
        ]);

        $this->actingAs($outro)
            ->put(route('professor.update', $professor), [
                'nome' => 'Tentativa',
                'email' => 'tentativa@example.com',
                'telefone' => '11988888888',
            ])->assertNotFound();
    }

    public function test_visitante_nao_acessa_a_listagem_de_professores(): void
    {
        $this->get(route('professor.index'))->assertRedirect(route('login'));
    }
}
