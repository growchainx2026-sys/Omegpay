@php
    use App\Helpers\Helper;
    $setting = Helper::settings();
@endphp

<nav class="navbar navbar-expand navbar-theme my-0 py-0 relative">
    <a class="sidebar-toggle d-flex me-2 menu-hamburguer">
        <i class="fa-solid fa-bars align-start" style="color: var(--gateway-text-color) !important;"></i>
    </a>
    <img src="/storage{{ auth()->user()->logo }}"  class="nav-logo-mobile" height="30px" width="auto">
    <form class="d-sm-inline-block">
        <!-- <span class="time"></span> -->
    </form>


    <x-nivel-bar></x-nivel-bar>
    <x-alert-user></x-alert-user>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <x-card-profile2></x-card-profile2>
        </ul>
    </div>
</nav>