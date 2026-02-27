@props(['produto'])
@php
    use App\Helpers\Helper;
    $setting = Helper::settings();
@endphp

<nav class="navbar navbar-expand navbar-theme my-0 py-0">
    @if (isset($produto->area_member_banner) && !is_null($produto->area_member_banner))
        <div class="w-100 absolute mx-n3 px-0"
            style="
            position: absolute !important;
            top: 0 !important;
            width: 100% !important;
            height:250px !important;
            background-image: url(/storage/{{ $produto->area_member_banner }}) !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            background-position: center center !important;
">
        </div>
    @endif
    <a class="sidebar-toggle d-flex me-2 menu-hamburguer">
        <i class="fa-solid fa-bars align-start" style="color: var(--gateway-text-color) !important;"></i>
    </a>
    <div class="gap-2 relative">
        <img src="/storage/{{ $setting->logo_light }}" class="nav-logo-mobile" height="30px" width="auto">
        <span class="tag-aluno"
            style="position:absolute; bottom: 12px;right:35%; color: var(--gateway-primary-color) !important;font-size: 12px;border: 1px solid var(--gateway-primary-color);border-radius:7px;padding:3px">ALUNO</span>
    </div>
    <form class="d-sm-inline-block">
        <!-- <span class="time"></span> -->
    </form>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <x-card-profile-aluno></x-card-profile-aluno>
        </ul>
    </div>
</nav>
