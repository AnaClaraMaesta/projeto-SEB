<?php

namespace Tests\Feature;

use App\Models\Livro;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroCapaTurmaCorTest extends TestCase
{
    use RefreshDatabase;

    private function usuario(): User
    {
        $user = User::create([
            'name' => 'Usuário Capa e Cor',
            'email' => 'capa-cor'.uniqid().'@example.com',
            'password' => 'senha-segura',
        ]);

        $user->markEmailAsVerified();

        $this->actingAs($user);

        return $user;
    }

    /**
     * @return array<string, mixed>
     */
    private function dadosLivro(array $extra = []): array
    {
        return array_merge([
            'titulo' => 'Livro com Capa',
            'autor' => 'Autor da Capa',
            'ano_publicacao' => 2024,
            'codigo' => '778899',
            'estoque' => 3,
        ], $extra);
    }

    /**
     * @return array<string, mixed>
     */
    private function dadosTurma(array $extra = []): array
    {
        return array_merge([
            'nome_turma' => 'Turma Colorida',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ], $extra);
    }

    public function test_livro_pode_ser_criado_com_capa_por_url(): void
    {
        $this->usuario();

        $url = 'https://exemplo.com/capas/livro.jpg';

        $this->post('/livros', $this->dadosLivro(['capa_url' => $url]))
            ->assertRedirect(route('livro.index'));

        $this->assertDatabaseHas('livros', [
            'titulo' => 'Livro com Capa',
            'capa_url' => $url,
        ]);

        $this->get('/livros')
            ->assertOk()
            ->assertSee($url, false)
            ->assertSee('Capa de Livro com Capa');
    }

    public function test_capa_do_livro_e_opcional(): void
    {
        $this->usuario();

        $this->post('/livros', $this->dadosLivro())
            ->assertRedirect(route('livro.index'));

        $this->assertDatabaseHas('livros', [
            'titulo' => 'Livro com Capa',
            'capa_url' => null,
        ]);
    }

    public function test_capa_invalida_e_rejeitada(): void
    {
        $this->usuario();

        $this->post('/livros', $this->dadosLivro(['capa_url' => 'nao-e-uma-url']))
            ->assertSessionHasErrors('capa_url');

        $this->assertDatabaseMissing('livros', ['titulo' => 'Livro com Capa']);
    }

    public function test_capa_do_livro_pode_ser_atualizada(): void
    {
        $this->usuario();

        $livro = Livro::create($this->dadosLivro());

        $this->put(route('livro.update', $livro), $this->dadosLivro([
            'capa_url' => 'https://exemplo.com/nova-capa.jpg',
        ]))->assertRedirect(route('livro.index'));

        $this->assertSame('https://exemplo.com/nova-capa.jpg', $livro->fresh()->capa_url);
    }

    public function test_turma_pode_ser_criada_com_cor_e_usa_no_card_e_no_header(): void
    {
        $this->usuario();

        $this->post('/turmas', $this->dadosTurma(['cor' => '#2F3D3D']))
            ->assertRedirect(route('turma.index'));

        $turma = Turma::firstOrFail();

        $this->assertSame('#2F3D3D', $turma->cor);

        $this->get('/turmas')
            ->assertOk()
            ->assertSee('background-color: #2F3D3D', false);

        $this->get(route('turma.show', $turma))
            ->assertOk()
            ->assertSee('background-color: #2F3D3D', false);
    }

    public function test_turma_sem_cor_usa_o_padrao_do_tema(): void
    {
        $this->usuario();

        $this->post('/turmas', $this->dadosTurma())
            ->assertRedirect(route('turma.index'));

        $turma = Turma::firstOrFail();

        $this->assertNull($turma->cor);

        $this->get('/turmas')
            ->assertOk()
            ->assertDontSee('background-color:', false);

        $this->get(route('turma.show', $turma))
            ->assertOk()
            ->assertDontSee('background-color:', false);
    }

    public function test_cor_invalida_e_rejeitada(): void
    {
        $this->usuario();

        $this->post('/turmas', $this->dadosTurma(['cor' => 'vermelho']))
            ->assertSessionHasErrors('cor');

        $this->assertDatabaseMissing('turmas', ['nome_turma' => 'Turma Colorida']);
    }

    public function test_cor_da_turma_pode_ser_atualizada(): void
    {
        $this->usuario();

        $turma = Turma::create($this->dadosTurma(['cor' => '#3D2F2F']));

        $this->put(route('turma.update', $turma), $this->dadosTurma(['cor' => '#46594A']))
            ->assertRedirect(route('turma.index'));

        $this->assertSame('#46594A', $turma->fresh()->cor);
    }

    public function test_cor_do_texto_garante_contraste_com_a_cor_da_turma(): void
    {
        $this->usuario();

        $escura = Turma::create($this->dadosTurma(['nome_turma' => 'Escura', 'cor' => '#3D2F2F']));
        $clara = Turma::create($this->dadosTurma(['nome_turma' => 'Clara', 'cor' => '#F5E6C8']));

        $this->assertSame('#ffffff', $escura->cor_texto);
        $this->assertSame('#111827', $clara->cor_texto);
    }
}
