<?php

use Spatie\Permission\Models\Role;

if (!function_exists('tanggal_indo')) {
    function tanggal_indo($tanggal, $cetak_hari = false, $cetak_bulan = false, $cetak_tanggal = false)
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

        if ($cetak_tanggal) {
            return $splitted_tgl_jam[0] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
        }
        return $tgl_indo;
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        return number_format($number, 0, ',', '.');
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('hitungPPN')) {
    function hitungPPN($angka)
    {
        return ($angka * 11) / 100;
    }
}
if (!function_exists('numberformat')) {
    function numberformat($number)
    {
        // Remove any non-numeric characters from the input number
        $number = preg_replace('/\D/', '', $number);

        // Check if the number starts with '0'
        if (strpos($number, '0') === 0) {
            // Replace '0' with '62'
            return '62' . substr($number, 1);
        } else if (strpos($number, '8') === 0) {
            // Replace '0' with '62'
            return '62' . $number;
        } else {
            // If it doesn't start with '0', return as is
            return $number;
        }
    }
}


if (!function_exists('array_email')) {
    function array_email($input)
    {
        $delimiters = [";", ",", " "];
        $emailArray = preg_split('/[' . implode('', $delimiters) . ']/', $input, -1, PREG_SPLIT_NO_EMPTY);

        // Trim each email address to remove any leading or trailing whitespaces
        $emailArray = array_map('trim', $emailArray);

        // Filter out invalid email addresses
        $emailArray = array_filter($emailArray, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        });

        return $emailArray;
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

if (!function_exists('checkApprovedKupa')) {
    function checkApprovedKupa($role, $record)
    {
        $lastApprovedBy = $record->status_approved_by_role;
        $statusKupa = $record->status;

        if ($lastApprovedBy != null && $statusKupa == 'Approved') {

            $alurApproved = Role::where('name', '<>', 'superuser')->orderBy('alur_approved')->pluck('name')->toArray();
            $staffIndex = array_search($lastApprovedBy, $alurApproved);
            $result = array_slice($alurApproved, $staffIndex + 1);
            if (isset($result[0])) {
                $canApprovedNowBy = $result[0];
                if ($canApprovedNowBy == $role) {
                    return False;
                } else {
                    return True;
                }
            } else {
                return True;
            }
        } else {
            if ($statusKupa == 'Draft') {
                return True;
            } else if ($statusKupa == 'Waiting Approved' && $role == 'Admin') {
                return False;
            } else {
                return True;
            }
        }
    }
}


if (!function_exists('checkApprovedLabelKupa')) {
    function checkApprovedLabelKupa($record)
    {
        $alurApproved = Role::where('name', '<>', 'superuser')->orderBy('alur_approved')->pluck('name')->toArray();
        $kupaFinishBy = last($alurApproved);
        $roles = auth()->user()->roles[0]->name;
        // dd($alurApproved);
        $main_title = 'Verifikasi Status';

        if ($record->status == 'Rejected') {
            return $main_title . ' (Rejected)';
        } else if ($record->status == 'Draft') {
            return $main_title . ' (On Draft)';
        } else if ($record->status == 'Approved' && $record->status_approved_by_role == $kupaFinishBy) {
            return 'Kupa Selesai';
        } else if ($record->status == 'Waiting Head Approved' && $roles === 'Admin') {
            return 'Waiting Head Approved';
        } else if ($record->status == 'Waiting Admin Approved'  && $roles === 'Head Of Lab SRS') {
            return 'Waiting Admin Approved';
        } else {
            return $main_title;
        }
    }
}

if (!function_exists('checkIconApproved')) {
    function checkIconApproved($record)
    {

        $alurApproved = Role::where('name', '<>', 'superuser')->orderBy('alur_approved')->pluck('name')->toArray();
        $kupaFinishBy = last($alurApproved);
        $roles = auth()->user()->roles[0]->name;
        if ($record->status == 'Rejected') {
            return 'heroicon-o-x-mark';
        } else if ($record->status == 'Draft') {
            return 'heroicon-o-x-mark';
        } else if ($record->status == 'Approved' && $record->status_approved_by_role == $kupaFinishBy) {
            return 'heroicon-m-check';
        } else if ($record->status == 'Waiting Admin Approved' && $roles === 'Head Of Lab SRS') {
            return 'heroicon-o-x-mark';
        } else {
            return 'heroicon-m-check-badge';
        }
    }
}

if (!function_exists('checkColorApproved')) {
    function checkColorApproved($record)
    {

        $alurApproved = Role::where('name', '<>', 'superuser')->orderBy('alur_approved')->pluck('name')->toArray();
        $kupaFinishBy = last($alurApproved);

        if ($record->status == 'Rejected') {
            return 'danger';
        } else if ($record->status == 'Approved' && $record->status_approved_by_role == $kupaFinishBy) {
            return 'success';
        } else {
            return 'info';
        }
    }
}


if (!function_exists('sendwhatsapp')) {
    function sendwhatsapp($dataarr, $number)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dawhatsappservices.srs-ssms.com/send-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('message' => $dataarr, 'number' => $number),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters from the phone number
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Check if the phone number starts with "8" followed by other digits
        if (preg_match('/^8\d+$/', $phoneNumber)) {
            $phoneNumber = '0' . $phoneNumber;
        }
        // Check if the phone number starts with "62" followed by other digits
        elseif (preg_match('/^62\d+$/', $phoneNumber)) {
            $phoneNumber = '0' . substr($phoneNumber, 2);
        }

        return $phoneNumber;
    }
}
if (!function_exists('formatLabNumber')) {
    function formatLabNumber($number)
    {
        if ($number >= 1000) {
            return number_format($number / 1000, 3, '.', '');
        } else {
            return $number;
        }
    }
}
if (!function_exists('incrementVersion')) {
    function incrementVersion($string)
    {
        // Extract the numeric part using a regular expression
        preg_match('/(\d+\.\d+)-(\d+)\.(\d+)/', $string, $matches);

        // Increment the last number
        $matches[3] += 1;

        // Rebuild the string with the incremented number
        return "FR-{$matches[1]}-{$matches[2]}.{$matches[3]}";
    }
}

if (!function_exists('incrementVersion_identitas')) {
    function incrementVersion_identitas($string)
    {
        // Extract the numeric parts using a regular expression
        preg_match('/FR-(\d+\.\d+)-(\d+\.\d+)-(\d+)/', $string, $matches);

        // Increment the last number
        $matches[3] += 1;

        // Rebuild the string with the incremented number
        return "FR-{$matches[1]}-{$matches[2]}-{$matches[3]}";
    }
}
