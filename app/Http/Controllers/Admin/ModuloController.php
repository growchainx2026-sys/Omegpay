<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\Produto;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function store(Request $request, $produtoId)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
            'ordem' => 'nullable|integer',
            'capa' => 'nullable|string',
        ]);

        $modulo = Modulo::create([
            'produto_id' => $produtoId,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'icone' => $request->icone ?? 'book',
            'ordem' => $request->ordem ?? 0,
            'capa' => $request->capa,
            'status' => true,
        ]);

        return response()->json(['success' => true, 'modulo' => $modulo]);
    }

    public function update(Request $request, $id)
    {
        $modulo = Modulo::findOrFail($id);
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
            'ordem' => 'nullable|integer',
            'capa' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $modulo->update($request->only([
            'nome', 'descricao', 'icone', 'ordem', 'capa', 'status'
        ]));

        return response()->json(['success' => true, 'modulo' => $modulo]);
    }

    public function destroy($id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->delete();

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'modulos' => 'required|array',
        ]);

        foreach ($request->modulos as $index => $moduloId) {
            Modulo::where('id', $moduloId)->update(['ordem' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
