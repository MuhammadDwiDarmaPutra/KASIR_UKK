@extends('main')
@section('title', 'Result Member Page')
@section('breadcrumb', 'Member')
@section('page-title', 'Member')

@section('content')
<div class="container">
    <div class="mb-4">
        <a href="{{ route('formatpdf', ['id' => $transactionId]) }}" class="btn btn-primary">Unduh</a>
        <a href="{{ route('pembelians.index') }}" class="btn btn-secondary text-white">Kembali</a>
    </div>

    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">Invoice - #{{ $invoiceNumber }}</h5>
            <span class="text-muted">{{ now()->format('d M Y') }}</span>
        </div>

        <div class="mt-4">
            @if ($member)
                <h5 class="fw-bold">Informasi Member</h5>
                <p><strong>Nomor Telepon:</strong> {{ $member->phone_number ?? '-' }}</p>
                <p><strong>Member Sejak:</strong> {{ $member ? \Carbon\Carbon::parse($member->created_at)->translatedFormat('d F Y') : '-' }}</p>
                <p><strong>Poin Member:</strong>
                    {{ $poinSebelum }}
                </p>
                {{-- <p><strong>Poin Setelah Transaksi:</strong>
                    {{ $member->poin_member }}
                </p> --}}
                
                {{-- <p><strong>Poin Saat Ini:</strong>
                    {{ $poinSaatIni }}
                    <small class="text-success">(Poin Baru: {{ number_format($poinBaru ?? 0, 0, ',', '.') }})</small>
                </p> --}}
            @endif          
        </div>
        <h5 class="fw-bold mt-4">Detail Pembelian</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sellingData as $sell)
                <tr>
                    <td>{{ $sell['product_name'] }}</td>
                    <td>Rp {{ number_format($sell['price'], 0, ',', '.') }}</td>
                    <td>{{ $sell['qty'] }}</td>
                    <td>Rp {{ number_format($sell['sub_total'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row mt-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Poin Digunakan</strong></td>
                        <td class="text-end">{{ $poinUsed ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kasir</strong></td>
                        <td class="text-end fw-bold">{{ $userName }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kembalian</strong></td>
                        <td class="text-end text-success fw-bold">Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="bg-dark text-white p-3 text-center rounded">
                    @if ($member)
                        <h6 class="text-white">Total Sebelum Poin</h6>
                        <h5 class="fw-bold text-white mb-3" style="text-decoration: line-through;">
                            Rp {{ number_format($totalSebelumPoin, 0, ',', '.') }}
                        </h5> 
                    @endif
                    <h5 class="m-0 text-white">TOTAL PRICE</h5>
                    <h3 class="fw-bold text-white" id="total_prices">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h3>
                </div>                
                {{-- <div class="bg-dark text-white p-3 text-center rounded">
                    <h6 class="text-white">Total Sebelum Poin</h6>
                    <h5 class="fw-bold text-white mb-3" style="text-decoration: line-through;">
                        Rp {{ number_format($totalSebelumPoin ?? $totalPrice, 0, ',', '.') }}
                    </h5>                    
                    <h5 class="m-0 text-white">TOTAL PRICE</h5>
                    <h3 class="fw-bold text-white" id="total_prices">Rp {{ number_format($totalPrice, 0, ',', '.') }}</h3>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection