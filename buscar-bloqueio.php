<?php

$locais = [
    // Layouts (PRIORIDADE MÁXIMA)
    'resources/views/layouts/*.blade.php',
    
    // Partials
    'resources/views/partials/*.blade.php',
    'resources/views/includes/*.blade.php',
    
    // Componentes
    'resources/views/components/**/*.blade.php',
    
    // JS público
    'public/js/**/*.js',
    'public/assets/**/*.js',
    
    // JS de recursos
    'resources/js/**/*.js',
    
    // Build (se compilado)
    'public/build/**/*.js',
];

$padroes = [
    'keyCode' => '123',
    'contextmenu' => 'contextmenu',
    'preventDefault' => 'preventDefault',
    'debugger' => 'debugger',
];

echo "🔍 BUSCANDO BLOQUEIOS DE DEVTOOLS\n";
echo str_repeat("=", 50) . "\n\n";

foreach ($locais as $local) {
    $arquivos = glob($local, GLOB_BRACE);
    
    foreach ($arquivos as $arquivo) {
        if (!is_file($arquivo)) continue;
        
        $conteudo = file_get_contents($arquivo);
        $linhas = explode("\n", $conteudo);
        
        foreach ($linhas as $num => $linha) {
            foreach ($padroes as $nome => $padrao) {
                if (stripos($linha, $padrao) !== false) {
                    echo "📌 ENCONTRADO: $arquivo\n";
                    echo "   Tipo: $nome\n";
                    echo "   Linha " . ($num + 1) . ": " . trim($linha) . "\n";
                    echo str_repeat("-", 50) . "\n";
                }
            }
        }
    }
}

echo "\n✅ Busca concluída!\n";