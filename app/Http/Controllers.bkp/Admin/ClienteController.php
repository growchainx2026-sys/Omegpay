<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{

    public function index(Request $request)
    {
        if (auth()->user()->permission !== 'admin') {
            return back()->route('dashboard');
        }

        $clientes = User::get();
        return view('pages.admin.clientes', compact('clientes'));
    }

    public function liberarUsuario(Request $request)
    {
        $ci = Str::uuid7()->toString();
        $cs = Str::uuid7()->toString();

        $id = $request->id;
        $user = User::find($id);
        $name = explode(' ', $user->name);
        $name = $name[0];
        $name = strtolower($name);

        $clientId = "ct_" . $name . '_' . str_replace('-', '', $ci);
        $secret = "cs_" . $name . '_' . str_replace('-', '', $cs);
        
    }

    public function indexEdit(Request $request, $id)
    {
        $client = User::where('id', $id)->first();
        return view('pages.admin.cliente', compact('client'));
    }

    public function update(Request $request)
    {
        //dd((int) $request->ativar_split == 1 ? true :  false);

        $cliente = User::findOrFail($request->id);
        $cliente->name = $request->name;
        $cliente->email = $request->email;
        $cliente->cpf_cnpj = $request->cpf_cnpj;
        $cliente->clientId = $request->clientId;
        $cliente->secret = $request->secret;
        $cliente->codigo_referencia = $request->codigo_referencia;
        $cliente->use_taxas_individual = (int) 1;
        $cliente->baseline = (float) str_replace([','], '.', $request->baseline);
        $cliente->taxa_cash_in = (float) str_replace([','], '.', $request->taxa_cash_in);
        $cliente->taxa_cash_out = (float) str_replace([','], '.', $request->taxa_cash_out);
        $cliente->taxa_cash_in_fixa = (float) str_replace([','], '.', $request->taxa_cash_in_fixa);
        $cliente->taxa_cash_out_fixa = (float) str_replace([','], '.', $request->taxa_cash_out_fixa);
        $cliente->ativar_split = (int) $request->ativar_split == 1 ? true :  false;
        $cliente->split_fixed = $request->split_fixed ?? 0;
        $cliente->split_percent = $request->split_percent ?? 0;

        if ($request->new_password) {
            $cliente->password = Hash::make($request->new_password);
        }

        $cliente->save();

        return back()->with('success', 'Cliente atualizado com sucesso!');
    }

    public function excluir(Request $request)
    {
        $id = $request->input('id');
        User::where('id', $id)->delete();
        return back()->with('success', 'Usuário excluído com sucesso.');
    }

    public function status(Request $request)
    {
        $data = [];
        $inputs = ['status', 'banido'];

        foreach ($inputs as $input) {
            if ($request->has($input)) {
                $data[$input] = $request->input($input);
            }
        }

        $id = $request->id;

        if (!empty($data)) {
            $user = User::find($id);
            if ($input === 'status' && $request->input('status') === 'aprovado') {

                $name = explode(' ', $user->name);
                $name = $name[0];
                $name = strtolower($name);

                if (is_null($user->clientId)) {
                    $ci = Str::uuid7()->toString();
                    $clientId = "ct_" . $name . '_' . str_replace('-', '', $ci);
                    $data['clientId'] = $clientId;
                }
                if (is_null($user->secret)) {
                    $cs = strrev(Str::uuid7()->toString());
                    $secret = "cs_" . $name . '_' . str_replace('-', '', $cs);
                    $data['secret'] = $secret;
                }
            }
            
            $user->update($data);
        }

        return back()->with('success', 'Cliente atualizado com sucesso!');
    }
}
