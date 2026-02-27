@props([
'produto'
])
@php
$checks = $produto->methods ?? [];
@endphp
<div class="row">
    <div class="col-lg-4">
        <h4 class="texto-branco">Trackeamento</h4>
        <p class="texto-branco">Configure as opções de trackeamento</p>
    </div>

    <div class="col-lg-8">
        <div class="row">
            {{-- Meta ADS --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-header">Meta ADS</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="mb-3 col-12">
                                    <label for="name">Pixel ID</label>
                                    <input type="text" autofocus class="form-control form-control-md" id="meta_ads" name="meta_ads" value="{{ $produto->meta_ads }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- GTM --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-header">GTM (Google Tag Manager)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="mb-3 col-12">
                                    <label for="name">GTM ID</label>
                                    <input type="text" class="form-control form-control-md" id="google_ads" name="google_ads" value="{{ $produto->google_ads }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>