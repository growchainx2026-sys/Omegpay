<nav class="navbar navbar-expand navbar-theme ">
    <a class="sidebar-toggle d-flex me-2 menu-hamburguer">
        <i class="hamburger align-self-center"></i>
    </a>

    <form class="d-sm-inline-block">
    </form>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            
            <li class="nav-item dropdown ms-lg-2">
                <a href="{{ route('login') }}" target="_blank">
                    <button class="btn btn-xs btn-outline-success">
                        Acessar
                    </button>
                </a>
            </li>
            <li class="nav-item dropdown ms-lg-2">
                <a href="{{ route('register') }}" target="_blank">
                    <button class="btn btn-xs btn-success">
                        Criar conta
                    </button>
                </a>
            </li>
        </ul>
    </div>
</nav>
