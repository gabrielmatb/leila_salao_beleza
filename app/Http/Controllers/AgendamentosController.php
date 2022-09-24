<?php

namespace App\Http\Controllers;

use App\Models\Agendamentos;
use App\Models\Servicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        $agendamentos = DB::table('agendamentos')->where('idUsuario', $idUsuario)->get();
        return view('agendamentos.index', compact('agendamentos'));
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
        $agendamentosUsuario = DB::table('agendamentos')->where('data', $data)->where('idUsuario', $request->idUsuario)->get();

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
        $agendamento = Agendamentos::findOrFail($request->textoid);

        $agendamento->update([
            'status' => $request->status,
        ]);

        $agendamentos = DB::table('agendamentos')->where('idUsuario', 1)->get();
        return view('agendamentos.index', compact('agendamentos'));
    }
}
