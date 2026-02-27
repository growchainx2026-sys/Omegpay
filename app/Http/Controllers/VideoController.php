<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Sessao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sessao_id' => 'required|exists:sessoes,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'url_youtube' => 'required|url',
            'duracao' => 'nullable|integer',
            'ordem' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sessao = Sessao::findOrFail($request->sessao_id);
        
        // Verifica se o usuário é dono do produto
        if ($sessao->modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.');
        }

        $data = $request->except(['_token', '_method']);
        $data['ordem'] = $data['ordem'] ?? (Video::where('sessao_id', $sessao->id)->max('ordem') ?? 0) + 1;

        // Upload de thumbnail se fornecido
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('videos', $imageName, 'public');
            $data['thumbnail'] = '/' . $imagePath;
        }

        $data['status'] = $request->has('status') && $request->status == '1' ? true : false;
        Video::create($data);

        return back()->with('success', 'Vídeo adicionado com sucesso!')->with('tab', 'area-membros');
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $video = Video::findOrFail($id);
        
        // Verifica se o usuário é dono do produto
        if ($video->sessao->modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.')->with('tab', 'area-membros');
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:videos,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'url_youtube' => 'required|url',
            'duracao' => 'nullable|integer',
            'ordem' => 'nullable|integer',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('tab', 'area-membros');
        }

        $data = $request->except(['_token', '_method', 'thumbnail', 'id']);
        $data['status'] = $request->has('status') && $request->status == '1' ? true : false;

        // Upload de thumbnail se fornecido
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('videos', $imageName, 'public');
            $data['thumbnail'] = '/' . $imagePath;
        }

        $video->update($data);

        return back()->with('success', 'Vídeo atualizado com sucesso!')->with('tab', 'area-membros');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $video = Video::findOrFail($id);
        
        // Verifica se o usuário é dono do produto
        if ($video->sessao->modulo->produto->user_id !== auth()->user()->id) {
            return back()->with('error', 'Você não tem permissão para isso.')->with('tab', 'area-membros');
        }

        $video->delete();

        return back()->with('success', 'Vídeo excluído com sucesso!')->with('tab', 'area-membros');
    }

    public function toggleStatus(Request $request)
    {
        $id = $request->input('id');
        $video = Video::findOrFail($id);
        
        // Verifica se o usuário é dono do produto
        if ($video->sessao->modulo->produto->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Você não tem permissão para isso.'], 403);
        }

        $video->status = !$video->status;
        $video->save();

        return response()->json([
            'success' => true,
            'status' => $video->status
        ]);
    }

    public function reorder(Request $request)
    {
        $videos = $request->input('videos', []);
        
        foreach ($videos as $index => $videoId) {
            $video = Video::findOrFail($videoId);
            
            // Verifica se o usuário é dono do produto
            if ($video->sessao->modulo->produto->user_id !== auth()->user()->id) {
                return response()->json(['error' => 'Você não tem permissão para isso.'], 403);
            }
            
            $video->ordem = $index + 1;
            $video->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordem dos vídeos atualizada com sucesso!'
        ]);
    }
}
