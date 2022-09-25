<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Relatório Parcial de Agendamentos</title>
        <style>
            .tableRelatorio {
                border-collapse: collapse;
                margin: 30px auto;
                width: 100%;
                margin-top: 2rem;
                text-align: center;
            }
            .tableRelatorio thead tr {
                background-color: #99ccff;
                color: black;
            }
            .tableRelatorio tbody tr {
                background-color: white;
                color: black;
            }
            .tableRelatorio th, td {
                padding: 8px 3px;
                border: 1px solid black;
            }
        </style>
        <?php use App\Models\Servicos;
                use App\Models\User;?>
    </head>
    <body>
        @auth
        <div class="container">
            <div class="row justify-content-center">
                @csrf
                <!--Listagem-->
                <img src="imagens/leila.jpg" width=300 height=100>
                <br>
                Relatório Gerado em: <?php
                echo $dataHoraAtual ?>.
                <br>
                <h2>Agendamentos</h2>
                <table class="tableRelatorio">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Serviço</th>
                            <th>Usuário</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agendamentos as $agendamento)
                        <tr>
                            <td>{{ $agendamento->id }}</td>
                            <td><?php $servico = Servicos::findOrFail($agendamento->idServico)?>{{ $servico->descricao }}</td>
                            <td><?php $usuario = User::findOrFail($agendamento->idUsuario)?>{{ $usuario->name }}</td>
                            <td>{{ $agendamento->data }}</td>
                            <td>{{ $agendamento->hora }}</td>
                            <td>{{ $agendamento->status }}</td>
                            <?php $qtdeAgendamentos += 1; ?>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <b>Há: <?php echo $qtdeAgendamentos ?> agendamentos cadastrados.</b>
            </div>
        </div>
        @endauth
    </body>
</html>