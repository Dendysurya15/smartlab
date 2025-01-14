<?php

namespace App\Livewire;

use App\Events\Smartlabsnotification;
use App\Mail\EmailPelanggan;
use App\Models\DepartementTrack;
use App\Models\JenisSampel;
use App\Models\Progress;
use App\Models\ParameterAnalisis;
use App\Models\TrackSampel;
use App\Models\SendMsg;
use App\Models\TrackParameter;
use App\Models\User;
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
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFit;
use Spatie\Permission\Models\Role;
use App\Models\Lablabel;

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
        // $data = TrackSampel::all()->pluck('departemen')->unique();
        // foreach ($data as $key => $value) {
        //     // dd($value);
        //     DB::table('departemet_pelanggan')->updateOrInsert([
        //         'nama' => $value
        //     ]);
        // }
        // dd($data);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                DatePicker::make('Nomor_Lab_Label')
                    ->label('Reset Nomor Lab Label')
                    ->default(function (Set $set) {
                        $labLabel = Lablabel::latest('tanggal')
                            ->first();
                        $labLabel = Carbon::parse($labLabel->tanggal)->format('Y-m-d');
                        $today = Carbon::now()->format('Y-m-d');

                        if ($today > $labLabel) {
                            // If today is after lab label date, set to next year January 1st
                            $nextYear = Carbon::parse($labLabel)->addYear()->startOfYear()->format('Y-m-d');
                            $set('default_lab_label', $nextYear);
                        } else {
                            $set('default_lab_label', $labLabel);
                        }

                        return $labLabel;
                    })
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $labLabel = Lablabel::latest('tanggal')
                            ->first();
                        $labLabel->tanggal = $state;
                        $labLabel->save();

                        $labLabel = Carbon::parse($labLabel->tanggal)->format('Y-m-d');
                        $today = Carbon::now()->format('Y-m-d');

                        if ($today > $labLabel) {
                            // If today is after lab label date, set to next year January 1st
                            $nextYear = Carbon::parse($labLabel)->addYear()->startOfYear()->format('Y-m-d');
                            $set('default_lab_label', $nextYear);
                        } else {
                            $set('default_lab_label', $labLabel);
                        }


                        Notification::make()
                            ->title('Nomor Lab Label berhasil diubah')
                            ->color('success')
                            ->success()
                            ->send();
                    })
                    ->live(debounce: 500)
                    ->required(),
                Select::make('Jenis_Sampel')
                    ->label('Jenis Komoditas')
                    ->options(JenisSampel::query()->where('soft_delete_id', '!=', 1)->pluck('nama', 'id'))
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        // Retrieve the progress column value from the JenisSampel model based on the updated state
                        // dd($state);
                        // Attempt to find the JenisSampel by its ID
                        $jenisSampel = JenisSampel::find($state);

                        // Use the ternary operator to set the 'preflab' value

                        $params = ParameterAnalisis::where('id_jenis_sampel', $state)->pluck('nama_parameter', 'id')->toArray();
                        $getlates_id = TrackSampel::with('trackParameters')->where('jenis_sampel', $state)->orderBy('id', 'desc')->first();
                        $getlates_doc = TrackSampel::with('trackParameters')
                            ->where('no_doc', '!=', null)
                            ->orderBy('id', 'desc')
                            ->first();

                        $getlates_doc_identitas = TrackSampel::with('trackParameters')
                            ->where('no_doc_indentitas', '!=', null)
                            ->orderBy('id', 'desc')
                            ->first();

                        // Extract the 'no_doc' field from the latest document
                        $laststring = $getlates_doc ? $getlates_doc->no_doc : null;
                        $laststring_identitas = $getlates_doc_identitas ? $getlates_doc_identitas->no_doc_indentitas : null;
                        // dd($laststring_identitas);
                        // Increment the version or set to default if no document is found
                        $newString = $laststring ? incrementVersion($laststring) : 'FR-7.1-1.1';
                        $newString_identitas = $laststring_identitas ? incrementVersion_identitas($laststring_identitas) : 'FR-7.4-1.2-1';
                        // Assign the new string to 'no_document'
                        $set('no_document', $newString);
                        $set('no_document_indentitas', $newString_identitas);
                        $set('preflab', $jenisSampel ? $jenisSampel->kode : '1'); // Replace 'default_value' with the appropriate default value
                        $set('parametersAnal', $params);
                        $set('nama_formulir', 'Kaji Ulang Permintaan,Tender dan Kontrak Sampel' . ' ' . $jenisSampel->nama);
                        $set('NomorKupa', isset($getlates_id->nomor_kupa) ? $getlates_id->nomor_kupa + 1 : 1);
                        $set('JumlahSampel', '');
                        $set('lab_kiri', '');
                        $set('lab_kanan', '');
                        if ($jenisSampel) {
                            $progress = $jenisSampel->progress;

                            $progressArray = explode(',', $progress);

                            foreach ($progressArray as $key => $value) {
                                $option =  Progress::find($value);
                                $getdata[$option->id] = $option->nama;
                            }

                            // dd($getdata);
                            $set('progressOpt', $getdata);
                        }
                    })
                    ->required()
                    ->live(debounce: 500),
                TextInput::make('jenis_pupuk')
                    ->label(function (Get $get) {
                        $string = JenisSampel::find($get('Jenis_Sampel'))->nama ?? 'Sample';
                        return 'Jenis Sampel ' . $string;
                        // dd($get('Jenis_Sampel'));
                    })
                    // ->hidden(fn(Get $get): bool => $get('Jenis_Sampel') !== '11' ? True : false)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false),
                Select::make('status_pengerjaan')
                    ->label('Status Pengerjaan')
                    ->disabled(function ($get) {
                        return is_null($get('Jenis_Sampel'));
                    })

                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->options(function (Get $get) {
                        // dd($state);
                        return $get('progressOpt');
                    }),
                Select::make('Asalampel')
                    ->label('Asal Sampel')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->default(function () {
                        $user = User::find(auth()->user()->id);

                        // Get all role names as a collection
                        $roles = $user->getRoleNames();

                        // dd($roles);
                        if ($roles->contains('marcom')) {
                            return 'Eksternal';
                        } else {
                            return 'Internal';
                        }
                    })
                    // ->disabled(auth()->user()->role == 'marcom')
                    ->disabled(function () {
                        $user = User::find(auth()->user()->id);

                        // Get all role names as a collection
                        $roles = $user->getRoleNames();

                        // dd($roles);
                        if ($roles->contains('marcom')) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->options([
                        'Internal' => 'Internal',
                        'Eksternal' => 'Eksternal',
                    ])
                    ->live(debounce: 500),
                DateTimePicker::make('TanggalMemo')
                    ->label('Tanggal Memo')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->default(function (Get $get, Set $set) {
                        $date = now();

                        $set('tanggalnowmemo', $date);
                        return $date;
                    })
                    ->seconds(true),
                DatePicker::make('TanggalTerima')
                    ->label('Tanggal Terima')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
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
                        return  $newDate;
                    })
                    ->format('Y-m-d H:m:s'),
                DatePicker::make('EstimasiKupa')
                    ->label('Estimasi Kupa')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->format('Y-m-d H:m:s'),
                TextInput::make('NomorKupa')
                    ->numeric()
                    ->minValue(1)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->label('Nomor Kupa'),
                TextInput::make('JumlahSampel')
                    ->label('Jumlah Sampel')
                    ->numeric()
                    ->minValue(1)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $tanggalTerima = $get('Nomor_Lab_Label');
                        $selectedYear = $tanggalTerima ? Carbon::parse($tanggalTerima)->year : date('Y');

                        // Get the latest record for the selected year
                        $getlates_id = TrackSampel::with('trackParameters')
                            ->where('jenis_sampel', $get('Jenis_Sampel'))
                            // ->whereYear('tanggal_terima', $selectedYear) // Changed from created_at to tanggal_terima
                            ->orderBy('id', 'desc')
                            ->first();

                        // dd($getlates_id);
                        // If no record exists for selected year or nomor_lab is null, start from 1
                        if (!$getlates_id || !$getlates_id->nomor_lab) {
                            $set('lab_kiri', '1');
                            $set('lab_kanan', $state);
                            $set('NamaKodeSampeljamak', '');
                            return;
                        }

                        $nomorlab = explode('$', $getlates_id->nomor_lab);

                        if (count($nomorlab) > 0) {
                            $nomorlabdata = $nomorlab[1] ?? '-';

                            if ($nomorlabdata !== '-') {
                                $data_labkiri = (int)$nomorlab[1] + 1;
                            } else {
                                $data_labkiri = (int)$nomorlab[0] + 1;
                            }

                            $data_labkanan = (int)$state + $data_labkiri - 1;

                            $set('lab_kiri', $data_labkiri);
                            $set('lab_kanan', $data_labkanan);
                            $set('NamaKodeSampeljamak', '');
                        } else {
                            // Fallback if the nomor_lab format is unexpected
                            $set('lab_kiri', '1');
                            $set('lab_kanan', $state);
                            $set('NamaKodeSampeljamak', '');
                        }
                    })
                    ->maxValue(1000)
                    ->live(debounce: 500),
                TextInput::make('NamaPengirim')
                    ->label('Nama Pengirim')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->minLength(2)
                    ->maxLength(255),
                // TextInput::make('NamaDep')
                //     ->label('Nama Departemen')
                //     ->minLength(2)
                //     ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                //     ->maxLength(255),
                Select::make('NamaDep')
                    ->label('Nama Departemen')
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('nama')
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        // Check if the department already exists in the database
                        $check = DepartementTrack::where('nama', $data['nama'])->first();

                        if ($check) {
                            Notification::make()
                                ->title('Departemen ini sudah ada di dalam database')
                                ->color('warning')
                                ->warning()
                                ->send();

                            // You should return something, possibly the existing ID or some other response
                            return $check->id;
                        }

                        // Create new department
                        $newDepartment = DepartementTrack::create($data);

                        Notification::make()
                            ->title('Departemen baru berhasil ditambahkan')
                            ->color('success')
                            ->success()
                            ->send();

                        // Return the newly created department ID
                        return $newDepartment->id;
                    })
                    ->options(DepartementTrack::query()->pluck('nama', 'nama'))
                    ->required(fn(Get $get): bool => $get('drafting') !== true),
                TextInput::make('NamaKodeSampel')
                    ->label('Nama Kode Sampel')
                    ->minLength(2)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->hidden(fn(Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? false : true)
                    ->maxLength(255)
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($state));
                        $array = explode('$', $NamaKodeSampeljamak);
                        $result = array_combine($array, $array);
                        $jumlahsample = $get('JumlahSampel');
                        $jumlah_kodesampel = count($array);
                        if ((int)$jumlahsample !== $jumlah_kodesampel) {
                            Notification::make()
                                ->title('Jumlah Kode sampel tidak sama dengan jumlah sampel harap dicek terlebih dahulu')
                                ->iconColor('danger')
                                ->color('warning')
                                ->success()
                                ->send();
                            $set('setoption_costumparams', []);
                        } else {
                            Notification::make()
                                ->title('Jumlah Kode sampel  dengan jumlah sampel sudah sesuai')
                                ->iconColor('success')
                                ->color('success')
                                ->success()
                                ->send();
                            $set('setoption_costumparams', $result);
                        }
                    })
                    ->live(debounce: 1500),
                Textarea::make('NamaKodeSampeljamak')
                    ->label('Nama Kode Sampel')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->placeholder('Harap Pastikan hanya paste satu baris saja dari excel.')
                    ->autosize()
                    ->live(debounce: 1500)
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($state));
                        $array = explode('$', $NamaKodeSampeljamak);
                        $result = array_combine($array, $array);
                        $jumlahsample = $get('JumlahSampel');
                        $jumlah_kodesampel = count($array);
                        if ((int)$jumlahsample !== $jumlah_kodesampel) {
                            Notification::make()
                                ->title('Jumlah Kode sampel tidak sama dengan jumlah sampel harap dicek terlebih dahulu')
                                ->iconColor('danger')
                                ->color('warning')
                                ->success()
                                ->send();
                            $set('setoption_costumparams', []);
                        } else {
                            Notification::make()
                                ->title('Jumlah Kode sampel  dengan jumlah sampel sudah sesuai')
                                ->iconColor('success')
                                ->color('success')
                                ->success()
                                ->send();
                            $set('setoption_costumparams', $result);
                        }
                    })
                    ->hidden(fn(Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? true : false),

                TextInput::make('KemasanSampel')
                    ->label('Kemasan Sampel')
                    ->minLength(2)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                Select::make('KondisiSampel')
                    ->label('Kondisi Sampel')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->options([
                        'Normal' => 'Normal',
                        'Abnormal' => 'Abnormal',
                    ]),
                Split::make([
                    TextInput::make('lab_kiri')
                        ->label('Nomor Lab')
                        ->minLength(1)
                        ->required(fn(Get $get): bool => $get('drafting') !== true)
                        ->prefix(function (Get $get, Set $set) {
                            $tanggalTerima = $get('default_lab_label');
                            $lastTwoDigitsOfYear = $tanggalTerima ? Carbon::parse($tanggalTerima)->format('y') : Carbon::now()->format('y');
                            // $lastTwoDigitsOfYear = '25';
                            return $lastTwoDigitsOfYear . '-' . $get('preflab');
                        })
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (!is_numeric($state)) {
                                $data = '';
                            } else {
                                $data = (int)$state + $get('JumlahSampel') - 1;
                            }
                            $set('lab_kanan', $data);
                        })
                        ->numeric()
                        ->live(debounce: 500)
                        ->maxLength(255),
                    TextInput::make('lab_kanan')
                        ->label('Nomor Lab Kanan')
                        ->minLength(1)
                        ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                        ->prefix(function (Get $get) {
                            $tanggalTerima = $get('default_lab_label');
                            $lastTwoDigitsOfYear = $tanggalTerima ? Carbon::parse($tanggalTerima)->format('y') : Carbon::now()->format('y');
                            // $lastTwoDigitsOfYear = '25';
                            return $lastTwoDigitsOfYear . '-' . $get('preflab');
                        })
                        ->maxLength(255)
                        ->hidden(fn(Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? true : false)
                ])->from('md'),

                TextInput::make('NomorSurat')
                    ->label('Nomor Surat')
                    ->minLength(2)
                    // ->required(fn (Get $get): bool => $get('drafting') !== True ? True : false)
                    ->required()
                    ->maxLength(255),
                TextInput::make('Tujuan')
                    ->label('Tujuan')
                    ->minLength(2)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                Select::make('SkalaPrioritas')
                    ->label('Skala Prioritas Sampel')
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
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
                    ->columns(3),
                TextInput::make('penerima_sampel')
                    ->label('Penerima Sampel')
                    ->minLength(2)
                    ->default(auth()->user()->name)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                TextInput::make('petugas_preperasi')
                    ->label('Petugas Preperasi')
                    ->minLength(2)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                TextInput::make('penyelia')
                    ->label('Penyelia')
                    ->minLength(2)
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->maxLength(255),
                TextInput::make('no_document')
                    ->label('No Dokumen Kupa')
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('no_document_indentitas')
                    ->label('No Dokumen Identitas')
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('nama_formulir')
                    ->label('Nama Formulir')
                    ->minLength(2)
                    ->maxLength(255),


                Section::make()
                    ->schema([
                        TextInput::make('Emaiilto')
                            ->label('Email To')
                            ->placeholder('Harap pisahkan dengan Koma (,) Jika lebih dari satu')
                            ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
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
                                            ->options(fn($get) => $get('../../parametersAnal') ?: session()->get('parametersAnal') ?: [])
                                            // ->required(fn (Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ->afterStateUpdated(function ($set, $state) {
                                                $params = ParameterAnalisis::find($state);
                                                $set('parametersdata', $params->nama_unsur);
                                                $set('harga_sampel', $params->harga);
                                                $set('total_harga', $params->harga);
                                            })
                                            ->disabled(function ($get) {

                                                $data = $get('../../parametersAnal');
                                                $data2 = session()->get('parametersAnal');
                                                // dd($data, $data2);
                                                if ($data != null || $data2 != null) {
                                                    // dd('false');
                                                    return false;
                                                } elseif ($data == null && $data2 == null) {
                                                    // dd('true');
                                                    return true;
                                                } else {
                                                    // dd('true');
                                                    return true;
                                                }
                                            })
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->required(fn(Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ->live(debounce: 500),
                                        TextInput::make('total_sample')
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                self::updateTotals($get, $set);
                                            })
                                            ->numeric()
                                            ->minValue(1)
                                            // ->required(fn (Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ->maxValue(1000)
                                            ->disabled(function ($get) {
                                                return is_null($get('parametersdata'));
                                            })
                                            ->required(fn(Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ->live(debounce: 500),
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
                                        Section::make('Nama Kode Sampel')
                                            ->description('Klik untuk membuka list nama kode sampel')
                                            ->schema([
                                                CheckboxList::make('nama_lab')
                                                    ->bulkToggleable()
                                                    ->columns(10)
                                                    ->options(function (Get $get) {
                                                        return $get('../../setoption_costumparams') ?: session()->get('setoption_costumparams') ?: [];
                                                    })
                                                    ->disabled(function ($get) {

                                                        $data = $get('../../setoption_costumparams');
                                                        $data2 = session()->get('setoption_costumparams');
                                                        // dd($data, $data2);
                                                        if ($data != null || $data2 != null) {
                                                            // dd('false');
                                                            return false;
                                                        } elseif ($data == null && $data2 == null) {
                                                            // dd('true');
                                                            return true;
                                                        } else {
                                                            // dd('true');
                                                            return true;
                                                        }
                                                    })
                                                    ->required(fn(Get $get): bool => $get('../../drafting') !== True ? True : false)
                                            ])
                                            ->collapsed(),
                                    ])

                            ])
                            ->deletable(true)
                            ->required(fn(Get $get): bool => $get('../../drafting') !== True ? True : false)
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
                            ->optimize('jpg')
                            ->resize(50)
                            ->multiple()
                            ->maxFiles(5)
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
                            ->disabled(function () {
                                $user = User::find(auth()->user()->id);

                                // Get all role names as a collection
                                $roles = $user->getRoleNames();

                                // dd($roles);
                                if ($roles->contains('marcom')) {
                                    return true;
                                } else {
                                    return false;
                                }
                            })
                            ->onIcon('heroicon-o-document-magnifying-glass')
                            ->offIcon('heroicon-o-clock')
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


        $form = $this->form->getState();

        $randomCode = generateRandomCode();

        $user = User::find(auth()->user()->id);

        // Get all role names as a collection
        $roles = $user->getRoleNames();

        $date = Carbon::now();
        $date = $date->format('Y-m-d H:i:s');
        $current[] = [
            'jenis_sampel' => $form['Jenis_Sampel'],
            'progress' => $form['status_pengerjaan'] == "0" ? "4" : $form['status_pengerjaan'],
            'updated_at' => $date
        ];
        // dd($current);
        $current = json_encode($current);

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

        $kodesampeldata = $form['NamaKodeSampeljamak'] ?? $form['NamaKodeSampel'] ?? null;

        // dd($kodesampeldata);
        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($kodesampeldata));

        // dd($NamaKodeSampeljamak);
        $commonRandomString = generateRandomString(rand(5, 10));
        $NomorLab = ($form['lab_kiri'] ?? '-') . '$' . ($form['lab_kanan'] ?? '-');
        $labLabel = Lablabel::latest('tanggal')
            ->first();

        $labLabel = Carbon::parse($labLabel->tanggal)->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');

        if ($today > $labLabel) {
            // If today is after lab label date, set to next year January 1st
            $lab_label_tahun = Carbon::parse($labLabel)->addYear()->format('Y');
        } else {
            $lab_label_tahun = Carbon::parse($labLabel)->format('Y');
        }

        // dd($lab_label_tahun);

        // dd($form);
        if (isset($form['drafting']) && $form['drafting'] !== true || $roles->contains('marcom')) {
            try {
                DB::beginTransaction();
                $trackSampel = new TrackSampel();
                $trackSampel->jenis_sampel = $form['Jenis_Sampel'];
                $trackSampel->tanggal_memo = $form['TanggalMemo'];
                $trackSampel->tanggal_terima = $form['TanggalTerima'];
                $trackSampel->asal_sampel = $form['Asalampel'] ?? 'Eksternal';
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
                $trackSampel->progress = $form['status_pengerjaan'] == "0" ? "4" : $form['status_pengerjaan'];
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
                $trackSampel->petugas_preparasi = $form['petugas_preperasi'];
                $trackSampel->penyelia = $form['penyelia'];
                $trackSampel->penerima_sampel = $form['penerima_sampel'];
                $trackSampel->no_doc = $form['no_document'];
                $trackSampel->no_doc_indentitas = $form['no_document_indentitas'];
                $trackSampel->formulir = $form['nama_formulir'];
                $trackSampel->created_by = auth()->user()->id;
                $trackSampel->jenis_pupuk = isset($form['jenis_pupuk']) ? $form['jenis_pupuk'] : null;
                $trackSampel->lab_label_tahun = $lab_label_tahun;

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



                $jenis_sampel_final = JenisSampel::where('id', (int) $form['Jenis_Sampel'])->pluck('nama')->first();
                $progress_state =  $form['status_pengerjaan'] == "0" ? "4" : $form['status_pengerjaan'];
                $progress = Progress::find($progress_state);

                // $nohp = formatPhoneNumber($form['nomerhpuser']);
                // dd($form['nomerhpuser']);
                if (isset($form['Asalampel']) && $form['Asalampel'] !== 'Eksternal') {
                    if ($form['nomerhpuser'] !== []) {
                        $dataToInsert2 = [];
                        foreach ($form['nomerhpuser'] as $data) {
                            $nomor_hp = numberformat_excel($data['NomorHp']);

                            if ($nomor_hp !== 'Error') {
                                $dataToInsert2[] = [
                                    'no_surat' => $form['NomorSurat'],
                                    'nama_departemen' => $form['NamaDep'],
                                    'jenis_sampel' => $jenis_sampel_final,
                                    'jumlah_sampel' => $form['JumlahSampel'],
                                    'progresss' => $progress->nama,
                                    'kodesample' => $randomCode,
                                    'penerima' =>  str_replace('+', '', $data['NomorHp']),
                                    'tanggal_registrasi' => $form['TanggalTerima'],
                                    'estimasi' => $form['EstimasiKupa'],
                                    'type' => 'input',
                                    'asal' => $form['Asalampel'],
                                ];
                            }
                        }
                        // dd($dataToInsert);
                        if (!empty($dataToInsert)) {
                            // dd($dataToInsert2);
                            event(new Smartlabsnotification($dataToInsert2));
                            // SendMsg::insert($dataToInsert2);
                        }
                    }

                    $emailAddresses = !empty($form['Emaiilto']) ? explode(',', $form['Emaiilto']) : null;
                    $emailcc = !empty($form['Emaiilcc']) ? explode(',', $form['Emaiilcc']) : null;

                    // dd($progress, $progress_state);
                    if ($emailAddresses !== null) {
                        Mail::to($emailAddresses)
                            ->cc($emailcc)
                            ->send(new EmailPelanggan($form['NomorSurat'], $form['NamaDep'], $jenis_sampel_final, $form['JumlahSampel'], $progress->nama, $randomCode, null, $form['TanggalTerima'], $form['EstimasiKupa']));
                    }
                }

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
                $form['repeater'] = [];
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
                $trackSampel->progress = $form['status_pengerjaan'] == "0" ? "4" : $form['status_pengerjaan'];
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
                $trackSampel->petugas_preparasi = $form['petugas_preperasi'];
                $trackSampel->penyelia = $form['penyelia'];
                $trackSampel->penerima_sampel = $form['penerima_sampel'];
                $trackSampel->no_doc = $form['no_document'];
                $trackSampel->no_doc_indentitas = $form['no_document_indentitas'];
                $trackSampel->formulir = $form['nama_formulir'];
                $trackSampel->status = 'Draft';
                $trackSampel->created_by = auth()->user()->id;
                $trackSampel->jenis_pupuk = isset($form['jenis_pupuk']) ? $form['jenis_pupuk'] : null;
                $trackSampel->lab_label_tahun = $lab_label_tahun;

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

                    $dataToInsert = [];
                    foreach ($form['nomerhpuser'] as $data) {
                        $nomor_hp = numberformat_excel($data['NomorHp']);

                        if ($nomor_hp !== 'Error') {
                            $dataToInsert[] = [
                                'no_surat' => $form['NomorSurat'],
                                'kodesample' => $randomCode,
                                'penerima' => str_replace('+', '', $data['NomorHp']),
                                'progres' => $getprogress,
                                'type' => 'input',
                            ];
                        }
                    }

                    // Uncomment to debug and check $dataToInsert before insertion
                    // dd($dataToInsert);

                    if (!empty($dataToInsert)) {
                        SendMsg::insert($dataToInsert);
                    }
                }

                if ($form['repeater'] !== []) {
                    if ($form['repeater'][0]['status'] != null) {
                        foreach ($form['repeater'] as $data) {
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
                        // dd('repeater  save');
                        TrackParameter::insert($dataToInsert2);
                    }
                }
                // dd('draft');
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
                $form['repeater'] = [];
            } catch (\Exception $e) {
                DB::rollBack();

                // Log or debug the error message to identify the issue
                Log::error('Error in catch block: ' . $e->getMessage());

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
