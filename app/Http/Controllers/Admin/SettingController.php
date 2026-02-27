<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fcm;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Storage;

class SettingController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        if (!$user || !in_array($user->permission, ['admin', 'dev'], true)) {
            return redirect()->route('dashboard');
        }
        $settings = Setting::first();
        $fcm = Fcm::first() ?? new Fcm();
        return view('pages.admin.settings', compact('settings', 'fcm'));
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $type = $request->input('type');

        $data = $request->only([
            'software_name',
            'software_description',
            'software_color',
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
            'taxa_fixa',
            'baseline',
            'adquirente_default',
            'phone_support',
            'software_color_background',
            'software_color_sidebar',
            'software_color_text',
            'rev',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password'
        ]);

        if ($request->has('card_days_to_release')) {
            $data['card_days_to_release'] = $request->input('card_days_to_release');
        }

        if ($request->has('card_days_to_anticipation_opt1')) {
            $data['card_days_to_anticipation_opt1'] = $request->input('card_days_to_anticipation_opt1');
        }

        if ($request->has('card_tx_to_anticipation_opt1')) {
            $data['card_tx_to_anticipation_opt1'] = $request->input('card_tx_to_anticipation_opt1');
        }

        if ($request->has('card_days_to_anticipation_opt2')) {
            $data['card_days_to_anticipation_opt2'] = $request->input('card_days_to_anticipation_opt2');
        }

        if ($request->has('card_tx_to_anticipation_opt2')) {
            $data['card_tx_to_anticipation_opt2'] = $request->input('card_tx_to_anticipation_opt2');
        }

        if ($request->has('billet_taxa_percent')) {
            $data['billet_taxa_percent'] = $request->input('billet_taxa_percent');
        }

        if ($request->has('billet_taxa_fixed')) {
            $data['billet_taxa_fixed'] = $request->input('billet_taxa_fixed');
        }
        
        if ($request->has('billet_days_to_release')) {
            $data['billet_days_to_release'] = $request->input('billet_days_to_release');
        }

        if ($request->has('dias_liberar_reserva')) {
            $data['dias_liberar_reserva'] = $request->input('dias_liberar_reserva');
        }

        if ($request->has('taxa_reserva')) {
            $data['taxa_reserva'] = $request->input('taxa_reserva');
        }

        if($request->has('valor_minimo_produto')){
            $data['valor_minimo_produto'] = floatval($request->input('valor_minimo_produto'));
        }

        $allowedRestore = ['logo_light', 'logo_dark', 'favicon_light', 'login_background'];
        $restoreDefault = $request->input('restore_default');
        if ($restoreDefault) {
            $fields = is_array($restoreDefault) ? $restoreDefault : [$restoreDefault];
            foreach ($fields as $field) {
                if (in_array($field, $allowedRestore) && (Schema::hasColumn('settings', $field) || in_array($field, ['logo_light', 'logo_dark', 'favicon_light']))) {
                    $data[$field] = '';
                }
            }
        }

        $imageFields = ['logo_light', 'logo_dark', 'favicon_light', 'favicon_dark', 'image_home'];
        if (Schema::hasColumn('settings', 'login_background')) {
            $imageFields[] = 'login_background';
        }

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Faz o upload da nova imagem com um nome único
                $image = $request->file($field);
                $imageName = $field . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('images', $imageName, 'public');

                $data[$field] = '/' . $imagePath;
            }
        }

        // Só envia para update colunas que existem na tabela settings (evita erro se migration não foi rodada)
        $data = collect($data)->filter(function ($value, $key) {
            return Schema::hasColumn('settings', $key);
        })->all();

        Setting::first()->update($data);

        if ($type == 'aplicar') {
            $taxas = [
                'taxa_cash_in' => (float) $request->input('taxa_cash_in'),
                'taxa_cash_out' => (float) $request->input('taxa_cash_out'),
                'taxa_cash_in_fixa' => (float) $request->input('taxa_cash_in_fixa'),
                'taxa_cash_out_fixa' => (float) $request->input('taxa_cash_out_fixa'),
                'taxa_reserva' => (float) $request->input('taxa_reserva'),
                'deposito_minimo' => (float) $request->input('deposito_minimo'),
                'deposito_maximo' => (float) $request->input('deposito_maximo'),
                'saque_minimo' => (float) $request->input('saque_minimo'),
                'saque_maximo' => (float) $request->input('saque_maximo'),
                'saques_dia' => (float) $request->input('saques_dia'),
            ];

            foreach (User::cursor() as $user) {
                $user->update($taxas);
            }
        }

        if ($request->has('fcm_apiKey')) {
            $fcm = [
                "apiKey" => $request->input('fcm_apiKey'),
                "authDomain" => $request->input('fcm_authDomain'),
                "projectId" => $request->input('fcm_projectId'),
                "storageBucket" => $request->input('fcm_storageBucket'),
                "messagingSenderId" => $request->input('fcm_messagingSenderId'),
                "appId" => $request->input('fcm_appId'),
                "measurementId" => $request->input('fcm_measurementId'),
                "title" => $request->input('fcm_title'),
                "body" => $request->input('fcm_body'),
                "firebase_config" => 'firebase-service-account.json',
            ];

            //dd($fcm);

            if ($request->hasFile('fcm_firebase_config')) {
                $firebaseconfig = $request->file('fcm_firebase_config')[0];
                $fileName = 'firebase-service-account.json';
                Storage::disk('certificados')->put($fileName, file_get_contents($firebaseconfig));
            }

            $fcmModel = Fcm::firstOrNew([]);
            $fcmModel->fill($fcm);
            $fcmModel->save();
        }
        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }

    /**
 * Exibe o formulário de edição da senha master admin
 */
public function editPassAdmin($id)
{
    // Verificar se o usuário tem permissão de admin
    if (auth()->user()->permission !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Acesso negado!');
    }

    return view('pages.admin.pass_admin.edit', [
        'userId' => $id
    ]);
}

/**
 * Atualiza a senha master admin
 */
public function updatePassAdmin(Request $request, $id)
{
    // Verificar se o usuário tem permissão de admin
    if (auth()->user()->permission !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Acesso negado!');
    }

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ], [
        'current_password.required' => 'A senha atual é obrigatória',
        'new_password.required' => 'A nova senha é obrigatória',
        'new_password.min' => 'A nova senha deve ter no mínimo 8 caracteres',
        'new_password.confirmed' => 'As senhas não coincidem',
    ]);

    $user = User::findOrFail($id);

    // Verifica se a senha atual está correta
    // Se pass_admin já usa Hash, use Hash::check
    // Se pass_admin é texto puro, use comparação direta
    if ($user->pass_admin !== $request->current_password) {
        return back()->with('error', 'Senha atual incorreta!')->withInput();
    }

    // Atualiza a senha master (armazenando como texto puro)
    $user->pass_admin = $request->new_password;
    $user->save();

    return back()->with('success', 'Senha master atualizada com sucesso!');
}
}
