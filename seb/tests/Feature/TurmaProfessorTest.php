<?php

namespace Tests\Feature;

use App\Models\Professor;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TurmaProfessorTest extends TestCase
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

    private function professor(User $user, string $nome = 'Professor Responsável'): Professor
    {
        return Professor::create([
            'user_id' => $user->id,
            'nome' => $nome,
            'email' => uniqid().'@example.com',
            'telefone' => '11999999999',
        ]);
    }

    public function test_turma_pode_ser_criada_com_professor_existente(): void
    {
        $user = $this->usuario();
        $professor = $this->professor($user);

        $this->actingAs($user)->post(route('turma.store'), [
            'nome_turma' => 'Turma com Professor',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
            'professor_id' => $professor->id,
        ])->assertRedirect(route('turma.index'));

        $turma = Turma::firstOrFail();

        $this->assertSame($professor->id, $turma->professor_id);
        $this->assertTrue($turma->professor->is($professor));
    }

    public function test_um_professor_pode_ser_responsavel_por_mais_de_uma_turma(): void
    {
        $user = $this->usuario();
        $professor = $this->professor($user);

        $this->actingAs($user)->post(route('turma.store'), [
            'nome_turma' => 'Turma A',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
            'professor_id' => $professor->id,
        ]);

        $this->actingAs($user)->post(route('turma.store'), [
            'nome_turma' => 'Turma B',
            'turno_turma' => 'tarde',
            'ano_turma' => 'em',
            'professor_id' => $professor->id,
        ]);

        $this->assertSame(2, $professor->turmas()->count());
    }

    public function test_turma_nao_pode_usar_professor_de_outro_usuario(): void
    {
        $dono = $this->usuario();
        $outro = $this->usuario();
        $professorDoDono = $this->professor($dono, 'Professor do Dono');

        $this->actingAs($outro)->post(route('turma.store'), [
            'nome_turma' => 'Turma Invasora',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
            'professor_id' => $professorDoDono->id,
        ])->assertSessionHasErrors('professor_id');

        $this->assertSame(0, Turma::withoutGlobalScopes()->count());
    }

    public function test_turma_pode_ser_atualizada_com_outro_professor(): void
    {
        $user = $this->usuario();
        $primeiro = $this->professor($user, 'Primeiro Professor');
        $segundo = $this->professor($user, 'Segundo Professor');

        $turma = Turma::create([
            'user_id' => $user->id,
            'professor_id' => $primeiro->id,
            'nome_turma' => 'Turma Editável',
            'turno_turma' => 'noite',
            'ano_turma' => 'em',
        ]);

        $this->actingAs($user)->put(route('turma.update', $turma), [
            'nome_turma' => 'Turma Editável',
            'turno_turma' => 'noite',
            'ano_turma' => 'em',
            'professor_id' => $segundo->id,
        ])->assertRedirect(route('turma.index'));

        $this->assertSame($segundo->id, $turma->fresh()->professor_id);
    }

    public function test_busca_de_turmas_encontra_pelo_nome_do_professor(): void
    {
        $user = $this->usuario();
        $professor = $this->professor($user, 'Joana da Silva');

        Turma::create([
            'user_id' => $user->id,
            'professor_id' => $professor->id,
            'nome_turma' => 'Turma Encontrada',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ]);

        Turma::create([
            'user_id' => $user->id,
            'nome_turma' => 'Turma Sem Professor',
            'turno_turma' => 'tarde',
            'ano_turma' => 'em',
        ]);

        $resposta = $this->actingAs($user)
            ->get(route('turma.index', ['search' => 'Joana']))
            ->assertOk()
            ->assertSee('Turma Encontrada');

        $resposta->assertViewHas('turmas', function ($turmas) {
            return $turmas->count() === 1
                && $turmas->first()->nome_turma === 'Turma Encontrada';
        });
    }

    public function test_detalhes_da_turma_mostram_o_professor_responsavel(): void
    {
        $user = $this->usuario();
        $professor = $this->professor($user, 'Carlos Responsável');

        $turma = Turma::create([
            'user_id' => $user->id,
            'professor_id' => $professor->id,
            'nome_turma' => 'Turma Detalhada',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ]);

        $this->actingAs($user)
            ->get(route('turma.show', $turma))
            ->assertOk()
            ->assertSee('Professor responsável')
            ->assertSee('Carlos Responsável');
    }

    public function test_professor_pode_ser_criado_via_json_pelo_atalho_da_turma(): void
    {
        $user = $this->usuario();

        $resposta = $this->actingAs($user)->postJson(route('professor.store'), [
            'nome' => 'Professor Atalho',
            'email' => 'atalho@example.com',
            'telefone' => '11999999999',
        ]);

        $resposta->assertCreated()
            ->assertJsonPath('nome', 'Professor Atalho')
            ->assertJsonStructure(['id', 'nome', 'email', 'telefone']);

        $this->assertDatabaseHas('professores', [
            'nome' => 'Professor Atalho',
            'email' => 'atalho@example.com',
            'user_id' => $user->id,
        ]);
    }
}
