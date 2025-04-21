<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Member;
use App\Models\Product;
use App\Models\DetailOrder;
use App\Models\Transaction;
use App\Exports\TransactionImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

public function index(Request $request)
{
    $limit = $request->limit ?? 10;

    $query = Transaction::with('user', 'member', 'details.product')->latest();

    // Filter berdasarkan nama member
    if ($request->has('search') && $request->search != '') {
        $query->whereHas('member', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // Filter berdasarkan hariF
    if ($request->has('day') && $request->day != '') {
        $query->whereDay('created_at', $request->day);
    }

    // Filter berdasarkan bulan
    if ($request->has('month') && $request->month != '') {
        $query->whereMonth('created_at', $request->month);
    }

    // Filter berdasarkan tahun
    if ($request->has('year') && $request->year != '') {
        $query->whereYear('created_at', $request->year);
    }

    $transaction = $query->paginate($limit)->appends($request->all());

    return view('pembelian.index', compact('transaction'));
}

    public function search(Request $request) {
        $search = Transaction::where('member_id', $request->search)->first();
        if ($search == null) {
            $search = Transaction::where('user_id', $request->search)->first();
        }
        return view('pembelian.index', compact('search'));
    }

    public function create()
    {
        session()->forget('cart');
        $products = Product::all();
        return view('pembelian.tambah', compact('products'));
    }

    public function cart(Request $request) {
        $request->validate([
            'cart_data' => 'required|json'
        ]);
        
        $user = Auth::user();
        Cart::where('user_id', $user->id)->delete();
        
        $cartItem = json_decode($request->cart_data, true);
        foreach ($cartItem as $productList => $qty) {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productList,
                'qty' => $qty,
            ]);
        }
        
        $cartItems = Cart::where('user_id', $user->id)->get();
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->qty;
        }

        session([
            'cartItems' => $cartItems->map(function ($item) {
                return [
                    'product' => [
                        'name' => $item->product->name,
                        'price' => $item->product->price,
                    ],
                    'qty' => $item->qty
                ];
            }),
            'totalPrice' => $totalPrice
        ]);

        return view('pembelian.member', compact('cartItems', 'totalPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member' => 'required|in:non-member,member',
            'total_bayar' => 'required|numeric',
        ]);

        $user = Auth::user();
        $carts = Cart::with('product')->where('user_id', $user->id)->get();
        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price * $cart->qty;
        }
        $kembalian = $request->total_bayar - $totalPrice;

        if ($request->member == 'member') {
            $request->validate(['phoneNumber' => 'required|numeric']);
            $phonenumber = $request->phoneNumber;
            $member = Member::where('phone_number', $phonenumber)->first();
            if (!$member) {
                $poinmember = $totalPrice * 1 / 100;
                $member = Member::create([
                    'phone_number' => $phonenumber,
                    'poin_member' => $poinmember,
                ]);
            }

            $sellingData = [];
            foreach ($carts as $cart) {
                $subtotal = $cart->product->price * $cart->qty;
                $sellingData[] = [
                    'product_name' => $cart->product->name,
                    'price' => $cart->product->price,
                    'qty' => $cart->qty,
                    'subtotal' => $subtotal,
                ];
            }

            $checkpoin = $member ? Transaction::where('member_id', $member->id)->count() : 0;

            return view('pembelian.checkMember', [
                'dataTransaction' => $sellingData,
                'member' => $member,
                'totalBayar' => $request->total_bayar,
                'subtotal' => $totalPrice,
                'poinmember' => $member->poin_member,
                'checkPoint' => $checkpoin
            ]);            
        }

        $sellingData = [];
        $transaction = Transaction::create([
            'member_id' => null,
            'user_id' => $user->id,
            'poin' => 0,
            'total_poin' => 0,
            'total_pay' => $request->total_bayar,
            'total_return' => $kembalian,
            'total_price' => $totalPrice,
        ]);

        foreach ($carts as $cart) {
            DetailOrder::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product->id,
                'qty' => $cart->qty,
                'sub_total' => $cart->product->price * $cart->qty,
            ]);

            $product = Product::find($cart->product->id);
            $product->stock -= $cart->qty;
            $product->save();

            $sellingData[] = [
                'product_name' => $cart->product->name,
                'price' => $cart->product->price,
                'qty' => $cart->qty,
                'sub_total' => $cart->product->price * $cart->qty,
            ];
        }

        Cart::where('user_id', $user->id)->delete();
        $invoiceNumber = Transaction::count();

        return view('pembelian.result', [
            'sellingData' => $sellingData,
            'totalPrice' => $totalPrice,
            'userName' => $user->name,
            'kembalian' => $kembalian,
            'invoiceNumber' => $invoiceNumber,
            'poinUsed' => 0,
            'transactionId' => $transaction->id,
            'member' => null,
            'poinSaatIni' => 0,
            
            'totalSebelumPoin' => $totalPrice
        ]);        
    }
    
public function checkMember(Request $request)
{
    $user = Auth::user();
    $member = Member::where('phone_number', $request->phone_number)->first();

    // Simpan poin sebelum transaksi
    $poinSebelum = $member ? $member->poin_member : 0;

    // Update nama member jika tersedia
    if ($member && $request->filled('name')) {
        $member->name = $request->name;
        $member->save();
    }

    // Ambil cart
    $carts = Cart::with('product')->where('user_id', $user->id)->get();

    // Hitung total harga
    $totalSebelumPoin = $carts->sum(fn($cart) => $cart->product->price * $cart->qty);
    $totalPrice = $totalSebelumPoin;

    $poinUsed = 0;

    if ($member) {
        if ($request->checkPoin) {
            // Jika menggunakan poin lama
            $poinUsed = min($member->poin_member, $totalPrice); // Gunakan poin maksimal sesuai total harga
            $totalPrice -= $poinUsed; // Kurangi total harga dengan poin yang digunakan
            $member->poin_member = 0; // Reset poin lama
            $member->save();
        }

        // Tambahkan poin baru ke poin lama
        $poinBaru = floor($totalSebelumPoin / 100);
        $member->poin_member += $poinBaru;
        $member->save();
    } else {
        // Jika member baru
        $member = Member::create([
            'phone_number' => $request->phone_number,
            'poin_member' => floor($totalSebelumPoin / 100),
        ]);
    }

    // Hitung kembalian
    $kembalian = $request->total_bayar - $totalPrice;

    // Kurangi poin sebelum transaksi dengan poin yang digunakan
    $poinSebelum -= $poinUsed;

    // Simpan transaksi
    $transaction = Transaction::create([
        'member_id' => $member->id,
        'user_id' => $user->id,
        'poin' => $poinUsed,
        'total_poin' => $member->poin_member,
        'total_pay' => $request->total_bayar,
        'total_return' => $kembalian,
        'total_price' => $totalPrice,
    ]);

    // Simpan detail pesanan & update stok
    foreach ($carts as $cart) {
        DetailOrder::create([
            'transaction_id' => $transaction->id,
            'product_id' => $cart->product->id,
            'qty' => $cart->qty,
            'sub_total' => $cart->product->price * $cart->qty,
        ]);

        $cart->product->decrement('stock', $cart->qty);
    }

    // Kosongkan cart
    Cart::where('user_id', $user->id)->delete();

    // Redirect ke halaman result setelah transaksi selesai
    return redirect()->route('result', [
        'transactionId' => $transaction->id,
        'poinSebelum' => $poinSebelum, // Kirim poin sebelum transaksi
    ]);
}

public function result($transactionId, Request $request)
{
    $transaction = Transaction::with('member')->findOrFail($transactionId);

    return view('pembelian.result', [
        'sellingData' => $transaction->details->map(function ($detail) {
            return [
                'product_name' => $detail->product->name,
                'price' => $detail->product->price,
                'qty' => $detail->qty,
                'sub_total' => $detail->sub_total,
            ];
        }),
        'totalPrice' => $transaction->total_price,
        'userName' => $transaction->user->name,
        'totalSebelumPoin' => $transaction->total_price + $transaction->poin, // Total harga asli sebelum poin digunakan
        'kembalian' => $transaction->total_return,
        'poinUsed' => $transaction->poin,
        'invoiceNumber' => $transaction->id,
        'transactionId' => $transaction->id,
        'member' => $transaction->member->fresh(), // Ambil data terbaru dari database
        'poinSebelum' => $request->poinSebelum ?? $transaction->member->poin_member, // Ambil poin sebelum transaksi
    ]);
}

    public function CetakPdf(Request $request, $id)
    {
        $transaction = Transaction::where('id', $id)->with('user', 'member', 'details.product')->first();
        $data = [
            'transaction' => $transaction,
            'member' => $transaction->member,
            'details' => $transaction->details,
        ];

        $pdf = Pdf::loadView('pembelian.invoice', $data);
        return $pdf->stream('bukti-pembelian.pdf');
    }

    public function exportExcel(Request $request)
{
    return Excel::download(new TransactionImport($request), 'penjualan.xlsx');
}
}