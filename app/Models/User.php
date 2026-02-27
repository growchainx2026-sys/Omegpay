<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'password',
        'username',
        'cpf_cnpj',
        'data_nascimento',
        'nome_mae',
        'nome_pai',
        'telefone',
        'saldo',
        'saldo_a_liberar',
        'saldo_reserva',
        'total_transacoes',
        'permission',
        'avatar',
        'status',
        'data_cadastro',
        'ip_user',
        'transacoes_aproved',
        'transacoes_recused',
        'valor_sacado',
        'valor_saque_pendente',
        'adquirente_default',
        'use_taxas_individual',
        'taxa_cash_in',
        'taxa_cash_out',
        'taxa_cash_in_fixa',
        'taxa_cash_out_fixa',
        'taxa_reserva',
        'deposito_minimo',
        'deposito_maximo',
        'saque_minimo',
        'saque_maximo',
        'saques_dia',
        'banido',
        'clientId',
        'secret',
        'taxa_percentual',
        'volume_transacional',
        'valor_pago_taxa',
        'cep',
        'rua',
        'estado',
        'cidade',
        'bairro',
        'numero_residencia',
        'complemento',
        'foto_rg_frente',
        'foto_rg_verso',
        'selfie_rg',
        'media_faturamento',
        'codigo_referencia',
        'client_id',
        'client_indication',
        'nivel',
        'prox_nivel',
        'webhook_cash_in',
        'webhook_cash_out',
        'ativar_split',
        'split_fixed',
        'split_percent',
        'razao_social',
        'utmfy',
        'spedy',
        'device_token',
        'plan_card',
        'logo',
        'software_color',
        'software_color_background',
        'software_color_sidebar',
        'software_color_text',
        'logo_light',
        'favicon_light',
    ];

    public function whitelist()
    {
        return $this->hasMany(Whitelist::class);
    }

    public function transactions_in()
    {
        return $this->hasMany(TransactionIn::class);
    }

    public function transactions_out()
    {
        return $this->hasMany(TransactionOut::class);
    }

    public function nivelAtual()
    {
        return $this->belongsTo(Gamefication::class, 'nivel', 'id');
    }

    public function proxNivel()
    {
        return $this->belongsTo(Gamefication::class, 'prox_nivel', 'id');
    }

    public function indicados()
    {
        return $this->hasMany(User::class, 'client_indication', 'codigo_referencia');
    }

    public function indicadoPor()
    {
        return $this->belongsTo(User::class, 'client_indication', 'codigo_referencia')->select(['ativar_split', 'split_percent', 'split_fixed']);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function coproducoes()
    {
        return $this->hasMany(Coprodutor::class);
    }

    public function afiliacoes()
    {
        return $this->hasMany(Affiliate::class);
    }

    public function historico_afiliado()
    {
        return $this->hasMany(AffiliateHistory::class);
    }

    public function historicoAfiliado()
    {
        return $this->hasMany(AffiliateHistory::class, 'user_id', 'id');
    }

    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }

    public function webhook()
    {
        return $this->belongsTo(Webhook::class);
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function tokens()
    {
        return $this->hasMany(Fmcdevice::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Identificador do JWT (normalmente o ID do usuário)
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retorna qualquer informação customizada que você quiser adicionar no payload
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
