<?php

namespace App\Livewire\Sample;


use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\JenisSampel;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Tables\Columns\TextInputColumn;


class JenisSample extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(JenisSampel::query())
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kode')
                    ->label('Kode')
                    ->sortable(),
                TextColumn::make('parameter_analisis')
                    ->label('Parameter Analisis')
                    ->separator(',')
                    ->limit(30) // tampil maksimal 30 karakter
                    ->tooltip(fn($record) => $record->parameter_analisis) // biar bisa hover liat full text
                    ->sortable(),
                TextInputColumn::make('nomor_kupa')
                    ->label('Nomor Kupa')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('Berhasil diupdate')
                            ->body("Data nomor kupa telah diubah menjadi: {$state}")
                            ->success()
                            ->send();
                    })
                    ->sortable(),
                TextInputColumn::make('nomor_identitas')
                    ->label('Nomor Identitas')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('Berhasil diupdate')
                            ->body("Data nomor identitas telah diubah menjadi: {$state}")
                            ->success()
                            ->send();
                    })
                    ->sortable(),
                TextInputColumn::make('penyelia')
                    ->label('Penyelia')
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('Berhasil diupdate')
                            ->body("Data penyelia telah diubah menjadi: {$state}")
                            ->success()
                            ->send();
                    }),
                TextInputColumn::make('petugas_preperasi')
                    ->label('Petugas Preperasi')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('Berhasil diupdate')
                            ->body("Data petugas preperasi telah diubah menjadi: {$state}")
                            ->success()
                            ->send();
                    }),
            ]);
    }





    public function render()
    {
        return view('livewire.sample.jenis-sample');
    }
}
