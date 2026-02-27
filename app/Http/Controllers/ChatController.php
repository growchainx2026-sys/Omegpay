<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Produto;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /** Aluno: listar mensagens da conversa com o produtor do curso */
    public function alunoMessages(Request $request, $produtoId)
    {
        $aluno = auth('aluno')->user();
        $produto = Produto::find($produtoId);
        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }
        $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
        if (!$pedido) {
            return response()->json(['error' => 'Sem acesso ao curso'], 403);
        }

        $produto->load('user');
        $messages = ChatMessage::where('produto_id', $produto->id)
            ->where('aluno_id', $aluno->id)
            ->orderBy('created_at')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'body' => $m->body,
                    'sender_type' => $m->sender_type,
                    'created_at' => $m->created_at->format('H:i'),
                    'created_at_iso' => $m->created_at->toIso8601String(),
                ];
            });

        return response()->json(['messages' => $messages, 'produtor_name' => $produto->user?->name ?? 'Suporte']);
    }

    /** Aluno: enviar mensagem */
    public function alunoSend(Request $request)
    {
        $request->validate(['produto_id' => 'required|exists:produtos,id', 'body' => 'required|string|max:2000']);
        $aluno = auth('aluno')->user();
        $produto = Produto::find($request->produto_id);
        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }
        $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
        if (!$pedido) {
            return response()->json(['error' => 'Sem acesso ao curso'], 403);
        }

        $m = ChatMessage::create([
            'produto_id' => $produto->id,
            'aluno_id' => $aluno->id,
            'sender_type' => 'aluno',
            'sender_id' => $aluno->id,
            'body' => $request->body,
        ]);

        return response()->json([
            'message' => [
                'id' => $m->id,
                'body' => $m->body,
                'sender_type' => $m->sender_type,
                'created_at' => $m->created_at->format('H:i'),
                'created_at_iso' => $m->created_at->toIso8601String(),
            ],
        ]);
    }

    /** Produtor: últimas mensagens por aluno (para notificações e ordenar lista) */
    public function produtorLastMessages(Request $request, $uuid)
    {
        $produto = Produto::where('uuid', $uuid)->first();
        if (!$produto || $produto->user_id !== auth()->id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $lastMessages = ChatMessage::where('produto_id', $produto->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('aluno_id')
            ->keyBy('aluno_id');

        $alunos = \App\Models\Pedido::where('produto_id', $produto->id)
            ->where('status', 'pago')
            ->with('aluno')
            ->get()
            ->pluck('aluno')
            ->unique('id')
            ->filter();

        $list = [];
        foreach ($alunos as $aluno) {
            $msg = $lastMessages->get($aluno->id);
            $list[] = [
                'aluno_id' => $aluno->id,
                'aluno_name' => $aluno->name,
                'aluno_avatar' => $aluno->avatar ? asset($aluno->avatar) : '',
                'last_message_id' => $msg ? $msg->id : null,
                'last_sender_type' => $msg ? $msg->sender_type : null,
                'last_created_at' => $msg ? $msg->created_at->format('d/m H:i') : null,
                'last_ts' => $msg ? $msg->created_at->timestamp : 0,
                'last_body_preview' => $msg ? \Str::limit($msg->body, 40) : null,
            ];
        }

        usort($list, function ($a, $b) {
            return ($b['last_ts'] ?? 0) <=> ($a['last_ts'] ?? 0);
        });

        return response()->json(['conversations' => $list]);
    }

    /** Produtor: listar mensagens com um aluno */
    public function produtorMessages(Request $request, $uuid, $alunoId)
    {
        $produto = Produto::where('uuid', $uuid)->first();
        if (!$produto || $produto->user_id !== auth()->id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $messages = ChatMessage::where('produto_id', $produto->id)
            ->where('aluno_id', $alunoId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'body' => $m->body,
                    'sender_type' => $m->sender_type,
                    'created_at' => $m->created_at->format('H:i'),
                    'created_at_iso' => $m->created_at->toIso8601String(),
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    /** Produtor: enviar mensagem para aluno */
    public function produtorSend(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'aluno_id' => 'required|exists:alunos,id',
            'body' => 'required|string|max:2000',
        ]);
        $produto = Produto::find($request->produto_id);
        if (!$produto || $produto->user_id !== auth()->id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $m = ChatMessage::create([
            'produto_id' => $produto->id,
            'aluno_id' => $request->aluno_id,
            'sender_type' => 'user',
            'sender_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return response()->json([
            'message' => [
                'id' => $m->id,
                'body' => $m->body,
                'sender_type' => $m->sender_type,
                'created_at' => $m->created_at->format('H:i'),
                'created_at_iso' => $m->created_at->toIso8601String(),
            ],
        ]);
    }
}
