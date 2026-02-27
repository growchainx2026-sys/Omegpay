<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Adquirente, Cashtime, Sixxpayments, Setting, Efi, Transfeera};
use App\Models\Pagarme;
use App\Models\Stripe;
use App\Models\Witetec;
use Illuminate\Http\Request;
use App\Traits\EfiTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class AdquirenteController extends Controller
{
    public function index(Request $request)
    {

        if (auth()->user()->permission !== 'admin') {
            return back()->route('dashboard');
        }
        $adquirenciaPix = Setting::first()->adquirencia_pix;
        $adquirenciaBillet = Setting::first()->adquirencia_billet;
        $adquirenciaCard = Setting::first()->adquirencia_card;
        $adquirencia = Setting::first()->adquirencia;

        $cashtime = Cashtime::first();
        $sixxpayments = Sixxpayments::first();
        $efi = Efi::first();
        $transfeera = Transfeera::first();
        $witetec = Witetec::first();
        $pagarme = Pagarme::first();
        $stripe = Stripe::first();

        return view(
            'pages.admin.adquirentes',
            compact(
                'adquirencia',
                'adquirenciaPix',
                'adquirenciaBillet',
                'adquirenciaCard',
                'cashtime',
                'sixxpayments',
                'efi',
                'transfeera',
                'witetec',
                'pagarme',
                'stripe'
            )
        );
    }

    public function update(Request $request)
    {

        $data = $request->all();
        //dd($data);
        /* $sixxpayments = [
            "secret" => $data["sixxpayments_secret"],
            "taxa_cash_in" => $data["sixxpayments_taxa_cash_in"],
            "taxa_cash_out" => $data["sixxpayments_taxa_cash_out"]
        ]; */

        $cashtime = [
            "secret" => $data["cashtime_secret"],
            "taxa_cash_in" => $data["cashtime_taxa_cash_in"],
            "taxa_cash_out" => $data["cashtime_taxa_cash_out"]
        ];

        $witetec = [
            "api_token" => $data['witetec_api_token'],
            "taxa_cash_in" => $data["witetec_taxa_cash_in"],
            "taxa_cash_out" => $data["witetec_taxa_cash_out"]
        ];


        $pagarme = [
            "secret" => $data['pagarme_secret'],
            "tx_pix_cash_in" => (float) str_replace([','], '.', $data["witetec_taxa_cash_in"]),
            "tx_pix_cash_out" => (float) str_replace([','], '.', $data["witetec_taxa_cash_out"]),
            '1x' => (float) str_replace([','], '.', $data['pagarme_1x']),
            '2x' => (float) str_replace([','], '.', $data['pagarme_2x']),
            '3x' => (float) str_replace([','], '.', $data['pagarme_3x']),
            '4x' => (float) str_replace([','], '.', $data['pagarme_4x']),
            '5x' => (float) str_replace([','], '.', $data['pagarme_5x']),
            '6x' => (float) str_replace([','], '.', $data['pagarme_6x']),
            '7x' => (float) str_replace([','], '.', $data['pagarme_7x']),
            '8x' => (float) str_replace([','], '.', $data['pagarme_8x']),
            '9x' => (float) str_replace([','], '.', $data['pagarme_9x']),
            '10x' => (float) str_replace([','], '.', $data['pagarme_10x']),
            '11x' => (float) str_replace([','], '.', $data['pagarme_11x']),
            '12x' => (float) str_replace([','], '.', $data['pagarme_12x']),
            'tx_billet_percent' => (float) str_replace([','], '.', $data['pagarme_tx_billet_percent']),
            'tx_billet_fixed' => (float) str_replace([','], '.', $data['pagarme_tx_billet_fixed'])
        ];

        $stripe = [
            'public_key' => $data['stripe_public_key'],
            'secret_key' => $data['stripe_secret_key'],
            'tx_card_fixed' => $data['stripe_tx_card_fixed'],
            'tx_card_percent' => $data['stripe_tx_card_percent'],
        ];

        Cashtime::first()->update($cashtime);
        /*  Sixxpayments::first()->update($sixxpayments); */
        Witetec::first()->update($witetec);
        Pagarme::first()->update($pagarme);
        Stripe::first()->update($stripe);
        Setting::first()->update([
            'adquirencia_pix' => $data['adquirencia_pix'],
            'adquirencia_billet' => $data['adquirencia_billet'],
            'adquirencia_card' => $data['adquirencia_card'],
        ]);

        self::updateEfi($request);

        return back()->with('success', 'Adquirente atualizado com sucesso!');
    }

    public function status(Request $request)
    {
        $id = $request->id;
        $data['active'] = (int) $request->active;
        //dd($id, $data);
        Adquirente::find($id)->update($data);

        return back()->back()->with('success', 'Adquirente atualizado com sucesso!');
    }

    private static function updateEfi(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        //dd($request);
        if ($request->hasFile('efi_cert') && $request->file('efi_cert')->isValid()) {
            $certificado = $request->file('efi_cert');
            $data['efi_cert'] = "Certificado adcionado";
            // Armazena como 'producao.pem'
            Storage::disk('certificados')->put('producao.p12', file_get_contents($certificado));
            $certPath = storage_path('app/private/certificados/producao.p12');
            $pemPath = storage_path('app/private/certificados/producao.pem');
            $process = new Process([
                'openssl',
                'pkcs12',
                '-in',
                $certPath,
                '-out',
                $pemPath,
                '-nodes',
                '-password',
                'pass:'
            ]);
            $process->run();

            if ($process->isSuccessful()) {
                Log::debug("Certificado convertido com sucesso.");
            } else {
                Log::error('Erro OpenSSL: ' . $process->getErrorOutput());
            }
        }

        $efi = [
            'client_id' => $data['efi_client_id'],
            'client_secret' => $data['efi_client_secret'],
            'chave_pix' => $data['efi_chave_pix'],
            'identificador_conta' => $data['efi_identificador_conta'],
            'cert' => "adcionado",
            'taxa_pix_cash_in' => (float) $data['efi_taxa_pix_cash_in'],
            'taxa_pix_cash_out' => (float) $data['efi_taxa_pix_cash_out'],
            'billet_tx_fixed' => (float) $data['efi_billet_tx_fixed'],
            'billet_tx_percent' => (float) $data['efi_billet_tx_percent'],
            'card_tx_fixed' => (float) $data['efi_card_tx_fixed'],
            'card_tx_percent' => (float) $data['efi_card_tx_percent'],
        ];

        Efi::first()->update($efi);
    }

    public function efiRegistrarWebhook(Request $request)
    {
        EfiTrait::cadastrarWebhook();
        return back()->with('success', 'Webhooks Efí atualizados com sucesso!');
    }
}
