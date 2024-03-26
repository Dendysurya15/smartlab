<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class checkbot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:checkbot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Illuminate\Support\Facades\Log::info('Task executed at: ' . now());

        $ch = curl_init('https://digi-kappa-lac.vercel.app/api/cronjob');

        // Set cURL options for POST request
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute POST request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            \Illuminate\Support\Facades\Log::error('cURL Error: ' . curl_error($ch));
        } else {
            \Illuminate\Support\Facades\Log::info('cURL Response: ' . $response);
        }

        // Close cURL session
        curl_close($ch);
    }
}
