<?php

use App\Events\Smartlabsnotification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryKupaController;
use App\Http\Controllers\InputProgressController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TrackSampelController;
use App\Http\Controllers\ExcelmanagementController;
use App\Http\Controllers\Kuesionerroot;
use App\Http\Middleware\TrackCaptchaFailures;
use App\Livewire\Kuesionerdata;
use App\Livewire\Managementkuesioner;
use Illuminate\Support\Facades\Artisan;
use App\Http\Middleware\BlockIpAfterFailedAttempts;
use App\Livewire\Trackingprogres;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\PdfController;
use Illuminate\Http\Request;
use App\Jobs\Generatebulkpdfpr;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');
Route::get('unblock', [Trackingprogres::class, 'unblockIp']);
Route::get('/blocked', function () {
    $exception = new \Exception('Your IP is blocked due to multiple failed attempts.');
    return view('errors.403', compact('exception'))->with('error', 'Your IP is blocked due to multiple failed attempts.');
})->name('blocked');

// Route::get('tracking_sampel', [TrackSampelController::class, 'index']);
// Route::get('tracking_sampels/{id}', [TrackSampelController::class, 'searchbyid']);
// Route::post('search_sampel_progress', [TrackSampelController::class, 'search'])->name('search_sampel_progress');
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});
Route::get('kuesioner', Kuesionerdata::class);
// Route::get('kuesioner', [Kuesionerroot::class, 'index'])->middleware([TrackCaptchaFailures::class]);
Route::get('kuesioner', [Kuesionerroot::class, 'index']);
Route::get('flush', [TrackSampelController::class, 'unblockIp']);
Route::get('/seedroles', function () {
    Artisan::call('db:seed', [
        '--class' => 'RolesSeeder',
    ]);
    return "Roles seeded successfully!";
});
Route::get('/export-excel/{id}', [HistoryKupaController::class, 'exportExcel'])
    ->name('export.excel');
Route::get('exporpdfkupa/{id}/{filename}', [HistoryKupaController::class, 'export_kupa_pdf'])->name('exporpdfkupa');
Route::middleware(['auth:sanctum', 'verified'])->group(function () {


    Route::get('/export-form-monitoring-kupa/{id}', [HistoryKupaController::class, 'exportFormMonitoringKupa'])
        ->name('export.form-monitoring-kupa');
    Route::get('/export-monotoringbulk/{id}', [HistoryKupaController::class, 'exportFormMonitoringKupabulk'])
        ->name('export.monotoringbulk');

    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('input_progress', InputProgressController::class);
    Route::resource('history_sampel', HistoryKupaController::class);
    Route::get('get-progress-options', [HistoryKupaController::class, 'getProgressOptions']);
    Route::get('exportvr/{id}', [HistoryKupaController::class, 'exportvr'])->name('exportvr');
    Route::get('exporpdfform/{id}/{filename}', [HistoryKupaController::class, 'export_form_pdf'])->name('exporpdfform');
    Route::get('exporpdfpr/{id}/{filename}', [HistoryKupaController::class, 'export_pr_pdf'])->name('exporpdfpr');
    Route::get('exportpdf_kuesiner/{id}/{filename}', [HistoryKupaController::class, 'exportpdf_kuesiner'])->name('exportpdf_kuesiner');
    Route::get('export_persurat/{id}/{filename}', [HistoryKupaController::class, 'export_persurat'])->name('exportpersurat');
    Route::get('exportexcel', function () {
        return view('excelView.exportotexcel');
    });
    Route::get('exportdocumentasi/{id}/{filename}', [HistoryKupaController::class, 'export_dokumentasi'])->name('exportdokumntasi');
    Route::fallback(function () {
        return view('pages/utility/404');
    });
    Route::get('/testing', function () {
        return view('layouts.email');
    });

    Route::resource('system', SystemController::class);
    Route::get('/roles', [SystemController::class, 'roles'])->name('roles');
    Route::get('/excelsettings', [ExcelmanagementController::class, 'index'])->name('excelsettings');

    Route::post('delete-data/{id}', [SystemController::class, 'delete_parameter_and_metode'])->name('delete-data');
    Route::get('/Managementkuesioner', function () {
        return view('pages.managementkuesioner');
    })->name('Managementkuesioner');

    Route::get('/404', function () {
        return view('errorpages.404');
    })->name('404');

    // Route::get('/download-bulk-pdf/{filename}', [PdfController::class, 'downloadBulkPdf'])
    //     ->name('download.bulk.pdf');
});
