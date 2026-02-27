
@props([
    'icon',
    'size' => 15,
    'color' => "white"    
])

<i data-lucide="{{ $icon }}" style="width: {{ $size }}px;height: {{ $size }}px;stroke: {{ $color }} !important;"></i>