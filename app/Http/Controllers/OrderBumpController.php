<?php

namespace App\Http\Controllers;

use App\Models\OrderBump;
use Illuminate\Http\Request;

class OrderBumpController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $data['methods'] = json_encode($data['methods'] ?? []);
        OrderBump::create($data);

        return back()->with('success','Order bump cadastrado com sucesso!');
    }

    public function delete(Request $request, $id)
    {
        //dd($id);
        $orderBump = OrderBump::find($id);
        //dd($orderBump);
        if (!$orderBump) {
            return back()->with('error','Order bump não encontrado!');
        }
        $orderBump->delete();
        return back()->with('success','Order bump excluído com sucesso!');
    }
}
