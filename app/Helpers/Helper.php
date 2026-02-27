<?php

namespace App\Helpers;

use App\Models\{Setting, User, TransactionIn, TransactionOut, Gamefication, Pwa, Fcm, Fmcdevice};

class Helper
{
    public static function settings()
    {
        return Setting::first();
    }

    /** URL da logo (customizada ou padrão em assets/images) */
    public static function logoUrl(?string $path = null): string
    {
        if (!empty($path)) {
            return asset('storage/' . ltrim($path, '/'));
        }
        return asset('assets/images/logo_light.png');
    }

    /** URL do favicon (customizado ou padrão em assets/images) */
    public static function faviconUrl(?string $path = null): string
    {
        if (!empty($path)) {
            return asset('storage/' . ltrim($path, '/'));
        }
        return asset('assets/images/favicon_light.png');
    }

    /** URL do background do login (customizado ou padrão) */
    public static function loginBackgroundUrl(?string $path = null): string
    {
        if (!empty($path)) {
            $url = asset('storage/' . ltrim($path, '/'));
            $fullPath = storage_path('app/public/' . ltrim($path, '/'));
            if (file_exists($fullPath)) {
                $url .= '?v=' . filemtime($fullPath);
            }
            return $url;
        }
        $defaultPath = public_path('assets/images/bg.webp');
        $url = asset('assets/images/bg.webp');
        if (file_exists($defaultPath)) {
            $url .= '?v=' . filemtime($defaultPath);
        }
        return $url;
    }

    public static function calculaSaldoLiquido($user_id)
    {
        $gamefications = Gamefication::orderBy('min')->get(); // garantir ordem crescente por min
        $nivelSelecionado = null;
        $proxNivelId = null;

        try {
            // Soma dos depósitos líquidos com status "pago"
            $totalDepositoLiquido = TransactionIn::where('user_id', $user_id)
                ->where('status', 'pago')
                ->sum('cash_in_liquido');

            $totalSaldoALiberar = TransactionIn::where('user_id', $user_id)
                ->where('status', 'revisao')
                ->sum('cash_in_liquido');

            // Soma dos taxas reservas com status "pago"
            $totalTaxaReserva = TransactionIn::where('user_id', $user_id)
                ->where('status', 'pago')
                ->where('taxa_reserva_resgatada', false)
                ->sum('taxa_reserva');

            $reservaPaga = TransactionIn::where('user_id', $user_id)
                ->where('status', 'pago')
                ->where('taxa_reserva_resgatada', true)
                ->sum('taxa_reserva');

            // Soma dos saques aprovados com status "pago"
            $totalSaquesAprovados = TransactionOut::where('user_id', $user_id)
                ->where('status', 'pago')
                ->sum('amount');

            // Total de saldo bloqueado
           $totalSaldoBloqueado = TransactionOut::where('user_id', $user_id)
          ->where('status', 'pendente')
          ->whereIn('descricao_transacao', ['WEB', 'LIBERADOADMIN'])
          ->sum('amount');

            // Saldo líquido
            $saldoLiquido = (float) $totalDepositoLiquido - (float) $totalSaquesAprovados - (float) $totalSaldoBloqueado;

            // Determinar nível atual e próximo nível
            foreach ($gamefications as $nivel) {
                if ($totalDepositoLiquido >= $nivel->min && $totalDepositoLiquido <= $nivel->max) {
                    $nivelSelecionado = $nivel->id;
                } elseif ($totalDepositoLiquido < $nivel->min && $proxNivelId === null) {
                    $proxNivelId = $nivel->id;
                }
            }

            // Atualizar usuário
            $updated = User::where('id', $user_id)->update([
                'saldo' => $saldoLiquido + $reservaPaga,
                'saldo_a_liberar' => $totalSaldoALiberar,
                'saldo_reserva' => $totalTaxaReserva,
                'valor_saque_pendente' => $totalSaldoBloqueado,
                'nivel' => $nivelSelecionado,
                'prox_nivel' => $proxNivelId
            ]);

            
            return $updated ? true : false;
        } catch (\Exception $e) {
            return false;
        }

    }

    public static function calcularSaldoLiquidoUsuarios()
    {
        $users = User::get();
        foreach ($users as $user) {
            
            self::calculaSaldoLiquido($user->id);
        }
        
    }

    public static function incrementAmount(User $user, $valor, $campo)
    {
        $usuario = $user->toArray();
        $novovalor = $usuario[$campo] + (float)$valor;
        $user->update([$campo => $novovalor]);
        $user->save();
    }

    public static function decrementAmount(User $user, $valor, $campo)
    {
        $usuario = $user->toArray();
        $novovalor = $usuario[$campo] - (float)$valor;
        $user->update([$campo => $novovalor]);
        $user->save();
    }

    public static function generateValidCpf($pontuado = false)
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);

        // Calcula o primeiro dígito verificador
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        // Calcula o segundo dígito verificador
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        if ($pontuado) {
            return sprintf(
                '%d%d%d.%d%d%d.%d%d%d-%d%d',
                $n1,
                $n2,
                $n3,
                $n4,
                $n5,
                $n6,
                $n7,
                $n8,
                $n9,
                $d1,
                $d2
            );
        } else {
            return sprintf(
                '%d%d%d%d%d%d%d%d%d%d%d',
                $n1,
                $n2,
                $n3,
                $n4,
                $n5,
                $n6,
                $n7,
                $n8,
                $n9,
                $d1,
                $d2
            );
        }
    }


    public static function verifyPixType($pixkey)
    {
        // Verificar se é CPF (11 dígitos numéricos)
        if (preg_match('/^\d{11}$/', $pixkey)) {
            // Verificar se é um CPF válido
            if (self::validarCPF($pixkey)) {
                return 'cpf';
            }
            
            // Caso contrário, pode ser um telefone
            return 'phone';
        }

        // Verificar se é CNPJ (14 dígitos numéricos)
        if (preg_match('/^\d{14}$/', $pixkey)) {
            return 'cnpj';
        }

        // Verificar se é email (formato padrão de email)
        if (filter_var($pixkey, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // Verificar se é chave aleatória (random) - normalmente começa com '000' ou um formato específico
        if (preg_match('/^[a-zA-Z0-9]{36}$/', $pixkey)) {
            return 'random';
        }
    
        return 'invalid';
    }

    public static function validarCPF($cpf) {
        // Remover caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);
    
        // Verificar se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }
    
        // Verificar se todos os números são iguais (exemplo: 111.111.111.11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        // Validar o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;
        if ($cpf[9] != $digito1) {
            return false;
        }
    
        // Validar o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;
        if ($cpf[10] != $digito2) {
            return false;
        }
    
        return true;
    }

    public static function getAddressForIP(string $ip): ?array
    {
        try {
            // Serviço gratuito sem chave de API
            $url = "http://ip-api.com/json/{$ip}?fields=status,message,country,regionName,city,zip,lat,lon,query";
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'success') {
                return [
                    'ip'      => $data['query'],
                    'country' => $data['country'],
                    'state'   => $data['regionName'],
                    'city'    => $data['city'],
                    'postcode'     => $data['zip'],
                    'lat'     => $data['lat'],
                    'lon'     => $data['lon'],
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function formatarTelefoneBR(string $numero): string
{
    // 1) Só dígitos
    $n = preg_replace('/\D+/', '', $numero);

    // 2) Remove código do país (55) se vier
    if (strpos($n, '55') === 0 && strlen($n) > 11) {
        $n = substr($n, 2);
    }

    // 3) Trata 0800
    if (strpos($n, '0800') === 0 && strlen($n) >= 11) {
        return preg_replace('/^(0800)(\d{3})(\d{4}).*$/', '$1-$2-$3', $n);
    }

    // 4) Formata telefones comuns
    if (strlen($n) >= 11) { // celular (9 dígitos) com DDD
        return preg_replace('/^(\d{2})(\d{5})(\d{4}).*$/', '($1) $2-$3', $n);
    }
    if (strlen($n) === 10) { // fixo (8 dígitos) com DDD
        return preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $n);
    }

    // Fallback: se só tiver 8 ou 9 dígitos sem DDD
    if (strlen($n) === 9) {
        return preg_replace('/^(\d{5})(\d{4})$/', '$1-$2', $n);
    }
    if (strlen($n) === 8) {
        return preg_replace('/^(\d{4})(\d{4})$/', '$1-$2', $n);
    }

    return $numero; // não reconhecido, retorna como veio
}

    public static function bankNameByCode($isbp)
    {
        $banks = [
            [ "isbp" => "0", "code" => "001", "name" => "Banco do Brasil S.A." ],
            [ "isbp" => 208, "code" =>	"070", "name" => "BRB – BANCO DE BRASILIA S.A." ],
            [ "isbp" => 38121, "code" =>	"n/a", "name" => "Banco Central do Brasil – Selic" ],
            [ "isbp" => 38166, "code" =>	"n/a", "name" => "Banco Central do Brasil" ],
            [ "isbp" => 250699, "code" =>	"272", "name" => "AGK CORRETORA DE CAMBIO S.A." ],
            [ "isbp" => 315557, "code" =>	"136", "name" => "CONFEDERAÇÃO NACIONAL DAS COOPERATIVAS CENTRAIS UNICRED LTDA. – UNICRED DO BRASIL"],
            [ "isbp" => 360305, "code" =>	"104", "name" => "CAIXA ECONOMICA FEDERAL"],
            [ "isbp" => 394460, "code" =>	"n/a", "name" => "Secretaria do Tesouro Nacional – STN"],
            [ "isbp" => 416968, "code" =>	"077", "name" => "Banco Inter S.A."],
            [ "isbp" => 517645, "code" =>	"741", "name" => "BANCO RIBEIRAO PRETO S.A."],
            [ "isbp" => 556603, "code" =>	"330", "name" => "BANCO BARI DE INVESTIMENTOS E FINANCIAMENTOS S.A."],
            [ "isbp" => 558456, "code" =>	"739", "name" => "Banco Cetelem S.A."],
            [ "isbp" => 795423, "code" =>	"743", "name" => "Banco Semear S.A."],
            [ "isbp" => 806535, "code" =>	"100", "name" => "Planner Corretora de Valores S.A."],
            [ "isbp" => 997185, "code" =>	"096", "name" => "Banco B3 S.A."],
            [ "isbp" => 1023570, "code" =>	"747", "name" => "Banco Rabobank International Brasil S.A."],
            [ "isbp" => 1027058, "code" =>	"362", "name" => "CIELO S.A."],
            [ "isbp" => 1073966, "code" =>	"322", "name" => "Cooperativa de Crédito Rural de Abelardo Luz – Sulcredi/Crediluz"],
            [ "isbp" => 1181521, "code" =>	"748", "name" => "BANCO COOPERATIVO SICREDI S.A."],
            [ "isbp" => 1330387, "code" =>	"350", "name" => "COOPERATIVA DE CRÉDITO RURAL DE PEQUENOS AGRICULTORES E DA REFORMA AGRÁRIA DO CE"],
            [ "isbp" => 1522368, "code" =>	"752", "name" => "Banco BNP Paribas Brasil S.A."],
            [ "isbp" => 1634601, "code" =>	"091", "name" => "CENTRAL DE COOPERATIVAS DE ECONOMIA E CRÉDITO MÚTUO DO ESTADO DO RIO GRANDE DO S"],
            [ "isbp" => 1658426, "code" =>	"379", "name" => "COOPERFORTE – COOPERATIVA DE ECONOMIA E CRÉDITO MÚTUO DOS FUNCIONÁRIOS DE "],
            [ "isbp" => 1701201, "code" =>	"399", "name" => "Kirton Bank S.A. – Banco Múltiplo"],
            [ "isbp" => 1800019, "code" =>	"108", "name" => "PORTOCRED S.A. – CREDITO, FINANCIAMENTO E INVESTIMENTO"],
            [ "isbp" => 1852137, "code" =>	"378", "name" => "BBC LEASING S.A. – ARRENDAMENTO MERCANTIL"],
            [ "isbp" => 2038232, "code" =>	"756", "name" => "BANCO COOPERATIVO DO BRASIL S.A. – BANCOOB"],
            [ "isbp" => 2276653, "code" =>	"360", "name" => "TRINUS CAPITAL DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS S.A."],
            [ "isbp" => 2318507, "code" =>	"757", "name" => "BANCO KEB HANA DO BRASIL S.A."],
            [ "isbp" => 2332886, "code" =>	"102", "name" => "XP INVESTIMENTOS CORRETORA DE CÂMBIO,TÍTULOS E VALORES MOBILIÁRIOS S/A"],
            [ "isbp" => 2398976, "code" =>	"084", "name" => "UNIPRIME NORTE DO PARANÁ – COOPERATIVA DE CRÉDITO LTDA"],
            [ "isbp" => 2685483, "code" =>	"180", "name" => "CM CAPITAL MARKETS CORRETORA DE CÂMBIO, TÍTULOS E VALORES MOBILIÁRIOS LTDA"],
            [ "isbp" => 2801938, "code" =>	"066", "name" => "BANCO MORGAN STANLEY S.A."],
            [ "isbp" => 2819125, "code" =>	"015", "name" => "UBS Brasil Corretora de Câmbio, Títulos e Valores Mobiliários S.A."],
            [ "isbp" => 2992317, "code" =>	"143", "name" => "Treviso Corretora de Câmbio S.A."],
            [ "isbp" => 2992335, "code" =>	"n/a", "name" => "Câmara Interbancária de Pagamentos – CIP – LDL"],
            [ "isbp" => 3012230, "code" =>	"062", "name" => "Hipercard Banco Múltiplo S.A."],
            [ "isbp" => 3017677, "code" =>	"074", "name" => "Banco J. Safra S.A."],
            [ "isbp" => 3046391, "code" =>	"099", "name" => "UNIPRIME CENTRAL – CENTRAL INTERESTADUAL DE COOPERATIVAS DE CREDITO LTDA."],
            [ "isbp" => 3215790, "code" =>	"387", "name" => "Banco Toyota do Brasil S.A."],
            [ "isbp" => 3311443, "code" =>	"326", "name" => "PARATI – CREDITO, FINANCIAMENTO E INVESTIMENTO S.A."],
            [ "isbp" => 3323840, "code" =>	"025", "name" => "Banco Alfa S.A."],
            [ "isbp" => 3502968, "code" =>	"315", "name" => "PI Distribuidora de Títulos e Valores Mobiliários S.A."],
            [ "isbp" => 3532415, "code" =>	"075", "name" => "Banco ABN Amro S.A."],
            [ "isbp" => 3609817, "code" =>	"040", "name" => "Banco Cargill S.A."],
            [ "isbp" => 3751794, "code" =>	"307", "name" => "Terra Investimentos Distribuidora de Títulos e Valores Mobiliários Ltda."],
            [ "isbp" => 3973814, "code" =>	"190", "name" => "SERVICOOP – COOPERATIVA DE CRÉDITO DOS SERVIDORES PÚBLICOS ESTADUAIS DO RIO GRAN"],
            [ "isbp" => 4062902, "code" =>	"296", "name" => "VISION S.A. CORRETORA DE CAMBIO"],
            [ "isbp" => 4184779, "code" =>	"063", "name" => "Banco Bradescard S.A."],
            [ "isbp" => 4257795, "code" =>	"191", "name" => "Nova Futura Corretora de Títulos e Valores Mobiliários Ltda."],
            [ "isbp" => 4307598, "code" =>	"382", "name" => "FIDÚCIA SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO PORTE L"],
            [ "isbp" => 4332281, "code" =>	"064", "name" => "GOLDMAN SACHS DO BRASIL BANCO MULTIPLO S.A."],
            [ "isbp" => 4391007, "code" =>	"n/a", "name" => "Câmara Interbancária de Pagamentos"],
            [ "isbp" => 4632856, "code" =>	"097", "name" => "Credisis – Central de Cooperativas de Crédito Ltda."],
            [ "isbp" => 4715685, "code" =>	"016", "name" => "COOPERATIVA DE CRÉDITO MÚTUO DOS DESPACHANTES DE TRÂNSITO DE SANTA CATARINA E RI"],
            [ "isbp" => 4814563, "code" =>	"299", "name" => "SOROCRED   CRÉDITO, FINANCIAMENTO E INVESTIMENTO S.A."],
            [ "isbp" => 4866275, "code" =>	"012", "name" => "Banco Inbursa S.A."],
            [ "isbp" => 4902979, "code" =>	"003", "name" => "BANCO DA AMAZONIA S.A."],
            [ "isbp" => 4913129, "code" =>	"060", "name" => "Confidence Corretora de Câmbio S.A."],
            [ "isbp" => 4913711, "code" =>	"037", "name" => "Banco do Estado do Pará S.A."],
            [ "isbp" => 5351887, "code" =>	"359", "name" => "ZEMA CRÉDITO, FINANCIAMENTO E INVESTIMENTO S/A"],
            [ "isbp" => 5442029, "code" =>	"159", "name" => "Casa do Crédito S.A. Sociedade de Crédito ao Microempreendedor"],
            [ "isbp" => 5463212, "code" =>	"085", "name" => "Cooperativa Central de Crédito – "],
            [ "isbp" => 5790149, "code" =>	"114", "name" => "Central Cooperativa de Crédito no Estado do Espírito Santo – CECOOP"],
            [ "isbp" => 6271464, "code" =>	"036", "name" => "Banco Bradesco BBI S.A."],
            [ "isbp" => 7207996, "code" =>	"394", "name" => "Banco Bradesco Financiamentos S.A."],
            [ "isbp" => 7237373, "code" =>	"004", "name" => "Banco do Nordeste do Brasil S.A."],
            [ "isbp" => 7450604, "code" =>	"320", "name" => "China Construction Bank (Brasil) Banco Múltiplo S/A"],
            [ "isbp" => 7512441, "code" =>	"189", "name" => "HS FINANCEIRA S/A CREDITO, FINANCIAMENTO E INVESTIMENTOS"],
            [ "isbp" => 7652226, "code" =>	"105", "name" => "Lecca Crédito, Financiamento e Investimento S/"],
            [ "isbp" => 7656500, "code" =>	"076", "name" => "Banco KDB do Brasil S.A."],
            [ "isbp" => 7679404, "code" =>	"082", "name" => "BANCO TOPÁZIO S.A."],
            [ "isbp" => 7853842, "code" =>	"286", "name" => "COOPERATIVA DE CRÉDITO RURAL DE OURO   SULCREDI/OURO"],
            [ "isbp" => 7945233, "code" =>	"093", "name" => "PÓLOCRED   SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO PORT"],
            [ "isbp" => 8240446, "code" =>	"391", "name" => "COOPERATIVA DE CREDITO RURAL DE IBIAM – SULCREDI/IBIAM"],
            [ "isbp" => 8253539, "code" =>	"273", "name" => "Cooperativa de Crédito Rural de São Miguel do Oeste – Sulcredi/São "],
            [ "isbp" => 8357240, "code" =>	"368", "name" => "Banco CSF S.A."],
            [ "isbp" => 8561701, "code" =>	"290", "name" => "Pagseguro Internet S.A."],
            [ "isbp" => 8609934, "code" =>	"259", "name" => "MONEYCORP BANCO DE CÂMBIO S.A."],
            [ "isbp" => 9089356, "code" =>	"364", "name" => "GERENCIANET S.A."],
            [ "isbp" => 9105360, "code" =>	"157", "name" => "ICAP do Brasil Corretora de Títulos e Valores Mobiliários Ltda."],
            [ "isbp" => 9210106, "code" =>	"183", "name" => "SOCRED S.A. – SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO "],
            [ "isbp" => 9274232, "code" =>	"014", "name" => "STATE STREET BRASIL S.A. ? BANCO COMERCIAL"],
            [ "isbp" => 9313766, "code" =>	"130", "name" => "CARUANA S.A. – SOCIEDADE DE CRÉDITO, FINANCIAMENTO E INVESTIMENTO"],
            [ "isbp" => 9512542, "code" =>	"127", "name" => "Codepe Corretora de Valores e Câmbio S.A."],
            [ "isbp" => 9516419, "code" =>	"079", "name" => "Banco Original do Agronegócio S.A."],
            [ "isbp" => 9554480, "code" =>	"340", "name" => "Super Pagamentos e Administração de Meios Eletrônicos S.A."],
            [ "isbp" => 10264663, "code" =>	"081", "name" => "BancoSeguro S.A."],
            [ "isbp" => 10398952, "code" =>	"133", "name" => "CONFEDERAÇÃO NACIONAL DAS COOPERATIVAS CENTRAIS DE CRÉDITO E ECONOMIA FAMILIAR "],
            [ "isbp" => 10573521, "code" =>	"323", "name" => "MERCADOPAGO.COM REPRESENTACOES LTDA."],
            [ "isbp" => 10664513, "code" =>	"121", "name" => "Banco Agibank S.A."],
            [ "isbp" => 10690848, "code" =>	"083", "name" => "Banco da China Brasil S.A."],
            [ "isbp" => 10853017, "code" =>	"138", "name" => "Get Money Corretora de Câmbio S.A."],
            [ "isbp" => 10866788, "code" =>	"024", "name" => "Banco Bandepe S.A."],
            [ "isbp" => 11165756, "code" =>	"384", "name" => "GLOBAL FINANÇAS SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO"],
            [ "isbp" => 11476673, "code" =>	"088", "name" => "BANCO RANDON S.A."],
            [ "isbp" => 11495073, "code" =>	"319", "name" => "OM DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA"],
            [ "isbp" => 11581339, "code" =>	"274", "name" => "MONEY PLUS SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E A EMPRESA DE PEQUENO "],
            [ "isbp" => 11703662, "code" =>	"095", "name" => "Travelex Banco de Câmbio S.A."],
            [ "isbp" => 11758741, "code" =>	"094", "name" => "Banco Finaxis S.A."],
            [ "isbp" => 11970623, "code" =>	"276", "name" => "Senff S.A. – Crédito, Financiamento e Investimento"],
            [ "isbp" => 12865507, "code" =>	"092", "name" => "BRK S.A. Crédito, Financiamento e Investimento"],
            [ "isbp" => 13009717, "code" =>	"047", "name" => "Banco do Estado de Sergipe S.A."],
            [ "isbp" => 13059145, "code" =>	"144", "name" => "BEXS BANCO DE CÂMBIO S/A"],
            [ "isbp" => 13140088, "code" =>	"332", "name" => "Acesso Soluções de Pagamento S.A."],
            [ "isbp" => 13220493, "code" =>	"126", "name" => "BR Partners Banco de Investimento S.A."],
            [ "isbp" => 13293225, "code" =>	"325", "name" => "Órama Distribuidora de Títulos e Valores Mobiliários S.A."],
            [ "isbp" => 13370835, "code" =>	"301", "name" => "BPP Instituição de Pagamento S.A."],
            [ "isbp" => 13486793, "code" =>	"173", "name" => "BRL Trust Distribuidora de Títulos e Valores Mobiliários S.A."],
            [ "isbp" => 13673855, "code" =>	"331", "name" => "Fram Capital Distribuidora de Títulos e Valores Mobiliários S.A."],
            [ "isbp" => 13720915, "code" =>	"119", "name" => "Banco Western Union do Brasil S.A."],
            [ "isbp" => 13884775, "code" =>	"396", "name" => "HUB PAGAMENTOS S.A"],
            [ "isbp" => 14190547, "code" =>	"309", "name" => "CAMBIONET CORRETORA DE CÂMBIO LTDA."],
            [ "isbp" => 14388334, "code" =>	"254", "name" => "PARANÁ BANCO S.A."],
            [ "isbp" => 14511781, "code" =>	"268", "name" => "BARI COMPANHIA HIPOTECÁRIA"],
            [ "isbp" => 15114366, "code" =>	"107", "name" => "Banco Bocom BBM S.A."],
            [ "isbp" => 15173776, "code" =>	"412", "name" => "BANCO CAPITAL S.A."],
            [ "isbp" => 15357060, "code" =>	"124", "name" => "Banco Woori Bank do Brasil S.A."],
            [ "isbp" => 15581638, "code" =>	"149", "name" => "Facta Financeira S.A. – Crédito Financiamento e Investimento"],
            [ "isbp" => 16501555, "code" =>	"197", "name" => "Stone Pagamentos S.A."],
            [ "isbp" => 16927221, "code" =>	"313", "name" => "AMAZÔNIA CORRETORA DE CÂMBIO LTDA."],
            [ "isbp" => 16944141, "code" =>	"142", "name" => "Broker Brasil Corretora de Câmbio Ltda."],
            [ "isbp" => 17184037, "code" =>	"389", "name" => "Banco Mercantil do Brasil S.A."],
            [ "isbp" => 17298092, "code" =>	"184", "name" => "Banco Itaú BBA S.A."],
            [ "isbp" => 17351180, "code" =>	"634", "name" => "BANCO TRIANGULO S.A."],
            [ "isbp" => 17352220, "code" =>	"545", "name" => "SENSO CORRETORA DE CAMBIO E VALORES MOBILIARIOS S.A"],
            [ "isbp" => 17453575, "code" =>	"132", "name" => "ICBC do Brasil Banco Múltiplo S.A."],
            [ "isbp" => 17772370, "code" =>	"298", "name" => "Vip’s Corretora de Câmbio Ltda."],
            [ "isbp" => 17826860, "code" =>	"377", "name" => "MS SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO PORTE "],
            [ "isbp" => 18188384, "code" =>	"321", "name" => "CREFAZ SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E A EMPRESA DE PEQUENO PORTE LT"],
            [ "isbp" => 18236120, "code" =>	"260", "name" => "Nu Pagamentos S.A."],
            [ "isbp" => 18520834, "code" =>	"129", "name" => "UBS Brasil Banco de Investimento S.A."],
            [ "isbp" => 19307785, "code" =>	"128", "name" => "MS Bank S.A. Banco de Câmbio"],
            [ "isbp" => 20155248, "code" =>	"194", "name" => "PARMETAL DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA"],
            [ "isbp" => 21018182, "code" =>	"383", "name" => "BOLETOBANCÁRIO.COM TECNOLOGIA DE PAGAMENTOS LTDA."],
            [ "isbp" => 21332862, "code" =>	"324", "name" => "CARTOS SOCIEDADE DE CRÉDITO DIRETO S.A."],
            [ "isbp" => 22610500, "code" =>	"310", "name" => "VORTX DISTRIBUIDORA DE TITULOS E VALORES MOBILIARIOS LTDA."],
            [ "isbp" => 22896431, "code" =>	"380", "name" => "PICPAY SERVICOS S.A."],
            [ "isbp" => 23522214, "code" =>	"163", "name" => "Commerzbank Brasil S.A. – Banco Múltiplo"],
            [ "isbp" => 23862762, "code" =>	"280", "name" => "Avista S.A. Crédito, Financiamento e Investimento"],
            [ "isbp" => 24074692, "code" =>	"146", "name" => "GUITTA CORRETORA DE CAMBIO LTDA."],
            [ "isbp" => 24537861, "code" =>	"343", "name" => "FFA SOCIEDADE DE CRÉDITO AO MICROEMPREENDEDOR E À EMPRESA DE PEQUENO PORTE LTDA."],
            [ "isbp" => 26563270, "code" =>	"279", "name" => "COOPERATIVA DE CREDITO RURAL DE PRIMAVERA DO LESTE"],
            [ "isbp" => 27098060, "code" =>	"335", "name" => "Banco Digio S.A."],
            [ "isbp" => 27214112, "code" =>	"349", "name" => "AL5 S.A. CRÉDITO, FINANCIAMENTO E INVESTIMENTO"],
            [ "isbp" => 27351731, "code" =>	"374", "name" => "REALIZE CRÉDITO, FINANCIAMENTO E INVESTIMENTO S.A."],
            [ "isbp" => 27652684, "code" =>	"278", "name" => "Genial Investimentos Corretora de Valores Mobiliários S.A."],
            [ "isbp" => 27842177, "code" =>	"271", "name" => "IB Corretora de Câmbio, Títulos e Valores Mobiliários S.A."],
            [ "isbp" => 28127603, "code" =>	"021", "name" => "BANESTES S.A. BANCO DO ESTADO DO ESPIRITO "],
            [ "isbp" => 28195667, "code" =>	"246", "name" => "Banco ABC Brasil S.A."],
            [ "isbp" => 28650236, "code" =>	"292", "name" => "BS2 Distribuidora de Títulos e Valores Mobiliários S.A."],
            [ "isbp" => 28719664, "code" =>	"n", "name" => "a	B3 SA – Brasil, Bolsa, Balcão – Segmento Cetip UTVM"],
            [ "isbp" => 29011780, "code" =>	"n", "name" => "a	Câmara Interbancária de Pagamentos – CIP C3"],
            [ "isbp" => 29030467, "code" =>	"751", "name" => "Scotiabank Brasil S.A. Banco Múltiplo"],
            [ "isbp" => 29162769, "code" =>	"352", "name" => "TORO CORRETORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA"],
            [ "isbp" => 30306294, "code" =>	"208", "name" => "Banco BTG Pactual S.A."],
            [ "isbp" => 30723886, "code" =>	"746", "name" => "Banco Modal S.A."],
            [ "isbp" => 31597552, "code" =>	"241", "name" => "BANCO CLASSICO S.A."],
            [ "isbp" => 31872495, "code" =>	"336", "name" => "Banco C6 S.A."],
            [ "isbp" => 31880826, "code" =>	"612", "name" => "Banco Guanabara S.A."],
            [ "isbp" => 31895683, "code" =>	"604", "name" => "Banco Industrial do Brasil S.A."],
            [ "isbp" => 32062580, "code" =>	"505", "name" => "Banco Credit Suisse (Brasil) S.A."],
            [ "isbp" => 32402502, "code" =>	"329", "name" => "QI Sociedade de Crédito Direto S.A."],
            [ "isbp" => 32648370, "code" =>	"196", "name" => "FAIR CORRETORA DE CAMBIO S.A."],
            [ "isbp" => 32997490, "code" =>	"342", "name" => "Creditas Sociedade de Crédito Direto S.A."],
            [ "isbp" => 33042151, "code" =>	"300", "name" => "Banco de la Nacion Argentina"],
            [ "isbp" => 33042953, "code" =>	"477", "name" => "Citibank N.A."],
            [ "isbp" => 33132044, "code" =>	"266", "name" => "BANCO CEDULA S.A."],
            [ "isbp" => 33147315, "code" =>	"122", "name" => "Banco Bradesco BERJ S.A."],
            [ "isbp" => 33172537, "code" =>	"376", "name" => "BANCO J.P. MORGAN S.A."],
            [ "isbp" => 33264668, "code" =>	"348", "name" => "Banco XP S.A."],
            [ "isbp" => 33466988, "code" =>	"473", "name" => "Banco Caixa Geral – Brasil S.A."],
            [ "isbp" => 33479023, "code" =>	"745", "name" => "Banco Citibank S.A."],
            [ "isbp" => 33603457, "code" =>	"120", "name" => "BANCO RODOBENS S.A."],
            [ "isbp" => 33644196, "code" =>	"265", "name" => "Banco Fator S.A."],
            [ "isbp" => 33657248, "code" =>	"007", "name" => "BANCO NACIONAL DE DESENVOLVIMENTO ECONOMICO E SOCIAL"],
            [ "isbp" => 33775974, "code" =>	"188", "name" => "ATIVA INVESTIMENTOS S.A. CORRETORA DE TÍTULOS, CÂMBIO E VALORES"],
            [ "isbp" => 33862244, "code" =>	"134", "name" => "BGC LIQUIDEZ DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA"],
            [ "isbp" => 33885724, "code" =>	"029", "name" => "Banco Itaú Consignado S.A."],
            [ "isbp" => 33923798, "code" =>	"243", "name" => "Banco Máxima S.A."],
            [ "isbp" => 34088029, "code" =>	"397", "name" => "LISTO SOCIEDADE DE CREDITO DIRETO S.A."],
            [ "isbp" => 34111187, "code" =>	"078", "name" => "Haitong Banco de Investimento do Brasil S.A."],
            [ "isbp" => 34335592, "code" =>	"355", "name" => "ÓTIMO SOCIEDADE DE CRÉDITO DIRETO S.A."],
            [ "isbp" => 34711571, "code" =>	"367", "name" => "VITREO DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS S.A."],
            [ "isbp" => 35977097, "code" =>	"373", "name" => "UP.P SOCIEDADE DE EMPRÉSTIMO ENTRE PESSOAS S.A."],
            [ "isbp" => 36113876, "code" =>	"111", "name" => "OLIVEIRA TRUST DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIARIOS S.A."],
            [ "isbp" => 36586946, "code" =>	"408", "name" => "BÔNUSCRED SOCIEDADE DE CRÉDITO DIRETO S.A."],
            [ "isbp" => 37241230, "code" =>	"404", "name" => "SUMUP SOCIEDADE DE CRÉDITO DIRETO S.A."],
            [ "isbp" => 37880206, "code" =>	"403", "name" => "CORA SOCIEDADE DE CRÉDITO DIRETO S.A."],
            [ "isbp" => 40303299, "code" =>	"306", "name" => "PORTOPAR DISTRIBUIDORA DE TITULOS E VALORES MOBILIARIOS LTDA."],
            [ "isbp" => 42272526, "code" =>	"017", "name" => "BNY Mellon Banco S.A."],
            [ "isbp" => 43180355, "code" =>	"174", "name" => "PEFISA S.A. – CRÉDITO, FINANCIAMENTO E INVESTIMENTO"],
            [ "isbp" => 44189447, "code" =>	"495", "name" => "Banco de La Provincia de Buenos Aires"],
            [ "isbp" => 45246410, "code" =>	"125", "name" => "Plural S.A. Banco Múltiplo"],
            [ "isbp" => 46518205, "code" =>	"488", "name" => "JPMorgan Chase Bank, National Association"],
            [ "isbp" => 48795256, "code" =>	"065", "name" => "Banco AndBank (Brasil) S.A."],
            [ "isbp" => 49336860, "code" =>	"492", "name" => "ING Bank N.V."],
            [ "isbp" => 50579044, "code" =>	"145", "name" => "LEVYCAM – CORRETORA DE CAMBIO E VALORES LTDA."],
            [ "isbp" => 50585090, "code" =>	"250", "name" => "BCV – BANCO DE CRÉDITO E VAREJO S.A."],
            [ "isbp" => 52904364, "code" =>	"354", "name" => "NECTON INVESTIMENTOS  S.A. CORRETORA DE VALORES MOBILIÁRIOS E COMMODITIES"],
            [ "isbp" => 52937216, "code" =>	"253", "name" => "Bexs Corretora de Câmbio S/A"],
            [ "isbp" => 53518684, "code" =>	"269", "name" => "BANCO HSBC S.A."],
            [ "isbp" => 54403563, "code" =>	"213", "name" => "Banco Arbi S.A."],
            [ "isbp" => 54641030, "code" =>	"n", "name" => "a	BMF Bovespa S.A. – Bolsa de Valores, Mercadorias e Futuros – Camara BMFBOVESPA"],
            [ "isbp" => 55230916, "code" =>	"139", "name" => "Intesa Sanpaolo Brasil S.A. – Banco Múltiplo"],
            [ "isbp" => 57839805, "code" =>	"018", "name" => "Banco Tricury S.A."],
            [ "isbp" => 58160789, "code" =>	"422", "name" => "Banco Safra S.A."],
            [ "isbp" => 58497702, "code" =>	"630", "name" => "Banco Smartbank S.A."],
            [ "isbp" => 58616418, "code" =>	"224", "name" => "Banco Fibra S.A."],
            [ "isbp" => 59109165, "code" =>	"393", "name" => "Banco Volkswagen S.A."],
            [ "isbp" => 59118133, "code" =>	"600", "name" => "Banco Luso Brasileiro S.A."],
            [ "isbp" => 59274605, "code" =>	"390", "name" => "BANCO GM S.A."],
            [ "isbp" => 59285411, "code" =>	"623", "name" => "Banco Pan S.A."],
            [ "isbp" => 59588111, "code" =>	"655", "name" => "Banco Votorantim S.A."],
            [ "isbp" => 60394079, "code" =>	"479", "name" => "Banco ItauBank S.A."],
            [ "isbp" => 60498557, "code" =>	"456", "name" => "Banco MUFG Brasil S.A."],
            [ "isbp" => 60518222, "code" =>	"464", "name" => "Banco Sumitomo Mitsui Brasileiro S.A."],
            [ "isbp" => 60701190, "code" =>	"341", "name" => "ITAÚ UNIBANCO S.A."],
            [ "isbp" => 60746948, "code" =>	"237", "name" => "Banco Bradesco S.A."],
            [ "isbp" => 60814191, "code" =>	"381", "name" => "BANCO MERCEDES-BENZ DO BRASIL S.A."],
            [ "isbp" => 60850229, "code" =>	"613", "name" => "Omni Banco S.A."],
            [ "isbp" => 60872504, "code" =>	"652", "name" => "Itaú Unibanco Holding S.A."],
            [ "isbp" => 60889128, "code" =>	"637", "name" => "BANCO SOFISA S.A."],
            [ "isbp" => 60934221, "code" =>	"n", "name" => "a	BMF Bovespa S/A – Bolsa de Valores, Mercadorias e Futuros – Camara Cambio"],
            [ "isbp" => 61024352, "code" =>	"653", "name" => "BANCO INDUSVAL S.A."],
            [ "isbp" => 61033106, "code" =>	"069", "name" => "Banco Crefisa S.A."],
            [ "isbp" => 61088183, "code" =>	"370", "name" => "Banco Mizuho do Brasil S.A."],
            [ "isbp" => 61182408, "code" =>	"249", "name" => "Banco Investcred Unibanco S.A."],
            [ "isbp" => 61186680, "code" =>	"318", "name" => "Banco BMG S.A."],
            [ "isbp" => 61348538, "code" =>	"626", "name" => "BANCO C6 CONSIGNADO S.A."],
            [ "isbp" => 61444949, "code" =>	"270", "name" => "Sagitur Corretora de Câmbio Ltda."],
            [ "isbp" => 61533584, "code" =>	"366", "name" => "BANCO SOCIETE GENERALE BRASIL S.A."],
            [ "isbp" => 61723847, "code" =>	"113", "name" => "Magliano S.A. Corretora de Cambio e Valores Mobiliarios"],
            [ "isbp" => 61747085, "code" =>	"131", "name" => "TULLETT PREBON BRASIL CORRETORA DE VALORES E CÂMBIO LTDA"],
            [ "isbp" => 61809182, "code" =>	"011", "name" => "CREDIT SUISSE HEDGING-GRIFFO CORRETORA DE VALORES S.A"],
            [ "isbp" => 61820817, "code" =>	"611", "name" => "Banco Paulista S.A."],
            [ "isbp" => 62073200, "code" =>	"755", "name" => "Bank of America Merrill Lynch Banco Múltiplo S.A."],
            [ "isbp" => 62109566, "code" =>	"089", "name" => "CREDISAN COOPERATIVA DE CRÉDITO"],
            [ "isbp" => 62144175, "code" =>	"643", "name" => "Banco Pine S.A."],
            [ "isbp" => 62169875, "code" =>	"140", "name" => "Easynvest – Título Corretora de Valores SA"],
            [ "isbp" => 62232889, "code" =>	"707", "name" => "Banco Daycoval S.A."],
            [ "isbp" => 62237649, "code" =>	"288", "name" => "CAROL DISTRIBUIDORA DE TITULOS E VALORES MOBILIARIOS LTDA."],
            [ "isbp" => 62285390, "code" =>	"363", "name" => "SOCOPA SOCIEDADE CORRETORA PAULISTA S.A."],
            [ "isbp" => 62287735, "code" =>	"101", "name" => "RENASCENCA DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA"],
            [ "isbp" => 62331228, "code" =>	"487", "name" => "DEUTSCHE BANK S.A. – BANCO ALEMAO"],
            [ "isbp" => 62421979, "code" =>	"233", "name" => "Banco Cifra S.A."],
            [ "isbp" => 65913436, "code" =>	"177", "name" => "Guide Investimentos S.A. Corretora de "],
            [ "isbp" => 68757681, "code" =>	"365", "name" => "SOLIDUS S.A. CORRETORA DE CAMBIO E VALORES MOBILIARIOS"],
            [ "isbp" => 68900810, "code" =>	"633", "name" => "Banco Rendimento S.A."],
            [ "isbp" => 71027866, "code" =>	"218", "name" => "Banco BS2 S.A."],
            [ "isbp" => 71371686, "code" =>	"169", "name" => "BANCO OLÉ CONSIGNADO S.A."],
            [ "isbp" => 71590442, "code" =>	"293", "name" => "Lastro RDV Distribuidora de Títulos e Valores Mobiliários Ltda."],
            [ "isbp" => 71677850, "code" =>	"285", "name" => "Frente Corretora de Câmbio Ltda."],
            [ "isbp" => 73622748, "code" =>	"080", "name" => "B&T CORRETORA DE CAMBIO LTDA."],
            [ "isbp" => 74828799, "code" =>	"753", "name" => "Novo Banco Continental S.A. – Banco Múltiplo"],
            [ "isbp" => 75647891, "code" =>	"222", "name" => "BANCO CRÉDIT AGRICOLE BRASIL S.A."],
            [ "isbp" => 76461557, "code" =>	"281", "name" => "Cooperativa de Crédito Rural Coopavel"],
            [ "isbp" => 76543115, "code" =>	"754", "name" => "Banco Sistema S.A."],
            [ "isbp" => 78157146, "code" =>	"098", "name" => "Credialiança Cooperativa de Crédito Rural"],
            [ "isbp" => 78626983, "code" =>	"610", "name" => "Banco VR S.A."],
            [ "isbp" => 78632767, "code" =>	"712", "name" => "Banco Ourinvest S.A."],
            [ "isbp" => 81723108, "code" =>	"010", "name" => "CREDICOAMO CREDITO RURAL COOPERATIVA"],
            [ "isbp" => 89960090, "code" =>	"283", "name" => "RB CAPITAL INVESTIMENTOS DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LIMITADA"],
            [ "isbp" => 90400888, "code" =>	"033", "name" => "BANCO SANTANDER (BRASIL) S.A."],
            [ "isbp" => 91884981, "code" =>	"217", "name" => "Banco John Deere S.A."],
            [ "isbp" => 92702067, "code" =>	"041", "name" => "Banco do Estado do Rio Grande do Sul S.A."],
            [ "isbp" => 92856905, "code" =>	"117", "name" => "ADVANCED CORRETORA DE CÂMBIO LTDA"],
            [ "isbp" => 92874270, "code" =>	"654", "name" => "BANCO DIGIMAIS S.A."],
            [ "isbp" => 92875780, "code" =>	"371", "name" => "WARREN CORRETORA DE VALORES MOBILIÁRIOS E CÂMBIO LTDA."],
            [ "isbp" => 92894922, "code" =>	"212", "name" => "Banco Original S.A."],
            [ "isbp" => 94968518, "code" =>	"289", "name" => "DECYSEO CORRETORA DE CAMBIO LTDA."],
            [ "isbp" => 13935893, "code" =>	"509", "name" => "CELCOIN IP S.A."]
        ];

        return $banks[$isbp]['name'] ?? 'Banco não encontrado';
    }

    public static function gerarPwa()
    {
        $setting = Setting::first();
        $softwareName = $setting?->software_name ?? config('app.name', 'Omegpay');
        $themeColor = $setting?->software_color ?? '#0d6efd';
        $faviconPath = $setting?->favicon_light ?? 'icon_azul.png';

        $data = [
            'name' => $softwareName,
            'short_name' => $softwareName,
            'start_url' => env('APP_URL').'/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => $themeColor,
            'icon_192' => url('/storage/'.$faviconPath),
            'icon_512' => url('/storage/'.$faviconPath)
        ];

        $pwa = Pwa::first();
        if ($pwa) {
            $pwa->update($data);
        }

        $manifest = [
            'name' => $softwareName,
            'short_name' => $softwareName,
            'start_url' => env('APP_URL').'/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => $themeColor,
            'orientation' => 'portrait',
            'icons' => [
                [
                    "src" => url('/storage/'.$faviconPath),
                    "sizes" => "192x192",
                    "type" => "image/png"
                ],
                [
                    "src" => url('/storage/'.$faviconPath),
                    "sizes" => "512x512",
                    "type" => "image/png"
                ]
            ]
        ];
        return $manifest;

    }

    public static function getFCM()
    {
        $fcm = Fcm::first();
        return $fcm ? $fcm->except(['id', 'created_at', 'updated_at']) : [];
    }

    public static function myTokensDevices($user_id)
    {
        return Fmcdevice::where('user_id', $user_id)->get()->pluck('device');
    }

    public static function formatK($value)
    {
        if ($value >= 1000000 && fmod($value, 1000000) === 0.0) {
            return 'R$ ' . number_format($value / 1000000, 0, ',', '.') . 'M';
        } elseif ($value >= 1000 && fmod($value, 1000) === 0.0) {
            return 'R$ ' . number_format($value / 1000, 0, ',', '.') . 'k';
        } else {
            return 'R$ ' . number_format($value, 2, ',', '.');
        }
    }
}
