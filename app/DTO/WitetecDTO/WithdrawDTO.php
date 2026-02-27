<?php

namespace App\DTO\WitetecDTO;

use App\DTO\WitetecDTO\Enums\PixKeyType;
use App\DTO\WitetecDTO\Enums\WithdrawMethod;


class withdrawDTO 
{
    public function __construct(
    public int $amount,
    public string $pixKey,
    public PixKeyType $pixKeyType,
    public WithdrawMethod $method 
    ) {}
}