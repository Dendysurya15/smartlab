<?php

namespace App\Livewire;

use App\Models\TrackSampel;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class HistoryKupa extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(TrackSampel::query())
            ->columns([


                TextColumn::make('tanggal_penerimaan')
                    ->formatStateUsing(function (TrackSampel $track) {
                        return tanggal_indo($track->tanggal_penerimaan);
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    // ->searchable(query: function (Builder $query, string $search): Builder {
                    //     $originalFormat = tanggal_indo($search);

                    //     return $query->orWhere('tanggal_penerimaan', 'like', "%{$search}%");
                    // })
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('jenisSampel.nama')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Jenis Sampel')
                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('kode_track')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('gray')
                    ->copyMessage(fn (string $state): string => "Copied {$state} to clipboard")
                    ->copyMessageDuration(1500)
                    ->size('xs'),
                TextColumn::make('progressSampel.nama')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('nomor_kupa')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('nama_pengirim')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('departemen')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable()
                    ->sortable()
                    ->size('xs'),

                TextColumn::make('estimasi')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->datetime()
                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('tujuan')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable()
                    ->sortable()
                    ->size('xs'),

                TextColumn::make('skala_prioritas')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->badge()
                    ->color(function (TrackSampel $track) {
                        return $track->skala_prioritas === 'Normal' ? 'warning' : ($track->skala_prioritas === 'Tinggi' ? 'danger' : 'gray');
                    })

                    ->sortable()
                    ->size('xs'),

                TextColumn::make('asal_sampel')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable()
                    ->sortable()

                    ->size('xs'),
                TextColumn::make('admin')
                    ->toggleable(isToggledHiddenByDefault: true)



                    ->size('xs'),
                TextColumn::make('no_hp')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->size('xs'),
                TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->size('xs'),

            ])->striped()

            ->filters([
                SelectFilter::make('skala_prioritas')
                    ->options([
                        'normal' => 'Normal',
                        'tinggi' => 'Tinggi',
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['value']) {
                            return null;
                        }
                        return  'Skala Prioritas : ' . ($data['value'] === 'normal' ? 'Normal' : 'Tinggi');
                    }),
                Filter::make('tanggal_penerimaan')
                    ->form([
                        DatePicker::make('Range Tanggal Awal'),
                        DatePicker::make('Range Tanggal Akhir')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Range Tanggal Awal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_penerimaan', '>=', $date),
                            )
                            ->when(
                                $data['Range Tanggal Akhir'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_penerimaan', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {

                        if (!$data['Range Tanggal Awal']) {
                            return null;
                        }

                        return 'Mulai dari ' . Carbon::parse($data['Range Tanggal Awal'])->toFormattedDateString() . ' hingga ' . Carbon::parse($data['Range Tanggal Akhir'])->toFormattedDateString();
                    }),



            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit Kupa')
                    ->url(fn (TrackSampel $record): string => route('history_sampel.edit', $record->id))
                    ->icon('heroicon-o-pencil')->color('warning')
                    ->openUrlInNewTab()
                    ->size('xs'),
                Action::make('delete')
                    ->url(fn (TrackSampel $record): string => route('history_sampel.edit', $record->id))
                    ->icon('heroicon-o-trash')->color('danger')
                    ->openUrlInNewTab()
                    ->size('xs'),
                // ActionGroup::make([
                //     ViewAction::make(),
                //     EditAction::make()->successRedirectUrl(fn (TrackSampel $record): string => route('history_sampel.edit', [
                //         'history_sampel' => $record->id,
                //     ])),
                //     DeleteAction::make(),
                // ])->icon('heroicon-m-ellipsis-horizontal')->size(ActionSize::Small),
            ])
            ->bulkActions([
                BulkAction::make('export')

                    ->color('success')
                    ->action(fn (Collection $records) => $records->each->delete())
            ]);
    }

    public function render(): View
    {
        return view('livewire.history-kupa');
    }
}
