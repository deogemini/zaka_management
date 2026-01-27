<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KandaController;
use App\Http\Controllers\JumuiyaController;
use App\Http\Controllers\MwanajumuiyaController;
use App\Http\Controllers\ZakaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('kandas', KandaController::class);
    Route::resource('jumuiyas', JumuiyaController::class);
    Route::get('mwanajumuiya/import', [MwanajumuiyaController::class, 'importForm'])->name('mwanajumuiya.import.form');
    Route::post('mwanajumuiya/import', [MwanajumuiyaController::class, 'import'])->name('mwanajumuiya.import');
    Route::get('mwanajumuiya/sample', [MwanajumuiyaController::class, 'sample'])->name('mwanajumuiya.sample');
    Route::get('mwanajumuiya/export', [MwanajumuiyaController::class, 'export'])->name('mwanajumuiya.export');
    Route::resource('mwanajumuiya', MwanajumuiyaController::class);

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::resource('users', UserController::class);
    });
    Route::get('zakas/import', [ZakaController::class, 'importForm'])->name('zakas.import.form');
    Route::post('zakas/import', [ZakaController::class, 'import'])->name('zakas.import');
    Route::get('zakas/sample', [ZakaController::class, 'sample'])->name('zakas.sample');
    Route::resource('zakas', ZakaController::class)->where(['zaka' => '[0-9]+']);

    Route::get('reports/zaka', [App\Http\Controllers\ReportController::class, 'zaka'])->name('reports.zaka');
    Route::get('reports/jumuiya', [App\Http\Controllers\ReportController::class, 'jumuiya'])->name('reports.jumuiya');
    Route::get('reports/kanda', [App\Http\Controllers\ReportController::class, 'kanda'])->name('reports.kanda');
});

require __DIR__.'/auth.php';
