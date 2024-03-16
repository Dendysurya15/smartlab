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
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;
use Closure;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Str;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Builder;
use Illuminate\Support\Facades\Mail;
use Filament\Support\Enums\VerticalAlignment;

class InputProgress extends Component implements HasForms
{

    use InteractsWithForms;

    public static $paramstest = [];


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

                        $set('parametersAnal', $params);
                        $set('testing1', $state);
                        self::parametersAnal($get, $set);
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
                    // ->required()
                    ->live(),
                Select::make('status_pengerjaan')
                    ->label('Status Pengerjaan')
                    // ->required()
                    ->options(fn ($get) => $get('progressOpt') ?: []),
                Select::make('Asalampel')
                    ->label('Asal Sampel')
                    ->options([
                        'Internal' => 'Internal',
                        'Eksternal' => 'Eksternal',
                    ]),
                DatePicker::make('Tanggal Memo')
                    ->label('TanggalMemo')
                    ->format('d/m/Y'),
                DatePicker::make('Tanggal Terima')
                    ->label('TanggalTerima')
                    ->format('d/m/Y'),
                DatePicker::make('Estimasi Kupa')
                    ->label('Estimasikupa')
                    ->format('d/m/Y'),
                TextInput::make('Nomor Kupa')
                    ->label('NomorKupa')
                    ->numeric()
                    ->minValue(1)
                    // ->required()
                    ->maxValue(1000)
                    ->placeholder('Kupa'),
                TextInput::make('Jumlah Sampel')
                    ->label('JumlahSampel')
                    ->numeric()
                    ->minValue(1)
                    // ->required()
                    ->maxValue(1000)
                    ->placeholder('Kupa'),
                TextInput::make('Nama Pengirim')
                    ->label('NamaPengirim')
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('Nama Departemen / Perusahaan')
                    ->label('NamaDepartemen')
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('Nama Kode Sampel')
                    ->label('Nama_Kode_Sampel')
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('Kemasan Sampel')
                    ->label('KemasanSampel')
                    ->minLength(2)
                    ->maxLength(255),
                Select::make('Kondisi Sampel')
                    ->label('KondisiSampel')
                    ->options([
                        'Normal' => 'Normal',
                        'Abnormal' => 'Abnormal',
                    ]),
                TextInput::make('Nomor Lab')
                    ->label('NomorLab')
                    ->minLength(2)
                    ->prefix('24.C')
                    ->maxLength(255),
                TextInput::make('Nomor Surat')
                    ->label('NomorSurat')
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('Tujuan')
                    ->label('Tujuan')
                    ->minLength(2)
                    ->maxLength(255),
                Select::make('Skala Prioritas Sampel')
                    ->label('SkalaPrio')
                    ->options([
                        'Normal' => 'Normal',
                        'Tinggi' => 'Tinggi',
                    ]),
                TextInput::make('Nomor Hp')
                    ->label('NomorHp')
                    ->numeric()
                    ->tel()
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
                    // ->required()
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

                Section::make('pengujian_sampel')
                    ->label('Pengujian Sampel')
                    ->description('Peringatan untuk crosscheck ulang seluruh data yang ingin akan dimasukkan ke sistem!')
                    ->schema([
                        Repeater::make('repeater')
                            ->schema([
                                Select::make('status')
                                    ->options(fn ($get) => $get('../../parametersAnal') ?: [])
                                    // ->options(function (Get $get) {
                                    //     dd($get('../../Jenis_Sampel'));
                                    // })
                                    ->afterStateUpdated(function ($set, $state) {
                                        $params = ParameterAnalisis::find($state);
                                        $set('parametersdata', $params->nama_unsur);
                                        $set('harga_sampel', $params->harga);
                                        $set('total_harga', $params->harga);
                                        $set('total_sample', '1');
                                    })
                                    ->live(),
                                TextInput::make('parametersdata'),
                                TextInput::make('harga_sampel'),
                                TextInput::make('total_harga'),
                                TextInput::make('total_sample'),
                            ])
                            ->deletable(false)
                            ->columnSpanFull()


                    ])->columns(4),


            ])
            ->columns(3)
            ->statePath('data');
    }

    public static function parametersAnal(Get $get, Set $set): void
    {
        $selected = collect($get('Jenis_Sampel'));

        $params = ParameterAnalisis::where('id_jenis_sampel', $selected)->pluck('nama_parameter', 'id')->toArray();

        // dd($params);

        $set('testingaja', $params);
    }

    public function create(): void
    {
        dd($this->form->getState());
    }

    public function render(): View
    {
        return view('livewire.input-progress');
    }
}
