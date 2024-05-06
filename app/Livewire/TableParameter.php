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

class TableParameter extends Component  implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;
    public function table(Table $table): Table
    {
        return $table
            ->query(ParameterAnalisis::query())
            ->headerActions([
                CreateAction::make()
                    ->model(ParameterAnalisis::class)
                    ->label('Tambah Parameter')
                    ->form([
                        Section::make()
                            ->description('Tambahkan Parameter baru')
                            ->schema([
                                Repeater::make('parameters')
                                    ->schema([
                                        Select::make('jenis')
                                            ->options(JenisSampel::query()->pluck('nama', 'id')),
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
                                    ])
                                    ->columns(3)

                            ])
                    ])
                    ->successNotification(null)
                    ->using(function (array $data, string $model): ParameterAnalisis {
                        $parametersToInsert = [];
                        foreach ($data as $key => $value) {
                            foreach ($value as $key1 => $value1) {
                                $parametersToInsert[] = [
                                    'nama_parameter' => $value1['namaparameter'],
                                    'metode_analisis' => $value1['namametode'],
                                    'harga' => $value1['hargaparams'],
                                    'satuan' => $value1['namasatuan'],
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

                            return true; // Indicate success
                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->danger()
                                ->title('Error ' . $e->getMessage())
                                ->color('danger')
                                ->body('Error occurred during parameter addition')
                                ->send();

                            return false; // Indicate failure
                        }
                    })
            ])
            ->columns([
                TextInputColumn::make('nama_parameter')
                    ->label('Nama Parameter')
                    ->rules(['required', 'max:255'])
                    ->searchable()
                    ->sortable(),
                TextInputColumn::make('nama_unsur')
                    ->label('Nama Unsur')
                    ->searchable()
                    ->rules(['required', 'max:255'])
                    ->sortable(),
                TextInputColumn::make('bahan_produk')
                    ->label('Bahan Produk')
                    ->searchable()
                    ->sortable()
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
                    ->visible(auth()->user()->can('edit_data'))
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
