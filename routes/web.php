<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistorySampelController;
use App\Http\Controllers\InputProgressController;
use App\Http\Controllers\TrackSampelController;
use App\Http\Controllers\SystemController;
use App\Mail\EmailPelanggan;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'login');
Route::get('tracking_sampel', [TrackSampelController::class, 'index']);
Route::post('search_sampel_progress', [TrackSampelController::class, 'search'])->name('search_sampel_progress');

// Route::get('send-email', function () {
//     $recipients = ['dendysurya15@gmail.com'];
//     $cc = ['valentinojaja@gmail.com'];

//     try {
//         Mail::to($recipients)
//             ->cc($cc)
//             ->send(new EmailPelanggan());

//         return "Email sent successfully!";
//     } catch (\Exception $e) {
//         return "Error: " . $e->getMessage();
//     }
// });
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Route for the getting the data feed
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::fallback(function () {
        return view('pages/utility/404');
    });
    Route::resource('input_progress', InputProgressController::class);
    Route::resource('history_sampel', HistorySampelController::class);
    Route::get('get-progress-options', [HistorySampelController::class, 'getProgressOptions']);
    Route::get('exportexcel', function () {
        return view('excelView.exportotexcel');
    });

    Route::resource('system', SystemController::class);
});
