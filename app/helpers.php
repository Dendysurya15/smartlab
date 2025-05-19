<?php

use App\Models\Kuesionerjawaban;
use App\Models\Kuesionerpertanyaan;
use App\Models\Kuesionertipe;
use App\Models\Layoutkue;
use App\Models\Resultkuesioner;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\View;
use Dompdf\Dompdf;
use Dompdf\Options;
use NumberToWords\NumberToWords;
use Cknow\Money\Money;
use App\Models\User;
use Carbon\Carbon;
use App\Models\ParameterAnalisis;
use App\Models\JenisSampel;
use App\Models\ProgressPengerjaan;
use App\Models\TrackSampel;
use App\Models\ExcelManagement;
use Barryvdh\DomPDF\Facade\Pdf;

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

if (!function_exists('numberformat_excel')) {
    function numberformat_excel($number)
    {
        // Remove any non-numeric characters from the input number
        $number = preg_replace('/\D/', '', $number);

        // Check if the number starts with '08'
        if (substr($number, 0, 2) === '08') {
            $number = '628' . substr($number, 2);
        }

        // Validate if the number starts with '628'
        if (substr($number, 0, 3) !== '628') {
            return "Error";
        }

        // Validate the length of the number
        $length = strlen($number);
        if ($length < 10 || $length > 15) {
            return "Error";
        }

        return $number;
    }
}
if (!function_exists('Generatetemplate')) {
    function Generatetemplate($data)
    {
        $options = [];

        if ($data != 0 && $data != null) {

            // dd($data);
            $query = Kuesionerjawaban::find($data);
            $jawaban = json_decode($query->jawaban);


            foreach ($jawaban as $key => $values) {
                $options[$values->value] = $values->nama_detail;
            }
            // dd($options, $jawaban);


        }
        return  Radio::make('Default_template')
            ->label('Default template')
            ->disabled()
            ->options($options);
    }
}




if (!function_exists('layoutkuesioner')) {
    function layoutkuesioner()
    {
        $data = Layoutkue::all();

        $layout = [];

        foreach ($data as $key => $value) {
            // Split the question IDs
            $pertanyaan_ids = explode(',', $value['list_pertanyaan']);

            // Fetch the questions associated with these IDs
            $pertanyaan = Kuesionerpertanyaan::whereIn('id', $pertanyaan_ids)
                ->with('Tipe', 'template_jawaban')
                ->get();

            // Initialize schema array for this step
            $new_data = []; // Reset $new_data for each layout entry

            foreach ($pertanyaan as $index => $item) {
                // Determine the type of question
                $type = $item->Tipe->nama;
                $label = $item->label;

                if ($type === 'text') {
                    $new_data[] = TextInput::make($item->id)
                        ->label($label)
                        ->required();
                } elseif ($type === 'radio') {
                    $options = [];

                    // If there is a template_jawaban, use it, otherwise use jawaban
                    $answers = json_decode($item->template_jawaban->jawaban ?? $item->jawaban);

                    foreach ($answers as $answer) {
                        $options[$answer->value] = $answer->nama_detail;
                    }

                    $new_data[] = Radio::make($item->id)
                        ->label($label)
                        ->options($options)
                        ->required();

                    // If this is the last item in the pertanyaan collection, add a TextInput

                }

                // Add more cases for other types if necessary
            }
            if ($key == 3 || $key === '3') {
                $new_data[] = View::make('forms.components.signature');
            }
            // Add the step to the layout
            $layout[] = Step::make($key)
                ->label($value['label'])
                ->schema($new_data)
                ->columns(2);
        }

        // Example usage in a Filament form
        return $layout;
    }
}

if (!function_exists('Generateresult')) {
    function Generateresult($id)
    {
        $query = Resultkuesioner::where('id', $id)->first();
        $data = json_decode($query->result, true);

        $result_data = [];
        foreach ($data as $keys => $value) {
            $source = Kuesionerpertanyaan::where('id', (int)$value['key'])->with('Tipe', 'template_jawaban')->first();
            $source_pertanyaan = $source->label ?? 'Kosong';
            $source_tipe = $source->Tipe->nama ?? 'Kosong';
            $answer_data = $value['value'];
            $options = [];

            $answers = json_decode($source->template_jawaban->jawaban ?? $source->jawaban);

            foreach ($answers as $answer) {
                $options[$answer->value] = $answer->nama_detail;
            }
            $result_data[] = [
                'id_pertanyaan' => $source->id,
                'pertanyaan' => $source_pertanyaan,
                'tipe' => $source_tipe,
                'jawaban' => $answer_data,
                'option' => $options
            ];
        }
        $adding_new = [
            "id_pertanyaan" => 41,
            "pertanyaan" => "Kesesuaian harga layanan dengan produk jasa",
            "tipe" => "radio",
            "jawaban" => "null",
            "option" => [
                1 => "Tidak puas",
                2 => "Kurang puas",
                3 => "Puas",
                4 => "Sangat puas",
            ]
        ];
        $check = count($result_data);

        if ($check < 19) {
            $new_result = array_merge($result_data, [$adding_new]);
        } else {
            $new_result = $result_data;
        }
        // dd($result_data);
        // Wrap $adding_new in an array to merge it properly

        $new_data_text = [];
        $new_data_radio = [];

        foreach ($new_result as $key => $item) {
            if ($item['tipe'] === 'text') {
                $new_data_text[] = TextInput::make($item['id_pertanyaan'])
                    ->label($item['pertanyaan'])
                    ->required()
                    ->default($item['jawaban']);
            } elseif ($item['tipe'] === 'radio') {
                $new_data_radio[] = Radio::make($item['id_pertanyaan'])
                    ->label($item['pertanyaan'])
                    ->default($item['jawaban'])
                    ->required()
                    ->options($item['option']);
            }
        }
        $fieldset_text = Fieldset::make('Text Inputs')
            ->schema($new_data_text)
            ->columns(1);

        $fieldset_radio = Fieldset::make('Radio Inputs')
            ->schema($new_data_radio)
            ->columns(3);


        return [
            $fieldset_text,
            $fieldset_radio,


        ];
    }
}

if (!function_exists('check_invalid_value')) {
    function check_invalid_value($value)
    {
        if ($value == null || $value == '' || $value == '[]') {
            return false;
        }
        return true;
    }
}


if (!function_exists('GeneratePdfKupa')) {
    function GeneratePdfKupa($id, $filename)
    {
        $idsArray = explode('$', $id);
        $queries = TrackSampel::whereIn('id', $idsArray)->with('trackParameters')->with('progressSampel')->with('jenisSampel')->get();
        $petugas = ExcelManagement::where('status', 1)->get();
        $petugas = $petugas->groupBy(['jabatan']);
        $petugas = json_decode($petugas, true);
        // dd($petugas);
        // dd($queries);
        $result = [];
        $result_total = [];
        $inc = 1;
        function transformArray($list_row)
        {
            $result = [];
            $total = 0;

            foreach ($list_row as $valuxe) {
                $result[$total] = $valuxe;
                $total += $valuxe;
            }

            return $result;
        }
        function combineKeysAndValues($keys, $values)
        {
            $result = [];

            foreach ($keys as $index => $key) {
                $result[$key] = $values[$index];
            }

            return $result;
        }

        $data = [];
        foreach ($queries as $key => $value) {
            $tanggal_terima = Carbon::parse($value->tanggal_terima);
            $trackparam = $value->trackParameters;

            $nama_params = [];
            foreach ($trackparam as $trackParameter) {
                if ($trackParameter->ParameterAnalisis) {
                    if ($trackParameter->ParameterAnalisis->paket_id != null) {
                        $data_paket = explode('$', $trackParameter->ParameterAnalisis->paket_id);
                        $nama_params[] = [
                            'unsur' => ParameterAnalisis::whereIn('id', $data_paket)->pluck('nama_unsur')->toArray(),
                            'metode_analisis' => ParameterAnalisis::whereIn('id', $data_paket)->pluck('metode_analisis')->toArray(),
                            'harga' => $trackParameter->ParameterAnalisis->harga,
                            'jenis' => 'Paket',
                            'row' => count($data_paket),
                            'jumlah_sampel' => $trackParameter->jumlah,
                            'satuan' => ParameterAnalisis::whereIn('id', $data_paket)->pluck('satuan')->toArray(),
                            'sub_total' => $trackParameter->ParameterAnalisis->harga * $trackParameter->jumlah,
                        ];
                    } else {
                        $nama_params[] = [
                            'unsur' => $trackParameter->ParameterAnalisis->nama_unsur,
                            'metode_analisis' => $trackParameter->ParameterAnalisis->metode_analisis,
                            'harga' => $trackParameter->ParameterAnalisis->harga,
                            'jenis' => 'Paket',
                            'row' => 1,
                            'jumlah_sampel' => $trackParameter->jumlah,
                            'satuan' => $trackParameter->ParameterAnalisis->satuan,
                            'sub_total' => $trackParameter->ParameterAnalisis->harga * $trackParameter->jumlah,
                        ];
                    }
                }
            }

            $total_row = 0;
            $jum_sampel = 0;
            $harga_total_per_sampel = 0;
            $list_unsur = [];
            $list_analisis = [];
            $list_satuan = [];
            $list_row = [];
            $list_jumlah_sampel = [];
            $list_harga = [];
            $sub_total = [];

            foreach ($nama_params as $param) {
                $total_row += $param['row'];
                $jum_sampel += $param['jumlah_sampel'];
                $harga_total_per_sampel += $param['sub_total'];

                $list_jumlah_sampel[] = $param['jumlah_sampel'];
                $list_harga[] = $param['harga'];
                $sub_total[] = $param['sub_total'];
                $list_row[] = $param['row'];

                if (is_array($param['unsur'])) {
                    $list_unsur = array_merge($list_unsur, $param['unsur']);
                    $list_analisis = array_merge($list_analisis, $param['metode_analisis']);
                    $list_satuan = array_merge($list_satuan, $param['satuan']);
                } else {
                    $list_unsur[] = $param['unsur'];
                    $list_analisis[] = $param['metode_analisis'];
                    $list_satuan[] = $param['satuan'];
                }
            }
            $harga_total_dengan_ppn = Money::IDR(hitungPPN($harga_total_per_sampel), true);
            $totalppn_harga = $harga_total_dengan_ppn->add(Money::IDR($harga_total_per_sampel, true));

            $discountDecimal = $value->discount != 0 ? $value->discount / 100 : 0;
            $discount = $totalppn_harga->multiply($discountDecimal);
            $total_akhir = $totalppn_harga->subtract($discount);

            $nolab = explode('$', $value->nomor_lab);
            $year = Carbon::parse($value->tanggal_terima)->format('y');
            $kode_sampel = $value->jenisSampel->kode;

            $nolab = explode('$', $value->nomor_lab);
            $year = Carbon::parse($value->tanggal_terima)->format('y');
            $kode_sampel = $value->jenisSampel->kode;

            $labkiri = $year . $kode_sampel . '.' . formatLabNumber($nolab[0]);
            $labkanan = isset($nolab[1]) ? $year . $kode_sampel . '.' . formatLabNumber($nolab[1]) : '';

            $colspandata = transformArray($list_row);
            $keys_default = array_keys($colspandata);

            $list_jumlah_sampel = combineKeysAndValues($keys_default, $list_jumlah_sampel);
            $list_harga = combineKeysAndValues($keys_default, $list_harga);
            $sub_total = combineKeysAndValues($keys_default, $sub_total);
            for ($i = 0; $i < $total_row; $i++) {

                $result[$key][$i] = [
                    'no_surat' => ($i == 0) ? $value->nomor_surat : '',
                    'kemasan' => ($i == 0) ? $value->kemasan_sampel : '',
                    'colspan' => ($i == 0) ? $total_row : 0,
                    'jum_sampel' => ($i == 0) ? $value->jumlah_sampel : '',
                    'nolab' => ($i == 0) ? $labkiri : (($i == 1) ? $labkanan : ''),
                    'Parameter_Analisis' => $list_unsur[$i] ?? '',
                    'mark' => '✓',
                    'Metode_Analisis' => $list_analisis[$i] ?? '',
                    'satuan' => $list_satuan[$i] ?? '',
                    'Personel' => ($value->personel == 1) ? '✔' : '',
                    'alat' => ($value->alat == 1) ? '✔' : '',
                    'bahan' => ($value->bahan == 1) ? '✔' : '',
                    'cols' => isset($colspandata[$i]) ? $colspandata[$i] : 0,
                    'jum_data' => isset($list_jumlah_sampel[$i]) ? $list_jumlah_sampel[$i] : 0,
                    'jum_harga' => isset($list_harga[$i]) ? $list_harga[$i] : 0,
                    'jum_sub_total' => isset($sub_total[$i]) ? $sub_total[$i] : 0,
                    'Konfirmasi' => ($value->konfirmasi == 1) ? '✔' : '',
                    'kondisi_sampel' => $value->kondisi_sampel,
                    'estimasi' => ($i == 0) ? Carbon::parse($value->estimasi)->locale('id')->translatedFormat('d F Y') : '',
                ];
            }

            $titles = ["Total Per Parameter", "PPn 11%", "Diskon", "Total"];
            $values_title = [Money::IDR($harga_total_per_sampel, true), $harga_total_dengan_ppn, $discount, $total_akhir];

            for ($i = 0; $i < 4; $i++) {
                // Initialize the array with empty strings
                $result_total[$i] = array_fill(0, 16, '');

                // Set the specific value at index 5
                $result_total[$i][5] = $titles[$i];
                $result_total[$i][11] = $values_title[$i];
            }
            $catatan = $value->catatan;
            $nama_pengirim = $value->nama_pengirim;
            $status = $value->status;
            $memo_created = $value->tanggal_memo;
            $verif = explode(',', $value->status_timestamp);

            $verifikasi_admin_timestamp = $verif[0];
            $verifikasi_head_timestamp = $verif[1] ?? '-';

            $approveby_head = $value->approveby_head;
            $petugas_penerima_sampel = User::where('id', $value->created_by)->pluck('name')->first();
            $jenis_kupa = $value->jenis_pupuk ?? $value->jenisSampel->nama;
            $tanggal_penerimaan = Carbon::parse($value->tanggal_terima)->locale('id')->translatedFormat('d F Y');
            $no_kupa = $value->nomor_kupa;
            $departemen = $value->departemen;
            $formulir = $value->formulir;
            $doc = $value->no_doc;
            $data[$key] = [
                'data' => $result[$key],
                'total_row' => $total_row,
                "result_total" => $result_total,
                "catatan" =>   $value->catatan,
                'nama_pengirim' => $nama_pengirim,
                'petugas_penerima_sampel' => $petugas_penerima_sampel,
                'approval' => $status,
                'memo_created' => $memo_created,
                'verifikasi_admin_timestamp' => $verifikasi_admin_timestamp,
                'isVerifiedByHead' => $approveby_head,
                'verifikasi_head_timestamp' => $verifikasi_head_timestamp,
                'jenis_kupa' => $jenis_kupa,
                'tanggal_penerimaan' => $tanggal_penerimaan,
                'no_kupa' => $no_kupa,
                'departemen' => $departemen,
                'formulir' => $formulir,
                'labkiri' => $labkiri,
                'labkanan' => $labkanan,
                'doc' => $doc,
                'img' => asset('images/Logo_CBI_2.png'), // Correctly generate the image URL
            ];
            $jenis_sampel[] = $value->jenisSampel->nama;
            $date[] = Carbon::parse($value->tanggal_terima)->locale('id')->translatedFormat('F Y');
        }
        if ($filename === 'bulk') {
            $uniqueArray = array_unique($jenis_sampel);
            $uniquedate = array_unique($date);
            // dd($uniquedate);

            $newfilename =  'Kupa_' . implode('_', $uniqueArray) . '_Date_' . implode('_', $uniquedate);
        } else {
            $newfilename = $filename;
        }

        // dd($data);

        // $options = new Options();
        // $options->set('defaultFont', 'DejaVu Sans');
        // $options->set('isRemoteEnabled', true); // Enable loading of remote resources
        // $dompdf = new Dompdf($options);

        // $view = view('pdfview.export_kupa', ['data' => $data])->render();
        // $dompdf->loadHtml($view);

        // // Set paper size and orientation
        // $dompdf->setPaper('A2', 'landscape');

        // // Render the PDF
        // $dompdf->render();
        // dd($data);
        return [
            'data' => $data,
            'filename' => $newfilename
        ];
    }
}


if (!function_exists('GeneratePR')) {
    function GeneratePR($iddata)
    {
        $id = explode('$', $iddata);

        $queries = TrackSampel::whereIn('id', $id)
            ->with('trackParameters')
            ->with('progressSampel')
            ->with('jenisSampel')
            ->get();

        $queries = $queries->groupBy(['jenis_sampel', 'nomor_kupa']);
        // dd($queries);

        $result = [];
        foreach ($queries as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $kode_sampel = [];
                $nomor_lab = [];
                $nama_parameter = [];
                foreach ($value1 as $key2 => $value2) {
                    $jenissample = $value2->jenisSampel->nama;
                    $jenissample_komuditas = $value2->jenis_pupuk ?? 'Tidak tersedia';
                    $jumlahsample = $value2['jumlah_sampel'];
                    $catatan = $value2['catatan'];
                    $kdsmpel = $value2['kode_sampel'];
                    $nolab = $value2['nomor_lab'];
                    $trackparam = $value2->trackParameters;
                    $carbonDate = Carbon::parse($value2['tanggal_terima'])->locale('id')->translatedFormat('d F Y');
                    $carbonDate2 = Carbon::parse($value2['estimasi'])->locale('id')->translatedFormat('d F Y');
                    $nama_parameter = [];
                    $hargatotal = 0;
                    $jumlah_per_parametertotal = 0;
                    $hargaasli = [];
                    $harga_total_per_sampel = [];
                    $jumlah_per_parameter = [];
                    $namakode_sampelparams = [];
                    foreach ($trackparam as $trackParameter) {

                        if ($trackParameter->ParameterAnalisis) {
                            $nama_parameter[] = $trackParameter->ParameterAnalisis->nama_parameter;
                            $hargaasli[] =  Money::IDR($trackParameter->ParameterAnalisis->harga, true);
                            $harga_total_per_sampel[] = Money::IDR($trackParameter->totalakhir, true);
                            $jumlah_per_parameter[] = $trackParameter->jumlah;

                            $statuspaket = $trackParameter->ParameterAnalisis->paket_id;

                            if ($statuspaket != null) {
                                $paket = explode('$', $statuspaket);
                                $params = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                                // $nama_parameter[] = $nama_params;
                                // $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = ParameterAnalisis::whereIn('id', $paket)->pluck('nama_unsur')->toArray();
                                $namakode_sampelparams[implode(',', $params)] =  explode('$', $trackParameter->namakode_sampel);
                            } else {
                                // $nama_parameter[] = $namaunsur;
                                $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_unsur] = explode('$', $trackParameter->namakode_sampel);
                            }

                            // $namakode_sampelparams[$trackParameter->ParameterAnalisis->nama_parameter] = explode('$', $trackParameter->namakode_sampel);
                        }
                        $hargatotal += $trackParameter->totalakhir;
                        $jumlah_per_parametertotal += $trackParameter->jumlah;
                    }
                    $harga_total_dengan_ppn = Money::IDR(hitungPPN($hargatotal), true);
                    $totalppn_harga = $harga_total_dengan_ppn->add(Money::IDR($hargatotal, true));

                    $discountDecimal = $value2->discount != 0 ? $value2->discount / 100 : 0;
                    $discount = $totalppn_harga->multiply($discountDecimal);

                    $total_akhir = $totalppn_harga->subtract($discount);
                    $newnamaparameter = [];

                    // dd($namakode_sampelparams);

                    $sampel_data = [];

                    foreach ($namakode_sampelparams as $attribute => $items) {
                        foreach ($items as $item) {
                            if (!isset($sampel_data[$item])) {
                                $sampel_data[$item] = [];
                            }

                            $explodedAttributes = strpos($attribute, ',') !== false ? explode(',', $attribute) : [$attribute];

                            foreach ($explodedAttributes as $attr) {
                                $trimmedAttr = trim($attr); // Ensure no leading/trailing spaces
                                if (!in_array($trimmedAttr, $sampel_data[$item])) {
                                    $sampel_data[$item][] = $trimmedAttr;
                                }
                            }
                        }
                    }
                }
                // dd($total_akhir);
                // dd($sampel_data, $namakode_sampelparams);

                $kode_sampel = explode('$', $kdsmpel);


                $nomor_lab = explode('$', $nolab);
                $new_sampel = [];
                $incc = 0;
                foreach ($sampel_data as $keyx => $valuex) {
                    $new_sampel[$incc++] = implode(',', $valuex);
                }
                // dd($value2);
                $timestamp = strtotime($value2['tanggal_terima']);
                $year = date('Y', $timestamp);
                $lab =  substr($year, 2) . $value2->jenisSampel->kode . '.';
                // Remove leading and trailing spaces from each element
                $kode_sampel = array_map(function ($value) {
                    return trim($value); // Removes spaces from both start and end
                }, $kode_sampel);
                $new_nomor_lab = $nomor_lab[0] - 1;
                $lab_counter = 1;
                $progress = $value2->progressSampel->nama;
                $progresHistory = json_decode($value2->last_update, true);

                $dateSertifikat = '-';
                $dateAnalisa = '-';

                if ($value2->progressSampel->id == 7) {
                    $dateSertifikat = Carbon::now()->locale('id')->translatedFormat('d F Y');
                    $foundProgress6 = false;

                    foreach ($progresHistory as $progress) {
                        if ($progress['progress'] == '7') {
                            $dateSertifikat = Carbon::parse($progress['updated_at'])
                                ->locale('id')
                                ->translatedFormat('d F Y');
                        }

                        if ($progress['progress'] == '6') {
                            $dateAnalisa = Carbon::parse($progress['updated_at'])
                                ->locale('id')
                                ->translatedFormat('d F Y');
                            $foundProgress6 = true;
                        }
                    }

                    if (!$foundProgress6) {
                        $dateAnalisa = $dateSertifikat;
                    }
                }
                foreach ($sampel_data as $keysx => $valuems) {
                    // $inc = 1;
                    foreach ($kode_sampel as $index => $kode) {
                        if ((string)$keysx === $kode) {
                            $result[$key][$key1][$keysx]['jenis_sample'] = $jenissample;
                            $result[$key][$key1][$keysx]['nama_unsur'] = $keysx;
                            $result[$key][$key1][$keysx]['jenis_sample_komoditas'] = $jenissample_komuditas;
                            $result[$key][$key1][$keysx]['jumlah_sampel'] = ($index == 0) ? $jumlahsample : 'null';
                            $result[$key][$key1][$keysx]['catatan'] = ($index == 0) ? $catatan : 'null';
                            $result[$key][$key1][$keysx]['kode_sampel'] = $kode_sampel[$index];
                            $current_lab_number = $new_nomor_lab + $lab_counter;
                            $result[$key][$key1][$keysx]['nomor_lab'] = $lab . formatLabNumber($current_lab_number);
                            $lab_counter++; // Increment the counter for next iteration
                            $result[$key][$key1][$keysx]['nama_pengirim'] = $value2['nama_pengirim'];
                            $result[$key][$key1][$keysx]['asal_sampel'] = $value2['asal_sampel'];
                            $result[$key][$key1][$keysx]['departemen'] = $value2['departemen'];
                            $result[$key][$key1][$keysx]['nomor_surat'] = $value2['nomor_surat'];
                            $result[$key][$key1][$keysx]['nomor_kupa'] = $value2['nomor_kupa'];
                            $result[$key][$key1][$keysx]['tanggal_terima'] = $carbonDate;
                            $result[$key][$key1][$keysx]['tanggal_memo'] = $value2['tanggal_memo'];
                            $result[$key][$key1][$keysx]['kode_track'] = $value2['kode_track'];
                            $result[$key][$key1][$keysx]['tujuan'] = $value2['tujuan'];
                            $result[$key][$key1][$keysx]['Jumlah_Parameter'] = count($valuems);
                            $result[$key][$key1][$keysx]['Parameter_Analisa'] = implode(',', $valuems);
                            $result[$key][$key1][$keysx]['tujuan'] = $value2['tujuan'];
                            $result[$key][$key1][$keysx]['estimasi'] = $carbonDate2;
                            $result[$key][$key1][$keysx]['Tanggal_Selesai_Analisa'] = $dateAnalisa;
                            $result[$key][$key1][$keysx]['No_sertifikat'] = '-';
                            $result[$key][$key1][$keysx]['total'] = ($index == 0) ? $total_akhir : 'null';
                            $result[$key][$key1][$keysx]['total_string'] = ($index == 0) ? NumberToWords::transformNumber('id', $hargatotal) : 'null';
                            $result[$key][$key1][$keysx]['progress'] = $progress;
                            $result[$key][$key1][$keysx]['Tanggal_Rilis_Sertifikat'] = $dateSertifikat;
                        }
                    }
                }
                // dd($result);
            }
            $result[$key]['jenis'] = $jenissample;
        }
        // dd($result);
        $jenissamplel = [];
        foreach ($result as $key => $value) {
            $jenissamplel[] = $value['jenis'];
        }
        $jenissamplefix = implode(',', $jenissamplel);

        $filename = 'PDF Kupa,' . $jenissamplefix . '.pdf';
        $nomor_labs = $nomor_lab[0] - 1;
        return [
            'result' => $result,
            'filename' => $filename,
            'nomor_lab' => $nomor_labs,
            'tanggal_penerimaan' => $value2['tanggal_terima']
        ];
    }

    if (!function_exists('defaultPTname')) {
        function defaultPTname($tanggal)
        {
            try {
                // Jika tanggal dalam format "17 Mei 2025" (Indonesia)
                $bulanIndonesia = [
                    'Januari' => '01',
                    'Februari' => '02',
                    'Maret' => '03',
                    'April' => '04',
                    'Mei' => '05',
                    'Juni' => '06',
                    'Juli' => '07',
                    'Agustus' => '08',
                    'September' => '09',
                    'Oktober' => '10',
                    'November' => '11',
                    'Desember' => '12'
                ];

                // Split tanggal "17 Mei 2025"
                $parts = explode(' ', $tanggal);
                if (count($parts) === 3) {
                    $tanggal = $parts[2] . '-' . $bulanIndonesia[$parts[1]] . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                }

                $tanggalPenerimaan = Carbon::parse($tanggal);
                $batasTanggal = Carbon::createFromFormat('Y-m-d', '2025-05-01');

                if ($tanggalPenerimaan < $batasTanggal) {
                    // return 'PT. CITRA BORNEO INDAH';
                    return [
                        'nama' => 'PT. CITRA BORNEO INDAH',
                        'revisi' => '02',
                        'tanggal_berlaku' => '1-jul-21'
                    ];
                } else {
                    return [
                        'nama' => 'PT. Sulung Research Station',
                        'revisi' => '00',
                        'tanggal_berlaku' => '01 Mei 2025'
                    ];
                }
            } catch (Exception $e) {
                // Fallback jika parsing gagal
                return 'PT. CITRA BORNEO INDAH';
            }
        }
    }

    if (!function_exists('defaultIconPT ')) {
        function defaultIconPT($tanggal)
        {
            try {
                // Jika tanggal dalam format "17 Mei 2025" (Indonesia)
                $bulanIndonesia = [
                    'Januari' => '01',
                    'Februari' => '02',
                    'Maret' => '03',
                    'April' => '04',
                    'Mei' => '05',
                    'Juni' => '06',
                    'Juli' => '07',
                    'Agustus' => '08',
                    'September' => '09',
                    'Oktober' => '10',
                    'November' => '11',
                    'Desember' => '12'
                ];

                // Split tanggal "17 Mei 2025"
                $parts = explode(' ', $tanggal);
                if (count($parts) === 3) {
                    $tanggal = $parts[2] . '-' . $bulanIndonesia[$parts[1]] . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                }

                $tanggalPenerimaan = Carbon::parse($tanggal);
                $batasTanggal = Carbon::createFromFormat('Y-m-d', '2025-05-01');

                if ($tanggalPenerimaan < $batasTanggal) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                // Fallback jika parsing gagal
                return true;
            }
        }
    }
}
