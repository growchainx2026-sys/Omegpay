@php
    $setting = \App\Helpers\Helper::settings();
    $user = auth('aluno')->user();
    $avatarUrl = $user->avatar ? asset($user->avatar) : asset('default-avatar.png');
@endphp
<li class="nav-item dropdown" style="width: 45px;">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
        data-bs-toggle="dropdown" aria-haspopup="true">
        <img src="{{ $avatarUrl }}" alt="Avatar de {{ $user->name }}"
            class="rounded-circle me-2" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid var(--gateway-primary-color, #0b6856);" onerror="this.src='{{ asset('default-avatar.png') }}'">



        {{-- Ícones de seta --}}
        {{-- <i class="fa-solid fa-chevron-up text-muted arrow-1" style="font-size: 8px;color:rgba(0, 0, 0, 0.18);"></i>
        <i class="fa-solid fa-chevron-down text-muted arrow-2" style="font-size: 8px;color:rgba(0,0,0,18);"></i> --}}
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow mb-2 w-100 rounded" aria-labelledby="userDropdown">
        <li class="w-100">
            <div class="d-md-block text-center" style="line-height:20px;">
                <div class="fw-semibold w-100 px-2" style="max-width: 120px;">
                    <div class="text-start text-truncated small">{{ auth('aluno')->user()->name }}</div>
                    <div class="text-start text-truncated small">{{ auth('aluno')->user()->email }}</div>
                </div>
            </div>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('aluno.profile') }}">
                <i class="fas fa-user me-2"></i> Perfil
            </a>
        </li>
        
        <li>
            <a class="dropdown-item" target="_blank"
                href="https://wa.me/55{{ $setting->phone_support }}?text=Olá, meu nome é {{ auth('aluno')->user()->name }}, sou cliente da {{ $setting->software_name }}. Preciso de suporte.">
                <i class="fas fa-headset me-2"></i> Suporte
            </a>
        </li>

        <li>
            <form method="POST" action="{{ route('aluno.logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">
                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                </button>
            </form>
        </li>
    </ul>
</li>
