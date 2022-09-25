<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php

    include_once"../links.php"?>
    <title>Serviços</title>
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
            {{ __('Serviços') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container">
                        <div class="row justify-content-center">
                            @csrf
                            <!--Listagem-->
                            <h2>Serviços</h2>
                            <button type="button" class="btn btn-primary create-servico text-dark">Adicionar</button>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-sm-1">ID</th>
                                        <th class="col-sm-6">Descrição</th>
                                        <th class="col-sm-1">Valor</th>
                                        <th class="col-sm-1">Duração</th>
                                        <th class="col-sm-2">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($servicos as $servico)
                                    <tr>
                                        <td>{{ $servico->id }}</td>
                                        <td>{{ $servico->descricao }}</td>
                                        <td>{{ $servico->valor }}</td>
                                        <td>{{ $servico->duracao }}</td>
                                        <!--Botões-->
                                        <td>
                                            <!--Editar-->
                                            <button type="button"  class="btn btn-primary edit-servico text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                                                fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                            </svg></button>
                                            <!--Remover-->
                                            <button type="button" class="btn btn-primary remove-servico text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg></button>
                                            <!--info-->
                                            <button type="button" class="btn btn-primary show-servico text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                            </svg></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!--Dialog Adicionar-->
                            <div class="modal fade" id="dialog-form-adicionar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Adicionar Novo Serviço</h5>
                                        </div>
                                        <form id="form-adicionar" action="{{ route('registrar_servico') }}" method="POST">
                                            <div class="modal-body">
                                                @csrf
                                                <fieldset>
                                                    <label for="dc">Descrição</label>
                                                    <input type="text" name="descricao" id="descricao" minlength="1" maxlength="100" required>
                                                    <label for="vl">Valor</label>
                                                    <input type="number" name="valor" id="valor" step="0.01" min=0 max=99999 required>
                                                    <label for="dc">Duração (em minutos)</label>
                                                    <input type="number" name="duracao" id="duracao" min="1" max="60" required>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <div class="modal-fade">
                                                        <button class="btn btn-primary text-dark" type="submit" id="botaoAdicionar" name="botaoAdicionar">Adicionar</button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!--Dialog Editar-->
                            <div class="modal fade" id="dialog-form-editar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Serviço</h5>
                                        </div>

                                        <form id="form-editar" action="{{  route('alterar_servico') }}" method="POST">
                                            <div class="modal-body">
                                                @csrf
                                                <fieldset>
                                                    <input type="hidden" name="textoid" id="textoid">
                                                    <label for="dc">Descrição</label>
                                                    <input type="text" name="textodescricao" id="textodescricao" minlength="1" maxlength="100" required>
                                                    <label for="vl">Valor</label>
                                                    <input type="number" name="textovalor" id="textovalor" step="0.01" min=0 max=99999 required>
                                                    <label for="dc">Duração (em minutos)</label>
                                                    <input type="number" name="textoduracao" id="textoduracao" min=1 max=60 required>
                                                    <br>
                                                    <br>
                                                </fieldset>
                                            </div>
                                        
                                            <div class="modal-fade">
                                                <button class="btn btn-primary text-dark" type="submit" id="botaoEditar" name="botaoEditar">Editar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!--Dialog Excluir-->
                            <div class="modal fade" id="dialog-form-excluir" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Excluir Serviço</h5>
                                        </div>

                                        <form id="form-excluir" action="{{  route('excluir_servico') }}" method="POST">
                                            <div class="modal-body">
                                                @csrf
                                                <fieldset>
                                                    <input type="hidden" name="textid" id="textid">
                                                    <label for="dc">Descrição</label>
                                                    <input type="text" name="textdescricao" id="textdescricao" disabled>
                                                    <label for="vl">Valor</label>
                                                    <input type="text" name="textvalor" id="textvalor" disabled>
                                                    <label for="dc">Duração (em minutos)</label>
                                                    <input type="text" name="textduracao" id="textduracao" disabled>
                                                    <br>
                                                    <br>
                                                </fieldset>
                                            </div>
                                        
                                            <div class="modal-fade">
                                                <button class="btn btn-primary text-dark" type="submit" id="botaoExcluir" name="botaoExcluir">Excluir</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!--Dialog Info-->
                            <div class="modal fade" id="dialog-form-visualizar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Visualizar Serviço</h5>
                                        </div>
                                        
                                        <form id="form-visualizar">
                                            <div class="modal-body">
                                                @csrf
                                                <fieldset>
                                                    <label for="dc">Descrição</label>
                                                    <input type="text" name="txtdescricao" id="txtdescricao" disabled>
                                                    <label for="vl">Valor</label>
                                                    <input type="text" name="txtvalor" id="txtvalor" disabled>
                                                    <label for="dc">Duração (em minutos)</label>
                                                    <input type="text" name="txtduracao" id="txtduracao" disabled>
                                                    <br>
                                                    <br>
                                                </fieldset>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--Dialogs - Pegar valor e adicionar no campo-->
                    <script>
                        //adicionar
                        $(document).ready(function(){
                            $('.create-servico').on('click', function(){
                                $('#dialog-form-adicionar').modal('show');
                            });
                        });
                        //editar
                        $(document).ready(function(){
                            $('.edit-servico').on('click', function(){
                                $('#dialog-form-editar').modal('show');
                                $tr = $(this).closest('tr');

                                var data = $tr.children("td").map(function(){
                                    return $(this).text();
                                }).get();

                                $('#textoid').val(data[0]);
                                $('#textodescricao').val(data[1]);
                                $('#textovalor').val(data[2]);
                                $('#textoduracao').val(data[3]);
                            });
                        });
                        //excluir
                        $(document).ready(function(){
                            $('.remove-servico').on('click', function(){
                                $('#dialog-form-excluir').modal('show');
                                $tr = $(this).closest('tr');

                                var data = $tr.children("td").map(function(){
                                    return $(this).text();
                                }).get();

                                $('#textid').val(data[0]);
                                $('#textdescricao').val(data[1]);
                                $('#textvalor').val(data[2]);
                                $('#textduracao').val(data[3]);
                            });
                        });
                        //info
                        $(document).ready(function(){
                            $('.show-servico').on('click', function(){
                                $('#dialog-form-visualizar').modal('show');
                                $tr = $(this).closest('tr');

                                var data = $tr.children("td").map(function(){
                                    return $(this).text();
                                }).get();

                                $('#txtdescricao').val(data[1]);
                                $('#txtvalor').val(data[2]);
                                $('#txtduracao').val(data[3]);
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