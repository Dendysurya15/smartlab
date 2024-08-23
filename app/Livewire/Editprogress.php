<?php

namespace App\Livewire;

use App\Events\Smartlabsnotification;
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
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneEntry;
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneInputNumberType;
use Filament\Support\RawJs;

class Editprogress extends Component implements HasForms
{
    use InteractsWithForms;
    public $sample;

    public static $paramstest = [];

    public $opt;
    public $database;
    public $getparam;
    public $labkiri;
    public $labkanan;
    public $Peralatan;
    public $foto_sampel;
    public $kode_sampel;
    public $Konfirmasi;
    public $status_progres;
    public ?array $data = [];

    public function mount(): void
    {
        $this->opt = TrackSampel::with('trackParameters')->where('id', $this->sample)->first();

        $this->getparam = TrackParameter::with('ParameterAnalisis')->where('id_tracksampel', $this->opt->parameter_analisisid)->get()->toArray();

        // dd($this->opt);


        $this->kode_sampel = explode('$', $this->opt->kode_sampel);

        // dd($kode_sampel);
        $nolab = $this->opt->nomor_lab;
        $string = $nolab;
        $parts = explode('$', $string);
        $this->labkiri = $parts[0];
        $this->labkanan = $parts[1] ?? '-';
        $alat = $this->opt->alat;
        $bahan = $this->opt->bahan;
        $personel = $this->opt->personel;

        $getarray = array_filter([
            $alat ? 'Alat' : null,
            $bahan ? 'Bahan' : null,
            $personel ? 'Personel' : null,
        ]);
        $this->Peralatan = array_values($getarray);
        $this->Konfirmasi = $this->opt->konfirmasi == 1 ? true : false;
        $img = $this->opt->foto_sampel;
        $img = explode('%', $img);
        $this->foto_sampel = $img;
        $this->status_progres = json_decode($this->opt->last_update, true);
        // dd($this->status_progres);
        $this->form->fill();
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
                    ->default(function () {
                        // dd($this->opt->status);
                        if ($this->opt->status === 'Waiting Head Approval') {
                            return  'Approved';
                        } elseif ($this->opt->status === 'Waiting Admin Approval') {
                            return  'Waiting Approved';
                        } else {
                            return  $this->opt->status;
                        }
                    })
                    ->disabled()
                    ->inline(),
                Select::make('Jenis_Sampel')
                    ->label('Jenis Sampel')
                    ->default($this->opt->jenis_sampel)
                    ->options(JenisSampel::query()->where('soft_delete_id', '!=', 1)->pluck('nama', 'id'))
                    ->disabled(),
                TextInput::make('jenis_pupuk')
                    ->default($this->opt->jenis_pupuk)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->hidden($this->opt->jenis_sampel !== 11 ? true : false)
                    ->label('Jenis Pupuk'),
                Select::make('status_pengerjaan')
                    ->label('Status Pengerjaan')
                    ->required()
                    ->default($this->opt->progress)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options(function () {
                        $jenisSampel = JenisSampel::find($this->opt->jenis_sampel);
                        // dd($jenisSampel);
                        $progress = $jenisSampel->progress;

                        $progressArray = explode(',', $progress);

                        // dd($progressArray);

                        foreach ($progressArray as $key => $value) {
                            $option =  Progress::find($value);
                            $getdata[$option->id] = $option->nama;
                        }
                        // dd($getdata);
                        return $getdata;
                    }),
                Select::make('Asalampel')
                    ->label('Asal Sampel')
                    ->required()
                    ->default($this->opt->asal_sampel)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options([
                        'Internal' => 'Internal',
                        'Eksternal' => 'Eksternal',
                    ]),
                DateTimePicker::make('TanggalMemo')
                    ->label('Tanggal Memo')
                    ->required()
                    ->default($this->opt->tanggal_memo)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->seconds(true),
                DatePicker::make('TanggalTerima')
                    ->label('Tanggal Terima')
                    ->required()
                    ->default($this->opt->tanggal_terima)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->format('Y-m-d H:m:s'),
                DatePicker::make('EstimasiKupa')
                    ->label('Estimasi Kupa')
                    ->required()
                    ->default($this->opt->estimasi)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->format('Y-m-d H:m:s'),
                TextInput::make('NomorKupa')
                    ->numeric()
                    ->default($this->opt->nomor_kupa)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->label('Nomor Kupa'),
                TextInput::make('JumlahSampel')
                    ->label('Jumlah Sampel')
                    ->numeric()
                    ->minValue(1)
                    ->default($this->opt->jumlah_sampel)
                    ->required()
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxValue(1000)
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $set('lab_kiri', '');
                        $set('lab_kanan', '');
                        $set('NamaKodeSampeljamak', '');
                    })
                    ->live(debounce: 500),
                TextInput::make('NamaPengirim')
                    ->label('Nama Pengirim')
                    ->required()
                    ->default($this->opt->nama_pengirim)
                    ->minLength(2)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                TextInput::make('NamaDep')
                    ->label('Nama Departemen')
                    ->minLength(2)
                    ->required()
                    ->default($this->opt->departemen)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                Textarea::make('NamaKodeSampel')
                    ->label('Nama Kode Sampel')
                    ->minLength(2)
                    ->default($this->opt->kode_sampel)
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($state));
                        $array = explode('$', $NamaKodeSampeljamak);
                        $result = array_combine($array, $array);
                        $jumlahsample = $get('JumlahSampel');
                        $jumlah_kodesampel = count($result);
                        // dd($jumlah_kodesampel == (int)$jumlahsample);
                        if ((int)$jumlahsample !== $jumlah_kodesampel) {
                            Notification::make()
                                ->title('Jumlah Kode sampel tidak sama dengan jumlah sampel haraf dicek terlebih dahulu')
                                ->iconColor('warning')
                                ->color('warning')
                                ->success()
                                ->send();
                            $set('setoption_costumparams', []);
                        } else {
                            $set('setoption_costumparams', $result);
                        }
                    })
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->hidden(fn(Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? false : true)
                    ->maxLength(255),

                Textarea::make('NamaKodeSampeljamak')
                    ->label('Nama Kode Sampel')
                    ->rows(10)
                    ->cols(20)
                    ->default(function () {
                        $data = $this->opt->kode_sampel;
                        // dd($data);
                        $data_with_newlines = str_replace("$", "\n", $data);
                        return $data_with_newlines;
                    })
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($state));
                        $array = explode('$', $NamaKodeSampeljamak);
                        $result = array_combine($array, $array);
                        $jumlahsample = $get('JumlahSampel');
                        $jumlah_kodesampel = count($result);
                        // dd($jumlah_kodesampel == (int)$jumlahsample);
                        if ((int)$jumlahsample !== $jumlah_kodesampel) {
                            Notification::make()
                                ->title('Jumlah Kode sampel tidak sama dengan jumlah sampel haraf dicek terlebih dahulu')
                                ->iconColor('warning')
                                ->color('warning')
                                ->success()
                                ->send();
                            $set('setoption_costumparams', []);
                        } else {
                            $set('setoption_costumparams', $result);
                        }
                    })
                    ->required(fn(Get $get): bool => $get('drafting') !== True ? True : false)
                    ->placeholder('Harap Pastikan hanya paste satu baris saja dari excel.')
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->hidden(fn(Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? true : false),

                TextInput::make('KemasanSampel')
                    ->label('Kemasan Sampel')
                    ->minLength(2)
                    ->default($this->opt->kemasan_sampel)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->required()
                    ->maxLength(255),
                Select::make('KondisiSampel')
                    ->label('Kondisi Sampel')
                    ->required()
                    ->default($this->opt->kondisi_sampel)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options([
                        'Normal' => 'Normal',
                        'Abnormal' => 'Abnormal',
                    ]),
                Split::make([
                    TextInput::make('lab_kiri')
                        ->label('Nomor Lab')
                        ->minLength(1)
                        ->required()
                        ->default($this->labkiri)
                        ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                        ->prefix(function (Get $get) {
                            $jenisSampel = JenisSampel::find($this->opt->jenis_sampel);
                            $lastTwoDigitsOfYear = Carbon::now()->format('y');
                            return $lastTwoDigitsOfYear . '-' . $jenisSampel->kode;
                        })
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (!is_numeric($state)) {
                                $data = '';
                            } else {
                                $data = (int)$state + $get('JumlahSampel') - 1;
                            }
                            $set('lab_kanan', $data);
                        })
                        ->live(debounce: 500)
                        ->maxLength(255),

                    TextInput::make('lab_kanan')
                        ->label('Nomor Lab Kanan')
                        ->minLength(1)
                        ->required()
                        ->default($this->labkanan)
                        ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                        ->prefix(function (Get $get) {
                            $jenisSampel = JenisSampel::find($this->opt->jenis_sampel);
                            $lastTwoDigitsOfYear = Carbon::now()->format('y');
                            return $lastTwoDigitsOfYear . '-' . $jenisSampel->kode;
                        })
                        ->maxLength(255)
                        ->hidden(fn(Get $get): bool => empty($get('JumlahSampel')) || intval($get('JumlahSampel') == 1) ? true : false)
                ])->from('md'),

                TextInput::make('NomorSurat')
                    ->label('Nomor Surat')
                    ->minLength(2)
                    ->required()
                    ->default($this->opt->nomor_surat)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                TextInput::make('Tujuan')
                    ->label('Tujuan')
                    ->minLength(2)
                    ->required()
                    ->default($this->opt->tujuan)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                Select::make('SkalaPrioritas')
                    ->label('Skala Prioritas Sampel')
                    ->required()
                    ->default($this->opt->skala_prioritas)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->options([
                        'Normal' => 'Normal',
                        'Tinggi' => 'Tinggi',
                    ]),

                CheckboxList::make('Peralatan')
                    ->label('Peralatan')
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
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
                    ->default($this->Peralatan)
                    ->columns(3),
                TextInput::make('petugas_preperasi')
                    ->label('Petugas Preperasi')
                    ->minLength(2)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->default($this->opt->petugas_preparasi)
                    ->maxLength(255),
                TextInput::make('penyelia')
                    ->label('Penyelia')
                    ->minLength(2)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->default($this->opt->penyelia)
                    ->maxLength(255),
                TextInput::make('no_document')
                    ->label('No Dokumen Kupa')
                    ->minLength(2)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->default(function () {
                        $data = $this->opt->no_doc;

                        if ($data != null) {
                            return $data;
                        } else {
                            $getlates_doc = TrackSampel::with('trackParameters')
                                ->where('no_doc', '!=', null)
                                ->orderBy('id', 'desc')
                                ->first();

                            // Extract the 'no_doc' field from the latest document
                            $laststring = $getlates_doc ? $getlates_doc->no_doc : 'FR-7.1-1.1';

                            // Increment the version or set to default if no document is found
                            return incrementVersion($laststring);
                        }
                    })
                    ->maxLength(255),
                TextInput::make('no_document_indentitas')
                    ->label('No Dokumen Identitas')
                    ->minLength(2)
                    ->default(function () {
                        $data = $this->opt->no_doc_indentitas;

                        if ($data != null) {
                            return $data;
                        } else {
                            $getlates_doc = TrackSampel::with('trackParameters')
                                ->where('no_doc_indentitas', '!=', null)
                                ->orderBy('id', 'desc')
                                ->first();

                            // Extract the 'no_doc' field from the latest document
                            $laststring = $getlates_doc ? $getlates_doc->no_doc_indentitas : 'FR-7.4-1.2-1';

                            // Increment the version or set to default if no document is found
                            return incrementVersion_identitas($laststring);
                        }
                    })
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(255),
                TextInput::make('nama_formulir')
                    ->label('Nama Formulir')
                    ->minLength(2)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->default(function () {
                        $data = $this->opt->formulir;

                        if ($data != null) {
                            return $data;
                        } else {
                            $jenisSampel = JenisSampel::find($this->opt->jenis_sampel);
                            // dd($jenisSampel);
                            // Increment the version or set to default if no document is found
                            return 'Kaji Ulang Permintaan,Tender dan Kontrak Sampel' . ' ' . $jenisSampel->nama;
                        }
                    })
                    ->maxLength(255),

                TextInput::make('Emaiilto')
                    ->label('Email To')
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->placeholder('Harap pisahkan dengan Koma (,) Jika lebih dari satu')
                    ->default($this->opt->emailTo)
                    ->required()
                    ->maxLength(255),
                TextInput::make('Emaiilcc')
                    ->label('Email Cc')
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->placeholder('Harap pisahkan dengan Koma (,) Jika lebih dari satu')
                    ->default($this->opt->emailCc)
                    ->maxLength(255),
                TextInput::make('Diskon')
                    ->numeric()
                    ->default($this->opt->discount)
                    ->minLength(0)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->maxLength(2)
                    ->prefix('%'),

                Toggle::make('Konfirmasi')
                    ->inline(false)
                    ->default($this->Konfirmasi)
                    ->label('Konfirmasi(Langsung / Telepon / Email)')
                    ->onColor('success')
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                    ->offColor('danger'),
                Section::make()
                    ->schema([
                        Repeater::make('nomerhpuser')
                            ->label('Nomor Hp')
                            ->schema([
                                PhoneInput::make('NomorHp')
                                    ->label('Masukan Nomor Hp')
                                    ->defaultCountry('id')

                                    ->onlyCountries(['tr', 'us', 'gb', 'id']),
                            ])
                            ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                            ->default(function () {
                                $newData = [];
                                $nomerhp = explode(',', $this->opt->no_hp);
                                // dd(empty($this->opt->no_hp));
                                if (!empty($this->opt->no_hp)) {
                                    foreach ($nomerhp as $key => $value) {
                                        $newData[] = [
                                            'NomorHp' => $value
                                        ];
                                    }
                                }
                                return $newData;
                            })
                            ->grid(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengujian sampel')
                    ->label('Pengujian Sampel')
                    ->description('Peringatan untuk crosscheck ulang seluruh data yang ingin akan dimasukkan ke sistem!')
                    ->schema([
                        Repeater::make('repeater')
                            ->label('Parameter')
                            ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
                            ->schema([
                                Grid::make(5)
                                    ->schema([
                                        Select::make('status')
                                            ->options(function () {
                                                $params = ParameterAnalisis::where('id_jenis_sampel', $this->opt->jenis_sampel)->pluck('nama_parameter', 'id')->toArray();

                                                // dd($params);
                                                return $params;
                                            })
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
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
                                            ->live(debounce: 500),
                                        TextInput::make('total_sample')
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                self::updateTotals($get, $set);
                                            })
                                            ->numeric()
                                            ->minValue(1)
                                            ->required(fn($get): bool => !is_null($get('status')) ? true : false)
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
                                            }),
                                        Section::make('Nama Kode Sampel')
                                            ->description('Klik untuk membuka list nama kode sampel')
                                            ->schema([
                                                CheckboxList::make('nama_lab')
                                                    ->label('Nama Kode Sampel')
                                                    ->columns(10)
                                                    // ->gridDirection('row')
                                                    ->bulkToggleable()
                                                    ->options(function (Get $get) {
                                                        $data = $get('../../setoption_costumparams');
                                                        // dd($data);
                                                        if ($data != null || $data != []) {
                                                            return $get('../../setoption_costumparams');
                                                        } else {
                                                            $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($this->opt->kode_sampel));
                                                            $array = explode('$', $NamaKodeSampeljamak);
                                                            $result = array_combine($array, $array);

                                                            return $result;
                                                        }
                                                    })

                                                    ->disabled(function ($get) {
                                                        return is_null($get('status'));
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
                                            ])
                                            ->collapsed(),
                                    ])
                            ])
                            ->default(function () {
                                $newData = [];
                                foreach ($this->getparam as $key => $value) {
                                    foreach ($value as $key1 => $value1) if (is_array($value1)) {
                                        // dd($value);
                                        $namakode_sampel = $value['namakode_sampel'];
                                        $array = explode('$', $namakode_sampel);
                                        $newData[] = [
                                            'status' => $value['id_parameter'],
                                            'total_sample' => $value['jumlah'],
                                            'parametersdata' => $value1['nama_unsur'],
                                            'harga_sampel' => $value1['harga'],
                                            'subtotal' => $value['totalakhir'],
                                            'nama_lab' => $array
                                        ];
                                    }
                                }
                                // dd($newData);
                                return $newData;
                            })
                            ->addable(function () {
                                $data = $this->opt->status;

                                // dd($data);
                                if ($data === 'Approved' || $data === 'Draft') {
                                    $data = true;
                                } else {
                                    $data = false;
                                }

                                return $data;
                            })

                            ->deletable(function () {
                                $data = $this->opt->status;

                                // dd($data);
                                if ($data === 'Approved' || $data === 'Draft') {
                                    $data = true;
                                } else {
                                    $data = false;
                                }

                                return $data;
                            })
                            ->columnSpanFull()
                        // ->columns(4)


                    ])->columns(4),

                Textarea::make('catatan')
                    ->rows(10)
                    ->default($this->opt->catatan)
                    ->disabled(fn(Get $get): bool => ($get('status_data') === 'Approved' || $get('status_data') === 'Draft') ? false : true)
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
                            ->default($this->foto_sampel)
                            ->uploadingMessage('Upoad Foto Sampel...')
                            ->deletable(function () {
                                $data = $this->opt->status;
                                // Waiting Head Approved
                                // dd($data);
                                if ($data === 'Approved' || $data === 'Draft' || $data === 'Waiting Head Approval') {
                                    $data = true;
                                } else {
                                    $data = false;
                                }

                                return $data;
                            })
                            ->imageEditor(function () {
                                $data = $this->opt->status;

                                // dd($data);
                                if ($data === 'Approved' || $data === 'Draft' || $data === 'Waiting Head Approval') {
                                    $data = true;
                                } else {
                                    $data = false;
                                }

                                return $data;
                            })
                            ->disabled(function () {
                                $data = $this->opt->status;

                                // dd($data);
                                if ($data === 'Approved' || $data === 'Draft' || $data === 'Waiting Head Approval') {
                                    $data = false;
                                } else {
                                    $data = true;
                                }

                                return $data;
                            })
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
        $randomCode = generateRandomCode();
        $date = Carbon::now();
        $date = $date->format('Y-m-d H:i:s');
        $progress = $this->status_progres;
        // dd($progress);
        $current = [
            'jenis_sampel' => $this->opt->jenis_sampel,
            'progress' => ($form['status_pengerjaan'] ?? "0") == "0" ? "4" : ($form['status_pengerjaan'] ?? null),
            'updated_at' => $date
        ];


        // Check if the progress already exists in the $progress array
        $exists = false;
        foreach ($progress as $item) {
            if ($item['jenis_sampel'] == $current['jenis_sampel'] && $item['progress'] == $current['progress']) {
                $exists = true;
                break;
            }
        }

        // Add the $current array to $progress only if it doesn't already exist
        if (!$exists) {
            $progress[] = $current;
        }

        // dd($progress, $current, $progress);
        $current = json_encode($progress);
        $jenis_sampel_final = JenisSampel::where('id', (int) $this->opt->jenis_sampel)->pluck('nama')->first();
        $progress_state = isset($form['status_pengerjaan']) ? ($form['status_pengerjaan'] == "0" ? "4" : $form['status_pengerjaan']) : null;

        $progress = Progress::find($progress_state);

        $userId = 1;
        if (auth()->check()) {
            $user = auth()->user();
            $userId = $user->id;
        }

        $kodesampeldata = $form['NamaKodeSampeljamak'] ?? $form['NamaKodeSampel'] ?? null;

        // dd($kodesampeldata);
        $NamaKodeSampeljamak = preg_replace('/\n/', '$', trim($kodesampeldata));

        // dd('dispatch');
        $commonRandomString = generateRandomString(rand(5, 10));
        $NomorLab = ($form['lab_kiri'] ?? '-') . '$' . ($form['lab_kanan'] ?? '-');
        // dd($this->opt->status);
        if ($this->opt->status === "Approved" || $this->opt->status === "Waiting Head Approval") {
            $Alat = 'Alat';
            $Personel = 'Personel';
            $bahan = 'Bahan';
            $checkalat = in_array($Alat, $form['Peralatan']);
            $checkpersonel = in_array($Personel, $form['Peralatan']);
            $checkbahan = in_array($bahan, $form['Peralatan']);
            // dd($form['status_pengerjaan'], $this->opt->progress);

            $lastUpdate = implode('$', [$this->opt->last_update, $current]);

            // dd($this->opt->last_update, $current, $lastUpdate);

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
                $trackSampel->kode_sampel = $form['NamaKodeSampel'] ?? $NamaKodeSampeljamak;
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
                $nomorHpArray = array_column($form['nomerhpuser'], 'NomorHp');
                $combinedNomorHp = implode(',', $nomorHpArray);
                $trackSampel->no_hp = $combinedNomorHp;
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
                $trackSampel->bahan = ($checkbahan ? 1 : 0);
                $trackSampel->personel = ($checkpersonel ? 1 : 0);
                $trackSampel->konfirmasi = ($form['Konfirmasi'] ? 1 : 0);
                $trackSampel->skala_prioritas = $form['SkalaPrioritas'];
                $trackSampel->discount = $form['Diskon'];
                $trackSampel->catatan = $form['catatan'];
                $trackSampel->petugas_preparasi = $form['petugas_preperasi'];
                $trackSampel->penyelia = $form['penyelia'];
                $trackSampel->no_doc = $form['no_document'];
                $trackSampel->no_doc_indentitas = $form['no_document_indentitas'];
                $trackSampel->formulir = $form['nama_formulir'];
                $trackSampel->jenis_pupuk = isset($form['jenis_pupuk']) ? $form['jenis_pupuk'] : null;

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


                // dd($this->opt->parameter_analisisid);
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
                                'namakode_sampel' => implode('$', $data['nama_lab']),
                            ];
                        }
                    }

                    // dd($idparams, $dataToInsert);

                    TrackParameter::insert($dataToInsert);
                }


                if ($form['Asalampel'] !== 'Eksternal') {
                    if ($form['nomerhpuser'] !== []) {
                        foreach ($form['nomerhpuser'] as $data) {
                            $dataToInsert2[] = [
                                'no_surat' => $this->opt->nomor_surat,
                                'nama_departemen' => $this->opt->departemen,
                                'jenis_sampel' => $jenis_sampel_final,
                                'jumlah_sampel' => $this->opt->jumlah_sampel,
                                'progresss' => $progress->nama,
                                'kodesample' => $this->opt->kode_track,
                                'penerima' =>  str_replace('+', '', $data['NomorHp']),
                                'type' => 'update',
                            ];
                        }
                        // dd($dataToInsert);
                        // SendMsg::insert($dataToInsert2);
                        event(new Smartlabsnotification($dataToInsert2));
                    }

                    $emailAddresses = !empty($form['Emaiilto']) ? explode(',', $form['Emaiilto']) : null;
                    $emailcc = !empty($form['Emaiilcc']) ? explode(',', $form['Emaiilcc']) : null;


                    Mail::to($emailAddresses)
                        ->cc($emailcc)
                        // ->send(new EmailPelanggan($form['TanggalTerima'], $form['NomorSurat'], $NomorLab,  $this->opt->kode_track, $nomorserif));
                        ->send(new EmailPelanggan($this->opt->nomor_surat, $this->opt->departemen, $jenis_sampel_final, $this->opt->jumlah_sampel, $progress->nama, $this->opt->kode_track, null));
                }


                $trackSampel->save();

                DB::commit();

                // sendwhatsapp($dataarr, $nohp);
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
                $trackSampel->kode_sampel = $form['NamaKodeSampel'] ?? $NamaKodeSampeljamak;
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
                $nomorHpArray = array_column($form['nomerhpuser'], 'NomorHp');
                $combinedNomorHp = implode(',', $nomorHpArray);
                $trackSampel->no_hp = $combinedNomorHp;
                $trackSampel->alat = ($checkalat ? 1 : 0);
                $trackSampel->emailTo = $form['Emaiilto'];
                $trackSampel->bahan = ($checkbahan ? 1 : 0);
                $trackSampel->personel = ($checkpersonel ? 1 : 0);
                $trackSampel->konfirmasi = ($form['Konfirmasi'] ? 1 : 0);
                $trackSampel->skala_prioritas = $form['SkalaPrioritas'];
                $trackSampel->discount = $form['Diskon'];
                $trackSampel->catatan = $form['catatan'];
                $trackSampel->petugas_preparasi = $form['petugas_preperasi'];
                $trackSampel->penyelia = $form['penyelia'];
                $trackSampel->no_doc = $form['no_document'];
                $trackSampel->no_doc_indentitas = $form['no_document_indentitas'];
                $trackSampel->formulir = $form['nama_formulir'];
                $trackSampel->status = 'Waiting Admin Approval';
                $trackSampel->last_update = $current;
                $trackSampel->jenis_pupuk = isset($form['jenis_pupuk']) ? $form['jenis_pupuk'] : null;


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
                                'namakode_sampel' => implode('$', $data['nama_lab']),
                            ];
                        }
                    }

                    // dd($idparams, $dataToInsert);

                    TrackParameter::insert($dataToInsert);
                }


                $getprogress = Progress::pluck('nama')->first();
                if ($form['Asalampel'] !== 'Eksternal') {
                    if ($form['nomerhpuser'] !== []) {
                        foreach ($form['nomerhpuser'] as $data) {
                            $dataToInsert2[] = [
                                'no_surat' => $form['NomorSurat'],
                                'nama_departemen' => $form['NamaDep'],
                                'jenis_sampel' => $jenis_sampel_final,
                                'jumlah_sampel' => $form['JumlahSampel'],
                                'progresss' => $progress->nama,
                                'kodesample' => $this->opt->kode_track,
                                'penerima' =>  str_replace('+', '', $data['NomorHp']),
                                'type' => 'input',
                            ];
                        }
                        // dd($dataToInsert);
                        // SendMsg::insert($dataToInsert2);
                        event(new Smartlabsnotification($dataToInsert2));
                    }

                    $emailAddresses = !empty($form['Emaiilto']) ? explode(',', $form['Emaiilto']) : null;
                    $emailcc = !empty($form['Emaiilcc']) ? explode(',', $form['Emaiilcc']) : null;


                    Mail::to($emailAddresses)
                        ->cc($emailcc)
                        // ->send(new EmailPelanggan($form['TanggalTerima'], $form['NomorSurat'], $NomorLab,  $this->opt->kode_track, $nomorserif));
                        ->send(new EmailPelanggan($this->opt->nomor_surat, $form['NamaDep'], $jenis_sampel_final, $form['JumlahSampel'], $progress->nama, $this->opt->kode_track, null));
                }

                $trackSampel->save();

                DB::commit();

                // sendwhatsapp($dataarr, $nohp);
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
        } elseif ($this->opt->status === "Waiting Admin Approval") {
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
