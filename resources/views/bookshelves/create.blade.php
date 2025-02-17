@extends('layouts.main')

@section('contentAdmin')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4>Tambah Rak Buku</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('bookshelves.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="rak" class="form-label">Nama Rak</label>
                            <input type="text" name="rak" id="rak" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="baris" class="form-label">Baris</label>
                            <input type="text" name="baris" id="baris" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Tambah Rak</button>
                            <a href="{{ route('bookshelves.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('contentPustakawan')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4>Tambah Rak Buku</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('bookshelves.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="rak" class="form-label">Nama Rak</label>
                            <input type="text" name="rak" id="rak" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="baris" class="form-label">Baris</label>
                            <input type="text" name="baris" id="baris" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Tambah Rak</button>
                            <a href="{{ route('bookshelves.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
