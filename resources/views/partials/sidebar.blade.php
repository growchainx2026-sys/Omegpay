@php
    $setting = \App\Helpers\Helper::settings();
    $logoLight = $setting->logo_light ?? null;
    $logoDark = $setting->logo_dark ?? $logoLight;
@endphp

<nav id="sidebar" class="sidebar h-80 py-2 ">
    <a class='sidebar-brand text-center px-4' href='/'>
        <img src="{{ \App\Helpers\Helper::logoUrl($logoLight) }}?ver={{ uniqid() }}"
            height="36" width="auto" class="sidebar-logo sidebar-logo-light" alt="">
        <img src="{{ \App\Helpers\Helper::logoUrl($logoDark) }}?ver={{ uniqid() }}"
            height="36" width="auto" class="sidebar-logo sidebar-logo-dark" alt="">
    </a>
    <div class="sidebar-content position-relative w-100">
        <ul class="sidebar-nav gap-2 p-3 px-2">

            @if (auth()->user()->status === 'aguardando')
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/enviar-docs'>
                        <i class="fas fa-fw fa-paper-plane me-2"></i>
                        <span class="align-middle">Completar cadastro</span>
                    </a>
                </li>
            @elseif(auth()->user()->status === 'analise')
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/enviar-docs'>
                        <i class="fas fa-fw fa-hourglass-half me-2"></i>
                        <span class="align-middle">Verificar Status</span>
                    </a>
                </li>
            @elseif(auth()->user()->permission === 'user' || auth()->user()->permission === 'affiliate')
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/dashboard?periodo=dia'>
                        <i data-lucide="layout-dashboard" class="me-2"></i>
                        <span class="align-middle">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a data-bs-target="#transacoes" data-bs-toggle="collapse"
                        class="sidebar-link collapsed d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2"></i>
                        <span class="align-middle">Transações</span>
                    </a>
                    <ul id="transacoes" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='/extratos/depositos?periodo=dia'>Entradas</a></li>
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='/extratos/saques?periodo=dia'>Saídas</a></li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link' href="/produtos">
                        <i data-lucide="package-2" class="me-2"></i>
                        <span class="align-middle">Produtos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link' href="/affiliates/vitrine">
                        <i data-lucide="store" class="me-2"></i>
                        <span class="align-middle">Vitrine</span>
                    </a>
                </li>
                @if(auth()->user()->afiliacoes()->count() > 0)
                    <li class="sidebar-item">
                        <a class='sidebar-link' href="/affiliates/my-affiliates">
                            <i data-lucide="diamond-percent" class="me-2"></i>
                            <span class="align-middle">Afiliações</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->coproducoes()->count() > 0)
                    <li class="sidebar-item">
                        <a class='sidebar-link' href="/coproducoes">
                            <i data-lucide="boxes" class="me-2"></i>
                            <span class="align-middle">Coproduções</span>
                        </a>
                    </li>
                @endif
                <li class="sidebar-item">
                    <a class='sidebar-link' href="/pedidos?status=aprovados">
                        <i data-lucide="receipt" class="me-2"></i>
                        <span class="align-middle">Vendas</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/financeiro'>
                        <i data-lucide="wallet" class="me-2"></i>
                        <span class="align-middle">Financeiro</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='{{ route('user.links.index') }}'>
                        <i data-lucide="external-link" class="me-2"></i>
                        <span class="align-middle">Link de pagamento</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='{{ route('user.vouchers.index') }}'>
                        <i data-lucide="tickets" class="me-2"></i>
                        <span class="align-middle">Voucher</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href="{{ route('infracoes') }}">
                        <i data-lucide="triangle-alert" class="me-2"></i>
                        <span class="align-middle">Infrações</span>
                    </a>
                </li>
                <!-- <li class="sidebar-item">
                            <a class='sidebar-link d-flex align-items-center' href="/account-view">
                                <i data-lucide="user-round" class="me-2"></i>
                                <span class="align-middle">Minha conta</span>
                            </a>
                        </li> -->
                <li class="sidebar-item">
                    <a data-bs-target="#conta" data-bs-toggle="collapse"
                        class="sidebar-link collapsed d-flex align-items-center">
                        <i data-lucide="user-round" class="me-2"></i>
                        <span class="align-middle">Minha conta</span>
                    </a>
                    <ul id="conta" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center' href='/account-view'>Meu
                                perfil</a></li>
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='/minhas-taxas'>Minhas taxas</a></li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href="{{ route('integracoes') }}">
                        <i data-lucide="puzzle" class="me-2"></i>
                        <span class="align-middle">Integrações</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href="{{ route('webhooks') }}">
                        <i data-lucide="link" class="me-2"></i>
                        <span class="align-middle">Webhooks</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href="{{ route('webhook-tests.index') }}">
                        <i data-lucide="flask-conical" class="me-2"></i>
                        <span class="align-middle">Testar Webhooks</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href="/afiliate?periodo=dia">
                        <i data-lucide="share-2" class="me-2"></i>
                        <span class="align-middle">Indicações</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='{{ route('user.customization.index') }}'>
                        <i data-lucide="palette" class="me-2"></i>
                        <span class="align-middle">Customização</span>
                    </a>
                </li>
            @elseif (auth()->user()->permission === 'admin' || auth()->user()->permission === 'dev')
                @if(auth()->user()->permission === 'dev')
                    <li class="sidebar-item">
                        <a class='sidebar-link d-flex align-items-center' href='{{ route('admin.dev.index') }}'>
                            <i data-lucide="code-2" class="me-2"></i>
                            <span class="align-middle">Desenvolvedor</span>
                        </a>
                    </li>
                @endif
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/dashboard'>
                        <i data-lucide="layout-dashboard" class="me-2"></i>
                        <span class="align-middle">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/clientes'>
                        <i data-lucide="users" class="me-2"></i>
                        <span class="align-middle">Clientes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/produtos'>
                        <i data-lucide="package-2" class="me-2"></i>
                        <span class="align-middle">Produtos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link' href="/affiliates/vitrine">
                        <i data-lucide="store" class="me-2"></i>
                        <span class="align-middle">Vitrine</span>
                    </a>
                </li>
                @if(auth()->user()->afiliacoes()->count() > 0)
                    <li class="sidebar-item">
                        <a class='sidebar-link' href="/affiliates/my-affiliates">
                            <i data-lucide="diamond-percent" class="me-2"></i>
                            <span class="align-middle">Afiliações</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->coproducoes()->count() > 0)
                    <li class="sidebar-item">
                        <a class='sidebar-link' href="/coproducoes">
                            <i data-lucide="boxes" class="me-2"></i>
                            <span class="align-middle">Coproduções</span>
                        </a>
                    </li>
                @endif
                <li class="sidebar-item">
                    <a data-bs-target="#admin-transacoes" data-bs-toggle="collapse"
                        class="sidebar-link collapsed d-flex align-items-center">
                        <i data-lucide="wallet" class="me-2"></i>
                        <span class="align-middle">Financeiro</span>
                    </a>
                    <ul id="admin-transacoes" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='/admin/aprovar-saques'>Aprovar saques</a></li>
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='/admin/depositos'>Depósitos</a></li>
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='/admin/saques'>Saques</a></li>
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='/admin/balance'>Balanceamento de saldo</a></li>
                        <li class="sidebar-item"><a class='sidebar-link d-flex align-items-center'
                                href='{{ route('admin.extrato') }}'>Extrato</a></li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/customization'>
                        <i data-lucide="palette" class="me-2"></i>
                        <span class="align-middle">Customização</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/taxas'>
                        <i data-lucide="square-percent" class="me-2"></i>
                        <span class="align-middle">Taxas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/adquirentes'>
                        <i data-lucide="currency" class="me-2"></i>
                        <span class="align-middle">Adquirentes</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/gamefication'>
                        <i data-lucide="medal" class="me-2"></i>
                        <span class="align-middle">Gameficação</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/account-view'>
                        <i data-lucide="user-round" class="me-2"></i>
                        <span class="align-middle">Meu perfil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/settings'>
                        <i data-lucide="settings" class="me-2"></i>
                        <span class="align-middle">Configurações</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/admin/banners'>
                        <i data-lucide="images" class="me-2"></i>
                        <span class="align-middle">Banners</span>
                    </a>
                </li>

            @endif
        </ul>

        <div class="sidebar-footer position-sticky bottom-0 p-3">
            <!-- <div class="col-12 mb-3 w-100">
                <x-card-profile></x-card-profile>
            </div> -->
            <div class="col-12 mb-3 w-100 text-center mt-0 p-0">
                <small class="text-muted text-center sidebar-footer-text">© {{ date('Y') }} {{ $setting->software_name }}</small>
                <div class="text-muted text-center mt-1" style="font-size: 0.7rem;">Versão 1.1.0</div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const currentPath = window.location.pathname;
        const sidebarLinks = document.querySelectorAll(".sidebar-nav .sidebar-link");

        sidebarLinks.forEach(link => {
            const href = link.getAttribute("href");

            if (!href) return;

            // Extrai apenas o pathname, ignorando query string
            const linkPath = new URL(href, window.location.origin).pathname;

            // Se o pathname da rota bater com o atual
            if (linkPath === currentPath) {
                const parentItem = link.closest(".sidebar-item");
                if (parentItem) parentItem.classList.add("active");

                // Se estiver em submenu, expande o item pai também
                const submenu = link.closest(".sidebar-dropdown");
                if (submenu) {
                    submenu.classList.add("show");

                    const parentDropdownItem = submenu.closest(".sidebar-item");
                    if (parentDropdownItem) {
                        parentDropdownItem.classList.add("active");

                        const triggerLink = parentDropdownItem.querySelector(
                            ".sidebar-link[data-bs-toggle='collapse']");
                        if (triggerLink) {
                            triggerLink.classList.remove("collapsed");
                        }
                    }
                }
            }
        });
    });
</script>

@if(request()->route('uuid'))
    <!-- Modal coprodutor -->
    <form method="POST" action="{{ route('coproducao.add', ['uuid' => request()->route('uuid')]) }}"
        enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="addCoprodutorModal" tabindex="-1" aria-labelledby="addCoprodutorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCoprodutorModalLabel">Adicionar coprodutor</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="coprodutor_email">Email</label>
                                <input type="text" autofocus class="form-control form-control-md" id="coprodutor_email"
                                    name="coprodutor_email" value="{{ old('coprodutor_email') }}">
                                <small class="text-warning">Digite ou cole aqui o email do produtor</small>
                                @error('coprodutor_email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12">
                                <label for="coprodutor_percentage">Porcentagem</label>
                                <input type="text" autofocus class="form-control form-control-md" id="coprodutor_percentage"
                                    name="coprodutor_percentage" value="{{ old('coprodutor_percentage') }}">
                                <small class="text-warning">Defina um valor de 0 a 99</small>
                                @error('coprodutor_percentage')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-12">
                                <label for="coprodutor_periodo">Período</label>
                                <select type="text" autofocus class="form-control form-control-md" id="coprodutor_periodo"
                                    name="coprodutor_periodo" value="{{ old('coprodutor_periodo') }}">
                                    <option>--selecione--</option>
                                    <option value="30">1 Mês</option>
                                    <option value="60">2 Meses</option>
                                    <option value="90">3 Meses</option>
                                    <option value="120">4 Meses</option>
                                    <option value="150">5 Meses</option>
                                    <option value="180">6 Meses</option>
                                    <option value="210">7 Meses</option>
                                    <option value="240">8 Meses</option>
                                    <option value="270">9 Meses</option>
                                    <option value="300">10 Meses</option>
                                    <option value="330">11 Meses</option>
                                    <option value="365">1 Ano</option>
                                    <option value="sempre">Sempre</option>
                                </select>
                                @error('coprodutor_periodo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif