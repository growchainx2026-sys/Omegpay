@props([
    'image' => null,
    'data' => null,
    'name' => null,
    'id' => uniqid(),
    'title' => '',
    'background' => 'black',
])

<div class=" card mb-3 w-100" style="border: 10px solid 'gray !important'">
    <div class="row g-0 w-100">
        <div class="col-12 d-flex align-items-center justify-content-center p-2"
            style="background: {{ $background }}; border-radius: 6px;height: 80px;position:relative;">
            <img src="{{ $image }}" class="img-fluid " alt="Integração {{ $title }}"
                style="height: 40px; width:auto;object-fit:contain;">
                @if(!empty($data))
                <div style="position:absolute;bottom: 10px;right: 10px;">
                    <i data-lucide="circle-check-big" style="stroke: var(--gateway-primary-color) !important;"></i>
                </div>
                @endif
        </div>
        <div class="col-12 w-100">
            <div class="card-body w-100 p-0 pt-2">
                    <a class="text-primary w-100" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#menu-{{ $id }}" aria-controls="menu-{{ $id }}">
                        <button class="btn btn-primary w-100" style="width:100%;color: white !important;"
                            type="submit">Editar</button>
                    </a>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="menu-{{ $id }}"
    aria-labelledby="menu-{{ $id }}Label">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menu-{{ $id }}Label">Integração - {{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @if($name == 'utmfy')
            <form action="{{ route('user.update.utmfy') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Token</label>
                    <input autofocus type="text" class="form-control" name="utmfy" id="utmfy"
                        value="{{ auth()->user()->utmfy }}">
                </div>

                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>
        @elseif($name == 'spedy')
            <form action="{{ route('user.update.spedy') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>API Key</label>
                    <input autofocus type="text" class="form-control" name="spedy" id="spedy"
                        value="{{ auth()->user()->spedy }}">
                </div>

                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>
        @endif
    </div>
</div>