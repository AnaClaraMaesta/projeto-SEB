<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmprestimoNotaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: User, 1: Turma, 2: Aluno, 3: Livro}
     */
    private function cenario(): array
    {
        $user = User::create([
            'name' => 'Usuário Nota',
            'email' => 'nota'.uniqid().'@example.com',
            'password' => 'senha-segura',
        ]);

        $user->markEmailAsVerified();

        $this->actingAs($user);

        $turma = Turma::create([
            'nome_turma' => 'Turma Nota',
            'turno_turma' => 'manha',
            'ano_turma' => 'fundamentali',
        ]);

        $aluno = $turma->alunos()->create([
            'nome' => 'Aluno da Nota',
            'telefone' => '11999999999',
        ]);

        $livro = Livro::create([
            'titulo' => 'Livro da Nota',
            'autor' => 'Autor da Nota',
            'ano_publicacao' => 2024,
            'codigo' => '112233',
            'estoque' => 2,
        ]);

        return [$user, $turma, $aluno, $livro];
    }

    private function registrarEmprestimo(): Emprestimo
    {
        [, , $aluno, $livro] = $this->cenario();

        $this->post(route('emprestimo.store'), [
            'aluno_id' => $aluno->id,
            'livro_id' => $livro->id,
            'dias_prazo' => 7,
        ])->assertRedirect(route('emprestimo.index'));

        return Emprestimo::latest('id')->firstOrFail();
    }

    public function test_nota_e_gerada_automaticamente_ao_registrar_o_emprestimo(): void
    {
        [, , $aluno, $livro] = $this->cenario();

        $this->post(route('emprestimo.store'), [
            'aluno_id' => $aluno->id,
            'livro_id' => $livro->id,
            'dias_prazo' => 7,
        ])->assertRedirect(route('emprestimo.index'))
            ->assertSessionHas('success')
            ->assertSessionHas('nota_emprestimo_id');

        $emprestimo = Emprestimo::latest('id')->firstOrFail();

        // A tela de consulta recebe o comando para baixar a nota recém-gerada.
        $this->get(route('emprestimo.index'))
            ->assertOk()
            ->assertSee('nota-emprestimo-gerada', false)
            ->assertSee('nota_emprestimo.pdf', false)
            ->assertSee(route('emprestimo.nota', $emprestimo), false);
    }

    public function test_nota_pode_ser_reimpressa_pela_listagem(): void
    {
        $emprestimo = $this->registrarEmprestimo();

        $this->get(route('emprestimo.index'))
            ->assertOk()
            ->assertSee('Reimprimir nota do empréstimo')
            ->assertSee(route('emprestimo.nota', $emprestimo), false);

        $this->get(route('emprestimo.nota', $emprestimo))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_nota_pode_ser_baixada_diretamente(): void
    {
        $emprestimo = $this->registrarEmprestimo();

        $this->get(route('emprestimo.nota', ['emprestimo' => $emprestimo, 'download' => 1]))
            ->assertOk()
            ->assertDownload('nota_emprestimo_'.$emprestimo->id.'.pdf');
    }

    public function test_nota_contem_os_dados_do_emprestimo(): void
    {
        $emprestimo = $this->registrarEmprestimo();
        $emprestimo->load(['aluno.turma', 'livro']);

        $html = view('emprestimo.nota', compact('emprestimo'))->render();

        $this->assertStringContainsString('Aluno da Nota', $html);
        $this->assertStringContainsString('Livro da Nota', $html);
        $this->assertStringContainsString('112233', $html);
        $this->assertStringContainsString($emprestimo->data_emprestimo->format('d/m/Y'), $html);
        $this->assertStringContainsString($emprestimo->data_devolucao_prevista->format('d/m/Y'), $html);
        $this->assertStringContainsString('Nota de Empréstimo', $html);
    }

    public function test_nota_de_emprestimo_de_outro_usuario_nao_e_acessivel(): void
    {
        $emprestimo = $this->registrarEmprestimo();

        $outro = User::create([
            'name' => 'Outro Usuário',
            'email' => 'outro'.uniqid().'@example.com',
            'password' => 'senha-segura',
        ]);

        $outro->markEmailAsVerified();

        $this->actingAs($outro)
            ->get(route('emprestimo.nota', $emprestimo))
            ->assertNotFound();
    }
}
