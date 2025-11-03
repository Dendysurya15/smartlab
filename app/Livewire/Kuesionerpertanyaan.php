<?php

namespace App\Livewire;

use Livewire\Component;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Forms\Get;
use Filament\Forms\Components\Fieldset;
use App\Models\Kuesionerpertanyaan as Modelsource;
use App\Models\Kuesionertipe;
use App\Models\Kuesionerjawaban as Modelsourcejawaban;
use Carbon\Carbon;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class Kuesionerpertanyaan extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data_add_ons_template = '0';
    public function table(Table $table): Table
    {
        return $table
            ->query(Modelsource::query()->with(('template_jawaban')))
            ->headerActions([
                CreateAction::make('Pertanyaan')
                    ->model(Modelsource::class)
                    ->modalWidth('7xl')
                    ->createAnother(false)
                    ->closeModalByClickingAway(false)
                    ->successNotification(null)
                    ->form([
                        Repeater::make('members')
                            ->label('Tambah Pertanyaan')
                            ->schema([
                                TextInput::make('label')
                                    ->required()
                                    ->label('Pertanyaan')
                                    ->placeholder('Detail judul pertanyaan contoh (Instansi Terkait)')
                                    ->maxLength(255),
                                Select::make('type')
                                    ->live()
                                    ->default('1')
                                    ->options(Kuesionertipe::query()->pluck('nama', 'id')),

                                Select::make('add_ons_template')
                                    ->live()
                                    ->default(0)
                                    ->selectablePlaceholder(false)
                                    ->hidden(fn(Get $get) => $get('type') == '1')
                                    ->options(function (Modelsourcejawaban $data) {
                                        $query = Modelsourcejawaban::query()->pluck('nama', 'id')->toArray();
                                        $default = [0 => 'Tidak Ada'];
                                        return $default + $query;
                                    }),

                                Repeater::make('add_ons')
                                    ->hidden(fn(Get $get) => $get('type') == '1' || $get('add_ons_template') != 0) // Fix: Only hide when 'type' is '1' or 'add_ons_template' is not 0
                                    ->schema([
                                        Section::make()
                                            ->schema([
                                                TextInput::make('value')
                                                    ->required()
                                                    ->numeric()
                                                    ->label('Nilai Pilihan')
                                                    ->placeholder('Contoh 1')
                                                    ->maxLength(255),
                                                TextInput::make('nama_detail')
                                                    ->required()
                                                    ->placeholder('Tidak Puas')
                                                    ->label('Nama Pilihan'),
                                            ])->columns(2)
                                    ])->columnSpanFull(),

                                Fieldset::make('Tambah Pilihan Jawaban')
                                    ->schema([
                                        TextInput::make('Template_jawaban_nama')
                                            ->live(debounce: 500)
                                            ->hidden(fn(Get $get) => $get('type') == '1')
                                            ->required(fn(Get $get) => $get('save_as') == '1'),
                                        Radio::make('save_as')
                                            ->label('Simpan Add ons Sebagai Template jawaban?')
                                            ->live(debounce: 500)
                                            ->hidden(fn(Get $get) => $get('type') == '1')
                                            ->default('2')
                                            ->afterStateUpdated(function ($state, Get $get) {
                                                $add_ons = $get('add_ons');
                                                $nama_temp = $get('Template_jawaban_nama');

                                                $errors = [];

                                                foreach ($add_ons as $key => $item) {
                                                    if (is_null($item['value']) || is_null($item['nama_detail'])) {
                                                        $errors[$key] = "Both 'value' and 'nama_detail' must not be null.";
                                                    }
                                                }

                                                if ($state == '1') {
                                                    if (empty($errors) && $nama_temp !== null) {
                                                        DB::beginTransaction();
                                                        try {
                                                            $new_record = new Modelsourcejawaban();
                                                            $new_record->jawaban = json_encode($add_ons);
                                                            $new_record->datetime = Carbon::now();
                                                            $new_record->tipe = (int)$get('add_ons');
                                                            $new_record->nama = $nama_temp;
                                                            $new_record->created_by = (int)auth()->user()->user_id;
                                                            $new_record->save();

                                                            DB::commit();
                                                            Notification::make()
                                                                ->success()
                                                                ->title('Success')
                                                                ->body('Template di simpan')
                                                                ->send();
                                                        } catch (\Exception $e) {
                                                            DB::rollBack();

                                                            Notification::make()
                                                                ->danger()
                                                                ->title('Error')
                                                                ->color('danger')
                                                                ->body($e->getMessage())
                                                                ->send();
                                                        }
                                                    } else {
                                                        Notification::make()
                                                            ->warning()
                                                            ->title('Warning')
                                                            ->body('Nilai Pilihan/Nama value tidak boleh kosong')
                                                            ->send();
                                                    }
                                                } else {
                                                    Notification::make()
                                                        ->warning()
                                                        ->title('Warning')
                                                        ->body('Template tidak di simpan')
                                                        ->send();
                                                }
                                            })
                                            ->disabled(fn(Get $get) => $get('Template_jawaban_nama') !== null ? false : true)
                                            ->disableOptionWhen(fn($state, Get $get) => $state == 1 && $get('Template_jawaban_nama') !== null)
                                            ->options(['1' => 'Ya', '2' => 'Tidak']),
                                    ])
                                    ->hidden(fn(Get $get) => $get('type') == '1' || $get('add_ons_template') != 0), // Fix: Only hide when 'type' is '1' or 'add_ons_template' is not 0

                                Fieldset::make('Template')
                                    ->hidden(fn(Get $get) => $get('add_ons_template') == 0 || $get('type') == '1') // Fix: Only show when 'add_ons_template' is not 0 and 'type' is not 1
                                    ->schema(function (Get $get) {
                                        return [
                                            Generatetemplate($get('add_ons_template')),
                                            Placeholder::make('Detail')
                                                ->content(new HtmlString('<h1>Template Jawaban ini akan di gunakan sebagai list jawaban dari pertanyaan yang di buat</h1>'))
                                        ];
                                    }),

                            ])
                            ->columns(2)


                    ])
                    ->using(function (array $data, string $model, CreateAction $action): Modelsource {
                        // dd($data);

                        $parametersToInsert = [];
                        foreach ($data as $key => $value) {
                            foreach ($value as $key1 => $value1) {
                                $tipedata = $value1['type'];
                                $template = $value1['add_ons_template'];

                                $check_label = Modelsource::where('label', $value1['label'])->first();
                                // dd($check);

                                if ($check_label) {
                                    Notification::make()
                                        ->warning()
                                        ->title('Warning')
                                        ->body('Pertanyaan' . ' ' . $value1['label'] . ' sudah ada')
                                        ->send();

                                    $action->halt();
                                }
                                // Initialize variables to null by default
                                $id_jawaban_template = null;
                                $id_jawaban_nontemplate = null;

                                if ($tipedata !== 1) {
                                    if ($template !== 0 && $value1['Template_jawaban_nama'] == null) {
                                        $id_jawaban_template = (int)$value1['add_ons_template'];
                                    } else if ($value1['Template_jawaban_nama'] !== null && $template == 0) {
                                        $query = Modelsourcejawaban::where('nama', $value1['Template_jawaban_nama'])->first();
                                        $id_jawaban_template = (int)$query->id;
                                    } else {
                                        $id_jawaban_nontemplate = json_encode($value1['add_ons']);
                                    }
                                }

                                $parametersToInsert[] = [
                                    'label' => $value1['label'],
                                    'id_tipe' => $tipedata,
                                    'id_jawaban' => $id_jawaban_template,
                                    'jawaban' => $id_jawaban_nontemplate,
                                    'timestamp' => Carbon::now(),
                                    'created_by' => (int)auth()->user()->user_id,
                                ];
                            }
                        }



                        try {
                            DB::beginTransaction();
                            $model::insert($parametersToInsert);
                            DB::commit();

                            Notification::make()
                                ->success()
                                ->title('Success')
                                ->body('Parameters successfully added')
                                ->send();

                            return new Modelsource();
                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->danger()
                                ->title('Error')
                                ->color('danger')
                                ->body($e)
                                ->send();

                            return new Modelsource();
                        }
                    }),
            ])
            ->columns([
                TextColumn::make('label')->label('Pertanyaan'),
                TextColumn::make('Tipe.nama'),
                TextColumn::make('child_jawaban')->label('Jawaban bawaan')
                    ->badge()
                    ->separator('$')
                    ->listWithLineBreaks()
                    ->state(function (Modelsource $record) {
                        // dd($record);
                        if ($record->id_tipe !== 1) {
                            if ($record->id_jawaban !== null) {
                                $data = json_decode($record->template_jawaban->jawaban);
                                $new_data = [];
                                foreach ($data as $key => $values) {
                                    $new_data[$values->value] = $values->nama_detail;
                                }
                                $new_data = implode('$', $new_data);
                                // dd($new_data);
                                return $new_data;
                            } else {
                                $data = json_decode($record->jawaban);
                                $new_data = [];
                                foreach ($data as $key => $values) {
                                    $new_data[$values->value] = $values->nama_detail;
                                }
                                $new_data = implode('$', $new_data);
                                return $new_data;
                            }
                        } else {
                            return 'Langsung  diisi';
                        }
                    })

            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('delete')
                    ->action(function (Modelsource $record) {
                        $record->delete();
                        Notification::make()
                            ->title("Berhasil di Hapus")
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(auth()->user()->can('hapus_kupa'))
                    ->modalHeading('Delete Pertanyaan')
                    ->modalButton('Yes'),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.kuesionerpertanyaan');
    }
}
