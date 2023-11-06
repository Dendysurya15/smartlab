<?php

if (!function_exists('tanggal_indo')) {
    function tanggal_indo($tanggal, $cetak_hari = false, $cetak_bulan = false)
    {
        $hari = array(
            1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );

        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $split = explode('-', $tanggal);
        $splitted_tgl_jam = explode(' ', $split[2]);
        $tgl_indo = $splitted_tgl_jam[0] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0] . ', ' . $splitted_tgl_jam[1];

        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }

        if ($cetak_bulan) {
            return $bulan[(int)$split[1]] . ' ' . $split[0];
        }
        return $tgl_indo;
    }
}

if (!function_exists('formatNumber')) {
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}
}
if (!function_exists('hitungPPN')) {
    function hitungPPN($angka)
    {
        return ($angka * 11) / 100;
    }
}

if (!function_exists('generateRandomCode')) {
    function generateRandomCode($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Alphanumeric characters
        $code = '';

        // Generate a random code of the specified length
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }
}
