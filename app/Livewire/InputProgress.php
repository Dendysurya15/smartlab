<?php

namespace App\Livewire;

use App\Mail\EmailPelanggan;
use App\Models\JenisSampel;
use App\Models\Progress;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use App\Models\SendMsg;
use App\Models\TrackParameter;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Filament\Notifications\Actions\Action;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;

class InputProgress extends Component
{
    use WithFileUploads;
    public $inputanParameter = [];
    public $biayaParameter = [];
    public $parameterAnalisis = [];
    public $metodeAnalisis = [];
    public $jenis_sampel = 5;
    public $tanggal_memo;
    public $tanggal_terima;
    public $asal_sampel;
    public $nomor_kupa;
    public $nama_pengirim;
    public $departemen;
    public $kode_sampel;
    public $jumlah_sampel;
    public $kondisi_sampel;
    public $kemasan_sampel;
    public $personel;
    public $alat;
    public $bahan;
    public $confirmation;
    public $nomor_surat;
    public $nomor_lab_left;
    public $nomor_lab_right;
    public $tgl_pengantaran_sampel;
    public $jumlah_sampel_view;
    public $estimasi;
    public $tujuan;
    public $last_update;
    public $admin;
    public $no_hp;
    public $emailTo;
    public $emailCc;
    public $foto_sampel;
    public $skala_prioritas;
    public $hargaparameter;
    public $parameterAnalisisOptions = [];
    public $hargasample = [0];
    public $satuanparameter;
    public $namaparameter;
    public $analisisparameter = [0];
    public $val_parameter;
    public $discount = 0;
    public $subtotal;
    public $jumsap = 0;
    public $list_metode = [];
    public $catatan = '';

    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;

    public $progress = 0;

    public $formData = [];

    protected $rules = [
        'tanggal_terima' => 'required|date',
        'jenis_sampel' => 'required',
        'asal_sampel' => 'required|in:Internal,Eksternal',
        'nomor_kupa' => 'required|numeric',
        'discount' => 'required',
        'nama_pengirim' => 'required|string',
        'departemen' => 'required|string',
        'jumlah_sampel' => 'required|numeric',
        'kondisi_sampel' => 'required|string',
        'kemasan_sampel' => 'required|string',
        'nomor_surat' => 'required|string',
        'estimasi' => 'required|date',
        'no_hp' => 'required',
        'tujuan' => 'required|string',
        // 'emailTo' => 'required|email', // Assuming it's an email field
        'foto_sampel' => 'max:5000',
        'formData' => 'required',
    ];

    protected $messages = [

        // 'email.required' => 'The Email Address cannot be empty.',
        // 'email.email' => 'The Email Address format is not valid.',

    ];


    public function ChangeFieldParamAndNomorLab()
    {
        $selectedJenisSampel = JenisSampel::find($this->jenis_sampel);
        if ($selectedJenisSampel) {
            $current = Carbon::now()->format('y');
            $this->nomor_lab_left = $current . $selectedJenisSampel->kode . '.';
            $this->nomor_lab_right = $current . $selectedJenisSampel->kode . '.';
            $options = ParameterAnalisis::where('id_jenis_sampel', $this->jenis_sampel)->get();

            if ($selectedJenisSampel->nama != 'Pupuk Anorganik') {
                $this->parameterAnalisisOptions = $options->groupBy('nama_parameter')->flatMap(function ($grouped) {
                    return $grouped->count() > 1 ? $grouped->map(function ($item) {
                        return ['id' => $item->id, 'nama_parameter_full' => $item->nama_parameter . ' ' . $item->metode_analisis];
                    }) : $grouped->map(function ($item) {
                        return ['id' => $item->id, 'nama_parameter_full' => $item->nama_parameter];
                    });
                })->values()->toArray();
            } else {
                $this->parameterAnalisisOptions = $options->groupBy('nama_parameter')->flatMap(function ($grouped) {
                    return $grouped->count() > 1 ? $grouped->map(function ($item) {
                        return ['id' => $item->id, 'nama_parameter_full' => $item->bahan_produk . ' ' . $item->nama_parameter . ' ' . $item->metode_analisis];
                    }) : $grouped->map(function ($item) {
                        return ['id' => $item->id, 'nama_parameter_full' => $item->nama_parameter];
                    });
                })->values()->toArray();
            }
        }
    }

    public function getlabstatus()
    {
        $this->jumsap = $this->jumlah_sampel;

        // dd($jumsamp);
    }

    public function addParameter()
    {

        $this->val_parameter = $this->val_parameter ?? $this->parameterAnalisisOptions[0]['id'];
        $defaultParameterAnalisis = ParameterAnalisis::Where('id', $this->val_parameter)->first();

        $this->hargaparameter = $defaultParameterAnalisis->harga;
        $this->satuanparameter = $defaultParameterAnalisis->satuan;
        $this->analisisparameter = $defaultParameterAnalisis->metode_analisis;

        $sub_total = $this->hargaparameter * 1;
        $total = $sub_total;

        $newForm = [
            'nama_parameter' => $defaultParameterAnalisis->nama_parameter,
            'id_parameter' => $defaultParameterAnalisis->id,
            'jumlahsample' => 'Jumlah Sampel',
            'hargassample' => 'Harga Sampel',
            // 'personel' => True,
            // 'alat' => True,
            // 'bahan' => True,
            'subtotal' => 'Sub Total',
            'totaljudul' => 'Total',
            'list_metode' => $this->analisisparameter,
            'jumlah_sampel' => 1,
            'harga_sampel' =>  $this->hargaparameter,
            'satuan' => '',
            'sub_total' => $sub_total,
            'totalharga' => $total
        ];

        $this->formData[] = $newForm;
    }

    public function gethargasample($index)
    {

        $form = $this->formData[$index];
        $harga = $this->formData[$index]['harga_sampel'];
        $sub_totalx[$index] = $harga *  $this->formData[$index]['jumlah_sampel'];
        $totalharga[$index] = $sub_totalx[$index] +  $sub_totalx[$index];
        $this->formData[$index]['sub_total'] = $sub_totalx[$index];
        $this->formData[$index]['totalharga'] = $totalharga[$index];
    }

    public function removeParameter($index)
    {
        // Log::info('Removing parameter at index ' . $index);
        // dd($this->formData[$index]);

        if (isset($this->formData[$index])) {
            unset($this->formData[$index]);
        }
    }


    public function updatedInputanParameter($value, $index)
    {
        $list = explode('.', $index);

        if ($list[1] == 'parameter_id') {
            $hargaSelected = ParameterAnalisis::find((int)$value)->harga;
            $this->biayaParameter[$list[0]]['harga_sampel'] = $hargaSelected;
        }
    }



    public function resetFotoSampel()
    {
        $this->foto_sampel = null;
    }



    public function mount()
    {

        $current = Carbon::now();
        $formattedDateTime = $current->format('Y-m-d\TH:i');

        // dd($formattedDateTime);

        $this->tanggal_memo = $formattedDateTime;
        // $current = $current->format('y');

        // $this->tanggal_memo = date('Y-m-d\TH:i', strtotime('2024-02-28 10:20 PM'));

        // dd($formattedDateTime, $this->tanggal_memo);
        if ($current->format('H:i') > '12:59') {
            // Add one day if the time is after 12:59 AM
            $this->tanggal_terima = $current->addDay()->toDateString();
        } else {
            $this->tanggal_terima = $current->toDateString();
        }
        $jumlah_sampel_default = 1;
        $this->jumlah_sampel = $jumlah_sampel_default;
        $this->kondisi_sampel = 'Normal';
        $this->asal_sampel = 'Internal';
        $this->discount = 0;
        $this->skala_prioritas = 'Normal';
        $this->estimasi = Carbon::now()->toDateString();
        $this->tgl_pengantaran_sampel = Carbon::now()->toDateString();
        $defaultJenisSampel = JenisSampel::first();
        $this->nomor_lab_left = $current . $defaultJenisSampel->kode . '.';
        $this->nomor_lab_right = $current . $defaultJenisSampel->kode . '.';
        $this->jenis_sampel = $defaultJenisSampel ? $defaultJenisSampel->id : null;
        $this->ChangeFieldParamAndNomorLab();
    }


    public function updateHargaSampel()
    {
        foreach ($this->formData as $index => $inputan) {
            $curr_jumlah_sampel = $inputan['jumlah_sampel'];
            $curr_harga_sampel = $inputan['harga_sampel'];
            $curr_sub_total = $curr_jumlah_sampel * $curr_harga_sampel;
            $this->formData[$index]['totalharga'] = $curr_sub_total;
        }
    }

    public function getJumlahSampel()
    {

        foreach ($this->inputanParameter as $index => $inputan) {
            if (isset($this->biayaParameter[$index])) {
                $this->biayaParameter[$index]['jumlah_sampel'] = $inputan['jumlah_per_parameter'];
                $this->biayaParameter[$index]['sub_total'] = $this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter'];
                $this->biayaParameter[$index]['total'] = ($this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter']) + $this->biayaParameter[$index]['harga_sampel'] * $inputan['jumlah_per_parameter'];
            }
        }
    }

    public function render()
    {
        $jenisSampelOptions = JenisSampel::all();

        return view(
            'livewire.input-progress',
            [
                'jenisSampelOptions' => $jenisSampelOptions,
            ]
        );
    }

    public function updatePPN()
    {
        foreach ($this->formData as $index => $inputan) {
            $this->formData[$index]['totalharga'] = $this->formData[$index]['ppn'] + $this->formData[$index]['sub_total'];
        }
    }

    public function resetForm()
    {
        $this->biayaParameter = []; // Reset parameters array
        $this->formData = []; // Reset metode array

    }

    public function cancelButton()
    {
        $this->redirect('history_sampel');
    }

    public function draftKupa()
    {
        $this->handleFormSubmission('draft');
    }

    public function save()
    {
        $this->handleFormSubmission('save');
    }


    public function handleFormSubmission($action)
    {



        if ($action === 'save') {

            $this->validate();
        }


        $formData = array_filter($this->formData, function ($item) {
            return !empty($item['nama_parameter']);
        });

        // dd($formData);
        $currentDateTime = Carbon::now();

        // Get the hour, minutes, and seconds
        $thisHour = $currentDateTime->hour;
        $thisMinute = $currentDateTime->minute;
        $thisSecond = $currentDateTime->second;

        // Alternatively, you can chain the methods to get the hour, minutes, and seconds directly
        list($thisHour, $thisMinute, $thisSecond) = explode(':', $currentDateTime->toTimeString());

        // You can also get the time formatted as HH:MM:SS
        $thisTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS


        // dd($thisTime, $this->tanggal_memo);
        $userId = 1;
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user->id;
        }

        do {
            $randomCode = generateRandomCode();
            $existingTrackSampel = TrackSampel::where('kode_Track', $randomCode)->first();
        } while ($existingTrackSampel);

        $current = Carbon::now();
        $current = $current->format('Y-m-d H:i:s');
        // dd($this->nomor_lab_left, $this->nomor_lab_right);
        $nomorlab =  $this->nomor_lab_left . '-' . $this->nomor_lab_right;


        $commonRandomString = generateRandomString(rand(5, 10));



        $recipients = array_email($this->emailTo);
        $cc = array_email($this->emailCc);

        // dd($this->personel, $this->alat, $this->bahan);
        // dd($this->discount);
        try {
            DB::beginTransaction();

            $trackSampel = new TrackSampel();
            $trackSampel->jenis_sampel = $this->jenis_sampel;
            $trackSampel->tanggal_memo = $this->tanggal_memo;
            $trackSampel->tanggal_terima = $this->tanggal_terima;
            $trackSampel->asal_sampel = $this->asal_sampel;
            $trackSampel->nomor_kupa = $this->nomor_kupa;
            $trackSampel->nama_pengirim = $this->nama_pengirim;
            $trackSampel->departemen = $this->departemen;
            $trackSampel->kode_sampel = $this->kode_sampel;
            $trackSampel->jumlah_sampel = $this->jumlah_sampel;
            $trackSampel->kondisi_sampel = $this->kondisi_sampel;
            $trackSampel->kemasan_sampel = $this->kemasan_sampel;
            $trackSampel->nomor_surat = $this->nomor_surat;
            $trackSampel->nomor_lab =  $nomorlab;
            $trackSampel->estimasi = $this->estimasi;
            $trackSampel->tujuan = $this->tujuan;
            $trackSampel->progress = 4;
            $trackSampel->last_update = $current;
            $trackSampel->admin = $userId;
            $trackSampel->no_hp = $this->no_hp;
            $trackSampel->alat = ($this->alat ? 1 : 0);
            $trackSampel->emailTo = $this->emailTo;
            $trackSampel->bahan = ($this->bahan ? 1 : 0);
            $trackSampel->personel = ($this->personel ? 1 : 0);
            $trackSampel->konfirmasi = ($this->confirmation ? 1 : 0);
            $trackSampel->parameter_analisisid = $commonRandomString;
            $trackSampel->kode_track = $randomCode;
            $trackSampel->skala_prioritas = $this->skala_prioritas;
            $trackSampel->discount = $this->discount;
            $trackSampel->catatan = $this->catatan;
            $trackSampel->tanggal_pengantaran = $this->tgl_pengantaran_sampel;

            $getprogress = Progress::pluck('nama')->first();

            if ($this->foto_sampel) {
                $fileName = time() . '_' . $this->foto_sampel->getClientOriginalName();
                $this->foto_sampel->storeAs('uploads', $fileName, 'public');
                $trackSampel->foto_sampel = $fileName;
            }

            if ($action === 'draft') {
                $trackSampel->status = 'Draft';
            }

            $saveResult = $trackSampel->save();

            if ($saveResult) {

                $trackParameters = [];

                if ($formData !== []) {
                    foreach ($formData as $data) {
                        $dataToInsert[] = [
                            'id_parameter' => $data['id_parameter'],
                            'jumlah' => $data['jumlah_sampel'],
                            'totalakhir' => $data['totalharga'],
                            // 'personel' => $data['personel'] == True ? 1 : 0,
                            // 'alat' => $data['alat'] == True ? 1 : 0,
                            // 'bahan' => $data['bahan'] == True ? 1 : 0,
                            'id_tracksampel' => $commonRandomString,
                        ];
                    }

                    TrackParameter::insert($dataToInsert);
                }

                //to store to database below code
                DB::commit();

                if ($action === 'save') {
                    $form_hp = $this->no_hp;

                    $nohp = numberformat($form_hp);
                    SendMsg::insert([
                        'no_surat' => $this->nomor_surat,
                        'kodesample' => $randomCode,
                        'penerima' => $nohp,
                        'progres' => $getprogress,
                        'type' => 'input',
                    ]);

                    Notification::make()
                        ->title('Berhasil disimpan')
                        ->body(' Record berhasil disimpan dengan kode track ' . $randomCode)
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->color('success')
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->url(route('history_sampel.index')),

                        ])
                        ->success()
                        ->send();


                    $nomorserif = '-';
                    // Mail::to($recipients)
                    //     ->cc($cc)
                    //     ->send(new EmailPelanggan($this->tanggal_terima, $this->nomor_surat, $nomorlab, $randomCode, $nomorserif));

                    // return "Email sent successfully!";

                } else if ($action === 'draft') {
                    Notification::make()
                        ->title('Draft Tersimpan')
                        ->body(' Record Kupa draft berhasil disimpan dengan kode track' . $randomCode)
                        ->icon('heroicon-o-document-text')
                        ->iconColor('warning')
                        ->color('warning')
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->url(route('history_sampel.index')),

                        ])
                        ->send();
                }

                $this->successSubmit = true;
                $this->msgSuccess = $randomCode;

                $this->reset([
                    'jenis_sampel',
                    'tanggal_memo',
                    'tanggal_terima',
                    'asal_sampel',
                    'nomor_kupa',
                    'nama_pengirim',
                    'departemen',
                    'kode_sampel',
                    'jumlah_sampel',
                    'kondisi_sampel',
                    'kemasan_sampel',
                    'formData',
                    // 'alat',
                    // 'bahan',
                    'nomor_surat',
                    // 'nomor_lab',
                    'estimasi',
                    'tujuan',
                    'last_update',
                    'admin',
                    'no_hp',
                    'emailTo',
                    'emailCc',
                    'foto_sampel',
                    'skala_prioritas'
                ]);



                // dd($recipients);

            } else {
                DB::rollBack();
                Notification::make()
                    ->title('Error ')
                    ->danger()
                    ->color('danger')
                    ->send();

                $this->msgError = 'An error occurred while saving the data: ';
                // Set the error flag
                $this->errorSubmit = true;
            }
        } catch (Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error ' . $e->getMessage())
                ->danger()
                ->color('danger')
                ->send();

            // session()->flash('errorSubmit', 'An error occurred while saving the data. ' .  $e->getMessage());
            $this->msgError = 'An error occurred while saving the data: ' . $e->getMessage();
            // Set the error flag
            $this->errorSubmit = true;
        }

        $this->reset([
            'jenis_sampel',
            'tanggal_memo',
            'tanggal_terima',
            'asal_sampel',
            'nomor_kupa',
            'nama_pengirim',
            'departemen',
            'kode_sampel',
            'jumlah_sampel',
            'kondisi_sampel',
            'kemasan_sampel',
            'formData',
            // 'alat',
            // 'bahan',
            'nomor_surat',
            // 'nomor_lab',
            'estimasi',
            'tujuan',
            'last_update',
            'admin',
            'no_hp',
            'emailTo',
            'emailCc',
            'foto_sampel',
            'skala_prioritas'
        ]);
    }
}
