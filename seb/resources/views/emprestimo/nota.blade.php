<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nota de Empréstimo</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .marca {
            text-align: center;
            color: #3D2F2F;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 3px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin: 6px 0 4px;
            color: #3D2F2F;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .subtitulo {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-bottom: 22px;
        }

        .nota {
            border: 2px solid #3D2F2F;
            border-radius: 8px;
            padding: 22px 26px;
        }

        .cabecalho-nota {
            border-bottom: 1px solid #ddd;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .cabecalho-nota table {
            width: 100%;
            border-collapse: collapse;
        }

        .cabecalho-nota td {
            padding: 0;
            vertical-align: top;
        }

        .numero {
            font-size: 13px;
            font-weight: bold;
            color: #3D2F2F;
        }

        .emissao {
            text-align: right;
            font-size: 11px;
            color: #777;
        }

        table.campos {
            width: 100%;
            border-collapse: collapse;
        }

        table.campos th,
        table.campos td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
        }

        table.campos th {
            width: 32%;
            background-color: #f4f1f1;
            color: #3D2F2F;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .destaque {
            font-size: 13px;
            font-weight: bold;
        }

        .aviso {
            margin-top: 22px;
            font-size: 11px;
            color: #555;
            line-height: 1.5;
        }

        .assinaturas {
            width: 100%;
            margin-top: 55px;
            border-collapse: collapse;
        }

        .assinaturas td {
            width: 50%;
            padding: 0 20px;
            text-align: center;
            vertical-align: bottom;
        }

        .linha-assinatura {
            border-top: 1px solid #777;
            padding-top: 6px;
            font-size: 11px;
            color: #555;
        }

        .rodape {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="marca">SEB</div>
    <h1>Nota de Empréstimo</h1>
    <div class="subtitulo">Comprovante de retirada de livro na biblioteca</div>

    <div class="nota">
        <div class="cabecalho-nota">
            <table>
                <tr>
                    <td class="numero">Nº {{ str_pad($emprestimo->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td class="emissao">Emitida em {{ now()->format('d/m/Y \à\s H:i') }}</td>
                </tr>
            </table>
        </div>

        <table class="campos">
            <tr>
                <th>Aluno</th>
                <td class="destaque">{{ $emprestimo->aluno?->nome ?? '-' }}</td>
            </tr>
            <tr>
                <th>Turma</th>
                <td>
                    {{ $emprestimo->aluno?->turma?->nome_turma ?? '-' }}
                    @if ($emprestimo->aluno?->turma?->turno_turma)
                        — {{ ucfirst($emprestimo->aluno->turma->turno_turma) }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Livro</th>
                <td class="destaque">
                    {{ $emprestimo->livro?->titulo ?? '-' }}
                    @if ($emprestimo->livro?->codigo)
                        <span style="font-weight: normal; color: #666;">(cód. {{ $emprestimo->livro->codigo }})</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Data do empréstimo</th>
                <td>{{ $emprestimo->data_emprestimo?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Data de devolução</th>
                <td class="destaque">{{ $emprestimo->data_devolucao_prevista?->format('d/m/Y') ?? '-' }}</td>
            </tr>
        </table>

        <p class="aviso">
            O aluno compromete-se a devolver o livro acima até a data indicada, em bom estado de conservação.
            Em caso de atraso, perda ou dano, as medidas previstas no regulamento da biblioteca serão aplicadas.
        </p>

        <table class="assinaturas">
            <tr>
                <td>
                    <div class="linha-assinatura">Assinatura do aluno / responsável</div>
                </td>
                <td>
                    <div class="linha-assinatura">Responsável pela biblioteca</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="rodape">
        Documento gerado pelo SEB em {{ now()->format('d/m/Y \à\s H:i') }}.
    </div>

</body>
</html>
