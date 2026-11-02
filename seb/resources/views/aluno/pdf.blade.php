<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Alunos</title>
    <style>
        @page {
        margin: 20px 25px;
    }

    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 11px;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 15px;
        color: #3D2F2F;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 6px 8px;
        text-align: left;
        word-wrap: break-word;
    }

    th {
        background-color: #3D2F2F;
        color: white;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

  
    th:nth-child(1), td:nth-child(1) { width: 15%; }
    th:nth-child(2), td:nth-child(2) { width: 10%; }
    th:nth-child(3), td:nth-child(3) { width: 8%;  }
    th:nth-child(4), td:nth-child(4) { width: 8%;  } 
    th:nth-child(5), td:nth-child(5) { width: 10%; } 
    th:nth-child(6), td:nth-child(6) { width: 12%; }
    th:nth-child(7), td:nth-child(7) { width: 9%;  } 
    th:nth-child(8), td:nth-child(8) { width: 9%;  } 
    th:nth-child(9), td:nth-child(9) { width: 9%;  } 
    th:nth-child(10), td:nth-child(10) { width: 10%; } 

    .footer {
        position: fixed;
        bottom: -10px;
        width: 100%;
        text-align: center;
        font-size: 9px;
        color: #777;
        border-top: 1px solid #ddd;
        padding-top: 5px;
    }
    </style>
</head>
<body>
    <h1>Relatório de Alunos</h1>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Turma</th>
                <th>Turno</th>
                <th>Ano</th>
                <th>Matéria</th>
                <th>Livro</th>
                <th>Empréstimo</th>
                <th>Devolução</th>
                <th>Previsão</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alunos as $aluno)
                <tr>
                    <td>{{ $aluno->nome }}</td>
                    <td>{{ $aluno->turma?->nome_turma ?? 'Sem Turma' }}</td>
                    <td>{{ $aluno->turma?->turno_turma ?? 'N/A' }}</td>
                    <td>{{ $aluno->turma?->ano_turma ?? 'N/A' }}</td>
                    <td>{{ $aluno->turma?->materia_turma ?? 'N/A' }}</td>
                    <td>{{ $aluno->livro?->nome_livro ?? 'N/A' }}</td>
                    <td>{{ $aluno->data_emprestimo ?? 'N/A' }}</td>
                    <td>{{ $aluno->data_devolucao ?? 'N/A' }}</td>
                    <td>{{ $aluno->data_previsao ?? 'N/A' }}</td>
                    <td>{{ $aluno->status ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>