<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }

        $produtos = auth()->user()->produtos;
        $webhooks = auth()->user()->webhooks;
        return view('pages.webhooks', compact('webhooks', 'produtos'));
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 'pago';

        if ($data['type'] == 'produto') {
            $validator = Validator::make($data, [
                'produto_id' => 'required',
            ], [
                'produto_id.required' => 'Selecione um produto.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('modal-webhooks', true) // flag para reabrir modal
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        Webhook::create($data);
        return back()->with('success', 'Webhook adcionado com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        Webhook::find($id)->update($data);
        return back()->with('success', 'Webhook alterado com sucesso.');
    }

    public function destroy(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        Webhook::find($id)->delete();
        return back()->with('success', 'Webhook excluído com sucesso.');
    }
}
