<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Services\StripeService;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function paymentIntent(Request $request)
    {
        $data = $request->all();
        $produto = Produto::where('uuid', $data['produto_id'])->first();
        $stripe = new StripeService();
        return $stripe->paymentCard($data['amount'], $data['currency'], 3);
    }
}
