<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PhoneController;
use App\Http\Controllers\Admin\RepairPartController;
use App\Http\Controllers\Admin\RepairController; // Added import

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('phones', PhoneController::class);
    Route::resource('repair-parts', RepairPartController::class);
    Route::resource('repairs', RepairController::class); // Added Repair Resource Route

    // Custom routes for managing parts on a repair
    Route::post('repairs/{repair}/add-part', [RepairController::class, 'addPart'])->name('repairs.addPart');
    Route::delete('repairs/{repair}/remove-part/{repairJobPart}', [RepairController::class, 'removePart'])->name('repairs.removePart');
});

require __DIR__.'/auth.php';
