<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuloController extends Controller
{
    public function store(Request $request)
    {
        // Baseado na área antiga, mas agora com campos extras
        $data = $request->except(['_token', '_method']);

        // Mapeia 'name' para 'nome' e 'description' para 'descricao'
        if (isset($data['name'])) {
            $data['nome'] = $data['name'];
            unset($data['name']);
        }
        if (isset($data['description'])) {
            $data['descricao'] = $data['description'];
            unset($data['description']);
        }

        // Remove campos que não vão para o banco
        unset($data['status'], $data['ordem']);

        // Define valores padrão
        $data['ordem'] = (Modulo::where('produto_id', $data['produto_id'])->max('ordem') ?? 0) + 1;
        $data['status'] = true;

        // Liberar em: converte string para datetime se preenchido
        if (!empty($data['liberar_em'])) {
            $data['liberar_em'] = \Carbon\Carbon::parse($data['liberar_em']);
        } else {
            $data['liberar_em'] = null;
        }
        $data['liberar_em_dias'] = isset($data['liberar_em_dias']) && $data['liberar_em_dias'] !== '' ? (int) $data['liberar_em_dias'] : null;
        
        Modulo::create($data);

        return back()->with('success', 'Módulo criado com sucesso!')->with('tab', 'area-membros');
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $modulo = Modulo::findOrFail($id);
        
        // Verifica se o usuário é dono do produto
        if ($modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.')->with('tab', 'area-membros');
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:modulos,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
            'ordem' => 'nullable|integer',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('tab', 'area-membros');
        }

        $data = $request->except(['_token', '_method', 'id']);
        $data['status'] = $request->has('status') && $request->status == '1' ? true : false;

        if ($request->filled('liberar_em')) {
            $data['liberar_em'] = \Carbon\Carbon::parse($request->liberar_em);
        } else {
            $data['liberar_em'] = null;
        }
        $data['liberar_em_dias'] = $request->filled('liberar_em_dias') ? (int) $request->liberar_em_dias : null;

        $modulo->update($data);

        return back()->with('success', 'Módulo atualizado com sucesso!')->with('tab', 'area-membros');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $modulo = Modulo::findOrFail($id);
        
        // Verifica se o usuário é dono do produto
        if ($modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.')->with('tab', 'area-membros');
        }

        $modulo->delete();

        return back()->with('success', 'Módulo excluído com sucesso!')->with('tab', 'area-membros');
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modulos' => 'required|array',
            'modulos.*.id' => 'required|exists:modulos,id',
            'modulos.*.ordem' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Dados inválidos'], 400);
        }

        foreach ($request->modulos as $item) {
            $modulo = Modulo::findOrFail($item['id']);
            
            // Verifica se o usuário é dono do produto
            if ($modulo->produto->user_id !== auth()->user()->id) {
                continue;
            }

            $modulo->update(['ordem' => $item['ordem']]);
        }

        return response()->json(['success' => true]);
    }
}
