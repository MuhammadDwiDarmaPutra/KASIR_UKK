<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('User.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('User.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,kasir',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);
        return redirect()->route('users.index')->with('success', 'Berhasil Menambahkan User');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('User.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'role' => 'required|in:admin,kasir',
            'password' => 'nullable|min:6'
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Berhasil memperbarui user');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function authLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'email atau password salah']);
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout');
    }

    public function dashboard(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $transaction = Transaction::whereBetween('created_at', [$today, $tomorrow])->get();
        $count = $transaction->count();

        $updated = Transaction::orderBy('created_at', 'asc')->first();
        $member = Transaction::whereNotNull('member_id')->count();
        $nonMember = Transaction::where('member_id', null)->count();

        $dailySales = Transaction::selectRaw('DAY(created_at) as day, COUNT(*) as total')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $productSales = Product::with('details')
        ->select('name')
        ->withSum('details', 'qty')
        ->get()
        ->pluck('details_sum_qty', 'name');

        $daysInMonth = now()->daysInMonth;
        $labels = [];
        $dailyCounts = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::createFromDate(now()->year, now()->month, $i);
            $labels[] = $date->format('d M Y'); // contoh: 13 Apr 2025
            $dailyCounts[] = $dailySales->get($i, 0);
        }        

        return view('dashboard', compact(
            'count',
            'updated',
            'transaction',
            'member',
            'nonMember',
            'labels',
            'dailyCounts',
            'productSales'
        ));
    }

}
