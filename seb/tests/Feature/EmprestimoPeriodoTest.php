<?php

namespace Tests\Feature;

use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmprestimoPeriodoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: Turma, 1: array<int, Emprestimo>}
     */
    private function cenario(): array
    {
        $user = User::create([
            'name' => 'Usuário Período',
            'email' => 'periodo'.uniqid().'@example.com',
            'password' => 'senha-segura',
        ]);

        $user->markEmailAsVerified();

        $this->actingAs($user);

        $turma = Turma::create([
            'nome_turma' => 'Turma Período',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ]);

        $livro = Livro::create([
            'titulo' => 'Livro Período',
            'autor' => 'Autor Período',
            'ano_publicacao' => 2024,
            'codigo' => '445566',
            'estoque' => 5,
        ]);

        $inicio = $turma->alunos()->create(['nome' => 'Aluno Início', 'telefone' => '11999999991']);
        $meio = $turma->alunos()->create(['nome' => 'Aluno Meio', 'telefone' => '11999999992']);
        $fim = $turma->alunos()->create(['nome' => 'Aluno Fim', 'telefone' => '11999999993']);

        return [$turma, [
            'inicio' => Emprestimo::create([
                'aluno_id' => $inicio->id,
                'livro_id' => $livro->id,
                'data_emprestimo' => '2026-11-01',
                'data_devolucao_prevista' => '2026-11-08',
            ]),
            'meio' => Emprestimo::create([
                'aluno_id' => $meio->id,
                'livro_id' => $livro->id,
                'data_emprestimo' => '2026-11-10',
                'data_devolucao_prevista' => '2026-11-17',
            ]),
            'fim' => Emprestimo::create([
                'aluno_id' => $fim->id,
                'livro_id' => $livro->id,
                'data_emprestimo' => '2026-11-20',
                'data_devolucao_prevista' => '2026-11-27',
            ]),
        ]];
    }

    public function test_busca_por_periodo_filtra_pela_data_do_emprestimo(): void
    {
        [, $emprestimos] = $this->cenario();

        $this->get(route('emprestimo.index', [
            'data_inicio' => '2026-11-05',
            'data_fim' => '2026-11-15',
        ]))
            ->assertOk()
            ->assertViewHas('emprestimo', function ($pagina) use ($emprestimos) {
                return $pagina->total() === 1
                    && $pagina->first()->is($emprestimos['meio']);
            });
    }

    public function test_periodo_aceita_apenas_data_inicial(): void
    {
        [, $emprestimos] = $this->cenario();

        $this->get(route('emprestimo.index', ['data_inicio' => '2026-11-15']))
            ->assertOk()
            ->assertViewHas('emprestimo', function ($pagina) use ($emprestimos) {
                return $pagina->total() === 1
                    && $pagina->first()->is($emprestimos['fim']);
            });
    }

    public function test_periodo_aceita_apenas_data_final(): void
    {
        [, $emprestimos] = $this->cenario();

        $this->get(route('emprestimo.index', ['data_fim' => '2026-11-05']))
            ->assertOk()
            ->assertViewHas('emprestimo', function ($pagina) use ($emprestimos) {
                return $pagina->total() === 1
                    && $pagina->first()->is($emprestimos['inicio']);
            });
    }

    public function test_tela_mostra_barra_de_periodo_e_nao_a_busca_antiga(): void
    {
        $this->cenario();

        $this->get(route('emprestimo.index'))
            ->assertOk()
            ->assertSee('Selecione o período')
            ->assertSee('id="periodo-emprestimo"', false)
            ->assertSee('flatpickr', false)
            ->assertSee('name="data_inicio"', false)
            ->assertSee('name="data_fim"', false)
            ->assertDontSee('Filtrar')
            ->assertDontSee('search_emprestimo', false);
    }

    public function test_data_final_anterior_a_inicial_retorna_erro_de_validacao(): void
    {
        $this->cenario();

        $this->get(route('emprestimo.index', [
            'data_inicio' => '2026-11-20',
            'data_fim' => '2026-11-10',
        ]))->assertSessionHasErrors('data_fim');
    }
}
