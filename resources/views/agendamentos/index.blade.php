<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php

use App\Models\Servicos;

    include_once"../links.php"?>
    <title>Agendamentos</title>
    <style>
        @charset "utf-8";

        label, input { display:block; }
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        fieldset { padding:0; border:0; margin-top:25px; }
        h1 { font-size: 1.2em; margin: .6em 0; }
        div#users-contain { width: 350px; margin: 20px 0; }
        div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            @csrf
            <input type="hidden" id="idUsuario" name="idUsuario" value="{{ Auth::user()->id }}">
            <!--Listagem-->
            <h2>Agendamento - Inicial</h2>
            <button type="button" class="btn btn-primary create-data">Adicionar</button>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="col-sm-1">ID</th>
                        <th class="col-sm-2">Data</th>
                        <th class="col-sm-2">Hora</th>
                        <th class="col-sm-4">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agendamentos as $agendamento)
                    <tr>
                        <td>{{ $agendamento->id }}</td>
                        <td>{{ $agendamento->data }}</td>
                        <td>{{ $agendamento->hora }}</td>
                        <?php $valor = Servicos::findOrFail($agendamento->idServico);?>
                        <td>{{ $valor->valor }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!--Dialog Adicionar-->
            <div class="modal fade" id="dialog-form-data" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Adicionar Novo Agendamento</h5>
                        </div>
                        <form id="form-adicionar" action="{{ route('registrar_data') }}" method="POST">
                            <div class="modal-body">
                                @csrf
                                <fieldset>
                                    <input type="hidden" id="idUsuario" name="idUsuario" value="{{ Auth::user()->id }}">
                                    <label for="dt">Data</label>
                                    <input type="date" name="data" id="data" required>
                                    <br>
                                    <button class="btn btn-primary" type="submit" id="botaoAdicionar" name="botaoAdicionar">Adicionar</button>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Dialog-->
    <script>
        //data
        $(document).ready(function(){
            $('.create-data').on('click', function(){
                $('#dialog-form-data').modal('show');
            });
        });
    </script>
</body>
</html>