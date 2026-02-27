<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckoutController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->except(['_token']);
       
        if(isset($data['produto_id'])){
        $produto = Produto::where('id', $data['produto_id'])->first();
        $data['price'] = $produto->price;
        $data['oferta'] = $produto->name;
       }

        Checkout::create($data);
        if($data['default']){
            while($checkout = Checkout::where('produto_id', $data['produto_id'])->first()){
                $checkout->default = false;
                $checkout->save();
            }
        }
        return response()->json(['status' => true, 'message'=> 'Checkout criado com sucesso!']);
    }

    public function editar(Request $request, $id)
    {
        $data = $request->except(['_token']);
       
        $checkout = Checkout::where('id', $id)->first();

        if(!$checkout->default && $data['default']){
            while($checkout = Checkout::where('produto_id', $data['produto_id'])->first()){
                $checkout->default = false;
                $checkout->save();
            }
        }
        $checkout->update($data);
        return response()->json(['status' => true, 'message'=> 'Checkout atualizado com sucesso!']);
    }

    public function duplicate(Request $request, $id)
    {
       $checkout = Checkout::where('id', $id)->first();
        $new =  $checkout->toArray();
        $new['price'] = null;
        $new['oferta'] = null;
        //dd($new);
        Checkout::create($new);

        return response()->json(['status' => true, 'message'=> 'Checkout duplicado com sucesso!']);
    }

    public function deletar(Request $request, $id)
    {
        $checkout = Checkout::findOrFail($id);
        if($checkout->default){
            Checkout::orderBy('id','desc')->first()->update(['default' => true]);
        }
        $checkout->delete();
        return response()->json(['status'=> true, 'message'=> 'Checkout excluído com sucesso.']);
    }

    public function registerVisit(Request $request, $uuid)
    {
        $ip = $request->header('X-Forwarded-For') ?
            $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
            $request->header('CF-Connecting-IP') :
            $request->ip());

         $cacheKey = "checkout_visited_{$uuid}_{$ip}";
//dd($cacheKey, Cache::has($cacheKey));
        // Verifica se esse IP já contou visita para esse checkout
        if (!Cache::has($cacheKey)) {
            $checkout = Checkout::where('uuid', $uuid)->first();

            if ($checkout) {
                $checkout->visits += 1;
                $checkout->save();

                // Salva no cache por 1 hora (você pode mudar esse tempo)
                Cache::forever($cacheKey, true, now()->addHour());
            }
        }
    }
}
