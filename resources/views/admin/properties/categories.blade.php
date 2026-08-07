@extends('layouts.main')

@section('title', 'Property Categories | Admin | ' . site_name())

@section('content')
<section class="py-4" style="background:#f8fafc; min-height:calc(100vh - 70px);">
    <div class="container" style="max-width:860px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="color:#0B1F3A;"><i class="bi bi-tags me-2" style="color:#2563eb;"></i>Property Categories</h4>
                <p class="text-muted small mb-0">Categories shown in the browse filters and listing forms.</p>
            </div>
            <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-light fw-bold rounded-3" style="border:1px solid #dbe2ec;">Back to Properties</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success small fw-bold rounded-3">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#0B1F3A;">Add Category</h6>
                <form action="{{ route('admin.properties.categories.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Name *" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="slug" class="form-control" placeholder="Slug (auto)">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="icon" class="form-control" placeholder="Bootstrap icon e.g. bi-house">
                    </div>
                    <div class="col-md-2">
                        <button class="btn fw-bold w-100 text-white rounded-3" style="background:#0B1F3A;">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background:#f8fafc; font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b;">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th>Slug</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <form action="{{ route('admin.properties.categories.update', $cat) }}" method="POST">
                                    @csrf
                                    <td class="px-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white"><i class="bi {{ $cat->icon }}"></i></span>
                                            <input type="text" name="name" class="form-control" value="{{ $cat->name }}">
                                        </div>
                                    </td>
                                    <td><code class="small">{{ $cat->slug }}</code></td>
                                    <td><input type="number" name="sort_order" class="form-control form-control-sm" style="width:80px;" value="{{ $cat->sort_order }}"></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($cat->is_active)>
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <button class="btn btn-sm fw-bold text-white rounded-3" style="background:#0B1F3A;">Save</button>
                                </form>
                                <form action="{{ route('admin.properties.categories.delete', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger fw-bold rounded-3"><i class="bi bi-trash"></i></button>
                                </form>
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
