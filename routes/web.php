<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KandaController;
use App\Http\Controllers\JumuiyaController;
use App\Http\Controllers\MwanajumuiyaController;
use App\Http\Controllers\ZakaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SmsSettingController;
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
        Route::get('audit-trails', [App\Http\Controllers\AuditTrailController::class, 'index'])->name('audit_trails.index');
        Route::get('settings/sms', [SmsSettingController::class, 'index'])->name('settings.sms.index');
        Route::put('settings/sms', [SmsSettingController::class, 'update'])->name('settings.sms.update');
    });
    Route::resource('watotos', App\Http\Controllers\WatotoController::class);
    Route::get('zakas/import', [ZakaController::class, 'importForm'])->name('zakas.import.form');
    Route::post('zakas/import', [ZakaController::class, 'import'])->name('zakas.import');
    Route::get('zakas/sample', [ZakaController::class, 'sample'])->name('zakas.sample');
    Route::post('zakas/{zaka}/resend-sms', [ZakaController::class, 'resendSms'])->name('zakas.resend-sms');
    Route::resource('zakas', ZakaController::class)->where(['zaka' => '[0-9]+']);

    Route::get('reports/zaka', [App\Http\Controllers\ReportController::class, 'zaka'])->name('reports.zaka');
    Route::get('reports/zaka/export', [App\Http\Controllers\ReportController::class, 'zakaExport'])->name('reports.zaka.export');
    Route::get('reports/jumuiya', [App\Http\Controllers\ReportController::class, 'jumuiya'])->name('reports.jumuiya');
    Route::get('reports/jumuiya/export', [App\Http\Controllers\ReportController::class, 'jumuiyaExport'])->name('reports.jumuiya.export');
    Route::get('reports/kanda', [App\Http\Controllers\ReportController::class, 'kanda'])->name('reports.kanda');
    Route::get('reports/kanda/export', [App\Http\Controllers\ReportController::class, 'kandaExport'])->name('reports.kanda.export');
    Route::get('reports/mwanajumuiya', [App\Http\Controllers\ReportController::class, 'mwanajumuiya'])->name('reports.mwanajumuiya');
    Route::get('reports/mwanajumuiya/export', [App\Http\Controllers\ReportController::class, 'mwanajumuiyaExport'])->name('reports.mwanajumuiya.export');
    Route::resource('shukranis', App\Http\Controllers\ShukraniController::class);
});

require __DIR__.'/auth.php';
