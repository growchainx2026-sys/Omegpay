<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'software_name',
        'software_description',
        'software_color',
        'logo_light',
        'logo_dark',
        'favicon_light',
        'favicon_dark',
        'taxa_cash_in',
        'taxa_cash_out',
        'taxa_cash_in_fixa',
        'taxa_cash_out_fixa',
        'card_taxa_percent',
        'card_taxa_fixed',
        'billet_taxa_percent',
        'billet_taxa_fixed',
        'card_days_to_release',
        'billet_days_to_release',
        'taxa_reserva',
        'dias_liberar_reserva',
        'deposito_minimo',
        'deposito_maximo',
        'saque_minimo',
        'saque_maximo',
        'saques_dia',
        'taxa_fixa',
        'baseline',
        'valor_min_deposito',
        'active_taxa_fixa_web',
        'adquirente_default',
        'image_home',
        'login_background',
        'phone_support',
        'software_color_background',
        'software_color_sidebar',
        'software_color_text',
        'adquirencia',
        'adquirencia_pix',
        'adquirencia_billet',
        'adquirencia_card',
        'cpa',
        'rev',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'card_days_to_anticipation_opt1',
        'card_tx_to_anticipation_opt1',
        'card_days_to_anticipation_opt2',
        'card_tx_to_anticipation_opt2',
        'valor_minimo_produto'
    ];
}
