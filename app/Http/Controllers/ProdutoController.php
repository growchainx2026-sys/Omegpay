<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Coprodutor;
use App\Models\Efi;
use App\Models\Produto;
use App\Models\ProdutoFile;
use App\Models\ProdutoFileCategoria;
use App\Models\Setting;
use App\Models\Stripe;
use App\Models\User;
use App\Notifications\GeralNotification;
use App\Notifications\NovaCoproducaoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $produtos = Produto::where('user_id', auth()->user()->id)->get();
        $setting = Setting::first();
        $valorMinimoProduto = $setting ? floatval($setting->valor_minimo_produto ?? 0) : 0;
        return view('pages.produtos.index', compact('produtos', 'valorMinimoProduto'));
    }


    public function indexAdmin(Request $request)
    {
        if (auth()->user()->permission != 'admin') {
            return redirect()->to('/login');
        }

        $produtos = Produto::get();
        return view('pages.admin.produtos', compact('produtos'));
    }



    public function indexClient(Request $request, $uuid)
    {
        $checkout = Checkout::with(['produto.bumps'])
            ->where("uuid", $uuid)
            ->first();

        $produto = $checkout?->produto ?? null;
        if (!$produto) {
            return abort(404);
        }
        $setting = Setting::first();
        $efi = Efi::first();
        $setting->efi_id_account = $efi?->identificador_conta;
        $setting->efi_card_env = env('EFI_CARD_ENV');
        $setting->efi_billet_env = env('EFI_BILLET_ENV');
        $vendedor = "Vendedor";
        $utmfy = $checkout->user?->utmfy;
        $stripe = Stripe::first();
        $setting->stripe_secret = $stripe?->secret_key;
        $setting->stripe_public = $stripe?->public_key;

        return Inertia::render("ProductPage", compact("produto", "checkout", "setting", "vendedor", "utmfy"));
    }

    public function areaMembros(Request $request, $uuid)
    {
        $produto = Produto::where('uuid', $uuid)->firstOrFail();
        
        // Verifica se o usuário é dono do produto
        if ($produto->user_id !== auth()->user()->id) {
            return redirect()->route('produtos.index')->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        // Carrega relacionamentos necessários
        $produto->load(['modulos.sessoes.videos', 'categories.files']);
        
        // Carrega alunos do produto através dos pedidos
        $alunos = \App\Models\Pedido::where('produto_id', $produto->id)
            ->where('status', 'pago')
            ->with('aluno')
            ->get()
            ->pluck('aluno')
            ->unique('id')
            ->filter();

        // Última mensagem por aluno (para ordenar e notificações)
        $lastMessages = \App\Models\ChatMessage::where('produto_id', $produto->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('aluno_id')
            ->keyBy('aluno_id');
        $lastMessagesByAluno = [];
        foreach ($lastMessages as $alunoId => $msg) {
            $lastMessagesByAluno[$alunoId] = [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'created_at' => $msg->created_at->format('d/m H:i'),
                'ts' => $msg->created_at->timestamp,
            ];
        }
        $alunos = $alunos->sortByDesc(function ($a) use ($lastMessagesByAluno) {
            $m = $lastMessagesByAluno[$a->id] ?? null;
            return $m ? ($m['ts'] ?? 0) : 0;
        })->values();
        
        return view('pages.produtos.area-membros', compact('produto', 'alunos', 'lastMessagesByAluno'));
    }

    /**
     * Prévia da área de membros (como o aluno vê). Abre em nova aba.
     * Usa o primeiro aluno com acesso ao curso para renderizar.
     */
    public function previewAreaMembros(Request $request, $uuid)
    {
        $produto = Produto::where('uuid', $uuid)->where('user_id', auth()->user()->id)->first();
        if (!$produto) {
            return redirect()->route('produtos.index')->with('error', 'Produto não encontrado.');
        }

        $produto->load(['modulosAtivos.sessoesAtivas.videosAtivos', 'categories.files']);

        $pedido = \App\Models\Pedido::where('produto_id', $produto->id)->where('status', 'pago')->with('aluno')->orderBy('created_at')->first();
        if (!$pedido || !$pedido->aluno) {
            return redirect()->route('produtos.area-membros', ['uuid' => $uuid])
                ->with('info', 'Para ver a prévia, é necessário ter pelo menos um aluno com acesso ao curso.')
                ->with('tab', 'customizacao');
        }

        $aluno = $pedido->aluno;
        $progressoGeral = $aluno->progressoProduto($produto->id);

        return view('pages.aluno.produto-novo', compact('produto', 'aluno', 'progressoGeral'));
    }

    public function indexEdit(Request $request, $uuid)
    {
        $produto = Produto::where('uuid', $uuid)->first();
        $produtos = Produto::where('user_id', auth()->user()->id)->get();
        
        // Carrega módulos, sessões e vídeos
        if ($produto) {
            $produto->load(['modulos.sessoes.videos', 'categories.files']);
        }
        
        return view('pages.produtos.edit', compact('produto', 'produtos'));
    }

    public function store(Request $request)
    {
        $dados = $request->all();
        $dados['price'] = floatval($this->normalizeDecimal($dados['price']));

        $setting = Setting::first();
        $minimo = floatval($setting->valor_minimo_produto) ?? 0;

        $validatorInicial = Validator::make(
            $dados,
            [
                'name' => ['required'],
                'price' => ['required', 'numeric', "min:{$minimo}"],
            ],
            [
                'name.required' => 'O campo Nome do produto é obrigatório',
                'price.required' => 'O campo Valor é obrigatório',
                'price.numeric' => 'O campo Valor deve ser numérico',
                'price.min' => "O valor mínimo é de R$ {$minimo}",
            ]
        );

        if ($validatorInicial->fails()) {
            return back()
                ->with('modal', 'true')
                ->withErrors($validatorInicial)
                ->withInput();
        }

        $cop = null;
        $data = $request->except(['_token', 'coproducao', 'coprodutor_email', 'coprodutor_percentage', 'coprodutor_periodo']);
        if (isset($request->coproducao) && $request->coproducao == 'sim') {
            $periodo = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300, 330, 365, 'sempre'];

            if (is_null($request->coprodutor_percentage)) {
                return back()
                    ->with('modal', 'true')
                    ->withErrors(['coprodutor_percentage' => 'Digite um valor entre 0 e 99'])
                    ->withInput();
            }

            $cop = [
                'coprodutor_email' => $request->coprodutor_email,
                'coprodutor_percentage' => (float) $request->coprodutor_percentage,
                'coprodutor_periodo' => $request->coprodutor_periodo,
            ];

            $validator = Validator::make(
                $cop,
                [
                    'coprodutor_email' => ['required', 'email'],
                    'coprodutor_percentage' => ['required', 'integer', 'min:0', 'max:99'],
                    'coprodutor_periodo' => ['required', Rule::in($periodo)],
                ],
                [
                    'coprodutor_email.required' => 'O campo email é obrigatório',
                    'coprodutor_email.email' => 'Digite um email válido',
                    'coprodutor_periodo.in' => 'Selecione um período.',
                    'coprodutor_periodo.required' => 'O campo período é obrigatório.',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->with('modal', 'true')
                    ->with('coproducao', 'true')
                    ->withErrors($validator)
                    ->withInput();
            }

            $cop = [
                'email' => $request->coprodutor_email,
                'percentage' => (float) $request->coprodutor_percentage,
                'periodo' => $request->coprodutor_periodo,
            ];
            $user = User::where('email', $cop['email'])->first();
            if (!$user) {
                return back()
                    ->with('modal', 'true')
                    ->with('coproducao', 'true')
                    ->withErrors(['coprodutor_email' => 'Esse usuário não existe. Verifique.'])
                    ->withInput();
            }
        }

        $data['price'] = $this->normalizeDecimal($data['price']);
        if (isset($data['affiliate_percentage'])) {
            $data['affiliate_percentage'] = (float) str_replace(',', '.', $data['affiliate_percentage']);
        }
        $data['user_id'] = auth()->user()->id;
        //dd($data);
        $produto = Produto::create($data);
        if (!is_null($cop)) {
            $user = User::where('email', $cop['email'])->first();
            $cop['user_id'] = $user->id;
            $cop['produto_id'] = $produto->id;
            $coproducao = Coprodutor::create($cop);

            $mensagem = $produto->name_exibition ?? explode(' ', $produto->user->name)[0] . "convidou você para coproduçao do produto: " . $produto->name;
            $user->notify(new GeralNotification(
                "Convite de Coprodução",
                $mensagem,
                "/coproducoes"
            ));
        }
        return back()->with('success', 'Produto adcionado com sucesso!');
    }

    public function edit(Request $request, $id)
    {
        // Remove campos que não pertencem ao produto
        $data = $request->except([
            '_token',
            '_method',
            'produto_id',
            'valor_de',
            'valor_por',
            'call_to_action',
            'product_name',
            'product_description',
            'checkout_name',
            'checkout_produto_id',
            'categoria_id',
            'file_type',
            'link',
            'id',
            'category_name',
            'category_description',
            'file_name',
            'file_description',
            // Campos de módulo que não pertencem ao produto
            'nome',
            'descricao',
            'icone',
            'capa',
            'ordem',
            'modulo_id',
            'sessao_id',
            'titulo',
            'url_youtube',
            'duracao',
            'thumbnail'
        ]);
        
        // Garante que apenas campos válidos do produto sejam atualizados
        $produto = Produto::findOrFail($id);
        $fillable = $produto->getFillable();
        
        // Filtra apenas campos que estão no fillable do Produto
        $dataFiltered = [];
        foreach ($fillable as $field) {
            if (isset($data[$field])) {
                $dataFiltered[$field] = $data[$field];
            }
        }
        
        // Adiciona campos especiais que não estão no fillable mas são válidos
        if (isset($data['type'])) {
            $dataFiltered['type'] = "unique";
        }
        if (isset($data['price'])) {
            $dataFiltered['price'] = $this->normalizeDecimal($data['price']);
        }
        $dataFiltered['area_member_shop_show'] = $request->has('area_member_shop_show');
        $dataFiltered['area_member_white_mode'] = $request->boolean('area_member_white_mode');
        
        // Remove gamificação se não estiver no fillable
        if (isset($dataFiltered['area_member_gamification_enabled'])) {
            unset($dataFiltered['area_member_gamification_enabled']);
        }
        
        $data = $dataFiltered;

        // Banner recortado (base64 do modal de crop)
        if ($request->filled('area_member_banner_cropped')) {
            $base64 = $request->input('area_member_banner_cropped');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64));
                if ($imageData !== false) {
                    $imageName = 'banner-' . uniqid() . '.' . $extension;
                    $path = 'produtos/' . $imageName;
                    \Storage::disk('public')->put($path, $imageData);
                    $data['area_member_banner'] = '/' . $path;
                }
            }
        }

        // Banner mobile recortado (base64 do modal de crop)
        if ($request->filled('area_member_banner_mobile_cropped')) {
            $base64 = $request->input('area_member_banner_mobile_cropped');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64));
                if ($imageData !== false) {
                    $imageName = 'banner-mobile-' . uniqid() . '.' . $extension;
                    $path = 'produtos/' . $imageName;
                    \Storage::disk('public')->put($path, $imageData);
                    $data['area_member_banner_mobile'] = '/' . $path;
                }
            }
        }

        $imageFields = ['image', 'area_member_banner', 'area_member_banner_mobile', 'area_member_course_background'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $image = $request->file($field);
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('produtos', $imageName, 'public');
                $data[$field] = '/' . $imagePath;
            }
        }
        // unset($data['checkout_name']);
        // dd(compact('id','data'));

        //if($data[''])
        $produto->update($data);
        return redirect()->back()->with('success', 'Produto alterado com sucesso!');
    }

    public function delete(Request $request, $id)
    {
        Produto::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Produto excluído com sucesso!');
    }

    public function addFile(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        if ($data['link']) {
            //dd($data);
            $data['file'] = $data['link'];
            unset($data['link'], $data['categoria_id'], $data['file_type']);
            ProdutoFile::create($data);
            return back()->with('success', "Arquivo adcionado com sucesso!");
        }

        $data = $request->except(['_token', '_method', 'file', 'cover']);

        // dd($data);
        $file = ProdutoFile::create($data);

        $imageFields = ['file', 'cover'];

        $arquivo = '';
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $image) {
                    if ($image && $image->isValid()) {
                        // Mantém o nome original
                        $imageName = $image->getClientOriginalName();

                        // Salva
                        $produto_id = $data['produto_id'];
                        $file_id = $file->id;
                        $imagePath = $image->storeAs("produtos/{$produto_id}/arquivos", $imageName, 'public');

                        $arquivo = '/' . $imagePath; // array pois são múltiplos
                    }
                }
            }
        }

        $file->update(['file' => $arquivo]);
        return back()->with('success', "Arquivo adcionado com sucesso!");
    }

    public function editFile(Request $request)
    {
        $data = $request->except(['_token', '_method', 'id']);

        $data['name'] = $data['file_name'];
        $data['description'] = $data['file_description'];

        unset($data['file_name'], $data['file_description']);

        if ($data['type'] == "link") {
            $data['file'] = $data['link'];
            unset($data['link']);
            ProdutoFile::where('id', $request->id)->update($data);
            return back()->with('success', "Arquivo alterado com sucesso!");
        }

        $data = $request->except(['_token', '_method', 'file', 'id', 'cover']);

        $file = ProdutoFile::where('id', $request->id)->first();
        $file->update($data);
        // dd($request->file('file')[0]);
        if ($request->hasFile('file')) {
            $image = $request->file('file')[0]; // UploadedFile único
            $name = $image->getClientOriginalName();
            $produto_id = $data['produto_id'];
            $imagePath = $image->storeAs("produtos/{$produto_id}/arquivos", $name, 'public');
            $file->update(['file' => "/" . $imagePath]);
        }

        if ($request->hasFile('cover')) {
            $image = $request->file('cover'); // UploadedFile único
            $name = $image->getClientOriginalName();
            $produto_id = $data['produto_id'];
            $imagePath = $image->storeAs("produtos/{$produto_id}/arquivos", $name, 'public');
            $file->update(['cover' => "/" . $imagePath]);
        }

        return back()->with('success', "Arquivo adcionado com sucesso!");
    }

    public function delFile(Request $request)
    {
        $id = $request->input('id');

        $file = ProdutoFile::find($id);
        $file->delete();

        return back()->with('success', 'Arquivo excluído com sucesso!');
    }

    public function addCategory(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        ProdutoFileCategoria::create($data);
        return back()->with('success', "Categoria adcionada com sucesso!");
    }

    public function editCategory(Request $request)
    {
        $data = $request->except(['_token', '_method', 'id']);
        $data['name'] = $data['category_name'];
        $data['description'] = $data['category_description'];

        unset($data['category_name'], $data['category_description']);
        ProdutoFileCategoria::where('id', $request->id)->first()->update($data);
        return back()->with('success', "Categoria alterada com sucesso!");
    }

    public function delCategory(Request $request)
    {
        $id = $request->input('id');

        $file = ProdutoFileCategoria::find($id);
        $file->delete();

        return back()->with('success', 'Categoria excluída com sucesso!');
    }

    public function memberConfig(Request $request)
    {
        $data = $request->except(['_token', '_method', 'area_member_background_image']);

        $user = auth()->user();
        $user->update($data);

        if ($request->hasFile('area_member_background_image')) {
            $image = $request->file('area_member_background_image');

            $name = uniqid('member_area_') . ".png";
            $imagePath = $image->storeAs("member-area", $name, 'public');
            $path = "/" . $imagePath;
            $user->update(['area_member_background_image' => $path]);
        }

        return back()->with('success', 'Configurações salvas com sucesso.');
    }

    protected function normalizeDecimal(string $valor): string
    {
        $valor = trim($valor);

        // Se contém vírgula, ela é o separador decimal
        if (str_contains($valor, ',')) {
            $partes = explode(',', $valor);
            $inteiro = preg_replace('/[^0-9]/', '', $partes[0]); // remove pontos e lixo
            $decimal = isset($partes[1]) ? preg_replace('/[^0-9]/', '', $partes[1]) : '00';
            $valor = $inteiro . '.' . $decimal;
        } else {
            // Só ponto ou só números → separador decimal é ponto
            $partes = explode('.', $valor);
            if (count($partes) > 1) {
                $decimal = array_pop($partes);
                $inteiro = preg_replace('/[^0-9]/', '', implode('', $partes));
                $valor = $inteiro . '.' . $decimal;
            } else {
                $valor = preg_replace('/[^0-9]/', '', $valor) . '.00';
            }
        }

        return number_format((float) $valor, 2, '.', '');
    }

    public function indexClientSimple($uuid)
{
        $checkout = Checkout::with(['produto.bumps'])
            ->where("uuid", $uuid)
            ->first();

        $produto = $checkout?->produto ?? null;
        if (!$produto) {
            return abort(404);
        }
        $setting = Setting::first();
        $efi = Efi::first();
        $setting->efi_id_account = $efi?->identificador_conta;
        $setting->efi_card_env = env('EFI_CARD_ENV');
        $setting->efi_billet_env = env('EFI_BILLET_ENV');
        $vendedor = "Vendedor";
        $utmfy = $checkout->user?->utmfy;
        $stripe = Stripe::first();
        $setting->stripe_secret = $stripe?->secret_key;
        $setting->stripe_public = $stripe?->public_key;

        return Inertia::render("ProductPageSimple", compact("produto", "checkout", "setting", "vendedor", "utmfy"));
    }

}
