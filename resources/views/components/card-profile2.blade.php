@php
    $user = auth()->user();
    $avatarUrl = $user->avatar ? asset($user->avatar) : asset('default-avatar.png');
@endphp
<li class="nav-item d-flex align-items-center">
    <a class="nav-link nav-profile-link d-flex align-items-center p-0" href="{{ route('accountView') }}" title="Meu perfil" aria-label="Meu perfil">
        <img src="{{ $avatarUrl }}"
            alt="Avatar de {{ $user->name }}"
            class="nav-profile-avatar"
            style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;"
            onerror="this.src='{{ asset('default-avatar.png') }}'">
    </a>
</li>
