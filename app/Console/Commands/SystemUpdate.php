<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class SystemUpdate extends Command
{
    protected $signature = 'system:update';
    protected $description = 'Atualiza automaticamente o sistema a partir do servidor central';

    public function handle()
    {
        $this->info('🔎 Verificando nova versão...');

        // 1. Consulta servidor central
        $response = Http::get('https://pagapix.shop/update/version.json');
        if ($response->failed()) {
            $this->error('❌ Não foi possível verificar versão no servidor.');
            return self::FAILURE;
        }

        $remote = $response->json();
        $remoteVersion = $remote['version'] ?? null;
        $remoteFile = $remote['file'] ?? null;

        if (!$remoteVersion || !$remoteFile) {
            $this->error('❌ Arquivo version.json inválido.');
            return self::FAILURE;
        }

        // 2. Versão local via .env
        $localVersion = config('app.version'); // APP_VERSION no .env

        if (version_compare($remoteVersion, $localVersion, '<=')) {
            $this->info("✅ Já está na versão mais recente ($localVersion).");
            return self::SUCCESS;
        }

        // 3. Baixar ZIP
        $this->info("📥 Baixando atualização $remoteVersion...");
        $zipPath = storage_path("update_$remoteVersion.zip");
        file_put_contents($zipPath, file_get_contents($remoteFile));

        // 4. Extrair
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $extractPath = storage_path("update_temp_$remoteVersion");
            $zip->extractTo($extractPath);
            $zip->close();

            // 5. Copiar pastas sobrescrevendo
            $this->recursiveCopy("$extractPath/app", base_path('app'));
            $this->recursiveCopy("$extractPath/resources", base_path('resources'));

            // 6. Atualizar versão no .env
            $this->setEnvValue('APP_VERSION', $remoteVersion);

            // 7. Rodar migrations (opcional)
            $this->call('migrate', ['--force' => true]);

            $this->info("🚀 Atualizado para versão $remoteVersion!");
        } else {
            $this->error('❌ Erro ao extrair o arquivo ZIP.');
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function recursiveCopy($src, $dst)
    {
        if (!is_dir($src)) return;

        $dir = opendir($src);
        @mkdir($dst, 0755, true);

        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($src . '/' . $file)) {
                    $this->recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    private function setEnvValue($key, $value)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $this->error('Arquivo .env não encontrado!');
            return false;
        }

        $env = file_get_contents($envPath);

        if (preg_match("/^{$key}=.*/m", $env)) {
            $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
        } else {
            $env .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $env);

        return true;
    }
}
