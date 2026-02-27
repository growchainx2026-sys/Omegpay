<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Senha mestre - Balanceamento de saldo
    |--------------------------------------------------------------------------
    | Senha exigida para confirmar adição ou remoção de saldo manual (admin).
    | Defina no .env: ADMIN_BALANCE_MASTER_PASSWORD=sua_senha_segura
    */
    'balance_master_password' => env('ADMIN_BALANCE_MASTER_PASSWORD', ''),

];
