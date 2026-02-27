<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Adquirente, Cashtime, Sixxpayments, Setting, Efi, Transfeera};
use App\Models\Getpay;
use App\Models\Getpay2;
use App\Models\Rapdyn;
use App\Models\Pagarme;
use App\Models\Stripe;
use App\Models\Witetec;
use App\Models\XGate;
use Illuminate\Http\Request;
use App\Traits\EfiTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class AdquirenteController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        if (!$user || !in_array($user->permission, ['admin', 'dev'], true)) {
            return redirect()->route('dashboard');
        }
        $adquirenciaPix = Setting::first()->adquirencia_pix;
        $adquirenciaBillet = Setting::first()->adquirencia_billet;
        $adquirenciaCard = Setting::first()->adquirencia_card;
        $adquirencia = Setting::first()->adquirencia;

        $cashtime = Cashtime::first() ?? new Cashtime();
        $sixxpayments = Sixxpayments::first() ?? new Sixxpayments();
        $efi = Efi::first() ?? new Efi();
        $transfeera = Transfeera::first() ?? new Transfeera();
        $witetec = Witetec::first() ?? new Witetec();
        $pagarme = Pagarme::first() ?? new Pagarme();
        $stripe = Stripe::first() ?? new Stripe();
        $xgate = XGate::first() ?? new XGate();
        $getpay = Getpay::first() ?? new Getpay();
        $getpay2 = Getpay2::first() ?? new Getpay2();
        $rapdyn = Rapdyn::first() ?? new Rapdyn();

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
                'stripe',
                'xgate',
                'getpay',
                'getpay2',
                'rapdyn'
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
            "secret" => $data["cashtime_secret"] ?? null,
            "taxa_cash_in" => (float) str_replace([','], '.', $data["cashtime_taxa_cash_in"] ?? 0),
            "taxa_cash_out" => (float) str_replace([','], '.', $data["cashtime_taxa_cash_out"] ?? 0),
        ];

        $witetec = [
            "api_token" => $data['witetec_api_token'] ?? null,
            "taxa_cash_in" => (float) str_replace([','], '.', $data["witetec_taxa_cash_in"] ?? 0),
            "taxa_cash_out" => (float) str_replace([','], '.', $data["witetec_taxa_cash_out"] ?? 0),
        ];


        $pagarme = [
            "secret" => $data['pagarme_secret'] ?? null,
            "tx_pix_cash_in" => (float) str_replace([','], '.', $data["witetec_taxa_cash_in"] ?? 0),
            "tx_pix_cash_out" => (float) str_replace([','], '.', $data["witetec_taxa_cash_out"] ?? 0),
            '1x' => (float) str_replace([','], '.', $data['pagarme_1x'] ?? 0),
            '2x' => (float) str_replace([','], '.', $data['pagarme_2x'] ?? 0),
            '3x' => (float) str_replace([','], '.', $data['pagarme_3x'] ?? 0),
            '4x' => (float) str_replace([','], '.', $data['pagarme_4x'] ?? 0),
            '5x' => (float) str_replace([','], '.', $data['pagarme_5x'] ?? 0),
            '6x' => (float) str_replace([','], '.', $data['pagarme_6x'] ?? 0),
            '7x' => (float) str_replace([','], '.', $data['pagarme_7x'] ?? 0),
            '8x' => (float) str_replace([','], '.', $data['pagarme_8x'] ?? 0),
            '9x' => (float) str_replace([','], '.', $data['pagarme_9x'] ?? 0),
            '10x' => (float) str_replace([','], '.', $data['pagarme_10x'] ?? 0),
            '11x' => (float) str_replace([','], '.', $data['pagarme_11x'] ?? 0),
            '12x' => (float) str_replace([','], '.', $data['pagarme_12x'] ?? 0),
            'tx_billet_percent' => (float) str_replace([','], '.', $data['pagarme_tx_billet_percent'] ?? 0),
            'tx_billet_fixed' => (float) str_replace([','], '.', $data['pagarme_tx_billet_fixed'] ?? 0),
        ];

        $stripe = [
            'public_key' => $data['stripe_public_key'] ?? null,
            'secret_key' => $data['stripe_secret_key'] ?? null,
            'tx_card_fixed' => (float) str_replace([','], '.', $data['stripe_tx_card_fixed'] ?? 0),
            'tx_card_percent' => (float) str_replace([','], '.', $data['stripe_tx_card_percent'] ?? 0),
        ];

        $xgate = [
            'email' => $data['xgate_email'] ?? null,
            'taxa_cash_in' => (float) str_replace([','], '.', $data['xgate_taxa_cash_in'] ?? 0),
            'taxa_cash_out' => (float) str_replace([','], '.', $data['xgate_taxa_cash_out'] ?? 0),
        ];
        if (!empty($data['xgate_senha'] ?? null)) {
            $xgate['password'] = $data['xgate_senha'];
        }

        $getpay = [
            'url_base' => $data['getpay_url_base'] ?? 'https://api.getpay.one/api',
            'client_id' => $data['getpay_client_id'] ?? null,
            'client_secret' => $data['getpay_client_secret'] ?? null,
            'webhook_token_deposit' => $data['getpay_webhook_token_deposit'] ?? null,
            'webhook_token_withdraw' => $data['getpay_webhook_token_withdraw'] ?? null,
            'taxa_cash_in' => (float) str_replace([','], '.', $data['getpay_taxa_cash_in'] ?? 0),
            'taxa_cash_out' => (float) str_replace([','], '.', $data['getpay_taxa_cash_out'] ?? 0),
        ];

        $getpay2 = [
            'url_base' => $data['getpay2_url_base'] ?? 'https://api.getpay.one/api',
            'client_id' => $data['getpay2_client_id'] ?? null,
            'client_secret' => $data['getpay2_client_secret'] ?? null,
            'webhook_token_deposit' => $data['getpay2_webhook_token_deposit'] ?? null,
            'webhook_token_withdraw' => $data['getpay2_webhook_token_withdraw'] ?? null,
            'taxa_cash_in' => (float) str_replace([','], '.', $data['getpay2_taxa_cash_in'] ?? 0),
            'taxa_cash_out' => (float) str_replace([','], '.', $data['getpay2_taxa_cash_out'] ?? 0),
        ];

        $rapdyn = [
            'url_base' => $data['rapdyn_url_base'] ?? 'https://app.rapdyn.io/api',
            'api_token' => $data['rapdyn_api_token'] ?? null,
            'webhook_token_deposit' => $data['rapdyn_webhook_token_deposit'] ?? null,
            'webhook_token_withdraw' => $data['rapdyn_webhook_token_withdraw'] ?? null,
            'taxa_cash_in' => (float) str_replace([','], '.', $data['rapdyn_taxa_cash_in'] ?? 0),
            'taxa_cash_out' => (float) str_replace([','], '.', $data['rapdyn_taxa_cash_out'] ?? 0),
        ];

        $cashtimeModel = Cashtime::first();
        if ($cashtimeModel) {
            $cashtimeModel->update($cashtime);
        } else {
            Cashtime::create($cashtime);
        }

        $witetecModel = Witetec::first();
        if ($witetecModel) {
            $witetecModel->update($witetec);
        } else {
            Witetec::create($witetec);
        }

        $pagarmeModel = Pagarme::first();
        if ($pagarmeModel) {
            $pagarmeModel->update($pagarme);
        } else {
            Pagarme::create($pagarme);
        }

        $stripeModel = Stripe::first();
        if ($stripeModel) {
            $stripeModel->update($stripe);
        } else {
            Stripe::create($stripe);
        }
        $xgateModel = XGate::first();
        if ($xgateModel) {
            $xgateModel->update($xgate);
        } else {
            XGate::create($xgate);
        }

        $getpayModel = Getpay::first();
        if ($getpayModel) {
            $getpayModel->update($getpay);
        } else {
            Getpay::create($getpay);
        }

        $getpay2Model = Getpay2::first();
        if ($getpay2Model) {
            $getpay2Model->update($getpay2);
        } else {
            Getpay2::create($getpay2);
        }

        $rapdynModel = Rapdyn::first();
        if ($rapdynModel) {
            $rapdynModel->update($rapdyn);
        } else {
            Rapdyn::create($rapdyn);
        }

        Setting::first()->update([
            'adquirencia_pix' => $data['adquirencia_pix'] ?? null,
            'adquirencia_billet' => $data['adquirencia_billet'] ?? null,
            'adquirencia_card' => $data['adquirencia_card'] ?? null,
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
            'client_id' => $data['efi_client_id'] ?? null,
            'client_secret' => $data['efi_client_secret'] ?? null,
            'chave_pix' => $data['efi_chave_pix'] ?? null,
            'identificador_conta' => $data['efi_identificador_conta'] ?? null,
            'cert' => 'adicionado',
            'taxa_pix_cash_in' => (float) ($data['efi_taxa_pix_cash_in'] ?? 0),
            'taxa_pix_cash_out' => (float) ($data['efi_taxa_pix_cash_out'] ?? 0),
            'billet_tx_fixed' => (float) ($data['efi_billet_tx_fixed'] ?? 0),
            'billet_tx_percent' => (float) ($data['efi_billet_tx_percent'] ?? 0),
            'card_tx_fixed' => (float) ($data['efi_card_tx_fixed'] ?? 0),
            'card_tx_percent' => (float) ($data['efi_card_tx_percent'] ?? 0),
        ];

        $efiModel = Efi::first();
        if ($efiModel) {
            $efiModel->update($efi);
        } else {
            Efi::create($efi);
        }
    }

    public function efiRegistrarWebhook(Request $request)
    {
        EfiTrait::cadastrarWebhook();
        return back()->with('success', 'Webhooks Efí atualizados com sucesso!');
    }
}
