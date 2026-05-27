<?php

use App\Http\Controllers\AdminCrudController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/data')->name('admin.')->group(function () {
    Route::get('/', [AdminCrudController::class, 'dashboard'])->name('dashboard');
    Route::get('{table}/create', [AdminCrudController::class, 'create'])->name('table.create');
    Route::post('{table}', [AdminCrudController::class, 'store'])->name('table.store');
    Route::get('{table}/{key}/edit', [AdminCrudController::class, 'edit'])->name('table.edit');
    Route::put('{table}/{key}', [AdminCrudController::class, 'update'])->name('table.update');
    Route::delete('{table}/{key}', [AdminCrudController::class, 'destroy'])->name('table.destroy');
    Route::get('{table}', [AdminCrudController::class, 'index'])->name('table.index');
});
