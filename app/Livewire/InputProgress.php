<?php

namespace App\Livewire;

use App\Mail\EmailPelanggan;
use App\Models\JenisSampel;
use App\Models\Progress;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use App\Models\SendMsg;
use App\Models\TrackParameter;
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
use Filament\Support\RawJs;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Forms\Components\KeyValue;

class InputProgress extends Component implements HasForms
{

    use InteractsWithForms;

    public static $paramstest = [];
    public $opt;
    protected $casts = [
        'drafting' => 'boolean',
    ];
    public ?array $data = [];

    public function mount(): void
    {
        $this->opt = TrackSampel::with('trackParameters')->orderBy('id', 'desc')->first();

        // dd($this->opt);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('Jenis_Sampel')
                    ->label('Jenis Sampel')
                    ->options(JenisSampel::query()->pluck('nama', 'id'))
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        // Retrieve the progress column value from the JenisSampel model based on the updated state
                        $jenisSampel = JenisSampel::find($state);
                        // dd($state);
                        $params = ParameterAnalisis::where('id_jenis_sampel', $state)->pluck('nama_parameter', 'id')->toArray();
                        $getlates_id = TrackSampel::with('trackParameters')->where('jenis_sampel', $state)->orderBy('id', 'desc')->first();

                        // dd($getlates_id->nomor_kupa ?? null);
                        $set('parametersAnal', $params);
                        $set('preflab', $jenisSampel->kode);
                        $set('NomorKupa', isset($getlates_id->nomor_kupa) ? $getlates_id->nomor_kupa + 1 : 1);

                        if ($jenisSampel) {
                            $progress = $jenisSampel->progress;

                            $progressArray = explode(',', $progress);

                            foreach ($progressArray as $key => $value) {
                                $option =  Progress::find($value);
                                $getdata[] = $option->nama;
                            }

                            // dd($getdata);
                            $set('progressOpt', $getdata);
                        }
                    })
                    ->required()
                    ->live(),
                Select::make('status_pengerjaan')
                    ->label('Status Pengerjaan')
                    ->disabled(function ($get) {
                        return is_null($get('Jenis_Sampel'));
                    })
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->options(fn ($get) => $get('progressOpt') ?: []),
                Select::make('Asalampel')
                    ->label('Asal Sampel')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->options([
                        'Internal' => 'Internal',
                        'Eksternal' => 'Eksternal',
                    ]),
                DateTimePicker::make('TanggalMemo')
                    ->label('Tanggal Memo')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->default(function (Get $get, Set $set) {
                        $date = now();

                        $set('tanggalnowmemo', $date);
                        return $date;
                    })
                    ->seconds(true),
                DatePicker::make('TanggalTerima')
                    ->label('Tanggal Terima')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->default(function (Get $get, Set $set) {
                        // $date = '23-04-1997';
                        $date = $get('tanggalnowmemo');
                        $carbonDate = Carbon::parse($date);

                        // Check if the hour is greater than or equal to 12
                        if ($carbonDate->hour >= 12) {
                            // Add one day if the hour is greater than or equal to 12
                            $newDate = $carbonDate->addDay()->toDateString();
                        } else {
                            // Use the current date if the hour is less than 12
                            $newDate = $carbonDate->toDateString();
                        }
                        // dd($date, $newDate);
                        return $newDate;
                    })
                    ->format('Y-m-d H:m:s'),
                DatePicker::make('EstimasiKupa')
                    ->label('Estimasi Kupa')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->format('Y-m-d H:m:s'),
                TextInput::make('NomorKupa')
                    ->numeric()
                    ->minValue(1)
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->label('Nomor Kupa'),
                TextInput::make('JumlahSampel')
                    ->label('Jumlah Sampel')
                    ->numeric()
                    ->minValue(1)
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxValue(1000)

                    ->live(),
                TextInput::make('NamaPengirim')
                    ->label('Nama Pengirim')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('NamaDep')
                    ->label('Nama Departemen')
                    ->minLength(2)
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                TextInput::make('NamaKodeSampel')
                    ->label('Nama Kode Sampel')
                    ->minLength(2)
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->hidden(fn (Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? false : true)
                    ->maxLength(255),

                Textarea::make('NamaKodeSampeljamak')
                    ->label('Nama Kode Sampel')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->placeholder('Harap Pastikan hanya paste satu baris saja dari excel.')
                    ->autosize()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {

                        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($state));
                        $array = explode('$', $NamaKodeSampeljamak);
                        $result = array_combine($array, $array);
                        // dd($result);

                        $set('setoption_costumparams', $result);
                    })
                    ->hidden(fn (Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? true : false),

                TextInput::make('KemasanSampel')
                    ->label('Kemasan Sampel')
                    ->minLength(2)
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                Select::make('KondisiSampel')
                    ->label('Kondisi Sampel')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->options([
                        'Normal' => 'Normal',
                        'Abnormal' => 'Abnormal',
                    ]),
                Split::make([
                    TextInput::make('lab_kiri')
                        ->label('Nomor Lab')
                        ->minLength(1)
                        ->required(fn (Get $get): bool => $get('drafting') !== true)
                        ->prefix(function (Get $get, Set $set) {
                            $lastTwoDigitsOfYear = Carbon::now()->format('y');
                            return $lastTwoDigitsOfYear . '-' . $get('preflab');
                        })
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (!is_numeric($state)) {
                                $data = '';
                            } else {
                                $data = (int)$state + $get('JumlahSampel');
                            }
                            $set('lab_kanan', $data);
                        })
                        ->live()
                        ->maxLength(255),
                    TextInput::make('lab_kanan')
                        ->label('Nomor Lab Kanan')
                        ->minLength(1)
                        ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
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
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                TextInput::make('Tujuan')
                    ->label('Tujuan')
                    ->minLength(2)
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                Select::make('SkalaPrioritas')
                    ->label('Skala Prioritas Sampel')
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->options([
                        'Normal' => 'Normal',
                        'Tinggi' => 'Tinggi',
                    ]),
                CheckboxList::make('Peralatan')
                    ->label('Peralatan')
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
                    // ->required()
                    ->columns(3),



                Section::make()
                    ->schema([
                        TextInput::make('Emaiilto')
                            ->label('Email To')
                            ->placeholder('Harap pisahkan dengan Koma (,) Jika lebih dari satu')
                            ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                            ->maxLength(255),
                        TextInput::make('Emaiilcc')
                            ->label('Email Cc')
                            ->placeholder('Harap pisahkan dengan Koma (,) Jika lebih dari satu')
                            ->maxLength(255),

                        TextInput::make('Diskon')
                            ->numeric()
                            ->minLength(0)
                            ->maxLength(2)
                            ->prefix('%'),
                        Toggle::make('Konfirmasi')
                            ->inline(false)
                            ->default(true)
                            ->label('Konfirmasi(Langsung / Telepon / Email)')
                            ->onColor('success')
                            ->offColor('danger'),
                        Repeater::make('nomerhpuser')
                            ->label('Nomor Hp')
                            ->schema([
                                PhoneInput::make('NomorHp')
                                    ->label('Masukan Nomor Hp')
                                    ->defaultCountry('id')

                                    ->onlyCountries(['tr', 'us', 'gb', 'id']),
                            ])
                            ->default([])
                            ->grid(4)
                            ->columnSpanFull()

                    ])
                    ->columns(4),

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
                                            ->options(fn ($get) => $get('../../parametersAnal') ?: [])
                                            ->required(fn (Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ->afterStateUpdated(function ($set, $state) {
                                                $params = ParameterAnalisis::find($state);
                                                $set('parametersdata', $params->nama_unsur);
                                                $set('harga_sampel', $params->harga);
                                                $set('total_harga', $params->harga);
                                            })
                                            ->disabled(function ($get) {
                                                return is_null($get('../../parametersAnal'));
                                            })
                                            ->live(),
                                        TextInput::make('total_sample')
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                self::updateTotals($get, $set);
                                            })
                                            ->numeric()
                                            ->minValue(1)
                                            ->required(fn (Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ->maxValue(1000)
                                            ->disabled(function ($get) {
                                                return is_null($get('parametersdata'));
                                            })
                                            ->live(true),
                                        TextInput::make('parametersdata')
                                            ->readOnly()
                                            ->disabled(function ($get) {
                                                return is_null($get('parametersdata'));
                                            }),
                                        TextInput::make('harga_sampel')
                                            ->label('Harga')
                                            ->disabled(function ($get) {
                                                return is_null($get('parametersdata'));
                                            })
                                            ->readOnly(),
                                        TextInput::make('subtotal')
                                            ->label('Total')
                                            ->readOnly()
                                            ->disabled(function ($get) {
                                                return is_null($get('parametersdata'));
                                            })
                                            ->afterStateHydrated(function (Get $get, Set $set) {
                                                self::updateTotals($get, $set);
                                            }),
                                        CheckboxList::make('nama_lab')
                                            ->label('Nama Lab')
                                            ->columns(2)
                                            ->bulkToggleable()
                                            ->default([])
                                            ->options(function (Get $get) {
                                                return $get('../../setoption_costumparams');
                                            })
                                            ->disabled(function ($get) {
                                                return is_null($get('parametersdata'));
                                            })
                                            ->required(fn (Get $get): bool => $get('../../drafting') !== True ? True : false)
                                    ])

                            ])
                            ->deletable(true)
                            ->columnSpanFull()
                    ])->columns(4),
                Textarea::make('catatan')
                    ->rows(10)
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

                    ]),
                Grid::make(3)
                    ->schema([

                        Toggle::make('drafting')
                            ->columnStart([
                                'sm' => 4,
                                'xl' => 4,
                                '2xl' => 4,
                            ])
                            ->label('Simpan sebagai Draft')
                            ->default(false)
                            ->onIcon('heroicon-o-document-magnifying-glass')
                            ->offIcon('heroicon-o-clock')
                            ->live()
                    ])
            ])
            ->columns(3)
            ->statePath('data');
    }

    public static function updateTotals(Get $get, Set $set): void
    {

        $selectedProducts = $get('total_sample');
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
        // dd($form);

        $current = Carbon::now();
        $randomCode = generateRandomCode();
        $current = $current->format('Y-m-d H:i:s');
        $userId = 1;
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user->id;
        }

        $Alat = 'Alat';
        $Personel = 'Personel';
        $bahan = 'Bahan';
        $checkalat = in_array($Alat, $form['Peralatan']);
        $checkpersonel = in_array($Personel, $form['Peralatan']);
        $checkbahan = in_array($bahan, $form['Peralatan']);
        // dd($form['tags']);
        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($form['NamaKodeSampeljamak']));

        // dd($NamaKodeSampeljamak);
        $commonRandomString = generateRandomString(rand(5, 10));
        $NomorLab = ($form['lab_kiri'] ?? '-') . '$' . ($form['lab_kanan'] ?? '-');
        if ($form['drafting'] !== true) {
            // dd('bukan Draft');

            try {
                DB::beginTransaction();
                $trackSampel = new TrackSampel();
                $trackSampel->jenis_sampel = $form['Jenis_Sampel'];
                $trackSampel->tanggal_memo = $form['TanggalMemo'];
                $trackSampel->tanggal_terima = $form['TanggalTerima'];
                $trackSampel->asal_sampel = $form['Asalampel'];
                $trackSampel->nomor_kupa = $form['NomorKupa'];
                $trackSampel->nama_pengirim = $form['NamaPengirim'];
                $trackSampel->departemen = $form['NamaDep'];
                $trackSampel->kode_sampel = $form['NamaKodeSampel'] ?? $NamaKodeSampeljamak;
                $trackSampel->jumlah_sampel = $form['JumlahSampel'];
                $trackSampel->kondisi_sampel = $form['KondisiSampel'];
                $trackSampel->kemasan_sampel = $form['KemasanSampel'];
                $trackSampel->nomor_surat = $form['NomorSurat'];
                $trackSampel->nomor_lab = $NomorLab;
                $trackSampel->estimasi = $form['EstimasiKupa'];
                $trackSampel->tujuan = $form['Tujuan'];
                $trackSampel->progress = 4;
                $trackSampel->last_update = $current;
                $trackSampel->admin = $userId;
                $nomorHpArray = array_column($form['nomerhpuser'], 'NomorHp');
                $combinedNomorHp = implode(',', $nomorHpArray);
                $trackSampel->no_hp = $combinedNomorHp;
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
                $trackSampel->emailCc = $form['Emaiilcc'];
                $trackSampel->bahan = ($checkbahan ? 1 : 0);
                $trackSampel->personel = ($checkpersonel ? 1 : 0);
                $trackSampel->konfirmasi = ($form['Konfirmasi'] ? 1 : 0);
                $trackSampel->parameter_analisisid = $commonRandomString;
                $trackSampel->kode_track = $randomCode;
                $trackSampel->skala_prioritas = $form['SkalaPrioritas'];
                $trackSampel->discount = $form['Diskon'];
                $trackSampel->catatan = $form['catatan'];
                // dd($trackSampel->toArray()); 
                if ($form['foto_sampel']) {
                    $filename = '';
                    foreach ($form['foto_sampel'] as $key => $value) {
                        $filename .= $value . '%';
                    }
                    $donefilename = rtrim($filename, '%');
                    $trackSampel->foto_sampel = $donefilename;
                }


                if ($form['repeater'] !== []) {
                    // dd($form['repeater']);
                    foreach ($form['repeater'] as $data) {
                        $dataToInsert[] = [
                            'id_parameter' => $data['status'],
                            'jumlah' => $data['total_sample'],
                            'totalakhir' => $data['subtotal'],
                            'namakode_sampel' => implode('$', $data['nama_lab']),
                            'id_tracksampel' => $commonRandomString,
                        ];
                    }
                    // dd($dataToInsert);
                    TrackParameter::insert($dataToInsert);
                }
                $getprogress = Progress::pluck('nama')->first();




                // $nohp = formatPhoneNumber($form['nomerhpuser']);
                // dd($form['nomerhpuser']);
                if ($form['nomerhpuser'] !== []) {
                    foreach ($form['nomerhpuser'] as $data) {
                        $dataToInsert2[] = [
                            'no_surat' => $form['NomorSurat'],
                            'kodesample' => $randomCode,
                            'penerima' =>  str_replace('+', '', $data['NomorHp']),
                            'progres' => $getprogress,
                            'type' => 'input',
                        ];
                    }
                    // dd($dataToInsert);
                    SendMsg::insert($dataToInsert2);
                }
                // SendMsg::insert([
                //     'no_surat' => $form['NomorSurat'],
                //     'kodesample' => $randomCode,
                //     'penerima' => $nohp,
                //     'progres' => $getprogress,
                //     'type' => 'input',
                // ]);

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
                $emailAddresses = !empty($form['Emaiilto']) ? explode(',', $form['Emaiilto']) : null;
                $emailcc = !empty($form['Emaiilcc']) ? explode(',', $form['Emaiilcc']) : null;

                if ($emailAddresses !== null) {
                    Mail::to($emailAddresses)
                        ->cc($emailcc)
                        ->send(new EmailPelanggan($form['TanggalTerima'], $form['NomorSurat'], $NomorLab, $randomCode, $nomorserif));
                }
                $dataarr = "$greeting\n"
                    . "Yth. Pelanggan Setia Lab CBI,\n"
                    . "Sampel anda telah kami terima dengan no surat *{$form['NomorSurat']}*.\n"
                    . "Progress saat ini: *$getprogress*\n"
                    . "Progress anda dapat dilihat di website https://smartlab.srs-ssms.com/tracking_sampel dengan kode tracking sample : *$randomCode*\n"
                    . "Terima kasih telah mempercayakan sampel anda untuk dianalisa di Lab kami.";
                $trackSampel->save();
                DB::commit();
                // sendwhatsapp($dataarr, $nohp);
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

                $this->form->fill();
            } catch (\Exception $e) {
                DB::rollBack();

                Notification::make()
                    ->title('Error ' . $e->getMessage())
                    ->danger()
                    ->color('danger')
                    ->send();
            }
        } else {
            // dd('Ini draft');

            // dd('send');
            try {
                $getprogress = Progress::pluck('nama')->first();
                DB::beginTransaction();
                $trackSampel = new TrackSampel();
                $trackSampel->jenis_sampel = $form['Jenis_Sampel'];
                $trackSampel->tanggal_memo = $form['TanggalMemo'];
                $trackSampel->tanggal_terima = $form['TanggalTerima'];
                $trackSampel->asal_sampel = $form['Asalampel'];
                $trackSampel->nomor_kupa = $form['NomorKupa'];
                $trackSampel->nama_pengirim = $form['NamaPengirim'];
                $trackSampel->departemen = $form['NamaDep'];
                $trackSampel->kode_sampel = $form['NamaKodeSampel'] ?? $NamaKodeSampeljamak;
                $trackSampel->jumlah_sampel = $form['JumlahSampel'];
                $trackSampel->kondisi_sampel = $form['KondisiSampel'];
                $trackSampel->kemasan_sampel = $form['KemasanSampel'];
                $trackSampel->nomor_surat = $form['NomorSurat'];
                $trackSampel->nomor_lab = $NomorLab;
                $trackSampel->estimasi = $form['EstimasiKupa'];
                $trackSampel->tujuan = $form['Tujuan'];
                $trackSampel->progress = 4;
                $trackSampel->last_update = $current;
                $nomorHpArray = array_column($form['nomerhpuser'], 'NomorHp');
                $combinedNomorHp = implode(',', $nomorHpArray);
                $trackSampel->no_hp = $combinedNomorHp;
                $trackSampel->admin = $userId;
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
                $trackSampel->emailCc = $form['Emaiilcc'];
                $trackSampel->bahan = ($checkbahan ? 1 : 0);
                $trackSampel->personel = ($checkpersonel ? 1 : 0);
                $trackSampel->konfirmasi = ($form['Konfirmasi'] ? 1 : 0);
                $trackSampel->parameter_analisisid = $commonRandomString;
                $trackSampel->kode_track = $randomCode;
                $trackSampel->skala_prioritas = $form['SkalaPrioritas'];
                $trackSampel->discount = $form['Diskon'];
                $trackSampel->catatan = $form['catatan'];
                $trackSampel->status = 'Draft';
                // dd($trackSampel->toArray()); 
                if ($form['foto_sampel']) {
                    $filename = '';
                    foreach ($form['foto_sampel'] as $key => $value) {
                        $filename .= $value . '%';
                    }
                    $donefilename = rtrim($filename, '%');
                    $trackSampel->foto_sampel = $donefilename;
                }
                // dd($form['nomerhpuser']);

                if ($form['nomerhpuser'] !== []) {

                    // dd($form['nomerhpuser']);
                    foreach ($form['nomerhpuser'] as $data) {

                        $dataToInsert[] = [
                            'no_surat' => $form['NomorSurat'] ?? '-',
                            'kodesample' => $randomCode,
                            'penerima' =>  str_replace('+', '', $data['NomorHp']),
                            'progres' => $getprogress,
                            'type' => 'input',
                        ];
                    }

                    // dd($dataToInsert);
                    SendMsg::insert($dataToInsert);
                }
                // dd($form['repeater']);
                if ($form['repeater'] !== []) {

                    if ($form['repeater'][0]['status'] != null) {
                        foreach ($form['repeater'] as $data) {
                            // dd($form['repeater']);
                            if ($data['status'] != null) {
                                $dataToInsert2[] = [
                                    'id_parameter' => $data['status'],
                                    'jumlah' => $data['total_sample'],
                                    'totalakhir' => $data['subtotal'],
                                    'namakode_sampel' => implode('$', $data['nama_lab']),
                                    'id_tracksampel' => $commonRandomString,
                                ];
                            }
                        }
                        // dd($dataToInsert);
                        TrackParameter::insert($dataToInsert2);
                    } else {
                        # code...
                    }
                }
                $trackSampel->save();
                DB::commit();

                Notification::make()
                    ->title('Berhasil disimpan')
                    ->body('Draft Berhasil Disaimpan dengan Kode ' . $randomCode)
                    ->icon('heroicon-o-document-text')
                    ->iconColor('warning')
                    ->color('warning')
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->url(route('history_sampel.index')),

                    ])
                    ->success()
                    ->send();
                $this->form->fill();
            } catch (\Exception $e) {
                DB::rollBack();

                Notification::make()
                    ->title('Error ' . $e->getMessage())
                    ->danger()
                    ->color('danger')
                    ->send();
            }
        }
    }

    public function render(): View
    {
        return view('livewire.input-progress');
    }
}
