<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{TransactionIn, TransactionOut};

class ComprovanteController extends Controller
{
    public function comprovantePix(Request $request, $uuid)
    {
        $tipo = 'deposito';
        $transaction = TransactionIn::where('idTransaction', $uuid)->where('status', 'pago')->first();
        if(!$transaction) {
            $tipo = 'saque';
            $transaction = TransactionOut::where('external_id', $uuid)->where('status', 'pago')->first();
        }
        if(!$transaction) {
            return abort(404,'Comprovante não encontrado');
        }
        return view('pages.comprovante', compact('transaction', 'tipo'));
    }
}
