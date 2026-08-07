@props(['label' => 'Active', 'bg' => '#f0fdf4', 'fg' => '#16a34a', 'icon' => null])

<span class="badge fw-bold rounded-pill" style="background:{{ $bg }}; color:{{ $fg }}; font-size:.68rem; padding:.38rem .8rem;">
    @if($icon)<i class="bi {{ $icon }} me-1"></i>@endif{{ $label }}
</span>
