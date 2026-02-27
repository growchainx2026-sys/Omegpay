@extends('layouts.app')

@section('title', 'Integrações')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Integrações
        </h1>
    </div>

    <div class="row mb-3 w-100">
        <div class="col-12 col-md-3">
            <x-card-integracao :title="'UTMFY'" :name="'utmfy'" :id="'utmfy'" :image="asset('/assets/ico-utmfy.png')" :data="auth()->user()->utmfy">
            </x-card-integracao>
        </div>

        <div class="col-12 col-md-3">
            <x-card-integracao :title="'SPEDY'" :name="'spedy'" :id="'spedy'" :image="asset('/assets/images/spedy.png')" :data="auth()->user()->spedy">
            </x-card-integracao>
        </div>
    </div>
@endsection