<?php

use App\Http\Controllers\ProgressController;
use App\Http\Controllers\TargetController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->middleware('guest')->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'targets' => Auth::user()->targets()->latest()->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('targets', TargetController::class);
    Route::post('/targets/{target}/progress', [ProgressController::class, 'store'])->name('targets.progress.store');
    Route::get('/targets/{target}/progress/chart', [ProgressController::class, 'getChartData'])->name('targets.progress.chart');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
