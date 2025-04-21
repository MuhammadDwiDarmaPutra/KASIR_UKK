@extends('main')
@section('title', 'Penjualan')
@section('breadcrumb', 'Penjualan')
@section('page-title', 'Penjualan')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-bold mb-0">Penjualan</h5>
            </div>
            {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                
                <div class="dropdown me-2">
                    Tampilkan
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        10
                    </button>
                    Entri
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" >10</a></li>
                        <li><a class="dropdown-item" href="#">15</a></li>
                        <li><a class="dropdown-item" href="#">20</a></li>
                    </ul>
                </div>
                <div>
                    <form method="GET">
                        <input type="text" name="search" class="form-control" placeholder="Cari..."
                            value="{{ request('search') }}">
                    </form>
                </div>
            </div> --}}

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    @if (Auth::user()->role == 'admin')
                        <button class="btn btn-outline-primary" onclick="showAccessDenied()">
                            <i class="bi bi-file-earmark-excel"></i> Export Penjualan
                        </button>
                    @else
                    <a class="btn btn-outline-primary" 
                        href="{{ route('formatexcel', request()->only(['day', 'month', 'year'])) }}">
                        <i class="bi bi-file-earmark-excel"></i> Export Penjualan
                    </a>
                    @endif
                    </div>
                @if (Auth::user()->role == 'kasir')
                    <a class="btn btn-success" href="{{ route('pembelians.create') }}">Tambah Penjualan</a>
                @endif
            </div>
            
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form method="GET" class="d-flex align-items-center gap-2">
                            <label for="day" class="mb-0">Tanggal</label>
                            <select name="day" id="day" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua</option>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ request('day') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                    
                            <label for="month" class="mb-0">Bulan</label>
                            <select name="month" id="month" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                    
                            <label for="year" class="mb-0">Tahun</label>
                            <select name="year" id="year" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua</option>
                                @for ($i = now()->year; $i >= 2000; $i--)
                                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </form>
                    </div>
                    <form method="GET">
                        <input type="text" name="search" class="form-control" placeholder="Cari..."
                            value="{{ request('search') }}">
                    </form>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Nama Pelanggan</th>
                        <th scope="col" class="text-center">Tanggal Penjualan</th>
                        <th scope="col" class="text-center">Total Harga</th>
                        <th scope="col" class="text-center">Dibuat Oleh</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $id = 1; @endphp
                    @foreach ($transaction as $key => $item)
                        <tr>
                            <th scope="row" class="text-center">{{ $transaction->firstItem() + $key }}</th>
                        <td class="text-center">
                            {{ $item->member ? $item->member->name : 'Non Member' }}
                        </td>
                        <td class="text-center">{{ $item->created_at->format('Y M d') }}</td>
                        <td class="text-center">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->user->name }}</td>
                        <td class="text-center">
                            <div class="d-grid gap-4 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalDetail{{ $item->id }}">Lihat</button>
                                <a href="{{ route('formatpdf', $item->id) }}" class="btn btn-primary text-white">Unduh Bukti</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $transaction->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>

@foreach ($transaction as $item)
<!-- Modal Detail Penjualan -->
<div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $item->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p>Member Status : <strong>{{ $item->member ? 'Member' : 'Non Member' }}</strong></p>
                    <p>No. HP : {{ $item->member->phone_number ?? '-' }}</p>
                    <p>Poin Yang Digunakan : {{ $item->poin ?? '-' }}</p>
                    <p>Bergabung Sejak : {{ $item->member ? \Carbon\Carbon::parse($item->member->created_at)->format('d F Y') : '-' }}</p>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->details as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->qty * $detail->product->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td><strong>Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <p class="mt-3 text-muted"><small>Dibuat pada : {{ $item->created_at }}<br>Oleh : {{ $item->user->name }}</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
<script>
    function showAccessDenied() {
        alert("Anda tidak memiliki akses");
    }
</script>
@endsection