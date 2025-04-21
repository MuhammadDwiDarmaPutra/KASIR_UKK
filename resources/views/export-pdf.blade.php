<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #INV001</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }
        h2, h4 {
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 6px 8px;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: #f3f3f3;
            text-align: left;
        }
        .summary {
            margin-top: 10px;
        }
        .summary td {
            padding: 4px 8px;
        }
        .total-row {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .thanks {
            margin-top: 30px;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>
    <h2><strong>Neo staff</strong></h2>

    <div class="info">
        <p>Member Status : Member</p>
        <p>No. HP : 08123456789</p>
        <p>Bergabung Sejak : 11 April 2023</p>
        <p>Poin Member : 200</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Produk A</td>
                <td>2</td>
                <td>Rp. 10.000</td>
                <td>Rp. 20.000</td>
            </tr>
            <tr>
                <td>Produk B</td>
                <td>1</td>
                <td>Rp. 15.000</td>
                <td>Rp. 15.000</td>
            </tr>
            <!-- Tambah produk lain di sini -->
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td>Poin Digunakan</td>
            <td class="text-right">500</td>
        </tr>
        <tr class="total-row">
            <td>Total Harga</td>
            <td class="text-right">Rp. 35.000</td>
        </tr>
        <tr class="total-row">
            <td>Harga Setelah Poin</td>
            <td class="text-right">Rp. 34.500</td>
        </tr>
        <tr class="total-row">
            <td>Jumlah Bayar</td>
            <td class="text-right">Rp. 35.000</td>
        </tr>
        <tr class="total-row">
            <td>Total Kembalian</td>
            <td class="text-right">Rp. 500</td>
        </tr>
    </table>

    <div class="footer">
        2025-04-11 10:20:00 | Petugas: Rivaldo
    </div>

    <div class="thanks">
        Terima kasih atas pembelian Anda!
    </div>
</body>
</html>
