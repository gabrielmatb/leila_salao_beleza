<?php

namespace App\Http\Controllers;

use App\Models\Servicos;
use Illuminate\Http\Request;

class ServicosController extends Controller
{
    public function index()
    {
        $servicos = Servicos::all();
        return view('servicos.index', compact('servicos'));
    }

    public function store(Request $request)
    {
        Servicos::create([
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'duracao' => $request->duracao,
        ]);

        $servicos = Servicos::all();
        return view('servicos.index', compact('servicos'));
    }

    public function show($id)
    {
        $servico = Servicos::findOrFail($id);
        return view('servicos.show', ['servico' => $servico]);
    }

    public function update(Request $request)
    {
        $id = $request->textoid;

        $servico = Servicos::findOrFail($id);

        $servico->update([
            'descricao' => $request->textodescricao,
            'valor' => $request->textovalor,
            'duracao' => $request->textoduracao,
        ]);

        $servicos = Servicos::all();
        return view('servicos.index', compact('servicos'));
    }

    public function destroy(Request $request)
    {
        $id = $request->textid;

        $servico = Servicos::findOrFail($id);
        $servico->delete();

        $servicos = Servicos::all();
        return view('servicos.index', compact('servicos'));
    }
}