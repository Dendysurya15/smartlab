<?php

namespace App\Http\Controllers;

use App\Models\JenisSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackSampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrackSampelController extends Controller
{
    public function index()
    {
        //

        return view('pages/trackingSampel/index');
    }

    public function search(Request $request)
    {
        $kode = $request->get('kode');

        if (!$kode) {
            return redirect('/')->with('error', 'Kode tracking harus diisi');
        }

        try {
            // Cari data berdasarkan kode tracking
            $trackSampel = TrackSampel::where('kode_tracking_sampel', $kode)->first();

            if (!$trackSampel) {
                return redirect('/')->with('error', 'Kode tracking tidak ditemukan');
            }

            // Ambil progress pengerjaan
            $progressData = ProgressPengerjaan::where('kode_tracking_sampel', $kode)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('pages.tracking-result', compact('trackSampel', 'progressData', 'kode'));
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Terjadi kesalahan saat mencari data tracking');
        }
    }

    public function unblockIp(Request $request)
    {
        $ip = $request->ip(); // Or set a specific IP if needed
        Cache::forget($ip . '_failed_attempts');
        Cache::forget($ip . '_blocked');
        return response()->json(['message' => 'IP unblocked']);
    }
}
