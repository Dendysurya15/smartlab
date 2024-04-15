<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\HistoryKupaController;
use App\Http\Controllers\InputProgressController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TrackSampelController;
use App\Http\Controllers\UserRoleController;


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
Route::get('tracking_sampel', [TrackSampelController::class, 'index']);
Route::get('tracking_sampels/{id}', [TrackSampelController::class, 'searchbyid']);
Route::post('search_sampel_progress', [TrackSampelController::class, 'search'])->name('search_sampel_progress');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/export-excel/{id}', [HistoryKupaController::class, 'exportExcel'])
        ->name('export.excel');
    Route::get('/export-form-monitoring-kupa/{id}', [HistoryKupaController::class, 'exportFormMonitoringKupa'])
        ->name('export.form-monitoring-kupa');
    Route::get('/export-monotoringbulk/{id}', [HistoryKupaController::class, 'exportFormMonitoringKupabulk'])
        ->name('export.monotoringbulk');

    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('input_progress', InputProgressController::class);
    Route::resource('history_sampel', HistoryKupaController::class);
    Route::get('get-progress-options', [HistoryKupaController::class, 'getProgressOptions']);
    Route::get('exportexcel', function () {
        return view('excelView.exportotexcel');
    });

    Route::fallback(function () {
        return view('pages/utility/404');
    });
    Route::get('/testing', function () {
        return view('layouts.email');
    });

    Route::resource('system', SystemController::class);
    Route::get('/roles', [SystemController::class, 'roles'])->name('roles');

    Route::post('delete-data/{id}', [SystemController::class, 'delete_parameter_and_metode'])->name('delete-data');
});
