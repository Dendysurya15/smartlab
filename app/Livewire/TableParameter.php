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
use Illuminate\Database\Eloquent\Builder;

class TableParameter extends Component  implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;
    public function table(Table $table): Table
    {
        return $table
            ->query(ParameterAnalisis::query())
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
                TextInputColumn::make('jenisSampel.nama')
                    ->label('Jenis Sample')
                    ->searchable()
                    ->rules(['required', 'max:255'])
                    ->sortable(),
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
