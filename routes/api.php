<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestEmailController;
use App\Mail\SendingEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailPelanggan;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/sendmail', function () {
    $recipients = 'valentinojaja@gmail.com';
    $cc = 'elsamayanti.putrisim@gmail.com';
    $tgl = '02-16-2024';
    $nomor_surat = 'Testtest';
    $nomorlab = 'testomg';
    $randomCode = 'testtest';
    $nomorserif = 'aaaa';
    $departement = 'aaaa';
    $jenis_sampel = 'aaaa';
    $jumlah_sampel = 'aaaa';
    $progress = 'aaaa';
    $kode_tracking_sampel = 'aaaa';
    $id = 32;
    try {
        Mail::to($recipients)
            ->cc($cc)
            ->send(new EmailPelanggan($nomor_surat, $departement, $jenis_sampel, $jumlah_sampel, $progress, $kode_tracking_sampel, $id));

        return "Email sent successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
Route::get('testing', function () {
    return 'hello';
});
