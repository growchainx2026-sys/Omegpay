<?php

namespace App\Console\Commands;

use App\Models\Produto;
use App\Models\ProdutoFileCategoria;
use App\Models\ProdutoFile;
use App\Models\Modulo;
use App\Models\Sessao;
use App\Models\Video;
use Illuminate\Console\Command;

class MigrateAreaMembros extends Command
{
    protected $signature = 'area-membros:migrate {--produto= : ID do produto específico}';
    protected $description = 'Migra dados da área de membros antiga para a nova estrutura';

    public function handle()
    {
        $produtoId = $this->option('produto');
        
        if ($produtoId) {
            $produtos = Produto::where('id', $produtoId)->get();
        } else {
            $produtos = Produto::all();
        }

        $this->info("🔄 Migrando dados da área de membros...");
        $this->newLine();

        foreach ($produtos as $produto) {
            $this->info("📦 Processando produto: {$produto->name} (ID: {$produto->id})");
            
            // Migra categorias (módulos antigos) para módulos novos
            foreach ($produto->categories as $categoria) {
                // Verifica se já existe módulo com esse nome
                $moduloExistente = Modulo::where('produto_id', $produto->id)
                    ->where('nome', $categoria->name)
                    ->first();
                
                if ($moduloExistente) {
                    $this->warn("  ⚠️  Módulo '{$categoria->name}' já existe, pulando...");
                    continue;
                }
                
                $modulo = Modulo::create([
                    'produto_id' => $produto->id,
                    'nome' => $categoria->name,
                    'descricao' => $categoria->description,
                    'ordem' => $categoria->id, // Usa o ID como ordem inicial
                    'status' => true,
                ]);
                
                $this->info("  ✅ Módulo criado: {$modulo->nome}");
                
                // Migra arquivos da categoria para sessões/vídeos
                $files = ProdutoFile::where('categoria_id', $categoria->id)->get();
                
                if ($files->count() > 0) {
                    // Cria uma sessão padrão para os arquivos
                    $sessao = Sessao::create([
                        'modulo_id' => $modulo->id,
                        'nome' => 'Conteúdo',
                        'descricao' => 'Arquivos e links do módulo',
                        'ordem' => 1,
                        'status' => true,
                    ]);
                    
                    foreach ($files as $file) {
                        if ($file->type === 'link' && $this->isYoutubeUrl($file->file)) {
                            // É um vídeo do YouTube
                            Video::create([
                                'sessao_id' => $sessao->id,
                                'titulo' => $file->name,
                                'descricao' => $file->description,
                                'url_youtube' => $file->file,
                                'ordem' => $file->id,
                                'status' => true,
                                'thumbnail' => $file->cover ? '/storage/' . ltrim($file->cover, '/') : null,
                            ]);
                            $this->info("    ✅ Vídeo criado: {$file->name}");
                        } else {
                            // Outros tipos de arquivo podem ser tratados como sessões adicionais
                            // ou mantidos na estrutura antiga
                        }
                    }
                }
            }
        }

        $this->newLine();
        $this->info("✅ Migração concluída!");
        
        return Command::SUCCESS;
    }

    private function isYoutubeUrl($url)
    {
        return preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url);
    }
}
