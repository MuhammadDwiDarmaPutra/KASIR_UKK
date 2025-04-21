<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            max-width: 800px;
            margin: auto;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            width: 150px;
        }

        h1 {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tfoot th {
            text-align: right;
        }

        .notes {
            margin-top: 20px;
            font-size: 14px;
        }

        address {
            margin-top: 20px;
            font-size: 14px;
            font-style: normal;
        }
    </style>
</head>

<body>
    <h1>Invoice - #1</h1>
    <p>Member Name : {{ $member ? $member->name : 'Non Member' }}</p>
    <p>No. HP : {{ $member ? $member->phone_number : '-' }}</p>
    <p>Bergabung Sejak : {{ $member ? $member->created_at->format('d F Y') : '-' }}</p>
    <p>Point Yang Digunakan : {{ $transaction->poin ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Sub total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>Rp.{{ number_format($item->product->price, 0, ',', '.') }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp.{{ number_format($item->qty * $item->product->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Poin Member</th>
                <td>{{ $member ? $member->poin_member : '-' }}</td>
            </tr>
            <tr>
                <th colspan="3">Tunai</th>
                <td>Rp.{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3">Kembalian</th>
                <td>Rp.{{ number_format($transaction->total_return, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3">Total</th>
                <td>Rp.{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        </table>
        <p><strong>Dibuat oleh:</strong> {{ $transaction->user->name }}</p>
        <div class="notes">
            Terima kasih atas pembelian Anda.
        </div>
        <hr>
        <address>
            Kasirku.<br>
            Alamat: Wangun Tengah, Sindangsari, Bogor Timur<br>
            Email: kasir@gmail.com
        </address>

</body>

</html>
