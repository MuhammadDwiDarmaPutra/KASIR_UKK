<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class TransactionImport implements FromArray, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        $query = Transaction::with('member', 'user', 'details.product');

        // FILTER BERDASARKAN REQUEST
        if ($this->request->filled('day')) {
            $query->whereDay('created_at', $this->request->day);
        }
        if ($this->request->filled('month')) {
            $query->whereMonth('created_at', $this->request->month);
        }
        if ($this->request->filled('year')) {
            $query->whereYear('created_at', $this->request->year);
        }

        return $query->get()->map(function ($transaction) {
            return $transaction->details->map(function ($detail) use ($transaction) {
                return [
                    'Tanggal' => $transaction->created_at->format('Y-m-d'),
                    'Nama Member' => $transaction->member ? $transaction->member->name : 'Non Member',
                    'Nomor Telepon' => $transaction->member ? $transaction->member->phone_number : '-',
                    'Total Harga' => number_format($transaction->total_price, 0, ',', '.'),
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