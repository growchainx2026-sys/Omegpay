<?php
// app/Models/Checkout.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Checkout extends Model
{
    protected $fillable = ['uuid', 'name', 'price', 'oferta', 'config', 'default', 'visits', 'produto_id', 'user_id', 'layout', 'template', 'depoimentos'];
    protected $casts = [
        'layout' => 'array',
        'template' => 'array',
        'depoimentos' => 'array',
        'config' => 'array',
        'default' => 'boolean'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Evento automático ao criar
    protected static function booted()
    {

        // Template padrão
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

        static::creating(function ($checkout) use ($defaultTemplate) {
           // dd($checkout);
            if (is_null($checkout->template)) {
                $checkout->template = json_encode($defaultTemplate);
            }
        });


        static::retrieved(function ($checkout) {
            foreach (['layout', 'template'] as $campo) {
                if (!is_null($checkout->$campo) && is_string($checkout->$campo)) {
                    $checkout->$campo = json_decode($checkout->$campo, true);
                }
            }
        });

        // Ao recuperar, transforma para array e aplica default se estiver null
        static::retrieved(function ($checkout) use ($defaultTemplate) {
            if (is_null($checkout->template)) {
                $checkout->template = $defaultTemplate;
                $checkout->save();
            } elseif (is_string($checkout->template)) {
                $checkout->template = json_decode($checkout->template, true);
            }
        });

        static::creating(function (self $checkout) {
            // só gera se ainda não tiver sido definido (útil para seeds/factories)
            if (empty($checkout->uuid)) {
                $checkout->uuid = Str::uuid()->toString(); // v4 por padrão
            }
        });

        static::creating(function ($checkout) {
            if (Auth::check() && !$checkout->user_id) {
                $checkout->user_id = Auth::id();
            }
        });
    }
}
