<?php

namespace App\Livewire;

use App\Models\ParameterAnalisis;
use App\Models\JenisSampel;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Spatie\Permission\Models\Permission;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;

class TableParameter extends Component  implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;
    public function table(Table $table): Table
    {
        return $table
            ->query(ParameterAnalisis::query())
            ->headerActions([
                CreateAction::make('Paket')
                    ->model(ParameterAnalisis::class)
                    ->label('Tambah Paket')
                    ->modalWidth('7xl')
                    ->createAnother(false)
                    ->successNotification(null)
                    ->form([
                        Repeater::make('members')
                            ->label('Tambah Paket')
                            ->schema([
                                Select::make('jenis_paketan')
                                    ->options(JenisSampel::query()->pluck('nama', 'id'))
                                    ->label('Jenis Parameter')
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        $params = ParameterAnalisis::where('id_jenis_sampel', $state)->where('jenis_paket', 'Nonpaket')->get();
                                        $test = ParameterAnalisis::where('id_jenis_sampel', $state)->where('jenis_paket', 'Nonpaket')->pluck('nama_parameter', 'id');


                                        $newparams = [];
                                        foreach ($params as $key => $value) {
                                            if ($value['nama_parameter'] === $value['nama_unsur']) {
                                                $nama = $value['nama_parameter'] . '(Rp-,' . $value['harga'] . ')';
                                            } else {
                                                $nama = $value['nama_parameter'] . '(' . $value['nama_unsur'] . ')' . '(Rp-,' . $value['harga'] . ')';
                                            }
                                            $newparams[$value['id']] = $nama;
                                        };
                                        // dd($newparams, $test);
                                        $set('datanamaparameter', $newparams);
                                        $set('hargaparams_paketan', 0);
                                    })
                                    ->required()
                                    ->live(debounce: 500),
                                CheckboxList::make('namaparameter_paketan')
                                    ->options(function (Get $get) {

                                        return $get('datanamaparameter');
                                    })
                                    ->label('Nama Parameter')
                                    ->gridDirection('row')
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->disabled(function ($get) {
                                        return is_null($get('datanamaparameter'));
                                    })
                                    ->noSearchResultsMessage('Parameter yang anda cari tidak tersedia, Silahkan Input lebih dahulu Parameter Non Satuan untuk muncul.')
                                    ->columns(6)
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        // dd($state);
                                        $total = ParameterAnalisis::wherein('id', $state)
                                            ->where('jenis_paket', 'Nonpaket')
                                            ->pluck('harga')
                                            ->sum();
                                        $params = ParameterAnalisis::wherein('id', $state)->where('jenis_paket', 'Nonpaket')->pluck('nama_unsur')->toArray();
                                        $namaunsur = implode('+', $params);
                                        // dd(implode(',', $params));
                                        $set('hargaparams_paketan', $total);
                                        $set('nama_unsur', $namaunsur);
                                    })
                                    ->required()
                                    ->live(debounce: 500),
                                TextInput::make('hargaparams_paketan')
                                    ->required()
                                    ->label('Harga Parameter')
                                    ->numeric()
                                    ->maxLength(255),
                                TextInput::make('nama_unsur')
                                    ->required()
                                    ->label('Nama Unsur Parameter')
                                    ->maxLength(255),
                            ])
                            ->columns(2)


                    ])
                    ->using(function (array $data, string $model): ParameterAnalisis {
                        // dd($data);

                        $parametersToInsert = [];
                        foreach ($data as $key => $value) {
                            foreach ($value as $key1 => $value1) {
                                $params = ParameterAnalisis::wherein('id', $value1['namaparameter_paketan'])->where('jenis_paket', 'Nonpaket')->pluck('nama_unsur')->toArray();
                                // dd(implode('$', $params));
                                $parametersToInsert[] = [
                                    'nama_parameter' => implode(',', $params),
                                    'harga' => $value1['hargaparams_paketan'],
                                    'id_jenis_sampel' => $value1['jenis_paketan'],
                                    'nama_unsur' => $value1['nama_unsur'],
                                    'jenis_paket' => 'Paket',
                                    'paket_id' => implode('$', $value1['namaparameter_paketan']),
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

                            return new ParameterAnalisis();
                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->danger()
                                ->title('Error')
                                ->color('danger')
                                ->body($e)
                                ->send();

                            return new ParameterAnalisis();
                        }
                    }),
                CreateAction::make('Non_Paket')
                    ->model(ParameterAnalisis::class)
                    ->label('Tambah Non Paket')
                    ->form([
                        Repeater::make('members')
                            ->schema([
                                Select::make('jenis')
                                    ->options(JenisSampel::query()->pluck('nama', 'id'))
                                    ->required(),
                                TextInput::make('namaparameter')
                                    ->required()
                                    ->label('Nama Parameter')
                                    ->maxLength(255),
                                TextInput::make('hargaparams')
                                    ->required()
                                    ->label('Harga Parameter')
                                    ->numeric()
                                    ->maxLength(255),
                                TextInput::make('namametode')
                                    ->required()
                                    ->label('Nama Metode')
                                    ->maxLength(255),
                                TextInput::make('namasatuan')
                                    ->required()
                                    ->label('Nama Satuan')
                                    ->maxLength(255),
                                TextInput::make('bahan_produk')
                                    ->label('Bahan Produk')
                                    ->maxLength(255),
                                TextInput::make('nama_unsur')
                                    ->label('Nama Unsur')
                                    ->maxLength(255),
                            ])
                            ->columns(4)


                    ])
                    ->successNotification(null)
                    ->createAnother(false)
                    ->modalWidth('7xl')
                    ->using(function (array $data, string $model): ParameterAnalisis {
                        // dd($data);

                        $parametersToInsert = [];
                        foreach ($data as $key => $value) {
                            foreach ($value as $key1 => $value1) {
                                $parametersToInsert[] = [
                                    'nama_parameter' => $value1['namaparameter'],
                                    'metode_analisis' => $value1['namametode'],
                                    'harga' => $value1['hargaparams'],
                                    'satuan' => $value1['namasatuan'],
                                    'nama_unsur' => (is_null($value1['nama_unsur']) ? $value1['namaparameter'] : $value1['nama_unsur']),
                                    'bahan_produk' => $value1['bahan_produk'],
                                    'id_jenis_sampel' => $value1['jenis'],
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

                            return new ParameterAnalisis();
                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->danger()
                                ->title('Error')
                                ->color('danger')
                                ->body($e)
                                ->send();

                            return new ParameterAnalisis();
                        }
                    })
            ])
            ->columns([
                TextInputColumn::make('nama_parameter')
                    ->label('Nama Parameter')
                    ->searchable()
                    ->rules(['required', 'max:255'])
                    ->sortable(),
                TextInputColumn::make('nama_unsur')
                    ->label('Nama Unsur')
                    ->searchable()
                    ->rules(['required', 'max:255'])
                    ->sortable(),
                TextInputColumn::make('bahan_produk')
                    ->label('Bahan Produk')
                    ->searchable()
                    ->sortable(),
                TextInputColumn::make('metode_analisis')
                    ->label('Metode Analisis')
                    ->searchable()
                    ->rules(['required', 'max:255'])
                    ->sortable(),
                TextInputColumn::make('harga')
                    ->label('Harga')
                    ->searchable()
                    ->rules(['required', 'int'])
                    ->sortable(),
                TextInputColumn::make('satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->rules(['required', 'max:255'])
                    ->sortable(),
                TextColumn::make('jenisSampel.nama')
                    ->label('Jenis Sampel')
                    ->searchable()
                    ->sortable()->size('xs'),
            ])->striped()
            ->filters([
                SelectFilter::make('id_jenis_sampel')
                    ->relationship('jenisSampel', 'nama')
                    ->multiple()
                    ->preload()
            ])

            ->actions([
                Action::make('delete')
                    ->action(fn (ParameterAnalisis $record) => $record->delete())
                    ->deselectRecordsAfterCompletion()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(auth()->user()->can('view_role_management'))
                    ->modalHeading('Delete Kupa')
                    ->modalSubheading(fn (ParameterAnalisis $record) => "Anda yakin ingin menghapus parameter ini? Ketika dihapus tidak dapat di pulihkan kembali.")
                    ->modalButton('Yes')

            ]);
    }

    public function render(): View

    {
        return view('livewire.table-parameter');
    }
}
