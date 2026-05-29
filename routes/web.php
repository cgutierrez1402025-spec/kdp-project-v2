<?php

use App\Http\Controllers\AdminCrudController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/data')->name('admin.')->group(function () {
    Route::get('/', [AdminCrudController::class, 'dashboard'])->name('dashboard');
    // Specific routes must come before parameterized ones to avoid conflicts
    Route::get('{table}/create', [AdminCrudController::class, 'create'])->name('table.create')->where('table', '[a-z_]+');
    Route::post('{table}', [AdminCrudController::class, 'store'])->name('table.store')->where('table', '[a-z_]+');
    Route::get('{table}/{key}/edit', [AdminCrudController::class, 'edit'])->name('table.edit')->where('table', '[a-z_]+');
    Route::put('{table}/{key}', [AdminCrudController::class, 'update'])->name('table.update')->where('table', '[a-z_]+');
    Route::delete('{table}/{key}', [AdminCrudController::class, 'destroy'])->name('table.destroy')->where('table', '[a-z_]+');
    Route::get('{table}', [AdminCrudController::class, 'index'])->name('table.index')->where('table', '[a-z_]+');
});
