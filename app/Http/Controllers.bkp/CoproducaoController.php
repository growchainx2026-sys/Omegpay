<?php

namespace App\Http\Controllers;

use App\Models\Coprodutor;
use App\Models\Produto;
use App\Models\User;
use App\Notifications\GeralNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CoproducaoController extends Controller
{
    public function index()
    {
        $coproducoes = Coprodutor::where('user_id', auth()->user()->id)
            ->where('produto_id', '!=', null)
            ->where('accept', '!=', 'recused')
            ->get();
        return view("pages.coproducoes", compact("coproducoes"));
    }

    public function accept(Request $request)
    {
        $id = $request->id;
        $coproducao = Coprodutor::where('id', $id)->first();
        $coproducao->accept = 'accept';
        $coproducao->save();

        $coproducao->produto->user->notify(new GeralNotification(
            'Coprodução aceita.',
            "{$coproducao->user->name} aceitou a coprodução para {$coproducao->produto->name}.",
            "#"
        ));

        return back()->with('success', 'Pedido de coprodução aceita com sucesso!');
    }
    public function recuse(Request $request)
    {
        $id = $request->id;
        $coproducao = Coprodutor::where('id', $id)->first();
        $coproducao->accept = 'recused';
        $coproducao->save();

        $coproducao->produto->user->notify(new GeralNotification(
            'Coprodução recusada.',
            "{$coproducao->user->name} recusou a coprodução para {$coproducao->produto->name}.",
            "#"
        ));

        return back()->with('success', 'Pedido de coprodução recusada com sucesso!');
    }

    public function revogue(Request $request, $id)
    {

        $coprodutor = Coprodutor::findOrFail($id); // garante que exista
        $notificationsCop = $coprodutor->user->notifications;
        $notificationsProd = $coprodutor->produto->user->notifications;

        foreach ($notificationsCop as $notification) {
            if (isset($notification->data['coproducao_id']) && $notification->data['coproducao_id'] == $coprodutor->id) {
                $notification->delete();
            }
        }

        foreach ($notificationsProd as $notification) {
            if (isset($notification->data['coproducao_id']) && $notification->data['coproducao_id'] == $coprodutor->id) {
                $notification->delete();
            }
        }
        $coprodutor->delete(); // ou forceDelete() se usar SoftDeletes

        return back()->with('success', 'Coprodução revogada com sucesso!');
    }

    public function add(Request $request, $uuid)
    {
        $periodo = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 365, 'sempre'];

        if (is_null($request->coprodutor_percentage)) {
            return back()
                ->with('modal', 'true')
                ->withErrors(['coprodutor_percentage' => 'Digite um valor entre 0 e 99'])
                ->withInput();
        }

        $cop = [
            'coprodutor_email' => $request->coprodutor_email,
            'coprodutor_percentage' => (float) $request->coprodutor_percentage,
            'coprodutor_periodo' => $request->coprodutor_periodo,
        ];

        $validator = Validator::make(
            $cop,
            [
                'coprodutor_email' => ['required', 'email'],
                'coprodutor_percentage' => ['required', 'integer', 'min:0', 'max:99'],
                'coprodutor_periodo' => ['required', Rule::in($periodo)],
            ],
            [
                'coprodutor_email.required' => 'O campo email é obrigatório',
                'coprodutor_email.email' => 'Digite um email válido',
                'coprodutor_periodo.in' => 'Selecione um período.',
                'coprodutor_periodo.required' => 'O campo período é obrigatório.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->with('modal', 'true')
                ->withErrors($validator)
                ->withInput();
        }

        $cop = [
            'email' => $request->coprodutor_email,
            'percentage' => (float) $request->coprodutor_percentage,
            'periodo' => $request->coprodutor_periodo,
        ];
        $user = User::where('email', $cop['email'])->first();
        if (!$user) {
            return back()
                ->with('modal', 'true')
                ->withErrors(['coprodutor_email' => 'Esse usuário não existe. Verifique.'])
                ->withInput();
        }

        $produto = Produto::where('uuid', $uuid)->first();

        if (!is_null($cop)) {
            $user = User::where('email', $cop['email'])->first();
            $cop['user_id'] = $user->id;
            $cop['produto_id'] = $produto->id;
            $coproducao = Coprodutor::create($cop);

            $produtor = $coproducao->produto->name_exibition ?? $coproducao->produto->name ?? "---";
            $produto = $coproducao->produto->name ?? "---";
            $coproducao->user->notify(new GeralNotification(
            'Solicitação de coprodução.',
            "{$produtor} enviou um convite de coprodução para {$produto}.",
            "/coproducoes"
        ));
        }
        return redirect()->back()->with('success', 'Solicitação de coprodução enviada com sucesso!');
    }

    public function indexNotify()
    {
        return view('pages.notifications');
    }

    public function deleteNotify(Request $request)
    {
        auth()->user()->notifications()->where('id', $request->input('id'))->delete();
        return redirect()->back()->with('success', 'Notificação excluída com sucesso!');
    }

    public function readNotify(Request $request)
    {
        auth()->user()->notifications()->where('id', $request->input('id'))->update(['read_at' => Carbon::now()]);
        auth()->user()->fresh();
    }
}
