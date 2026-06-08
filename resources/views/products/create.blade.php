@extends('layouts.master')

@section('title', 'Tambah Produk')

@section('content')

<div class="container">


<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">
        Tambah Produk Baru
    </h4>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form
            action="{{ route('products.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Kategori
                </label>

                <select
                    name="category_id"
                    class="form-select"
                >
                    <option value="">
                        Pilih Kategori
                    </option>

                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Harga
                </label>

                <input
                    type="number"
                    name="price"
                    class="form-control"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Stok
                </label>

                <input
                    type="number"
                    name="stock"
                    class="form-control"
                >
            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan Produk
            </button>

        </form>

    </div>
</div>

</div>

@endsection
