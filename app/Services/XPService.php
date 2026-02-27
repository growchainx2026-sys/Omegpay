<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AlunoXP;
use App\Models\ProgressoAluno;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

/**
 * Serviço de XP e Level
 * 
 * Configurações de XP (editar apenas se for programador):
 * - XP por aula assistida: 10
 * - XP por módulo completo: 100
 * - XP por curso completo: 500
 * - Level máximo: 100
 */
class XPService
{
    // ⚠️ CONFIGURAÇÕES - APENAS PROGRAMADOR PODE EDITAR
    const XP_POR_AULA = 10;
    const XP_POR_MODULO = 100;
    const XP_POR_CURSO = 500;
    const LEVEL_MAXIMO = 100;
    const XP_BASE_LEVEL = 100; // XP necessário para level 2
    const MULTIPLICADOR_XP = 1.5; // Multiplicador por level
    
    /**
     * Adiciona XP ao aluno
     */
    public static function adicionarXP(Aluno $aluno, int $xp, string $tipo = 'aula'): array
    {
        $alunoXP = AlunoXP::firstOrCreate(
            ['aluno_id' => $aluno->id],
            [
                'xp_total' => 0,
                'level' => 1,
                'xp_proximo_level' => self::XP_BASE_LEVEL,
            ]
        );

        $levelAnterior = $alunoXP->level;
        $alunoXP->xp_total += $xp;
        
        // Calcula novo level
        $novoLevel = self::calcularLevel($alunoXP->xp_total);
        $alunoXP->level = min($novoLevel, self::LEVEL_MAXIMO);
        $alunoXP->xp_proximo_level = self::calcularXPProximoLevel($alunoXP->level);
        $alunoXP->save();

        $subiuLevel = $novoLevel > $levelAnterior && $novoLevel <= self::LEVEL_MAXIMO;

        return [
            'xp_total' => $alunoXP->xp_total,
            'level' => $alunoXP->level,
            'xp_proximo_level' => $alunoXP->xp_proximo_level,
            'xp_atual_level' => self::calcularXPAtualLevel($alunoXP->xp_total, $alunoXP->level),
            'subiu_level' => $subiuLevel,
            'level_anterior' => $levelAnterior,
        ];
    }

    /**
     * Calcula o level baseado no XP total
     */
    private static function calcularLevel(int $xpTotal): int
    {
        if ($xpTotal < self::XP_BASE_LEVEL) {
            return 1;
        }

        $level = 1;
        $xpNecessario = self::XP_BASE_LEVEL;
        $xpAcumulado = 0;

        while ($xpAcumulado + $xpNecessario <= $xpTotal && $level < self::LEVEL_MAXIMO) {
            $xpAcumulado += $xpNecessario;
            $level++;
            $xpNecessario = (int) ($xpNecessario * self::MULTIPLICADOR_XP);
        }

        return min($level, self::LEVEL_MAXIMO);
    }

    /**
     * Calcula XP necessário para o próximo level
     */
    private static function calcularXPProximoLevel(int $level): int
    {
        if ($level >= self::LEVEL_MAXIMO) {
            return 0; // Level máximo atingido
        }

        $xpBase = self::XP_BASE_LEVEL;
        for ($i = 2; $i <= $level; $i++) {
            $xpBase = (int) ($xpBase * self::MULTIPLICADOR_XP);
        }

        return $xpBase;
    }

    /**
     * Calcula XP atual do level (para barra de progresso)
     */
    private static function calcularXPAtualLevel(int $xpTotal, int $level): int
    {
        if ($level >= self::LEVEL_MAXIMO) {
            return 0;
        }

        $xpNecessarioAnterior = 0;
        $xpBase = self::XP_BASE_LEVEL;
        
        for ($i = 2; $i < $level; $i++) {
            $xpNecessarioAnterior += $xpBase;
            $xpBase = (int) ($xpBase * self::MULTIPLICADOR_XP);
        }

        return $xpTotal - $xpNecessarioAnterior;
    }

    /**
     * Verifica e adiciona XP quando um vídeo é concluído
     */
    public static function verificarXPVideo(Aluno $aluno, Video $video): ?array
    {
        $progresso = ProgressoAluno::where('aluno_id', $aluno->id)
            ->where('video_id', $video->id)
            ->where('concluido', true)
            ->first();

        if (!$progresso) {
            return null;
        }

        // Verifica se já ganhou XP por este vídeo
        $xpGanho = \DB::table('aluno_xp_logs')
            ->where('aluno_id', $aluno->id)
            ->where('tipo', 'aula')
            ->where('referencia_id', $video->id)
            ->exists();

        if ($xpGanho) {
            return null; // Já ganhou XP por este vídeo
        }

        // Adiciona XP
        $resultado = self::adicionarXP($aluno, self::XP_POR_AULA, 'aula');

        // Registra no log
        \DB::table('aluno_xp_logs')->insert([
            'aluno_id' => $aluno->id,
            'xp_ganho' => self::XP_POR_AULA,
            'tipo' => 'aula',
            'referencia_id' => $video->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $resultado;
    }

    /**
     * Verifica e adiciona XP quando um módulo é completo
     */
    public static function verificarXPModulo(Aluno $aluno, int $moduloId, int $produtoId): ?array
    {
        // Verifica se já ganhou XP por este módulo
        $xpGanho = \DB::table('aluno_xp_logs')
            ->where('aluno_id', $aluno->id)
            ->where('tipo', 'modulo')
            ->where('referencia_id', $moduloId)
            ->exists();

        if ($xpGanho) {
            return null;
        }

        // Adiciona XP
        $resultado = self::adicionarXP($aluno, self::XP_POR_MODULO, 'modulo');

        // Registra no log
        \DB::table('aluno_xp_logs')->insert([
            'aluno_id' => $aluno->id,
            'xp_ganho' => self::XP_POR_MODULO,
            'tipo' => 'modulo',
            'referencia_id' => $moduloId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $resultado;
    }

    /**
     * Verifica e adiciona XP quando um curso é completo
     */
    public static function verificarXPCurso(Aluno $aluno, int $produtoId): ?array
    {
        // Verifica se já ganhou XP por este curso
        $xpGanho = \DB::table('aluno_xp_logs')
            ->where('aluno_id', $aluno->id)
            ->where('tipo', 'curso')
            ->where('referencia_id', $produtoId)
            ->exists();

        if ($xpGanho) {
            return null;
        }

        // Adiciona XP
        $resultado = self::adicionarXP($aluno, self::XP_POR_CURSO, 'curso');

        // Registra no log
        \DB::table('aluno_xp_logs')->insert([
            'aluno_id' => $aluno->id,
            'xp_ganho' => self::XP_POR_CURSO,
            'tipo' => 'curso',
            'referencia_id' => $produtoId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $resultado;
    }

    /**
     * Retorna dados de XP do aluno
     */
    public static function getXPAluno(Aluno $aluno): array
    {
        $alunoXP = AlunoXP::firstOrCreate(
            ['aluno_id' => $aluno->id],
            [
                'xp_total' => 0,
                'level' => 1,
                'xp_proximo_level' => self::XP_BASE_LEVEL,
            ]
        );

        $xpAtualLevel = self::calcularXPAtualLevel($alunoXP->xp_total, $alunoXP->level);
        $xpNecessario = $alunoXP->xp_proximo_level;
        $porcentagem = $xpNecessario > 0 ? ($xpAtualLevel / $xpNecessario) * 100 : 100;

        return [
            'xp_total' => $alunoXP->xp_total,
            'level' => $alunoXP->level,
            'xp_atual_level' => $xpAtualLevel,
            'xp_proximo_level' => $xpNecessario,
            'porcentagem' => min(100, round($porcentagem, 2)),
            'level_maximo' => self::LEVEL_MAXIMO,
            'nivelou' => $alunoXP->level >= self::LEVEL_MAXIMO,
        ];
    }
}
