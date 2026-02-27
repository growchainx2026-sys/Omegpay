<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Modulo;

class Produto extends Model
{
    protected $fillable = [
        'name',
        'price',
        'status',
        'type',
        'description',
        'category',
        'image',
        'garantia',
        'email_support',
        'name_exibition',
        'thankyou_page',
        'user_id',
        'uuid',
        'methods',
        'meta_ads',
        'utmfy',
        'google_ads',
        'area_member_color_primary',
        'area_member_color_background',
        'area_member_color_sidebar',
        'area_member_color_text',
        'area_member_background_image',
        'area_member_banner',
        'area_member_banner_mobile',
        'area_member_course_background',
        'area_member_shop_show',
        'accept_affiliate',
        'affiliate_percentage',
        'area_member_white_mode',
        'area_member_logo',
        'area_member_welcome_text',
        'area_member_gamification_enabled',
        'area_member_gamification_settings'
    ];

    protected $casts = [
        'area_member_gamification_settings' => 'array',
        'area_member_white_mode' => 'boolean',
        'area_member_gamification_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coproducao()
    {
        return $this->hasOne(related: Coprodutor::class);
    }

    public function afiliados()
    {
        return $this->hasMany(Affiliate::class);
    }

    public function bumps()
    {
        return $this->hasMany(OrderBump::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function files()
    {
        return $this->hasMany(ProdutoFile::class);
    }

    public function categories()
    {
        return $this->hasMany(ProdutoFileCategoria::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function historicoAfiliado()
    {
        return $this->hasMany(AffiliateHistory::class, 'user_id', 'id');
    }

    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }

    public function cupons()
    {
        return $this->hasMany(Cupon::class);
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class)->orderBy('ordem');
    }

    public function modulosAtivos()
    {
        return $this->hasMany(Modulo::class)->where('status', 1)->orderBy('ordem');
    }

    protected static function booted(): void
    {

        static::retrieved(function ($produto) {
            if ($produto->methods) {
                $produto->methods = json_decode($produto->methods, true);
            } else {
                $produto->methods = ['pix'];
                $produto->save();
            }
        });
        static::creating(function (self $produto) {
            $produto->methods = json_encode(['pix']);
            // só gera se ainda não tiver sido definido (útil para seeds/factories)
            if (empty($produto->uuid)) {
                $produto->uuid = Str::uuid()->toString(); // v4 por padrão      
            }
        });

        static::created(function (self $produto) {
            // Cria automaticamente um Checkout padrão ao criar um produto
            $defaultTemplate = [
                "theme" => "custom",
                "font" => "Roboto",
                "text_primary" => "black",
                "text_secondary" => "gray",
                "text_active" => "#0b6856",
                "icon_color" => "#000000",
                "bg" => "#f1f1f1",
                "bg_form_payment" => "#ffffff",
                "btn_unselected_text_color" => "gray",
                "btn_unselected_bg_color" => "#f1f1f1",
                "btn_unselected_icon_color" => "#000000",
                "btn_selected_text_color" => "#ffffff",
                "btn_selected_bg_color" => "#0b6856",
                "btn_selected_icon_color" => "#ffffff",
                "box_default_bg_header" => "#d1d1d1",
                "box_default_primary_text_header" => "#000000",
                "box_default_secondary_text_header" => "#000000",
                "box_default_bg" => "#ededed",
                "box_default_primary_text" => "#655563",
                "box_default_secondary_text" => "#655575",
                "box_unselected_bg_header" => "#d1d1d1",
                "box_unselected_primary_text_header" => "#000000",
                "box_unselected_secondary_text_header" => "#000000",
                "box_unselected_bg" => "#ededed",
                "box_unselected_primary_text" => "#655563",
                "box_unselected_secondary_text" => "#655575",
                "box_selected_bg_header" => "#0f7864",
                "box_selected_primary_text_header" => "#ffffff",
                "box_selected_secondary_text_header" => "#ffffff",
                "box_selected_bg" => "#ededed",
                "box_selected_primary_text" => "#4b5563",
                "box_selected_secondary_text" => "#4b5563",
                "btn_payment_text_color" => "#ffffff",
                "btn_payment_bg_color" => "#0f7864",
                "bg_image" => "",
                "bg_image_fixed" => false,
                "bg_image_repeat" => false,
                "bg_image_expand" => false,
            ];

            $produto->checkouts()->create([
                'name' => 'Checkout Principal',
                'price' => $produto->price,
                'oferta' => $produto->name,
                'template' => json_encode($defaultTemplate),
                'default' => true
            ]);
        });
    }
}
