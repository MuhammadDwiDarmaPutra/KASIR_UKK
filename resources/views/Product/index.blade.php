@extends('main')
@section('title', 'All Product')
@section('breadcrumb', 'Product')
@section('page-title', 'Product')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-bold mb-0">Product List</h5>
                @if (Auth::user()->role == 'admin')
                    <a class="btn btn-primary" href="{{ route('products.create') }}">Add Product</a>
                @endif
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Product Name</th>
                        <th scope="col" class="text-center">Price</th>
                        <th scope="col" class="text-center">Stock</th>
                        @if (Auth::user()->role == 'admin')
                        <th scope="col" class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php $id = 1; @endphp
                    @foreach ($products as $product)
                    <tr>
                        <th scope="row" class="text-center">{{ $id++ }}</th>
                        <td class="align-middle">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <span class="fw-medium text-dark">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="text-center">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $product->stock }}</td>
                        @if (Auth::user()->role == 'admin')
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light border-0" type="button" id="dropdownMenuButton{{ $product->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $product->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('products.edit', $product->id) }}">
                                            <i class="mdi mdi-pencil-outline me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn-update-stock" data-bs-toggle="modal"
                                            data-bs-target="#updateStockModal"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-stock="{{ $product->stock }}">
                                            <i class="mdi mdi-database-edit-outline me-2"></i> Update Stock
                                        </button>
                                    </li>
                                    <li>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="mdi mdi-trash-can-outline me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update Stok -->
<div class="modal fade" id="updateStockModal" tabindex="-1" aria-labelledby="updateStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStockModalLabel">Update Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStockForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="product_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok Baru</label>
                        <input type="number" class="form-control" name="stock" id="stock" required min="0" max="1000">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let updateStockModal = document.getElementById("updateStockModal");
        let updateStockForm = document.getElementById("updateStockForm");

        updateStockModal.addEventListener("show.bs.modal", function (event) {
            let button = event.relatedTarget;
            let productId = button.getAttribute("data-id");
            let productName = button.getAttribute("data-name");
            let productStock = button.getAttribute("data-stock");

            document.getElementById("product_id").value = productId;
            document.getElementById("product_name").value = productName;
            document.getElementById("stock").value = productStock;

            console.log(productId);
            console.log(productStock);
            console.log(productName);

            updateStockForm.action = `/product/${productId}/updateStock`;
        });

        // Validasi input stok max 1000
        const stockInput = document.getElementById("stock");

        if (stockInput) {
            stockInput.addEventListener("input", function () {
                let value = parseInt(this.value);
                if (value > 1000) {
                    this.value = 1000;
                } else if (value < 0) {
                    this.value = 0;
                }
            });
        }
    });
</script>

@endsection
