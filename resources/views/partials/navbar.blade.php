@php
    use App\Helpers\Helper;
    $setting = Helper::settings();
    $navLogoLight = $setting->logo_light ?? null;
    $navLogoDark = $setting->logo_dark ?? $navLogoLight;
@endphp

<nav class="navbar navbar-expand navbar-theme my-0 py-0 relative" style="display: flex; align-items: center; justify-content: space-between;">
    <div class="d-flex align-items-center">
        <a class="sidebar-toggle d-flex me-2 menu-hamburguer">
            <i class="fa-solid fa-bars align-start" style="color: var(--gateway-text-color) !important;"></i>
        </a>
        <img src="{{ Helper::logoUrl($navLogoLight) }}?ver={{ uniqid() }}" class="nav-logo-mobile nav-logo-mobile-light" height="30px" width="auto" alt="">
        <img src="{{ Helper::logoUrl($navLogoDark) }}?ver={{ uniqid() }}" class="nav-logo-mobile nav-logo-mobile-dark" height="30px" width="auto" alt="">
        <form class="d-sm-inline-block">
            <!-- <span class="time"></span> -->
        </form>
    </div>

    <x-nivel-bar></x-nivel-bar>

    <div class="navbar-collapse collapse" style="flex-grow: 0;">
        <ul class="navbar-nav ms-auto d-flex align-items-center nav-actions-list">
            <li class="nav-item d-flex align-items-center">
                <x-alert-user></x-alert-user>
            </li>
            <x-dark-mode-toggle></x-dark-mode-toggle>
            <x-card-profile2></x-card-profile2>
            <li class="nav-item">
                <form method="POST" action="{{ route('auth.logout') }}" class="nav-logout-form d-flex align-items-center">
                    @csrf
                    <button type="submit" class="nav-link nav-icon-btn border-0 bg-transparent" title="Sair" aria-label="Sair">
                        <i data-lucide="log-out" class="nav-action-icon" style="width: 20px; height: 20px;"></i>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>