
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Livros</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
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
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #3D2F2F;
            color: white;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>RELATÓRIO DE LIVROS</h1>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Ano</th>
                <th>Código</th>
                <th>Estoque</th>
                <th>Disponibilidade</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($livros as $livro)
                <tr>
                    <td>{{ $livro->titulo }}</td>
                    <td>{{ $livro->autor }}</td>
                    <td>{{ $livro->ano_publicacao }}</td>
                    <td>{{ $livro->codigo }}</td>
                    <td>{{ $livro->estoque }}</td>
                    <td>{{ $livro->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhum livro cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
