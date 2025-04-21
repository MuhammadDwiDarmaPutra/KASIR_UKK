<?php

use App\Exports\TransactionImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::put('/product/{id}/updateStock', [ProductController::class, 'updateStock']);
    Route::post('/storeCart', [TransactionController::class, 'cart'])->name('store.cart');
    Route::get('/transaction/{id}/pdf', [TransactionController::class, 'CetakPdf'])->name('formatpdf');
    Route::get('/result/{transactionId}', [TransactionController::class, 'result'])->name('result');
    Route::patch('/orderMember', [TransactionController::class, 'checkMember'])->name('orderMember');
    Route::resource('pembelians', TransactionController::class);
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/export-excel', function () {
        return Excel::download(new TransactionImport, 'selling.xlsx');
    })->name('formatexcel');
});

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [UserController::class, 'authLogin'])->name('login.auth');
});