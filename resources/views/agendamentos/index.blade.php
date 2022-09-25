
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php

use App\Models\Agendamentos;
use App\Models\Servicos;
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
use Illuminate\Support\Facades\DB;

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
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agendamentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container">
                        <div class="row justify-content-center">
                            @csrf
                            <input type="hidden" id="idUsuario" name="idUsuario" value="{{ Auth::user()->id }}">
                            <!--Listagem-->
                            <h2>Agendamento - Inicial</h2>
                            <button type="button" class="btn btn-secundary relatCond-agendamentos">Relatório</button>
                            <br>
                            <button type="button" class="btn btn-primary create-data text-dark">Adicionar</button>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-sm-1">ID</th>
                                        <th class="col-sm-2">Data</th>
                                        <th class="col-sm-2">Hora</th>
                                        <th class="col-sm-4">Valor</th>
                                        <?php if(Auth::user()->office == 'adm'){?>
                                            <th class="col-sm-2">Usuario</th>
                                        <?php }?>
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
                                        <?php if(Auth::user()->office == 'adm'){
                                            $usuario = User::findorFail($agendamento->idUsuario);?>
                                            <td>{{ $usuario->name }}</td>
                                        <?php }?>
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
                                                    <?php 
                                                        if($mensagem != ""){
                                                            echo "<b>'$mensagem'</b>";
                                                        }
                                                    ?>
                                                    <input type="hidden" id="idUsuario" name="idUsuario" value="{{ Auth::user()->id }}">
                                                    <label for="dt">Data</label>
                                                    <input type="date" name="data" id="data" required>
                                                    <br>
                                                    <button class="btn btn-primary text-dark" type="submit" id="botaoAdicionar" name="botaoAdicionar">Adicionar</button>
                                                </fieldset>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!--Dialog Relatório Condicional de Agendamentos-->
                            <div class="modal fade" tabindex="-1" role="dialog" id="dialog-form-relatorioParcialAgendamentos" aria-hidden="true" style="margin-top:100px;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Relatório Parcial de Agendamentos</h5>
                                        </div>
                                        <form id="form-relatorioparcialAgendamentos" action="{{ route('relatorioCondicionalPDF_agendamentos') }}" target="_blank" method="GET">
                                            <div class="modal-body">
                                                @csrf
                                                @if (Auth::user()->office == 'adm')
                                                    <br>
                                                    <label>ID Usuario</label>
                                                    <input type="number" id="idUsuario" name="idUsuario">
                                                    <br>
                                                    <br>
                                                @endif
                                                @if (Auth::user()->office == 'cli')
                                                <input type="hidden" id="idUsuario" name="idUsuario" value="{{ Auth::user()->id }}">
                                                @endif
                                                <label>Data Inicial</label>
                                                <input type="date" id="dataInicial" name="dataInicial">
                                                <br>
                                                <label>Data Final</label>
                                                <input type="date" id="dataFinal" name="dataFinal">
                                                <br>
                                                <button type="submit">Gerar</button>
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
                        //relatorioAgendamentos
                        $(document).ready(function(){
                            $('.relatCond-agendamentos').on('click', function(){
                                $('#dialog-form-relatorioParcialAgendamentos').modal('show');
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
</body>
</html>