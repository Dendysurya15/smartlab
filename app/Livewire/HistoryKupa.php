<?php

namespace App\Livewire;

use App\Models\TrackSampel;
use App\Models\User;
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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Notifications\Notification;

class HistoryKupa extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(TrackSampel::query())
            ->defaultSort('tanggal_terima', 'desc')
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Tidak Ada History')
            ->emptyStateDescription('Jika Terdapat History Kupa, Akan tercatat otomatis di halaman ini')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('tanggal_terima')
                    ->formatStateUsing(function (TrackSampel $track) {
                        return tanggal_indo($track->tanggal_terima, false, false, true);
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    // ->searchable(query: function (Builder $query, string $search): Builder {
                    //     $originalFormat = tanggal_indo($search);

                    //     return $query->orWhere('tanggal_terima', 'like', "%{$search}%");
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
                    ->copyable()
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
                    ->formatStateUsing(function (TrackSampel $track) {
                        return tanggal_indo($track->estimasi, false, false, true);
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    // ->datetime()
                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('tujuan')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('status')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(function (TrackSampel $track) {
                        if ($track->status_changed_by != null) {
                            $user = User::find($track->status_changed_by);
                            if ($user) {
                                $roles = $user->getRoleNames();
                                // dd($roles);
                                return $track->status . ' by ' . ($roles->isNotEmpty() ? $roles->implode(', ') : 'No Role');
                            } else {
                                return $track->status;
                            }
                        } else {
                            return $track->status;
                        }
                    })
                    ->limit(23)
                    ->searchable()
                    ->badge()
                    ->color(function (TrackSampel $track) {
                        $result = '';
                        switch ($track->status) {
                            case 'Approved':
                                $result = 'success';
                                break;
                            case 'Pending':
                                $result = 'warning';
                                break;
                            case 'Rejected':
                                $result = 'danger';
                                break;
                            default:
                                $result = 'gray';
                        }

                        return $result;
                    })
                    ->sortable()
                    ->size('xs'),

                TextColumn::make('skala_prioritas')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->badge()
                    ->color(function (TrackSampel $track) {
                        return $track->skala_prioritas === 'Normal' ? 'gray' : ($track->skala_prioritas === 'Tinggi' ? 'danger' : 'gray');
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
                // Filter::make('tanggal_terima')
                //     ->form([
                //         DatePicker::make('Range Tanggal Awal'),
                //         DatePicker::make('Range Tanggal Akhir')->default(now()),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['Range Tanggal Awal'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '>=', $date),
                //             )
                //             ->when(
                //                 $data['Range Tanggal Akhir'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '<=', $date),
                //             );
                //     })
                //     ->indicateUsing(function (array $data): ?string {

                //         if (!$data['Range Tanggal Awal']) {
                //             return null;
                //         }

                //         return 'Mulai dari ' . Carbon::parse($data['Range Tanggal Awal'])->toFormattedDateString() . ' hingga ' . Carbon::parse($data['Range Tanggal Akhir'])->toFormattedDateString();
                //     }),



            ])
            ->actions([
                Action::make('export_kupa')
                    ->label('Kupa')
                    ->url(fn (TrackSampel $record): string => route('export.excel', $record->id))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->visible(auth()->user()->can('export_kupa'))
                    ->size('xs'),
                Action::make('export_form_monitoring_kupa')
                    ->label('Form Monitoring')
                    ->url(fn (TrackSampel $record): string => route('export.form-monitoring-kupa', $record->id))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                    ->size('xs'),
                Action::make('edit')
                    ->label('Edit Kupa')
                    ->url(fn (TrackSampel $record): string => route('history_sampel.edit', $record->id))
                    ->icon('heroicon-o-pencil')->color('warning')
                    ->openUrlInNewTab()
                    ->visible(auth()->user()->can('edit_kupa'))
                    ->size('xs'),
                Action::make('delete')
                    ->action(function (TrackSampel $record) {
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
                    ->modalHeading('Delete Kupa')
                    ->modalSubheading(
                        fn (TrackSampel $record) => "Anda yakin ingin menghapus data ini dengan kode track: {$record->kode_track}? Ketika dihapus tidak dapat dipulihkan kembali."
                    )
                    ->modalButton('Yes')

            ]);
    }

    public function render(): View
    {
        return view('livewire.history-kupa');
    }
}
