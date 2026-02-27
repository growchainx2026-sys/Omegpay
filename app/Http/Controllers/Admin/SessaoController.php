<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sessao;
use Illuminate\Http\Request;

class SessaoController extends Controller
{
    public function store(Request $request, $moduloId)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer',
        ]);

        $sessao = Sessao::create([
            'modulo_id' => $moduloId,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'ordem' => $request->ordem ?? 0,
            'status' => true,
        ]);

        return response()->json(['success' => true, 'sessao' => $sessao]);
    }

    public function update(Request $request, $id)
    {
        $sessao = Sessao::findOrFail($id);
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $sessao->update($request->only([
            'nome', 'descricao', 'ordem', 'status'
        ]));

        return response()->json(['success' => true, 'sessao' => $sessao]);
    }

    public function destroy($id)
    {
        $sessao = Sessao::findOrFail($id);
        $sessao->delete();

        return response()->json(['success' => true]);
    }
}
