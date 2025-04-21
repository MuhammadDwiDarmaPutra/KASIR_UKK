@extends('main')
@section('title', 'Member Page')
@section('breadcrumb', 'Member')
@section('page-title', 'Member')

@section('content')
<div class="container mt-4">
    <div class="card p-4">
        <h4 class="fw-semibold">Produk yang dipilih</h4>
        <ul class="list-unstyled">
            @foreach ($cartItems as $item)
                <li class="d-flex justify-content-between mt-2">
                    <div>
                        <span class="fw-semibold">{{ $item->product->name }}</span>
                        <br>
                        <small class="text-muted">Rp. {{ number_format($item->product->price, 0, ',', '.') }} x {{ $item->qty }}</small>
                    </div>
                    <span class="fw-semibold">Rp. {{ number_format($item->product->price * $item->qty, 0, ',', '.') }}</span>
                </li>
                @endforeach
        </ul>
        <hr>
        {{-- <div class="d-flex justify-content-between">
            <strong>Total</strong>
            <strong>Rp. {{ number_format($totalPrice, 0, ',', '.') }}</strong>
        </div> --}}
        <div class="d-flex justify-content-between">
            <strong>Total</strong>
            <strong id="totalHarga">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</strong>
        </div>        
        <hr>
        <form action="{{ route('pembelians.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="memberStatus" class="form-label">Member Status</label>
                <select class="form-select" id="memberStatus" name="member">
                    <option selected disabled>Pilih Tipe</option>
                    <option value="non-member">Bukan Member</option>
                    <option value="member">Member</option>
                </select>
            </div>
            <div class="mb-3 d-none" id="phoneInput">
                <label for="phoneNumber" class="form-label">Number Phone</label>
                <input type="number" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Masukkan nomor telepon">
            </div>
            <div class="mb-3">
                <label for="totalBayar" class="form-label">Total Bayar</label>
                <input type="text" class="form-control" id="totalBayar" name="total_bayar" placeholder="Masukkan jumlah pembayaran">
                <small class="text-danger d-none" id="errorBayar">Jumlah bayar kurang!</small>
            </div>
            <button class="btn btn-primary w-100" type="submit" id="btnPesan">Pesan</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('memberStatus').addEventListener('change', function () {
        let phoneInput = document.getElementById('phoneInput');
        if (this.value === 'member') {
            phoneInput.classList.remove('d-none');
        } else {
            phoneInput.classList.add('d-none');
            document.getElementById('phoneNumber').value = '';
        }
    });

    const totalBayarInput = document.getElementById('totalBayar');
    const totalHarga = {{ $totalPrice }};
    const btnPesan = document.getElementById('btnPesan');
    const errorBayar = document.getElementById('errorBayar');

    totalBayarInput.addEventListener('input', function () {
        let bayar = parseInt(this.value) || 0;

        if (bayar < totalHarga) {
            errorBayar.classList.remove('d-none');
            btnPesan.disabled = true;
        } else {
            errorBayar.classList.add('d-none');
            btnPesan.disabled = false;
        }
    });
</script>


{{-- <script>
    document.getElementById('memberStatus').addEventListener('change', function() {
        let phoneInput = document.getElementById('phoneInput')
        if(this.value === 'member'){
            phoneInput.classList.remove('d-none')
        }else{
            phoneInput.classList.add('d-none')
            document.getElementById('phoneNumber').value = ''
        }
    })
</script> --}}
@endsection