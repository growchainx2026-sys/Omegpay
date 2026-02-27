<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CuponController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->except('_token', '_method');
        //dd($data);

      	$data['desconto'] = str_replace([',', 'R$','R$ '], ['.', '',''], $data['desconto']);
        $data['inicio'] = Carbon::parse($data['inicio'])->timestamp;
        $data['fim'] = Carbon::parse($data['fim'])->timestamp;
        // Se você quiser salvar como timestamp (inteiro)
        $data['data_inicio'] = $data['inicio'];
        $data['data_termino'] = $data['fim'];

        unset($data['inicio'], $data['fim']);
        $data['aplicar_orderbumps'] = $request->has('aplicar_orderbumps');
        Cupon::create($data);

        return back()->with('success', 'Cupom adicionado com sucesso.');

    }

    public function edit(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $data['inicio'] = Carbon::parse($data['inicio'])->timestamp;
        $data['fim'] = Carbon::parse($data['fim'])->timestamp;
        // Se você quiser salvar como timestamp (inteiro)
        $data['data_inicio'] = $data['inicio'];
        $data['data_termino'] = $data['fim'];
      	$data['desconto'] = str_replace([',', 'R$','R$ '], ['.', '',''], $data['desconto']);

        unset($data['inicio'], $data['fim']);

        Cupon::findOrFail($id)->update($data);
        return back()->with('success','Cupom alterado com sucesso.');
    }

    public function del(Request $request, $id)
    {
        Cupon::find($id)->delete();
        return back()->with('success','Cupom excluído com sucesso.');
    }
}
