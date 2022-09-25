<?php

namespace App\Http\Controllers;

use App\Models\Agendamentos;
use App\Models\Servicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\PDF;
use DateTime;
use Illuminate\Support\Facades\File;

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}

class AgendamentosController extends Controller
{
    
    public function index()
    {
        $idUsuario = Auth::user()->id;

        $mensagem = "";

        //verificar se em 7 dias possui agendamento
        if(Auth::user()->office == 'cli'){
            $data = date('Y-m-d');
            $qtdDias = 7;
            $resultado = date('Y-m-d', strtotime("+{$qtdDias} days",strtotime($data)));
            $idUsuario = Auth::user()->id;
            $sql = "select * from agendamentos where idUsuario='$idUsuario' and data between '$data' and '$resultado'";
            $agendamentosUsuario = DB::select($sql);
            if(count($agendamentosUsuario) > 0){
                $dataNova = new DateTime($agendamentosUsuario[0]->data);
                $dataMensagem = date_format($dataNova, 'd/m/Y');
                $mensagem = "Você possui agendamento para a data ". $dataMensagem. ", seria interessante agendar mais serviços para o mesmo dia :-)";
            }
        }
        

        if(Auth::user()->office == 'adm'){
            $agendamentos = Agendamentos::all();
        } else {
            $agendamentos = DB::table('agendamentos')->where('idUsuario', $idUsuario)->get();
        }
        return view('agendamentos.index', compact("agendamentos", "mensagem"));
    }

    public function storeData(Request $request)
    {
        $idUsuario = Auth::user()->id;
        echo $idUsuario;

        //transforma horarios em string
        $horariosTotal = array();
        $horarios = array();
        $horariosStr = ["08:00:00", "09:00:00", "10:00:00", "11:00:00", "13:00:00", "14:00:00", "15:00:00", "16:00:00"];
        foreach($horariosStr as $horario){
            array_push($horariosTotal, $horario);
        }
        
        $data = $request->data;
        
        //verifica agendamentos do dia
        $agendamentosDia = DB::table('agendamentos')->where('data', $data)->get();

        //verifica horarios livres
        for($i = 0; $i < count($agendamentosDia); $i++){
            $valorIndice = array_search($agendamentosDia[$i]->hora, $horariosTotal);
            if($agendamentosDia[$i]->status == 'A'){
                $horariosTotal[$valorIndice] = 0;
            }
        }

        $k = 0;
        for($i = 0; $i < count($horariosTotal); $i++){
            if($horariosTotal[$i] != 0){
                $horarios[$k] = $horariosTotal[$i];
                $k++;
            }
        }
        
        //verifica agendamentos do usuário
        if(Auth::user()->office == 'adm'){
            $agendamentosUsuario = DB::table('agendamentos')->where('data', $data)->get();
        } else {
            $agendamentosUsuario = DB::table('agendamentos')->where('data', $data)->where('idUsuario', $request->idUsuario)->get();
        }

        $servicos = Servicos::all();
        return view('agendamentos.agendamento', compact("servicos", "data", "horarios", "agendamentosUsuario"));
    }

    public function store(Request $request)
    {
        debug_to_console($request->data);
        Agendamentos::create([
            'idServico' => $request->idServico,
            'idUsuario' => $request->idUsuario,
            'data' => $request->data,
            'hora' => $request->hora,
            'status' => $request->status,
        ]);

       //transforma horarios em string
       $horariosTotal = array();
       $horarios = array();
       $horariosStr = ["08:00:00", "09:00:00", "10:00:00", "11:00:00", "13:00:00", "14:00:00", "15:00:00", "16:00:00"];
       foreach($horariosStr as $horario){
           array_push($horariosTotal, $horario);
       }

        $data = $request->data;
        $agendamentosDia = DB::table('agendamentos')->where('data', $data)->get();

        //verifica horarios livres
        for($i = 0; $i < count($agendamentosDia); $i++){
            $valorIndice = array_search($agendamentosDia[$i]->hora, $horariosTotal);
            if($agendamentosDia[$i]->status == 'A'){
                $horariosTotal[$valorIndice] = 0;
            }
        }

        $k = 0;
        for($i = 0; $i < count($horariosTotal); $i++){
            if($horariosTotal[$i] != 0){
                $horarios[$k] = $horariosTotal[$i];
                $k++;
            }
        }
        
        //verifica agendamentos do usuário
        $agendamentosUsuario = DB::table('agendamentos')->where('data', $data)->where('idUsuario', $request->idUsuario)->get();

        $servicos = Servicos::all();
        return view('agendamentos.agendamento', compact("servicos", "data", "horarios", "agendamentosUsuario"));
    }

    public function update(Request $request)
    {
        $mensagem = "";
        $idUsuario = Auth::user()->id;
        if($request->textoid != ""){
            $agendamento = Agendamentos::findOrFail($request->textoid);
        } else {
            $agendamento = Agendamentos::findOrFail($request->textid);
        }
        

        $agendamento->update([
            'status' => $request->status,
        ]);

        $agendamentos = DB::table('agendamentos')->where('idUsuario', $idUsuario)->get();
        return view('agendamentos.index', compact("agendamentos", "mensagem"));
    }

    public function relatorioCondicionalPDF(Request $request){

        if($request->dataInicial != "" && $request->dataFinal != ""){
            if($request->idUsuario != ""){
                $sql = "select * from agendamentos where idUsuario='$request->idUsuario' and data between '$request->dataInicial' and '$request->dataFinal'";
            } else {
                $sql = "select * from agendamentos where data between '$request->dataInicial' and '$request->dataFinal'";
            }
        } else {
            if($request->idUsuario != ""){
                $sql = "select * from agendamentos where idUsuario='$request->idUsuario'";
            } else {
                $sql = "select * from agendamentos";
            }
        }

        $agendamentos = DB::select($sql);

        date_default_timezone_set('America/Sao_Paulo');
        $dataHoraAtual = date('d-m-Y H:i');

        $qtdeAgendamentos = 0;

        //return PDF::loadView('agendamentos.relatorioCondicional', compact("agendamentos", "dataHoraAtual", "qtdeAgendamentos"))->setPaper('A4', 'landscape')->stream('RelatorioParcialAgendamentos.pdf');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('agendamentos.relatorioCondicional', compact("agendamentos", "dataHoraAtual", "qtdeAgendamentos"));
        $fileName = "teste";
        return $pdf->stream($fileName.'.pdf');
    }
}
