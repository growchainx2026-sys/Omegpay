<?php

namespace App\Http\Controllers\Api\Adquirentes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\{Aluno, Pedido, TransactionIn, TransactionOut, User};
use App\Mail\SendCredentialsAluno;

class AppmaxController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::info("[APPMAX][WEBHOOK][BODY]: ", $data);
    }
}