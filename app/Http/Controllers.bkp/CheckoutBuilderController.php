<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Efi;
use App\Models\Produto;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutBuilderController extends Controller
{
    public function index(Request $request, $uuid)
    {
        $checkout = Checkout::where("uuid", $uuid)->first();
        $produto = $checkout->produto;
        $setting  = Setting::first();
        $vendedor = $produto->user->name;
      	$title = $produto->name ?? $setting->software_name;
        return Inertia::render("CheckoutPage", compact("produto", "checkout", "setting", "vendedor", "title"));
    }


    public function update(Request $request, $uuid)
    {
        $data = $request->all();

        /* // Percorrer o layout e salvar imagens base64
        foreach ($data['layout']['rows'] as &$row) {
            foreach ($row['components'] as &$component) {
                if ($component['type'] === 'image') {
                    $src = $component['props']['src'] ?? null;

                    if ($src && str_starts_with($src, 'data:image/')) {
                        // Extrair e decodificar base64
                        if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                            $data = substr($src, strpos($src, ',') + 1);
                            $extension = strtolower($type[1]);

                            if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                                $decoded = base64_decode($data);
                                if ($decoded !== false) {
                                    $filename = Str::uuid() . '.' . $extension;
                                    $path = 'checkout-builder/' . $filename;

                                    Storage::disk('public')->put($path, $decoded);

                                    // Substituir o src por URL pública
                                    $component['props']['src'] = Storage::url($path); // /storage/checkout-builder/xxx.png
                                }
                            }
                        }
                    }
                }
            }
        } */
        Checkout::where('uuid', $uuid)->update($data);
        return response()->json(['status' => 'success', 'message' => 'Checkout alterado com sucesso!']);
    }


    public static function salvarImagemBase64(Request $request, $local)
    {
        $base64 = $request->input('image'); // base64 completa: data:image/png;base64,...

        // Separa o "data:image/png;base64," do conteúdo
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            $data = substr($base64, strpos($base64, ',') + 1);
            $extension = strtolower($type[1]); // png, jpg, etc.

            if (!in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                return response()->json(['error' => 'Tipo de imagem não suportado.'], 400);
            }

            $data = base64_decode($data);

            if ($data === false) {
                return response()->json(['error' => 'Base64 inválido.'], 400);
            }

            $filename = Str::uuid() . '.' . $extension;
            $path = $local . $filename;

            Storage::disk('public')->put($path, $data);

            return response()->json([
                'success' => true,
                'url' => Storage::url($path) // ex: /storage/checkout-builder/uuid.png
            ]);
        } else {
            return response()->json(['error' => 'Base64 mal formatado.'], 400);
        }
    }
}
