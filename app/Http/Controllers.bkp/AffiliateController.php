<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\AffiliateHistory;
use App\Models\Produto;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function vitrine(Request $request)
    {
        $produtos = Produto::where('accept_affiliate', true)
            ->get();
        $afiliacoesIds = auth()->user()->afiliacoes()->pluck('produto_id')->toArray();
        return view('pages.vitrine', compact('produtos', 'afiliacoesIds'));
    }

    public function myAffiliates(Request $request)
    {
        $afiliacoes = auth()->user()->afiliacoes;
        return view('pages.my-affiliates', compact('afiliacoes'));
    }

    public function view(Request $request, string $id)
    {
        $id = str_replace(['af', 'op'], '',explode('-', $request->id)[1]);
        $afiliacao = Affiliate::where('id', $id)->first();
        if(!$afiliacao){
            return view('pages.404');
        }

        return view('pages.afiliate-produto', compact('afiliacao'));
    }
    public function affiliateMe(Request $request)
    {
        $data = $request->except(['_token']);

        $afiliacao = Affiliate::where('user_id', auth()->user()->id)
        ->where('produto_id', $request->produto_id)
        ->first();

        if($afiliacao){
            return back()->with('error','Você já é afiliado a esse produto.');
        }

        $produto = Produto::where('id', $request->produto_id)->first();

        $data['produto_id'] = $produto->id;
        $data['user_id'] = auth()->user()->id;
        $data['percentage'] = (float) $produto->affiliate_percentage;
        $data['status'] = 'accept';

        Affiliate::create($data);
        $afiliacoesIds = auth()->user()->afiliacoes()->pluck('produto_id')->toArray();
        return redirect('/affiliates/my-affiliates')->with('success', 'Afiliação registrada com sucesso.');
    }

    public function desaffiliateMe(Request $request)
    {
        $id = $request->id;
        Affiliate::where('id', $id)->first()->delete();
        return back()->with('success','Desafiliado com sucesso.');
    }
}
