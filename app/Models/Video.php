<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';
    
    protected $fillable = [
        'sessao_id',
        'titulo',
        'descricao',
        'url_youtube',
        'duracao',
        'ordem',
        'status',
        'thumbnail',
    ];

    public function sessao()
    {
        return $this->belongsTo(Sessao::class);
    }

    public function progressos()
    {
        return $this->hasMany(ProgressoAluno::class);
    }

    /**
     * Extrai o ID do vídeo do YouTube da URL
     */
    public function getYoutubeIdAttribute()
    {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->url_youtube, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Gera URL embed do YouTube
     */
    public function getEmbedUrlAttribute()
    {
        $id = $this->youtube_id;
        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }
}
