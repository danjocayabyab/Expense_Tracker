<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('transactions.index');
});
Route::get('transactions/summary', [TransactionController::class, 'summary'])->name('transactions.summary');
Route::get('transactions/export/csv', [TransactionController::class, 'exportCsv'])->name('transactions.export.csv');
Route::resource('transactions', TransactionController::class);
