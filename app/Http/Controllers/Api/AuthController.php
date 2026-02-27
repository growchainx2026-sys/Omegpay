<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

public function tokenApi(Request $request)
{
    $validator = Validator::make($request->all(), [
        'clientId' => 'required|string',
        'secret' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'clientId e secret são obrigatórios.',
        ], 500);
    }

    $credentials = $validator->validated();


    $user = User::where('clientId', $credentials['clientId'])
    ->where('secret', $credentials['secret'])
    ->first();

    if (!$user) {
    return response()->json([
    'message' => 'Credenciais inválidas.',
    ], 401);
    }

    $token = JWTAuth::fromUser($user);

    return response()->json([
        'accessToken' => $token,
        'expiration' => auth('api')->factory()->getTTL() * 60, // tempo em segundos
    ]);
}

}
