<?php

namespace App\Http\Controllers;

use App\Models\Efi;
use App\Models\Pagarme;
use App\Models\Setting;
use Illuminate\Http\Request;

class MinhataxaController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $taxa_cartao_fixa = 0;
        $taxa_cartao_percent = 0;

        if ($setting->adquirencia_card == 'efi') {
            $efi = Efi::first();
            $taxa_cartao_fixa = $efi->card_tx_fixed;
            $taxa_cartao_percent = $efi->card_tx_percent;
        } elseif ($setting->adquirencia_card == 'pagarme') {
            $pagarme = Pagarme::first();
            $tx = "1x";
            $taxa_cartao_fixa = 0;
            $taxa_cartao_percent = $pagarme->$tx;
        }

        $taxa_c_fix = $taxa_cartao_fixa;
        $taxa_c_perc = $taxa_cartao_percent;

        if (auth()->user()->plan_card == 'opt1') {
            $taxa_cartao_percent += $setting->card_tx_to_anticipation_opt1;
        } elseif (auth()->user()->plan_card == 'opt2') {
            $taxa_cartao_percent += $setting->card_tx_to_anticipation_opt2;
        }

        $antecipacoes = [];
        $antecipacoes[] = [
            'value' => 'default',
            'label' => $setting->card_days_to_release . ' dias',
            'taxa' => 'R$ ' . number_format($taxa_c_fix, 2, ',', '.') . ' + ' . number_format($taxa_c_perc, 2, ',', '.') . '%',
        ];
        if ($setting->card_days_to_anticipation_opt1 > 0 && $setting->card_tx_to_anticipation_opt1 > 0) {
            $antecipacoes[] = [
                'value' => 'opt1',
                'label' =>  $setting->card_days_to_anticipation_opt1 > 1 ? $setting->card_days_to_anticipation_opt1 .' dias' : $setting->card_days_to_anticipation_opt1 .' dia',
                'taxa' => 'R$ ' . number_format($taxa_c_fix, 2, ',', '.') . ' + ' . number_format($taxa_c_perc + $setting->card_tx_to_anticipation_opt1, 2, '.') . '%'
            ];
        }

        if ($setting->card_days_to_anticipation_opt2 > 0 && $setting->card_tx_to_anticipation_opt2 > 0) {
            $antecipacoes[] = [
                'value' => 'opt2',
                'label' => $setting->card_days_to_anticipation_opt2 > 1 ? $setting->card_days_to_anticipation_opt2 . ' dias' : $setting->card_days_to_anticipation_opt2 . ' dia',
                'taxa' => 'R$ ' . number_format($taxa_c_fix, 2, ',', '.') . ' + ' . number_format($taxa_c_perc + $setting->card_tx_to_anticipation_opt2, 2, '.') . '%'
            ];
        }

        return view('pages.minhas-taxas', compact('setting', 'taxa_cartao_fixa', 'taxa_cartao_percent', 'antecipacoes'));

    }

    public function update(Request $request)
    {
        $data = $request->all();
        auth()->user()->update(['plan_card' => $data['plan_card']]);
        auth()->user()->save();
        auth()->user()->fresh();
        return back()->with('success', 'Antecipação atualizada com sucesso!');
    }
}
