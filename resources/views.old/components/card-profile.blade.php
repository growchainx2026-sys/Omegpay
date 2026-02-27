@php
$setting = \App\Helpers\Helper::settings();
@endphp

<li class="nav-item dropdown dropup card border border-1 px-2 mb-0 shadow-md">
    <a class="nav-link dropdown-toggle d-flex align-items-center relative py-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset(auth()->user()->avatar) }}"
            alt="Avatar"
            class="rounded-circle me-2"
            style="width: 40px; height: 40px; object-fit: cover; border: 1px solid {{ $setting->software_color }};">

        <div class="d-md-block text-start w-100">
            <div class="fw-semibold small text-truncate" style="max-width: 120px;">
                {{ auth()->user()->name }}
            </div>
            <div class="text-muted small">{{ auth()->user()->email }}</div>
        </div>
        <i class="fa-solid fa-chevron-up text-muted arrow-1" style="font-size: 8px;color:rgba(0, 0, 0, 0.18);"></i>
        <i class="fa-solid fa-chevron-down text-muted arrow-2" style="font-size: 8px;color:rgba(0,0,0,18);"></i>
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow mb-5 w-100 rounded" aria-labelledby="userDropdown">
        <li>
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">
                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                </button>
            </form>
        </li>
        <li>
            <a style="text-decoration: none;" target="_blank" href="https://wa.me/55{{$setting->phone_support}}?text=Olá, meu nome é {{ auth()->user()->name }}, sou cliente da {{ $setting->software_name }}. Preciso de suporte.">
                <button class="dropdown-item" type="button">
                    <i class="fas fa-headset me-2"></i> Suporte
                </button>
            </a>
        </li>
    </ul>
</li>