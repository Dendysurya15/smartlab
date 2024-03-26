<?php

namespace App\Livewire;

use App\Mail\EmailPelanggan;
use App\Models\JenisSampel;
use App\Models\Progress;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use App\Models\SendMsg;
use App\Models\TrackParameter;
use App\Models\ProgressPengerjaan;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\ToggleButtons;

class Editprogress extends Component implements HasForms
{
    use InteractsWithForms;
    public $sample;

    public static $paramstest = [];

    public $opt;
    public $database;
    public ?array $data = [];

    public function mount(): void
    {
        $this->opt = TrackSampel::with('trackParameters')->where('id', $this->sample)->first();

        $getparam = TrackParameter::with('ParameterAnalisis')->where('id_tracksampel', $this->opt->parameter_analisisid)->get()->toArray();

        // dd($getparam, $this->opt->parameter_analisisid);

        $newData['Jenis_Sampel'] = $this->opt->jenis_sampel;
        $newData['status_pengerjaan'] = $this->opt->progress;
        $newData['Asalampel'] = $this->opt->asal_sampel;
        $newData['TanggalMemo'] = $this->opt->tanggal_memo;
        $newData['TanggalTerima'] = $this->opt->tanggal_terima;
        $newData['EstimasiKupa'] = $this->opt->estimasi;
        $newData['NomorKupa'] = $this->opt->nomor_kupa;
        $newData['JumlahSampel'] = $this->opt->jumlah_sampel;
        $newData['NamaPengirim'] = $this->opt->nama_pengirim;
        $newData['NamaDep'] = $this->opt->departemen;
        $newData['NamaKodeSampel'] = $this->opt->kode_sampel;
        $newData['KemasanSampel'] = $this->opt->kemasan_sampel;
        $newData['KondisiSampel'] = $this->opt->kondisi_sampel;

        $nolab = $this->opt->nomor_lab;
        $string = $nolab;
        $parts = explode('$', $string);
        $newData['lab_kiri'] = $parts[0];
        $newData['lab_kanan'] = $parts[1] ?? '-';

        $newData['NomorSurat'] = $this->opt->nomor_surat;
        $newData['Tujuan'] = $this->opt->tujuan;
        $newData['SkalaPrioritas'] = $this->opt->skala_prioritas;
        $newData['NomorHp'] = $this->opt->no_hp;

        $alat = $this->opt->alat;
        $bahan = $this->opt->bahan;
        $personel = $this->opt->personel;

        $getarray = array_filter([
            $alat ? 'Alat' : null,
            $bahan ? 'Bahan' : null,
            $personel ? 'Personel' : null,
        ]);
        $newData['Peralatan'] = array_values($getarray);

        $newData['Emaiilto'] = $this->opt->emailTo;
        $newData['Emaiilcc'] = "";
        $newData['Diskon'] = $this->opt->discount;
        $newData['Konfirmasi'] = $this->opt->konfirmasi == 1 ? true : false;

        $params = ParameterAnalisis::where('id_jenis_sampel', $this->opt->jenis_sampel)->pluck('nama_parameter', 'id')->toArray();
        $newData['Diskon'] = $this->opt->discount;
        $newData['repeater']['status'] = $params;
        // dd($getparam);
        foreach ($getparam as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $newData['repeater'][] = [
                    'status' => $value['id_parameter'],
                    'total_sample' => $value['jumlah'],
                    'parametersdata' => $value1['nama_unsur'],
                    'harga_sampel' => $value1['harga'],
                    'subtotal' => $value['totalakhir']
                ];
            }
        }
        $img = $this->opt->foto_sampel;
        $img = explode('%', $img);
        // dd($img, $getparam);

        $newData['catatan'] = $this->opt->catatan;
        $newData['foto_sampel'] = $img;
        $newData['status_data'] = $this->opt->status;

        $this->database = $newData;

        $this->form->fill($newData);
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([

                ToggleButtons::make('status_data')
                    ->label('Status Form')
                    ->options([
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                        'Waiting Approved' => 'Waiting Approved',
                        'Draft' => 'Draft'
                    ])
                    ->colors([
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Waiting Approved' => 'info',
                        'Draft' => 'warning'
                    ])
                    ->icons([
                        'Approved' => 'heroicon-o-document-check',
                        'Rejected' => 'heroicon-o-archive-box-x-mark',
                        'Waiting Approved' => 'heroicon-o-clock',
                        'Draft' => 'heroicon-o-document-magnifying-glass'
                    ])
                    ->disabled()
                    ->inline(),
                Select::make('Jenis_Sampel')
                    ->label('Jenis Sampel')
                    ->options(JenisSampel::query()->pluck('nama', 'id'))
                    ->disabled(),
                Select::make('status_pengerjaan')
                    ->label('Status Pengerjaan')
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options(function () {
                        $jenisSampel = JenisSampel::find($this->opt->jenis_sampel);
                        // dd($jenisSampel);
                        $progress = $jenisSampel->progress;

                        $progressArray = explode(',', $progress);

                        // dd($progressArray);

                        foreach ($progressArray as $key => $value) {
                            $option =  Progress::find($value);
                            $getdata[$value] = $option->nama;
                        }
                        // dd($getdata);
                        return $getdata;
                    }),
                Select::make('Asalampel')
                    ->label('Asal Sampel')
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options([
                        'Internal' => 'Internal',
                        'Eksternal' => 'Eksternal',
                    ]),
                DateTimePicker::make('TanggalMemo')
                    ->label('Tanggal Memo')
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->seconds(true),
                DatePicker::make('TanggalTerima')
                    ->label('Tanggal Terima')
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->format('Y-m-d H:m:s'),
                DatePicker::make('EstimasiKupa')
                    ->label('Estimasi Kupa')
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->format('Y-m-d H:m:s'),
                TextInput::make('NomorKupa')
                    ->numeric()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->label('Nomor Kupa'),
                TextInput::make('JumlahSampel')
                    ->label('Jumlah Sampel')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxValue(1000)
                    ->live(),
                TextInput::make('NamaPengirim')
                    ->label('Nama Pengirim')
                    ->required()
                    ->minLength(2)
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                TextInput::make('NamaDep')
                    ->label('Nama Departemen')
                    ->minLength(2)
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                TextInput::make('NamaKodeSampel')
                    ->label('Nama Kode Sampel')
                    ->minLength(2)
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                TextInput::make('KemasanSampel')
                    ->label('Kemasan Sampel')
                    ->minLength(2)
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->required()
                    ->maxLength(255),
                Select::make('KondisiSampel')
                    ->label('Kondisi Sampel')
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options([
                        'Normal' => 'Normal',
                        'Abnormal' => 'Abnormal',
                    ]),
                Split::make([
                    TextInput::make('lab_kiri')
                        ->label('Nomor Lab')
                        ->minLength(2)
                        ->required()
                        ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                        ->prefix(function (Get $get) {
                            // dd($get('preflab'));
                            $lastTwoDigitsOfYear = Carbon::now()->format('y');
                            return $lastTwoDigitsOfYear . '-' . $get('preflab');
                        })
                        ->maxLength(255),
                    TextInput::make('lab_kanan')
                        ->label('Nomor Lab Kanan')
                        ->minLength(2)
                        ->required()
                        ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                        ->prefix(function (Get $get) {
                            // dd($get('preflab'));
                            $lastTwoDigitsOfYear = Carbon::now()->format('y');
                            return $lastTwoDigitsOfYear . '-' . $get('preflab');
                        })
                        ->maxLength(255)
                        ->hidden(fn (Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? true : false)
                ])->from('md'),

                TextInput::make('NomorSurat')
                    ->label('Nomor Surat')
                    ->minLength(2)
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                TextInput::make('Tujuan')
                    ->label('Tujuan')
                    ->minLength(2)
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                Select::make('SkalaPrioritas')
                    ->label('Skala Prioritas Sampel')
                    ->required()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options([
                        'Normal' => 'Normal',
                        'Tinggi' => 'Tinggi',
                    ]),
                TextInput::make('NomorHp')
                    ->label('NomorHp')
                    ->numeric()
                    ->tel()
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->placeholder('852xxxxxx')
                    ->minLength(2)
                    ->maxLength(255)
                    ->prefix('+62'),
                CheckboxList::make('Peralatan')
                    ->label('Peralatan')
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options([
                        'Personel' => 'Personel',
                        'Alat' => 'Alat',
                        'Bahan' => 'Bahan',
                    ])
                    ->descriptions([
                        'Personel' => 'Tersedia dan Kompeten',
                        'Alat' => 'Tersedia dan Baik',
                        'Bahan' => 'Tersedia dan Baik',
                    ])
                    ->columns(3),
                TextInput::make('Emaiilto')
                    ->label('Emaiilto')
                    ->label('Email To')
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->placeholder('Hanya untuk satu buah email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('Emaiilcc')
                    ->label('Emaiilcc')
                    ->label('Email Cc')
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->placeholder('Dapat Memasukan Lebih dari satu email dengan diakhiri dengan (;)')
                    ->maxLength(255),
                TextInput::make('Diskon')
                    ->numeric()
                    ->minLength(0)
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(2)
                    ->prefix('%'),
                Toggle::make('Konfirmasi')
                    ->inline(false)
                    ->label('Konfirmasi(Langsung / Telepon / Email)')
                    ->onColor('success')
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->offColor('danger'),

                Section::make('Pengujian sampel')
                    ->label('Pengujian Sampel')
                    ->description('Peringatan untuk crosscheck ulang seluruh data yang ingin akan dimasukkan ke sistem!')
                    ->schema([
                        Repeater::make('repeater')
                            ->label('Parameter')
                            ->schema([
                                Grid::make(5)
                                    ->schema([
                                        Select::make('status')
                                            ->options(function () {
                                                $params = ParameterAnalisis::where('id_jenis_sampel', $this->opt->jenis_sampel)->pluck('nama_parameter', 'id')->toArray();

                                                // dd($params);
                                                return $params;
                                            })

                                            ->afterStateUpdated(function ($set, $state) {
                                                $params = ParameterAnalisis::find($state);
                                                $set('parametersdata', $params->nama_unsur);
                                                $set('harga_sampel', $params->harga);
                                                $set('total_harga', $params->harga);
                                                // $set('total_sample', '3');
                                            })
                                            ->required(function () {
                                                $data = $this->opt->status;

                                                // dd($data);
                                                if ($data === 'Approved' || $data === 'Draft') {
                                                    $data = true;
                                                } else {
                                                    $data = false;
                                                }

                                                return $data;
                                            })
                                            ->live(),
                                        TextInput::make('total_sample')
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                self::updateTotals($get, $set);
                                            })
                                            ->numeric()
                                            ->minValue(1)
                                            ->required(fn ($get): bool => !is_null($get('status')) ? true : false)
                                            ->maxValue(1000)
                                            ->disabled(function ($get) {
                                                return is_null($get('status'));
                                            })
                                            ->live(true),
                                        TextInput::make('parametersdata')
                                            ->readOnly()
                                            ->disabled(function ($get) {
                                                return is_null($get('status'));
                                            }),
                                        TextInput::make('harga_sampel')
                                            ->label('Harga')
                                            ->disabled(function ($get) {
                                                return is_null($get('status'));
                                            })
                                            ->readOnly(),
                                        TextInput::make('subtotal')
                                            ->label('Total')
                                            ->readOnly()
                                            ->disabled(function ($get) {
                                                return is_null($get('status'));
                                            })
                                        // ->afterStateHydrated(function (Get $get, Set $set) {
                                        //     self::updateTotals($get, $set);
                                        // })
                                    ])
                            ])
                            ->deletable(true)
                            ->columnSpanFull()
                        // ->columns(4)


                    ])->columns(4),

                Textarea::make('catatan')
                    ->rows(10)
                    ->disabled(fn (Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->columnSpanFull(),

                Section::make('Upload')

                    ->description('Upload Foto Sampel')
                    ->schema([
                        FileUpload::make('foto_sampel')
                            ->label('Uplod Foto Sampel (Maks 5 Foto)')
                            ->image()
                            ->imageEditor()
                            ->imageEditorEmptyFillColor('#000000')
                            ->multiple()
                            ->maxFiles(5)
                            ->fetchFileInformation(false)
                            ->maxSize(3000)
                            ->uploadingMessage('Upoad Foto Sampel...')
                            ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg']),

                    ])
            ])
            ->columns(3)
            ->statePath('data');
    }

    public static function updateTotals(Get $get, Set $set): void
    {

        $selectedProducts = $get('total_sample') == '' ? 0 : $get('total_sample');

        // dd($selectedProducts);
        $harga = $get('total_harga');
        // dd($selectedProducts);

        // Calculate subtotal based on the selected products and quantities
        $subtotal = $harga * $selectedProducts;

        // Update the state with the new values
        $set('subtotal', $subtotal);
    }


    public function create(): void
    {
        // dd($this->form->getState());
        $form = $this->form->getState();
        $current = Carbon::now();
        $randomCode = generateRandomCode();
        $current = $current->format('Y-m-d H:i:s');
        $userId = 1;
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user->id;
        }


        // dd('dispatch');
        $commonRandomString = generateRandomString(rand(5, 10));
        $NomorLab = ($form['lab_kiri'] ?? '-') . '$' . ($form['lab_kanan'] ?? '-');

        if ($this->opt->status === "Approved") {
            $Alat = 'Alat';
            $Personel = 'Personel';
            $bahan = 'Bahan';
            $checkalat = in_array($Alat, $form['Peralatan']);
            $checkpersonel = in_array($Personel, $form['Peralatan']);
            $checkbahan = in_array($bahan, $form['Peralatan']);

            try {
                DB::beginTransaction();
                $id = $this->sample;
                $trackSampel = TrackSampel::find($id);
                $trackSampel->tanggal_memo = $form['TanggalMemo'];
                $trackSampel->tanggal_terima = $form['TanggalTerima'];
                $trackSampel->asal_sampel = $form['Asalampel'];
                $trackSampel->nomor_kupa = $form['NomorKupa'];
                $trackSampel->nama_pengirim = $form['NamaPengirim'];
                $trackSampel->departemen = $form['NamaDep'];
                $trackSampel->kode_sampel = $form['NamaKodeSampel'];
                $trackSampel->jumlah_sampel = $form['JumlahSampel'];
                $trackSampel->kondisi_sampel = $form['KondisiSampel'];
                $trackSampel->kemasan_sampel = $form['KemasanSampel'];
                $trackSampel->nomor_surat = $form['NomorSurat'];
                $trackSampel->nomor_lab = $NomorLab;
                $trackSampel->estimasi = $form['EstimasiKupa'];
                $trackSampel->tujuan = $form['Tujuan'];
                $trackSampel->progress = $form['status_pengerjaan'];
                $trackSampel->last_update = $current;
                $trackSampel->admin = $userId;
                $trackSampel->no_hp = $form['NomorHp'];
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
                $trackSampel->bahan = ($checkbahan ? 1 : 0);
                $trackSampel->personel = ($checkpersonel ? 1 : 0);
                $trackSampel->konfirmasi = ($form['Konfirmasi'] ? 1 : 0);
                $trackSampel->skala_prioritas = $form['SkalaPrioritas'];
                $trackSampel->discount = $form['Diskon'];
                $trackSampel->catatan = $form['catatan'];
                // dd($trackSampel->toArray()); 
                if (!empty($form['foto_sampel'])) {
                    $filename = '';

                    foreach ($form['foto_sampel'] as $key => $value) {
                        $filename .= $value . '%';
                    }
                    $donefilename = rtrim($filename, '%');
                    $trackSampel->foto_sampel = $donefilename;
                } else {
                    $trackSampel->foto_sampel = null;
                }


                // dd($form['repeater']);
                if ($form['repeater'] !== []) {
                    $idparams = $this->opt->parameter_analisisid;
                    TrackParameter::where('id_tracksampel', $idparams)->delete();
                    foreach ($form['repeater'] as $data) {

                        if ($data['status'] != null) {
                            $dataToInsert[] = [
                                'id_parameter' => $data['status'],
                                'jumlah' => $data['total_sample'],
                                'totalakhir' => $data['subtotal'],
                                'id_tracksampel' => $idparams,
                            ];
                        }
                    }

                    // dd($idparams, $dataToInsert);

                    TrackParameter::insert($dataToInsert);
                }
                $trackSampel->save();


                $getprogress = Progress::pluck('nama')->first();

                DB::commit();


                $nohp = formatPhoneNumber($form['NomorHp']);
                SendMsg::insert([
                    'no_surat' => $form['NomorSurat'],
                    'kodesample' => $randomCode,
                    'penerima' => $nohp,
                    'progres' => $getprogress,
                    'type' => 'input',
                ]);

                $now = Carbon::now();

                if ($now->hour >= 5 && $now->hour < 12) {
                    $greeting = "Selamat Pagi";
                } elseif ($now->hour >= 12 && $now->hour < 18) {
                    $greeting = "Selamat Siang";
                } else {
                    $greeting = "Selamat Malam";
                }
                $nomorserif = '-';
                Mail::to($form['Emaiilto'])
                    ->cc($form['Emaiilcc'])
                    ->send(new EmailPelanggan($form['TanggalTerima'], $form['NomorSurat'], $NomorLab, $randomCode, $nomorserif));
                $dataarr = "$greeting\n"
                    . "Yth. Pelanggan Setia Lab CBI,\n"
                    . "Sampel anda telah kami update dengan no surat *{$form['NomorSurat']}*.\n"
                    . "Progress saat ini: *$getprogress*\n"
                    . "Progress anda dapat dilihat di website https://smartlab.srs-ssms.com/tracking_sampel dengan kode tracking sample : *$randomCode*\n"
                    . "Terima kasih telah mempercayakan sampel anda untuk dianalisa di Lab kami.";

                sendwhatsapp($dataarr, $nohp);
                Notification::make()
                    ->title('Berhasil disimpan')
                    ->body(' Record berhasil Di update')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->color('success')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                DB::rollBack();

                Notification::make()
                    ->title('Error ' . $e->getMessage())
                    ->danger()
                    ->color('danger')
                    ->send();
            }
        } elseif ($this->opt->status === "Rejected") {
            Notification::make()
                ->title('Status Rejected Tidak Bisa di simpan')
                ->danger()
                ->color('danger')
                ->send();
        } elseif ($this->opt->status === "Draft") {
            $Alat = 'Alat';
            $Personel = 'Personel';
            $bahan = 'Bahan';
            $checkalat = in_array($Alat, $form['Peralatan']);
            $checkpersonel = in_array($Personel, $form['Peralatan']);
            $checkbahan = in_array($bahan, $form['Peralatan']);

            try {
                DB::beginTransaction();
                $id = $this->sample;
                $trackSampel = TrackSampel::find($id);
                $trackSampel->tanggal_memo = $form['TanggalMemo'];
                $trackSampel->tanggal_terima = $form['TanggalTerima'];
                $trackSampel->asal_sampel = $form['Asalampel'];
                $trackSampel->nomor_kupa = $form['NomorKupa'];
                $trackSampel->nama_pengirim = $form['NamaPengirim'];
                $trackSampel->departemen = $form['NamaDep'];
                $trackSampel->kode_sampel = $form['NamaKodeSampel'];
                $trackSampel->jumlah_sampel = $form['JumlahSampel'];
                $trackSampel->kondisi_sampel = $form['KondisiSampel'];
                $trackSampel->kemasan_sampel = $form['KemasanSampel'];
                $trackSampel->nomor_surat = $form['NomorSurat'];
                $trackSampel->nomor_lab = $NomorLab;
                $trackSampel->estimasi = $form['EstimasiKupa'];
                $trackSampel->tujuan = $form['Tujuan'];
                $trackSampel->progress = $form['status_pengerjaan'];
                $trackSampel->last_update = $current;
                $trackSampel->admin = $userId;
                $trackSampel->no_hp = $form['NomorHp'];
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
                $trackSampel->bahan = ($checkbahan ? 1 : 0);
                $trackSampel->personel = ($checkpersonel ? 1 : 0);
                $trackSampel->konfirmasi = ($form['Konfirmasi'] ? 1 : 0);
                $trackSampel->skala_prioritas = $form['SkalaPrioritas'];
                $trackSampel->discount = $form['Diskon'];
                $trackSampel->catatan = $form['catatan'];
                $trackSampel->status = 'Waiting Approved';
                // dd($trackSampel->toArray()); 
                if (!empty($form['foto_sampel'])) {
                    $filename = '';

                    foreach ($form['foto_sampel'] as $key => $value) {
                        $filename .= $value . '%';
                    }
                    $donefilename = rtrim($filename, '%');
                    $trackSampel->foto_sampel = $donefilename;
                } else {
                    $trackSampel->foto_sampel = null;
                }


                // dd($form['repeater']);
                if ($form['repeater'] !== []) {
                    $idparams = $this->opt->parameter_analisisid;
                    TrackParameter::where('id_tracksampel', $idparams)->delete();
                    foreach ($form['repeater'] as $data) {

                        if ($data['status'] != null) {
                            $dataToInsert[] = [
                                'id_parameter' => $data['status'],
                                'jumlah' => $data['total_sample'],
                                'totalakhir' => $data['subtotal'],
                                'id_tracksampel' => $idparams,
                            ];
                        }
                    }

                    // dd($idparams, $dataToInsert);

                    TrackParameter::insert($dataToInsert);
                }
                $trackSampel->save();


                $getprogress = Progress::pluck('nama')->first();

                DB::commit();


                $nohp = formatPhoneNumber($form['NomorHp']);

                SendMsg::insert([
                    'no_surat' => $form['NomorSurat'],
                    'kodesample' => $randomCode,
                    'penerima' => $nohp,
                    'progres' => $getprogress,
                    'type' => 'input',
                ]);

                $now = Carbon::now();

                // Determine the greeting based on the time of day
                if ($now->hour >= 5 && $now->hour < 12) {
                    $greeting = "Selamat Pagi";
                } elseif ($now->hour >= 12 && $now->hour < 18) {
                    $greeting = "Selamat Siang";
                } else {
                    $greeting = "Selamat Malam";
                }
                $nomorserif = '-';


                Mail::to($form['Emaiilto'])
                    ->cc($form['Emaiilcc'])
                    ->send(new EmailPelanggan($form['TanggalTerima'], $form['NomorSurat'], $NomorLab, $randomCode, $nomorserif));
                $dataarr = "$greeting\n"
                    . "Yth. Pelanggan Setia Lab CBI,\n"
                    . "Sampel anda telah kami terima dengan no surat *{$form['NomorSurat']}*.\n"
                    . "Progress saat ini: *$getprogress*\n"
                    . "Progress anda dapat dilihat di website https://smartlab.srs-ssms.com/tracking_sampel dengan kode tracking sample : *$randomCode*\n"
                    . "Terima kasih telah mempercayakan sampel anda untuk dianalisa di Lab kami.";

                sendwhatsapp($dataarr, $nohp);
                Notification::make()
                    ->title('Berhasil disimpan')
                    ->body(' Record berhasil Di update')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->color('success')
                    ->success()
                    ->send();


                $this->dispatch('refresh-form');
            } catch (\Exception $e) {
                DB::rollBack();

                Notification::make()
                    ->title('Error ' . $e->getMessage())
                    ->danger()
                    ->color('danger')
                    ->send();
            }
        } elseif ($this->opt->status === "Waiting Approved") {
            Notification::make()
                ->title('Harap beri Approval Terlebih Dahulu Sebelum Mengedit')
                ->danger()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(route('history_sampel.index')),

                ])
                ->color('danger')
                ->send();
        } else {
            Notification::make()
                ->title('Terjadi Kesalahan Tidak Terduga Hubungi Admin')
                ->danger()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(route('history_sampel.index')),

                ])
                ->color('danger')
                ->send();
        }
    }
    public function render(): View
    {


        return view(
            'livewire.editprogress'
        );
    }
}
