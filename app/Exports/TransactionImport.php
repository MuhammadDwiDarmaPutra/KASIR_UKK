<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionImport implements FromArray, WithHeadings
{
    /**
    * @return array
    */
    public function array(): array
    {
        return Transaction::with('member', 'user', 'details.product')->get()->map(function ($transaction) {
            return $transaction->details->map(function ($detail) use ($transaction) {
                return [
                    'Tanggal' => $transaction->created_at->format('Y-m-d'),
                    'Nama Member' => $transaction->member ? $transaction->member->name : 'Non Member',
                    'Nomor Telepon' => $transaction->member ? $transaction->member->phone_number : '-',
                    'Total Harga' => $transaction->total_price,
                    'Nama Kasir' => $transaction->user->name,
                    'Product' => $detail->product->name,
                    'Qty' => $detail->qty,
                    'Poin Yang Digunakan' => $transaction->poin ?? 0,
                    'Total Bayar' => number_format($transaction->total_pay, 0, ',', '.'),
                    'Total Diskon' => number_format(($transaction->total_price ?? 0) - ($transaction->total_pay ?? 0), 0, ',', '.'),
                    'Total Kembalian' => number_format($transaction->total_return ?? 0, 0, ',', '.'),
                ];
            });
        })->flatten(1)->toArray();
    }

    /**
    * Define the header row.
    *
    * @return array
    */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Member',
            'Nomor Telepon',
            'Total Harga',
            'Nama Kasir',
            'Product',
            'Qty',
            'Poin Yang Digunakan',
            'Total Bayar',
            'Total Diskon',
            'Total Kembalian',
        ];
    }
}
