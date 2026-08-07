{{-- Fixed left sidebar shell for standalone user dashboard pages --}}
<style>
    @media (min-width: 768px) {
        .user-shell-sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            bottom: 0;
            width: 300px;
            overflow: hidden;
            background: #0F1E3D;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
        }
        .user-shell-content {
            margin-left: 300px;
        }
    }
</style>
<div class="user-shell-sidebar d-none d-md-block">
    <div class="h-100">
        @include('partials.navy-sidebar')
    </div>
</div>
@section('footer')<!-- suppressed -->@endsection
