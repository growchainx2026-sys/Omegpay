@php
    $selectedCategory = request()->get('tab') ?? 'category-' . ($produto->categories->first()->id ?? null);
    $selectedCategoryId = (int) str_replace('category-', '', $selectedCategory);
@endphp

@extends('layouts.aluno', [
    'produto' => $produto,
    'colors' => [
        'area_member_color_primary' => $produto->area_member_color_primary,
        'area_member_color_background' => $produto->area_member_color_background,
        'area_member_color_sidebar' => $produto->area_member_color_sidebar,
        'area_member_color_text' => $produto->area_member_color_text,
        'area_member_background_image' => $produto->area_member_background_image,
    ],
])

@section('title', $produto->name)

@section('content')
    <div class="header mb-3" style="{{ !is_null($produto->area_member_banner) ? 'margin-top: 180px !important;' : '' }}">
        <h1 class="header-title">
            <i data-lucide="arrow-left" class="me-2" style="cursor: pointer; stroke: var(--gateway-text-color) !important"
                onclick="history.back()"></i>
            {{ $produto->name }}
        </h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="row g-3">
                @foreach ($produto->files as $file)
                    @if ($file->categoria_id == $selectedCategoryId)
                        <div class="col-sm-12 col-lg-4 col-xl-3">
                            <div class="card produto">
                                @php
                                    $capa = '/storage/' . $file->cover;
                                @endphp
                                <img loading="lazy" src="{{ $capa }}" class="card-img-top "
                                    alt="{{ $file->name }}">
                                <div class="card-body" style="position: relative;">
                                    <h5 class="card-title">{{ $file->name }}</h5>
                                    <p class="card-text descricao">{{ $file->description ?? '' }}</p>
                                    <div class="card-link">
                                        <a href="{{ route('aluno.produto.id', ['id' => $file->produto->id]) }}"
                                            class="btn btn-primary w-100" data-bs-toggle="offcanvas"
                                            data-bs-target="#file-{{ $file->id }}"
                                            aria-controls="offcanvasBottom">{{ $file->file_type == 'video' ? 'Assistir' : 'Ver' }}</a>
                                        @php
                                            $types = ['audio', 'zip'];
                                            $height = 'h-100';

                                            if (in_array($file->file_type, $types, true)) {
                                                $height = '';
                                            }
                                        @endphp
                                        <div class="offcanvas offcanvas-bottom {{ $height }}" tabindex="-1"
                                            id="file-{{ $file->id }}" aria-labelledby="file-{{ $file->id }}Label">
                                            <div class="offcanvas-header d-flex align-items-center justify-content-between">
                                                <h5 class="offcanvas-title" id="file-{{ $file->id }}Label">
                                                    {{ $file->name }}</h5>

                                                <i data-lucide="x" class="cursor-pointer close"
                                                    style="stroke: var(--gateway-text-color) !important"
                                                    data-bs-dismiss="offcanvas" aria-label="Close"
                                                    onclick="pauseVideo{{ $file->id }}()"></i>
                                            </div>

                                            <div class="offcanvas-body small">
                                                @if ($file->file_type == 'video')
                                                    <x-aluno-video :file="$file"></x-aluno-video>
                                                @elseif ($file->file_type == 'audio')
                                                    <x-aluno-audio :file="$file"></x-aluno-audio>
                                                @elseif ($file->file_type == 'pdf')
                                                    <x-aluno-pdf :file="$file"></x-aluno-pdf>
                                                @elseif ($file->file_type == 'txt')
                                                    <x-aluno-txt :file="$file"></x-aluno-txt>
                                                @elseif ($file->file_type == 'zip')
                                                    <x-aluno-zip :file="$file"></x-aluno-zip>
                                                @else
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
