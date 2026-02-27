@php
$setting = \App\Helpers\Helper::settings();
@endphp
<li class="nav-item dropdown" style="width: 45px;">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
        <img src="{{ asset(auth()->user()->avatar) }}"
            alt="Avatar de {{ auth()->user()->name }}"
            class="rounded rounded-3 me-2"
            style="width: 40px; height: 40px; object-fit: cover;">



        {{-- Ícones de seta --}}
       {{-- <i class="fa-solid fa-chevron-up text-muted arrow-1" style="font-size: 8px;color:rgba(0, 0, 0, 0.18);"></i>
        <i class="fa-solid fa-chevron-down text-muted arrow-2" style="font-size: 8px;color:rgba(0,0,0,18);"></i> --}}
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow mb-2 w-100 rounded" aria-labelledby="userDropdown">
        <li class="w-100">
            <div class="d-md-block text-center" style="line-height:20px;">
                <div class="fw-semibold small text-truncate text-muted" style="max-width: 120px;">
                    {{ auth()->user()->name }}
                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">
                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                </button>
            </form>
        </li>
        <li>
            <a class="dropdown-item" target="_blank"
                href="https://wa.me/55{{ $setting->phone_support }}?text=Olá, meu nome é {{ auth()->user()->name }}, sou cliente da {{ $setting->software_name }}. Preciso de suporte.">
                <i class="fas fa-headset me-2"></i> Suporte
            </a>
        </li>
    </ul>
</li>