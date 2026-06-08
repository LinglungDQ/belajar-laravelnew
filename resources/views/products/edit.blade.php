@extends('layouts.master')

@section('title', 'Edit Produk')

@section('content')

<div class="container">

    <h3 class="mb-4">
        Edit Produk
    </h3>

    <form
        action="{{ route('products.update', $product) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">
                Nama Produk
            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name', $product->name) }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">
                Harga
            </label>

            <input
                type="number"
                name="price"
                class="form-control"
                value="{{ old('price', $product->price) }}"
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
                value="{{ old('stock', $product->stock) }}"
            >
        </div>

        <button
            type="submit"
            class="btn btn-primary"
        >
            Update Produk
        </button>

    </form>

</div>

@endsection
