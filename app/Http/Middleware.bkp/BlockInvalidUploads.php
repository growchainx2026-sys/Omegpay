<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class BlockInvalidUploads
{
    protected array $allowedMimes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'application/json',
        'video/mp4',
        'application/pdf', 'text/plain',
        'application/zip',
    ];

    protected array $allowedExtensions = [
        'p12', 'pem', 'json'
    ];

    protected array $blockedExtensions = [
        'php', 'html', 'htm', 'js', 'py', 'xhtml', 'cmd', 'sh', 'bat', 'exe', 'vbs',
        'ws', 'scr', 'bin', 'cab', 'cda', 'cdf', 'cdr', 'cfm', 'cgi', 'tar', 'tar.gz',
        'gz', 'csh', 'ksh', 'out', 'ps1', 'reg', 'run'
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $files = $request->allFiles();

        foreach ($files as $file) {
            $this->validateFileOrArray($file, $request);
        }

        return $next($request);
    }

    /**
     * Valida recursivamente um arquivo ou um array de arquivos.
     */
    protected function validateFileOrArray($file, Request $request): void
    {
        if (is_array($file)) {
            // Se for array, percorre recursivamente
            foreach ($file as $innerFile) {
                $this->validateFileOrArray($innerFile, $request);
            }
            return;
        }

        if (!$file->isValid()) {
            abort(back()->with('error', 'Arquivo inválido.'));
        }

        $mime = $file->getMimeType();
        $ext = strtolower($file->getClientOriginalExtension());
        $usuario = auth()->user()->name ?? "";

        if (in_array($ext, $this->blockedExtensions, true)) {
            \Log::debug("USUARIO {$usuario} BANIDO ENVIANDO ARQUIVO PROIBIDO: {$mime} ({$ext})");
            $this->blockRequest($request, "Tipo de arquivo proibido: {$ext}");
        }

        if (!in_array($mime, $this->allowedMimes, true) && !in_array($ext, $this->allowedExtensions, true)) {
            abort(back()->with('error', "Arquivo não aceito: {$mime}"));
        }
    }

    protected function blockRequest(Request $request, string $message): Response
    {
        if ($user = auth()->user()) {
            $user->banido = true;
            $user->save();

            $token = $request->cookie('token');
            if ($token) {
                JWTAuth::setToken($token)->invalidate();
            }
        }

        return redirect()
            ->route('login')
            ->withErrors(['banido' => 'Houve um erro. Contate o suporte.']);
    }
}
