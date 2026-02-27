@props(['produto' => null])
@php
    $setting = \App\Helpers\Helper::settings();
@endphp

@if (is_null($produto))
    <nav id="sidebar" class="sidebar h-80 py-2 w-100">
        <a class='sidebar-brand text-center' href='/alunos/meus-produtos'
            style="position:relative;text-align:start;width:100%;">
            <img src="/storage/{{ $setting->logo_light . '?ver=' . uniqid() }}" height="36" width="auto">
            <span
                style="position:absolute;bottom:20px;right:10px;color: var(--gateway-primary-color) !important;font-size: 12px; border: 1px solid var(--gateway-primary-color);border-radius:7px;padding:3px">ALUNO</span>
        </a>
        <div class="sidebar-content position-relative w-100">
            <ul class="sidebar-nav gap-2 p-3 px-2">
                <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/alunos/meus-produtos'>
                        <i data-lucide="package-open" class="me-2"></i>
                        <span class="align-middle">Minhas Compras</span>
                    </a>
                </li>
                {{-- <li class="sidebar-item">
                    <a class='sidebar-link d-flex align-items-center' href='/alunos/shop'>
                        <i data-lucide="store" class="me-2"></i>
                        <span class="align-middle">Shop</span>
                    </a>
                </li> --}}
            </ul>

            <div class="sidebar-footer position-sticky bottom-0 p-3">
                <div class="col-12 mb-3 w-100 text-center mt-0 p-0">
                    <small class="text-muted text-center">© {{ date('Y') }} {{ $setting->software_name }}</small>
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
@else
    <div id="sidebar" class="col-12 col-xl-2 sidebar h-80 p-2 w-100">
        <div class="d-flex align-item-center justify-content-between">
            <h4 class="text-center">
                <i data-lucide="arrow-left" class="me-2" style="cursor: pointer; stroke: var(--gateway-text-color) !important" onclick="history.back()"></i>
            </h4>
            <h4></h4>
        </div>
        <ul class="nav flex-column nav-tabs nav-category">
            @foreach ($produto->categories as $category)
                <li class="nav-item">
                    <a class="nav-link {{ $selectedCategoryId === $category->id ? 'active' : '' }}"
                        href="#category-{{ $category->id }}">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

@endif
