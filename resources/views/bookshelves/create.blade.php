    @extends('layouts.app')

    @section('contentAdmin')
    <div class="container">
        <h2>Tambah Rak Buku</h2>

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
                <label for="rak" class="form-label">Rak</label>
                <input type="text" name="rak" id="rak" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="baris" class="form-label">Baris</label>
                <input type="text" name="baris" id="baris" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="{{ route('bookshelves.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    @endsection

    @section('contentPustakawan')
    <div class="container">
        <h2>Tambah Rak Buku</h2>

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
                <label for="rak" class="form-label">Rak</label>
                <input type="text" name="rak" id="rak" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="baris" class="form-label">Baris</label>
                <input type="text" name="baris" id="baris" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="{{ route('bookshelves.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    @endsection
