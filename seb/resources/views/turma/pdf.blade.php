<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Turmas</title>

    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            color: #3D2F2F;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background-color: #3D2F2F;
            color: white;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <h1>RELATÓRIO DE TURMAS</h1>
    
    <table>
        <thead>
            <tr>
                <th>Turma</th>
                <th>Turno</th>
                <th>Nível/Ano</th>
                <th>Matéria</th>
            </tr>
        </thead>
        <tbody>
            @foreach($turmas as $turma)
            <tr>
                <td>{{ $turma->nome_turma }}</td>
                <td>{{ ucfirst($turma->turno_turma) }}</td>
                <td>{{ ucfirst($turma->ano_turma) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Gerado em {{ date('d/m/Y H:i') }}
    </footer>
    
</body>
</html>