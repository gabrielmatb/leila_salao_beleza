<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php

use App\Models\Servicos;

    include_once"../links.php"?>
    <title>Agendamento</title>
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
            <h2>Agendamento - Serviços</h2>
            <button type="button" class="btn btn-primary create-agendamento">Adicionar</button>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="col-sm-1">ID</th>
                        <th class="col-sm-5">Serviço</th>
                        <th class="col-sm-2">Data</th>
                        <th class="col-sm-1">Hora</th>
                        <th class="col-sm-1">Status</th>
                        <th class="col-sm-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($agendamentosUsuario as $agendamentoUsuario)
                    <tr>
                        <td>{{ $agendamentoUsuario->id }}</td>
                        <td><?php $servico = Servicos::findOrFail($agendamentoUsuario->idServico)?>{{ $servico->descricao}}</td>
                        <td>{{ $agendamentoUsuario->data }}</td>
                        <td>{{ $agendamentoUsuario->hora }}</td>
                        <td>{{ $agendamentoUsuario->status }}</td>
                        <!--Botões-->
                        <td>
                            <?php 
                                //Verifica se diferença das datas é igual ou maior que 2 dias
                                $dataHoje = date('Y-m-d');
                                $dataAtual = date_create($dataHoje); 
                                $dataAgendamento = date_create($agendamentoUsuario->data); 
                                $diferencaDias = date_diff($dataAtual,$dataAgendamento);
                                $diferencaDiasFormatado = $diferencaDias->format("%a");

                                if(($agendamentoUsuario->status == 'A') && $diferencaDiasFormatado >= 2){?>
                                <!--Cancelar-->
                                <button type="button"  class="btn btn-primary cancela-agendamento">Cancelar</button>
                            <?php } else {?>
                                <label style="color:red">Para cancelar, entrar em contato com o salão.</label>
                            <?php }?>
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
                            <h5 class="modal-title">Adicionar Novo Agendamento</h5>
                        </div>
                        <form id="form-adicionar" action="{{ route('registrar_agendamento') }}" method="POST">
                            <div class="modal-body">
                                @csrf
                                <fieldset>
                                    <input type="hidden" id="idUsuario" name="idUsuario" value="{{ Auth::user()->id }}">
                                    <input type="text" id="data" name="data" value="{{ $data }}" readonly>
                                    <br>
                                    <select class="form-select" name="hora" id="hora">
                                        @foreach($horarios as $horario)
                                            <option value="{{ $horario }}">{{ $horario }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-select" name="idServico" id="idServico">
                                        @foreach($servicos as $servico)
                                            <option value="{{ $servico->id }}">{{ $servico->descricao }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="status" name="status" value="A">
                                    <br>
                                    <br>
                                    <br>
                                    <div class="modal-fade">
                                        <button class="btn btn-primary" type="submit" id="botaoAdicionar" name="botaoAdicionar">Adicionar</button>
                                    </div>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--Dialog Cancelar-->
            <div class="modal fade" id="dialog-form-cancelar" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancelar Serviço</h5>
                        </div>
                        <form id="form-visualizar" action="{{ route('alterar_agendamento') }}" method="POST">
                            <div class="modal-body">
                                @csrf
                                <fieldset>
                                    <input type="hidden" id="textoid" name="textoid">
                                    <label for="sr">Serviço</label>
                                    <input type="text" name="textoservico" id="textoservico" disabled>
				                    <label for="dt">Data</label>
                                    <input type="text" name="textodata" id="textodata" disabled>
                                    <label for="hr">Hora</label>
                                    <input type="text" name="textohora" id="textohora" disabled>
                                    <input type="hidden" id="status" name="status" value="C">
                                    <br>
                                    <br>
                                </fieldset>
                            </div>
                            <div class="modal-fade">
                                <button class="btn btn-primary" type="submit" id="botaoCancelar" name="botaoCancelar">Cancelar</button>
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
            $('.create-agendamento').on('click', function(){
                $('#dialog-form-adicionar').modal('show');
            });
        });
        //cancelar
        $(document).ready(function(){
            $('.cancela-agendamento').on('click', function(){
                $('#dialog-form-cancelar').modal('show');
                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function(){
                    return $(this).text();
                }).get();

                $('#textoid').val(data[0]);
                $('#textoservico').val(data[1]);
                $('#textodata').val(data[2]);
                $('#textohora').val(data[3]);
            });
        });
    </script>
</body>
</html>