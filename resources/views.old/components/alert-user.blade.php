<div class="nav-alert">
    @if (auth()->user()->notifications()->count() > 0)
        <span class="translate-middle badge rounded-pill bg-danger text-white"
            style="position:absolute;top:-5px;right:-10px;z-index:999;">
            @php
                $notificacoes = auth()->user()->unreadNotifications();
                $quantidade = (clone $notificacoes)->count();
                $qtd_cop = $quantidade > 99 ? '99+' : $quantidade;
            @endphp
            {{ $qtd_cop }}
            <span class="visually-hidden">Mensagens</span>
        </span>
    @endif
    <div class="dropstart">
        <i data-lucide="bell" class="me-2 cursor-pointer" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
        <ul class="dropdown-menu">
            @if (auth()->user()->notifications()->count() > 0)
                @foreach (auth()->user()->notifications()->orderBy('created_at', 'DESC')->limit(5)->get() as $notificacao)
                    <li class="" onclick="readNotification('{{ $notificacao->id }}', '{{ $notificacao->data['pagina'] }}')">
                        <a class="dropdown-item my-0" href="{{ $notificacao->data['pagina'] ?? '#' }}"
                            style="background-color: {{ is_null($notificacao->read_at) ? '#ff535334' : null }};">
                            <div style="line-height: 5px;">
                                <h6 class="text-truncate">{{ $notificacao->data['assunto'] ?? '--' }} </h6>
                                <small class="text-truncate">{{ $notificacao->data['mensagem'] ?? '--' }}</small>
                            </div>

                        </a>
                    </li>
                @endforeach
                <li><a class="dropdown-item text-center" href="/notifications">Ver todas</a></li>
            @else
                <li><a class="dropdown-item text-center disabled" href="#">Nada a exibir</a></li>
            @endif
        </ul>
    </div>
</div>
<script>
    function readNotification(id, rota) {
        let endpoint = `{{ route('coproducao.notify.read') }}`; // coloque a rota de envio
        let csrf = "{{ csrf_token() }}";

        fetch(endpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf
                },
                body: JSON.stringify({
                    id: id
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erro na requisição");
                }
                if(rota === '#'){
                    window.location.reload()
                }
            })
            .catch(error => {
                console.error("Erro:", error);
            });

    }
</script>
