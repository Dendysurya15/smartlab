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

class InputProgress extends Component implements HasForms
{

    use InteractsWithForms;

    public static $paramstest = [];

    protected $casts = [
        'drafting' => 'boolean',
    ];
    public ?array $data = [];

    public function mount(): void
    {
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

                        $params = ParameterAnalisis::where('id_jenis_sampel', $state)->pluck('nama_parameter', 'id')->toArray();
                        // dd($jenisSampel->kode);
                        $set('parametersAnal', $params);
                        $set('preflab', $jenisSampel->kode);
                        if ($jenisSampel) {
                            $progress = $jenisSampel->progress;

                            $progressArray = explode(',', $progress);

                            foreach ($progressArray as $key => $value) {
                                $option =  Progress::find($value);
                                $getdata[] = $option->nama;
                            }

                            // dd($getdata);
                            $set('progressOpt', $getdata);
                        } else {
                            // Handle the case where the JenisSampel with the given state is not found
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
                    // ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->required()
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
                    ->maxLength(255),
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
                        ->minLength(2)
                        ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                        ->prefix(function (Get $get) {
                            // dd($get('preflab'));
                            $lastTwoDigitsOfYear = Carbon::now()->format('y');
                            return $lastTwoDigitsOfYear . '-' . $get('preflab');
                        })
                        ->maxLength(255),
                    TextInput::make('lab_kanan')
                        ->label('Nomor Lab Kanan')
                        ->minLength(2)
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
                TextInput::make('NomorHp')
                    ->label('NomorHp')
                    ->numeric()
                    ->tel()
                    ->placeholder('852xxxxxx')
                    ->minLength(2)
                    ->maxLength(255)
                    ->prefix('+62'),
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
                TextInput::make('Emaiilto')
                    ->label('Emaiilto')
                    ->label('Email To')
                    ->placeholder('Hanya untuk satu buah email')
                    ->email()
                    ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                TextInput::make('Emaiilcc')
                    ->label('Emaiilcc')
                    ->label('Email Cc')
                    ->placeholder('Dapat Memasukan Lebih dari satu email dengan diakhiri dengan (;)')
                    ->maxLength(255),

                TextInput::make('Diskon')
                    ->numeric()
                    ->minLength(0)
                    ->maxLength(2)
                    ->prefix('%'),
                Toggle::make('Konfirmasi')
                    ->inline(false)
                    ->label('Konfirmasi(Langsung / Telepon / Email)')
                    ->onColor('success')
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
                                            ->options(fn ($get) => $get('../../parametersAnal') ?: [])
                                            ->required(fn (Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ->afterStateUpdated(function ($set, $state) {
                                                $params = ParameterAnalisis::find($state);
                                                $set('parametersdata', $params->nama_unsur);
                                                $set('harga_sampel', $params->harga);
                                                $set('total_harga', $params->harga);
                                                // $set('total_sample', '3');
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
                                            })
                                    ])

                            ])
                            ->deletable(true)
                            ->columnSpanFull()
                        // ->columns(4)


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
        // dd($form['drafting']);
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
                $trackSampel->kode_sampel = $form['NamaKodeSampel'];
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
                $trackSampel->no_hp = $form['NomorHp'];
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
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

                // dd($form['repeater']);
                if ($form['repeater'] !== []) {
                    foreach ($form['repeater'] as $data) {
                        $dataToInsert[] = [
                            'id_parameter' => $data['status'],
                            'jumlah' => $data['total_sample'],
                            'totalakhir' => $data['subtotal'],
                            'id_tracksampel' => $commonRandomString,
                        ];
                    }
                    // dd($dataToInsert);
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
                DB::beginTransaction();
                $trackSampel = new TrackSampel();
                $trackSampel->jenis_sampel = $form['Jenis_Sampel'];
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
                $trackSampel->progress = 4;
                $trackSampel->last_update = $current;
                $trackSampel->admin = $userId;
                $trackSampel->no_hp = $form['NomorHp'];
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
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

                // dd($form['repeater']);
                if ($form['repeater'] !== []) {

                    if ($form['repeater'][0]['status'] != null) {
                        foreach ($form['repeater'] as $data) {

                            if ($data['status'] != null) {
                                $dataToInsert[] = [
                                    'id_parameter' => $data['status'],
                                    'jumlah' => $data['total_sample'],
                                    'totalakhir' => $data['subtotal'],
                                    'id_tracksampel' => $commonRandomString,
                                ];
                            }
                        }
                        // dd($dataToInsert);
                        TrackParameter::insert($dataToInsert);
                    } else {
                        # code...
                    }
                }
                $trackSampel->save();


                $getprogress = Progress::pluck('nama')->first();

                DB::commit();


                $nohp = numberformat($form['NomorHp']);

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


                $nomorserif = '-';
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
