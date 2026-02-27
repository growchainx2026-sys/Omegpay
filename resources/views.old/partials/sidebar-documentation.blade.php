@php
$setting = \App\Helpers\Helper::settings();
@endphp
<nav id="sidebar h-100" class="sidebar" >
    <a class='sidebar-brand text-center' href='/' style="position:relative;">
        <img src="/storage/{{ $setting->logo_light.'?ver='.uniqid() }}" height="36" width="auto">
        <span style="font-size: 12px;position:absolute:bottom:-10px;right:-3px; border: 1px solid var(--gateway-primary-color);border-radius:7px;padding:3px">DOC</span>
    </a>
    <div class="sidebar-content position-relative w-100">
      <ul class="sidebar-nav">
            
           <li class="sidebar-item">
                <a class='sidebar-link' href="{{ route('docs.index') }}">
                    <i class="align-middle me-2 fas fa-fw fa-home"></i> <span class="align-middle">Introdução</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class='sidebar-link' href="/docs/api-pix/receive#success">
                    <i class="align-middle me-2 fa-solid fa-download"></i> <span class="align-middle">Receber Pix</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class='sidebar-link' href="/docs/api-pix/send#success">
                    <i class="align-middle me-2 fa-solid fa-upload"></i> <span class="align-middle">Enviar Pix</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class='sidebar-link' href="{{ route('docs.webhooks') }}">
                    <i class="align-middle me-2 fa-solid fa-rotate"></i> <span class="align-middle">Webhooks</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer position-sticky bottom-0 p-3">
    <div class="col-12 mb-3 w-100">
        <a target="_blank" href="https://wa.me/55{{$setting->phone_support}}?text=Olá, estou fazendo a integração da {{ $setting->software_name }}, e estou precisando de ajuda.">
            <button type="button" class="btn btn-dark w-100">
                <i class="fa-solid fa-headset"></i>&nbsp;    
                Suporte
            </button>
        </a>
    </div>
</div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const currentUrl = window.location.href.split('#')[0]; // Ignora fragmentos
    const sidebarLinks = document.querySelectorAll(".sidebar-nav .sidebar-link");

    sidebarLinks.forEach(link => {
        const href = link.href.split('#')[0]; // Ignora fragmentos no href
        const parentItem = link.closest(".sidebar-item");

        if (href === currentUrl) {
            // Ativa o item atual
            parentItem?.classList.add("active");

            // Se estiver dentro de um dropdown
            const submenu = link.closest(".sidebar-dropdown");
            if (submenu) {
                submenu.classList.add("show");

                const parentDropdownItem = submenu.closest(".sidebar-item");
                if (parentDropdownItem) {
                    parentDropdownItem.classList.add("active");

                    // Remove a classe 'collapsed' do botão que abre o dropdown
                    const triggerLink = parentDropdownItem.querySelector(".sidebar-link[data-bs-toggle='collapse']");
                    if (triggerLink) {
                        triggerLink.classList.remove("collapsed");
                    }
                }
            }
        }
    });
});
</script>
