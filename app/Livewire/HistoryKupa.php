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
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class HistoryKupa extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;


    public $rolesAuthUser;

    public function mount()
    {
        // $this->roles = Role::where('name', '<>', 'superuser')->orderBy('alur_approved')->pluck('name');

        $this->rolesAuthUser = auth()->user()->roles[0]->name;
    }

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
                        if ($track->status_approved_by_role != null) {
                            return $track->status . ' by ' . $track->status_approved_by_role;
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
                            case 'Waiting Approved':
                                $result = 'gray';
                                break;
                            case 'Rejected':
                                $result = 'danger';
                                break;
                            case 'Draft':
                                $result = 'warning';
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
                // ->afterStateUpdated(function ($record, $state) {
                //     // Runs after the state is saved to the database.
                // })
                // ->modalHeading('Delete Kupa')
                // ->modalSubheading(fn (TrackSampel $record) => "Anda yakin ingin menghapus parameter ini? Ketika dihapus tidak dapat di pulihkan kembali.")
                // ->modalButton('Yes')

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
            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->label('Hapus Kupa')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                        $records->each(function (TrackSampel $record) {
                            $record->delete();
                            Notification::make()
                                ->title("Berhasil di Hapus")
                                ->body("Record dengan kode " . $record->kode_track . "  berhasil dihapus")
                                ->success()
                                ->send();
                        });
                    }),
                // BulkAction::make('Approved Group')
                //     ->requiresConfirmation()
                //     ->label('Approved')
                //     ->icon('heroicon-m-check-badge')
                //     ->color('success')
                //     ->deselectRecordsAfterCompletion()
                //     ->action(function (Collection $records) {
                //         $records->each(function (TrackSampel $record) {
                //             $record->delete();
                //             Notification::make()
                //                 ->title("Berhasil di Hapus")
                //                 ->body("Record dengan kode " . $record->kode_track . "  berhasil dihapus")
                //                 ->success()
                //                 ->send();
                //         });
                //     }),
            ])
            ->actions([

                ActionGroup::make([
                    Action::make('export_kupa')
                        ->label(' Kupa')
                        ->url(fn (TrackSampel $record): string => route('export.excel', $record->id))
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->visible(auth()->user()->can('export_kupa'))
                        ->size('xs'),
                    Action::make('export_form_monitoring_kupa')
                        ->label(' Form Monitoring')
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
                        ->modalButton('Yes'),
                    EditAction::make('Verifikasi Status')
                        ->label(fn (TrackSampel $record): string => checkApprovedLabelKupa($record))
                        // ->disabled(fn (TrackSampel $record): bool => checkApprovedKupa($this->rolesAuthUser, $record))
                        ->disabled(function (TrackSampel $record) {
                            $user = Auth::user();
                            $roles = $user->getRoleNames();



                            if (checkApprovedLabelKupa($record) === 'Kupa Selesai' || checkApprovedLabelKupa($record) === 'Verifikasi Status (On Draft)') {
                                $func = True;
                            } elseif ($roles[0] === 'Head Of Lab SRS' || $roles[0] === 'Admin') {
                                $func = False;
                            } else {
                                $func = True;
                            }
                            // if ($roles[0] === 'Head Of Lab SRS' || $roles[0] === 'Admin') {
                            //     $func = False;
                            // } else {
                            //     $func = true;
                            // }

                            return $func;
                        })
                        ->icon(fn (TrackSampel $record): string => checkIconApproved($record))
                        ->color(fn (TrackSampel $record): string => checkColorApproved($record))
                        ->modalHeading(fn (TrackSampel $record) => "Verifikasi Kupa " . $record->kode_track)
                        ->modalSubmitActionLabel('Submit')
                        ->form([
                            Select::make('status')
                                ->options([
                                    'Approved' => 'Approved',
                                    'Rejected' => 'Rejected',
                                ])
                        ])
                        ->successNotification(function (Model $record) {
                            return Notification::make()
                                ->success()
                                ->title('Verifikasi Berhasil')
                                ->body(function () use ($record) {
                                    return "Kupa " . $record->kode_track . " telah di-" . $record->status;
                                });
                        })
                        ->using(function (TrackSampel $record, array $data): TrackSampel {

                            if ($record->status_timestamp != null) {
                                $status_timestamp = $record->status_timestamp . ' , ' . Carbon::now()->format('Y-m-d H:i:s') . ' , ';
                            } else {
                                $status_timestamp = Carbon::now()->format('Y-m-d H:i:s');
                            }

                            $record->update(
                                [
                                    'status' => $data['status'],
                                    'status_changed_by_id' => auth()->user()->id,
                                    'status_approved_by_role' => auth()->user()->roles[0]->name,
                                    'status_timestamp' => $status_timestamp,
                                ]
                            );
                            return $record;
                        })
                ])->tooltip('Actions'),
            ]);
    }

    public function render(): View
    {
        return view('livewire.history-kupa');
    }
}
