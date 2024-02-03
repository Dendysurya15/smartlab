<?php

namespace App\Livewire;

use App\Models\ParameterAnalisis;
use Livewire\Component;
use App\Models\JenisSampel;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Spatie\Permission\Models\Permission;


class TableParameter extends Component  implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;
    public function table(Table $table): Table
    {
        return $table
            ->query(ParameterAnalisis::query())
            ->columns([
                TextColumn::make('nama_parameter')
                    ->label('Nama Parameter')
                    // ->inlineEditing(true)
                    ->searchable()
                    ->sortable()
                    ->size('xs')
                    ->sortable(),
                TextColumn::make('nama_unsur')
                    ->label('Nama Unsur')
                    ->searchable()
                    ->sortable()
                    ->size('xs')
                    ->sortable(),
                TextColumn::make('bahan_produk')
                    ->label('Bahan Produk')
                    ->searchable()
                    ->sortable()
                    ->size('xs')
                    ->sortable(),
                TextColumn::make('metode_analisis')
                    ->label('Metode Analisis')
                    ->searchable()
                    ->sortable()
                    ->size('xs')
                    ->sortable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->searchable()
                    ->sortable()
                    ->size('xs')
                    ->sortable(),
                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable()
                    ->size('xs')
                    ->sortable(),
                TextColumn::make('jenisSampel.nama')
                    ->label('Jenis Sample')
                    ->searchable()
                    ->sortable()
                    ->size('xs')
                    ->sortable(),
            ])->striped();
    }

    public function render(): View

    {
        return view('livewire.table-parameter');
    }
}
