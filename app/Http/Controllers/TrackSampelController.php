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

    public function unblockIp(Request $request)
    {
        $ip = $request->ip(); // Or set a specific IP if needed
        Cache::forget($ip . '_failed_attempts');
        Cache::forget($ip . '_blocked');
        return response()->json(['message' => 'IP unblocked']);
    }
}
