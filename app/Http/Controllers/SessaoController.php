<?php

namespace App\Http\Controllers;

use App\Models\Sessao;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessaoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modulo_id' => 'required|exists:modulos,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ordem' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $modulo = Modulo::findOrFail($request->modulo_id);
        
        // Verifica se o usuário é dono do produto
        if ($modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.');
        }

        $data = $request->except(['_token', '_method', 'capa']);
        $data['ordem'] = $data['ordem'] ?? (Sessao::where('modulo_id', $modulo->id)->max('ordem') ?? 0) + 1;
        $data['status'] = $request->has('status') && $request->status == '1' ? true : false;

        if ($request->filled('liberar_em')) {
            $data['liberar_em'] = \Carbon\Carbon::parse($request->liberar_em);
        } else {
            $data['liberar_em'] = null;
        }
        $data['liberar_em_dias'] = $request->filled('liberar_em_dias') ? (int) $request->liberar_em_dias : null;

        // Upload de capa se fornecido
        if ($request->hasFile('capa')) {
            $image = $request->file('capa');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('sessoes', $imageName, 'public');
            $data['capa'] = '/' . $imagePath;
        }

        Sessao::create($data);

        // Redireciona de volta para a área de membros na aba de módulos
        $produto = $modulo->produto;
        return redirect()->route('produtos.area-membros', ['uuid' => $produto->uuid])->with('success', 'Sessão criada com sucesso!')->with('tab', 'modulos');
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $sessao = Sessao::findOrFail($id);
        
        // Verifica se o usuário é dono do produto
        if ($sessao->modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.')->with('tab', 'area-membros');
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sessoes,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ordem' => 'nullable|integer',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('tab', 'area-membros');
        }

        $data = $request->except(['_token', '_method', 'id', 'capa']);
        $data['status'] = $request->has('status') && $request->status == '1' ? true : false;

        if ($request->filled('liberar_em')) {
            $data['liberar_em'] = \Carbon\Carbon::parse($request->liberar_em);
        } else {
            $data['liberar_em'] = null;
        }
        $data['liberar_em_dias'] = $request->filled('liberar_em_dias') ? (int) $request->liberar_em_dias : null;
        
        // Upload de capa se fornecido
        if ($request->hasFile('capa')) {
            // Remove capa antiga se existir
            if ($sessao->capa && file_exists(storage_path('app/public/' . ltrim($sessao->capa, '/')))) {
                unlink(storage_path('app/public/' . ltrim($sessao->capa, '/')));
            }
            $image = $request->file('capa');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('sessoes', $imageName, 'public');
            $data['capa'] = '/' . $imagePath;
        }
        
        $sessao->update($data);

        return back()->with('success', 'Sessão atualizada com sucesso!')->with('tab', 'area-membros');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $sessao = Sessao::findOrFail($id);
        
        // Verifica se o usuário é dono do produto
        if ($sessao->modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.')->with('tab', 'area-membros');
        }

        $sessao->delete();

        return back()->with('success', 'Sessão excluída com sucesso!')->with('tab', 'area-membros');
    }

    public function getVideos($sessaoId)
    {
        $sessao = Sessao::findOrFail($sessaoId);
        
        // Verifica se o usuário é dono do produto
        if ($sessao->modulo->produto->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Você não tem permissão para isso.'], 403);
        }

        $videos = $sessao->videos()->orderBy('ordem')->get();
        
        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modulo_id' => 'required|exists:modulos,id',
            'sessoes' => 'required|array',
            'sessoes.*.id' => 'required|exists:sessoes,id',
            'sessoes.*.ordem' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Dados inválidos'], 400);
        }

        $modulo = Modulo::findOrFail($request->modulo_id);
        if ($modulo->produto->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Você não tem permissão para isso.'], 403);
        }

        foreach ($request->sessoes as $item) {
            $sessao = Sessao::findOrFail($item['id']);
            if ($sessao->modulo_id != $modulo->id) {
                continue;
            }
            $sessao->update(['ordem' => $item['ordem']]);
        }

        return response()->json(['success' => true]);
    }
}
