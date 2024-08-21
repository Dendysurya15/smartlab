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
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\MonitoringKupabulk;
use App\Exports\LogbookBulkExport;
use App\Exports\pdfpr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Grouping\Group;
use Barryvdh\DomPDF\Facade\Pdf;


class HistoryKupa extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;
    public $openurl;

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
            ->defaultSort('id', 'desc')
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
                    ->copyMessage(fn(string $state): string => "Copied {$state} to clipboard")
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
                TextColumn::make('nomor_surat')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('departemen')
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function (TrackSampel $track) {
                        if ($track->status_changed_by_id != null) {

                            $user = User::find($track->status_changed_by_id);
                            // dd($track);
                            if ($user && $track->status !== 'Waiting Head Approval') {
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
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color(function (TrackSampel $track) {
                        $result = '';
                        switch ($track->status) {
                            case 'Approved':
                                $result = 'success';
                                break;
                            case 'Waiting Admin Approval':
                                $result = 'gray';
                                break;
                            case 'Waiting Head Approval':
                                $result = 'info';
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
            ->groups([
                Group::make('jenisSampel.nama')
                    ->label('Jenis Sampel'),
                Group::make('status')
                    ->label('Status'),
            ])
            ->filters([
                SelectFilter::make('jenisSampel')
                    ->label('Jenis sampel')
                    ->relationship('jenisSampel', 'nama')
                    // ->multiple()
                    ->preload(),
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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Tanggal terima dari'),
                        DatePicker::make('created_until')
                            ->label('Tanggal terima sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // dd($data);
                        return $query
                            ->when(
                                $data['created_from'],
                                function (Builder $query, $date) {
                                    // dd($query->whereDate('tanggal_terima', '>=', $date));

                                    return $query->whereDate('tanggal_terima', '>=', $date);
                                }
                                // fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                function (Builder $query, $date) {
                                    return $query->whereDate('tanggal_terima', '<=', $date);
                                }
                            );
                    })



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
                ActionGroup::make([
                    BulkAction::make('export_pdf')
                        ->label('PDF')
                        ->button()
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('warning')
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Perhatian')
                        ->modalSubheading(
                            "Harap Memilih data yang tidak dalam kondisi status Draft"
                        )
                        ->modalButton('Export PDF')
                        ->action(function (Collection $records) {
                            $recordIds = [];
                            $jenis_sampel = [];
                            $dates = [];
                            $year = [];

                            $records->each(function ($record) use (&$recordIds, &$jenis_sampel, &$dates, &$year) {
                                if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                    $recordIds[] = $record->id;
                                }
                                $jenis_sampel[] = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates[] = $carbonDate->format('F');
                                $year[] = $carbonDate->format('Y');
                            });
                            $data = implode('$', $recordIds);

                            // Redirect with a target attribute for opening in a new tab
                            return redirect()->route('exportvr', $data)->with('target', '_blank');
                        }),
                    BulkAction::make('export_excelpr')
                        ->label('Excel')
                        ->button()
                        ->icon('heroicon-o-document-chart-bar')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Perhatian')
                        ->modalSubheading(
                            "Harap Memilih data yang tidak dalam kondisi status Draft"
                        )
                        ->modalButton('Export PR')
                        ->action(function (Collection $records) {
                            $recordIds = [];
                            $jenis_sampel = [];
                            $dates = [];
                            $year = [];

                            $records->each(function ($record) use (&$recordIds, &$jenis_sampel, &$dates, &$year) {
                                if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                    $recordIds[] = $record->id;
                                }
                                $jenis_sampel[] = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates[] = $carbonDate->format('F');
                                $year[] = $carbonDate->format('Y');
                            });

                            $jenis_sample_final = implode(',', array_unique($jenis_sampel));
                            $dates_final = implode(',', array_unique($dates));
                            $year_final = implode(',', array_unique($year));
                            // dd($recordIds, $records);
                            $data = implode('$', $recordIds);

                            // Concatenate strings and variables using the concatenation operator (.)
                            $filename = 'PR Kupa ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final . '.xlsx';
                            return Excel::download(new pdfpr($data), $filename);
                        }),

                ])->button()
                    ->color('info')
                    ->label('Export PR'),
                ActionGroup::make([
                    BulkAction::make('export')
                        ->label('Excel')
                        ->button()
                        ->icon('heroicon-o-document-chart-bar')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Perhatian')
                        ->modalSubheading(
                            "Harap Memilih data yang tidak dalam kondisi status Draft"
                        )
                        ->modalButton('Excel')
                        ->action(function (Collection $records) {
                            $recordIds = [];
                            $jenis_sampel = [];
                            $dates = [];
                            $year = [];

                            $records->each(function ($record) use (&$recordIds, &$jenis_sampel, &$dates, &$year) {
                                if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                    $recordIds[] = $record->id;
                                }
                                $jenis_sampel[] = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates[] = $carbonDate->format('F');
                                $year[] = $carbonDate->format('Y');
                            });

                            $jenis_sample_final = implode(',', array_unique($jenis_sampel));
                            $dates_final = implode(',', array_unique($dates));
                            $year_final = implode(',', array_unique($year));
                            // dd($recordIds, $records);
                            $data = implode('$', $recordIds);

                            // Concatenate strings and variables using the concatenation operator (.)
                            $filename = 'Form Monitoring Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final . '.xlsx';
                            return Excel::download(new MonitoringKupabulk($data), $filename);
                        }),

                    BulkAction::make('export_pdf_monotoring')
                        ->label('PDF')
                        ->button()
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('warning')
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Perhatian')
                        ->modalSubheading(
                            "Harap Memilih data yang tidak dalam kondisi status Draft"
                        )
                        ->modalButton('Export PDF')
                        ->action(function (Collection $records) {
                            $recordIds = [];
                            $jenis_sampel = [];
                            $dates = [];
                            $year = [];

                            $records->each(function ($record) use (&$recordIds, &$jenis_sampel, &$dates, &$year) {
                                if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                    $recordIds[] = $record->id;
                                }
                                $jenis_sampel[] = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates[] = $carbonDate->format('F');
                                $year[] = $carbonDate->format('Y');
                            });

                            $jenis_sample_final = implode(',', array_unique($jenis_sampel));
                            $dates_final = implode(',', array_unique($dates));
                            $year_final = implode(',', array_unique($year));
                            // dd($recordIds, $records);
                            $data = implode('$', $recordIds);
                            $filename = 'Form Monitoring Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                            return redirect()->route('exporpdfform', ['id' => $data, 'filename' => $filename])->with('target', '_blank');
                        }),
                ])->button()
                    ->color('info')
                    ->label('Export Form Monitoring'),
                ActionGroup::make([
                    BulkAction::make('export_logbook_pdf')
                        ->label('PDF')
                        ->button()
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('warning')
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Perhatian')
                        ->modalSubheading(
                            "Harap Memilih data yang tidak dalam kondisi status Draft"
                        )
                        ->modalButton('Export PDF')
                        ->action(function (Collection $records) {
                            $recordIds = [];
                            $jenis_sampel = [];
                            $dates = [];
                            $year = [];

                            $records->each(function ($record) use (&$recordIds, &$jenis_sampel, &$dates, &$year) {
                                if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                    $recordIds[] = $record->id;
                                }
                                $jenis_sampel[] = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates[] = $carbonDate->format('F');
                                $year[] = $carbonDate->format('Y');
                            });

                            $jenis_sample_final = implode(',', array_unique($jenis_sampel));
                            $dates_final = implode(',', array_unique($dates));
                            $year_final = implode(',', array_unique($year));
                            // dd($recordIds, $records);
                            $data = implode('$', $recordIds);

                            // Concatenate strings and variables using the concatenation operator (.)
                            $filename = 'Identitas Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final . '.xlsx';
                            return redirect()->route('exporpdfpr', ['id' => $data, 'filename' => $filename])->with('target', '_blank');
                        }),
                    BulkAction::make('export_logbook')
                        ->label('Excel')
                        ->button()
                        ->icon('heroicon-o-document-chart-bar')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Perhatian')
                        ->modalSubheading(
                            "Harap Memilih data yang tidak dalam kondisi status Draft"
                        )
                        ->modalButton('Export Kupa')
                        ->action(function (Collection $records) {
                            $recordIds = [];
                            $jenis_sampel = [];
                            $dates = [];
                            $year = [];

                            $records->each(function ($record) use (&$recordIds, &$jenis_sampel, &$dates, &$year) {
                                if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                    $recordIds[] = $record->id;
                                }
                                $jenis_sampel[] = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates[] = $carbonDate->format('F');
                                $year[] = $carbonDate->format('Y');
                            });

                            $jenis_sample_final = implode(',', array_unique($jenis_sampel));
                            $dates_final = implode(',', array_unique($dates));
                            $year_final = implode(',', array_unique($year));
                            // dd($recordIds, $records);
                            $data = implode('$', $recordIds);

                            // Concatenate strings and variables using the concatenation operator (.)
                            $filename = 'Identitas Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final . '.xlsx';
                            return Excel::download(new LogbookBulkExport($data), $filename);
                        }),
                ])->button()
                    ->color('info')
                    ->label('Export Identitas'),
                BulkAction::make('export_dokumentasi')
                    ->label('Dokumentasi')
                    ->button()
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->deselectRecordsAfterCompletion()
                    ->modalHeading('Perhatian')
                    ->modalSubheading(
                        "Harap Memilih data yang tidak dalam kondisi status Draft"
                    )
                    ->modalButton('Export PDF')
                    ->action(function (Collection $records) {
                        $recordIds = [];
                        $jenis_sampel = [];
                        $dates = [];
                        $year = [];

                        $records->each(function ($record) use (&$recordIds, &$jenis_sampel, &$dates, &$year) {
                            if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                $recordIds[] = $record->id;
                            }
                            $jenis_sampel[] = $record->jenisSampel->nama;
                            $carbonDate = Carbon::parse($record->tanggal_memo);
                            $dates[] = $carbonDate->format('F');
                            $year[] = $carbonDate->format('Y');
                        });

                        $jenis_sample_final = implode(',', array_unique($jenis_sampel));
                        $dates_final = implode(',', array_unique($dates));
                        $year_final = implode(',', array_unique($year));
                        // dd($recordIds, $records);
                        $data = implode('$', $recordIds);
                        $filename = 'Dokumentasi ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                        return redirect()->route('exportdokumntasi', ['id' => $data, 'filename' => $filename])->with('target', '_blank');
                    }),

            ])
            ->actions([
                ActionGroup::make([
                    ActionGroup::make([
                        Action::make('export_kupa_pdf')
                            ->label('PDF')
                            ->url(function (TrackSampel $record) {
                                $jenis_sample_final = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates_final = $carbonDate->format('F');
                                $year_final = $carbonDate->format('Y');

                                $filename = 'Kupa ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                                return route('exporpdfkupa', ['id' => $record->id, 'filename' => $filename]);
                            })
                            ->icon('heroicon-o-document-arrow-down')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->openUrlInNewTab()
                            ->color('warning')
                            ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                            ->size('xs'),
                        Action::make('export_kupa')
                            ->label('Kupa')
                            ->url(fn(TrackSampel $record): string => route('export.excel', $record->id))
                            ->icon('heroicon-o-document-arrow-down')
                            ->color('success')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->visible(auth()->user()->can('export_kupa'))
                            ->size('xs'),
                    ])->button()
                        ->color('info')
                        ->icon('heroicon-o-document-arrow-down')
                        ->label('Kupa Export'),
                    ActionGroup::make([
                        Action::make('export_form_monitoring_kupa_pdf')
                            ->label('PDF')
                            ->url(function (TrackSampel $record) {
                                $jenis_sample_final = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates_final = $carbonDate->format('F');
                                $year_final = $carbonDate->format('Y');

                                $filename = 'Form Monitoring Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                                return route('exporpdfform', ['id' => $record->id, 'filename' => $filename]);
                            })
                            ->icon('heroicon-o-document-arrow-down')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->openUrlInNewTab()
                            ->color('warning')
                            ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                            ->size('xs'),
                        Action::make('export_form_monitoring_kupa')
                            ->label('Excel')
                            ->icon('heroicon-o-document-arrow-down')
                            ->color('success')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->action(function (TrackSampel $records) {
                                // dd($records);
                                $jenis_sample_final = $records->jenisSampel->nama;
                                $carbonDate = Carbon::parse($records->tanggal_memo);
                                $dates_final = $carbonDate->format('F');
                                $year = $carbonDate->format('Y');
                                $filename = 'Form Monitoring Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year . '.xlsx';
                                return Excel::download(new MonitoringKupabulk($records->id), $filename);
                            })
                            ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                            ->size('xs'),
                    ])->button()
                        ->color('info')
                        ->icon('heroicon-o-document-arrow-down')
                        ->label('Form Monitoring Export'),
                    ActionGroup::make([
                        Action::make('export_logbook_pdf')
                            ->label('PDF')
                            ->url(function (TrackSampel $record) {
                                $jenis_sample_final = $record->jenisSampel->nama;
                                $carbonDate = Carbon::parse($record->tanggal_memo);
                                $dates_final = $carbonDate->format('F');
                                $year_final = $carbonDate->format('Y');

                                $filename = 'Identitas Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                                return route('exporpdfpr', ['id' => $record->id, 'filename' => $filename]);
                            })
                            ->icon('heroicon-o-document-arrow-down')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->openUrlInNewTab()
                            ->color('warning')
                            ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                            ->size('xs'),
                        Action::make('export_logbook')
                            ->label('Excel')
                            // ->url(fn (TrackSampel $record): string => route('export.form-monitoring-kupa', $record->id))
                            ->action(function (TrackSampel $records) {
                                // dd($records);
                                $jenis_sample_final = $records->jenisSampel->nama;
                                $carbonDate = Carbon::parse($records->tanggal_memo);
                                $dates_final = $carbonDate->format('F');
                                $year = $carbonDate->format('Y');


                                // Concatenate strings and variables using the concatenation operator (.)
                                $filename = 'Identitas Sampel ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year . '.xlsx';
                                return Excel::download(new LogbookBulkExport($records->id), $filename);
                            })
                            ->icon('heroicon-o-document-arrow-down')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->color('success')
                            ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                            ->size('xs'),
                    ])->button()
                        ->color('info')
                        ->icon('heroicon-o-document-arrow-down')
                        ->label('Identitas Export'),
                    ActionGroup::make([
                        Action::make('export_vr')
                            ->label('PDF')
                            ->url(fn(TrackSampel $record): string => route('exportvr', $record->id))
                            ->icon('heroicon-o-document-arrow-down')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->openUrlInNewTab()
                            ->color('warning')
                            ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                            ->size('xs'),
                        Action::make('export_vr_excel')
                            ->label('Excel')
                            ->action(function (TrackSampel $records) {
                                // dd($records);
                                $jenis_sample_final = $records->jenisSampel->nama;
                                $carbonDate = Carbon::parse($records->tanggal_memo);
                                $dates_final = $carbonDate->format('F');
                                $year = $carbonDate->format('Y');


                                // Concatenate strings and variables using the concatenation operator (.)
                                $filename = 'PR Kupa ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year . '.xlsx';
                                return Excel::download(new pdfpr($records->id), $filename);
                            })
                            ->icon('heroicon-o-document-chart-bar')
                            ->disabled(function (TrackSampel $record) {
                                if ($record->status === 'Draft') {
                                    $func = true;
                                } else {
                                    $func = false;
                                }

                                return $func;
                            })
                            ->openUrlInNewTab()
                            ->color('success')
                            ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                            ->size('xs'),
                    ])->button()
                        ->color('info')
                        ->icon('heroicon-o-document-arrow-down')
                        ->label('PR Export'),
                    Action::make('export_dokumentasi')
                        ->label('Dokumentasi')
                        ->url(function (TrackSampel $record) {
                            $jenis_sample_final = $record->jenisSampel->nama;
                            $carbonDate = Carbon::parse($record->tanggal_memo);
                            $dates_final = $carbonDate->format('F');
                            $year_final = $carbonDate->format('Y');

                            $filename = 'Dokumentasi ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                            return route('exportdokumntasi', ['id' => $record->id, 'filename' => $filename]);
                        })
                        ->icon('heroicon-o-document-arrow-down')
                        ->disabled(function (TrackSampel $record) {
                            if ($record->status === 'Draft') {
                                $func = true;
                            } else {
                                $func = false;
                            }

                            return $func;
                        })
                        ->openUrlInNewTab()
                        ->color('warning')
                        ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                        ->size('xs'),
                    Action::make('export_persurat')
                        ->label('Export Persurat')
                        ->url(function (TrackSampel $record) {
                            $jenis_sample_final = $record->jenisSampel->nama;
                            $carbonDate = Carbon::parse($record->tanggal_memo);
                            $dates_final = $carbonDate->format('F');
                            $year_final = $carbonDate->format('Y');

                            $filename = 'Persurat ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                            return route('exportpersurat', ['id' => $record->id, 'filename' => $filename]);
                        })
                        ->icon('heroicon-o-document-arrow-down')
                        ->disabled(function (TrackSampel $record) {
                            if ($record->status === 'Draft') {
                                $func = true;
                            } else {
                                $func = false;
                            }

                            return $func;
                        })
                        ->openUrlInNewTab()
                        ->color('warning')
                        ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                        ->size('xs'),
                    Action::make('edit')
                        ->label('Edit Kupa')
                        ->url(fn(TrackSampel $record): string => route('history_sampel.edit', $record->id))
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
                            fn(TrackSampel $record) => "Anda yakin ingin menghapus data ini dengan kode track: {$record->kode_track}? Ketika dihapus tidak dapat dipulihkan kembali."
                        )
                        ->modalButton('Yes'),
                    EditAction::make('Verifikasi_Status')
                        ->label(fn(TrackSampel $record): string => checkApprovedLabelKupa($record))
                        ->disabled(function (TrackSampel $record) {
                            $user = Auth::user();
                            $roles = $user->getRoleNames();
                            $roles = auth()->user()->roles[0]->name;
                            $admin = $record->approveby_admin;
                            $head = $record->approveby_head;
                            if ($admin == 1 && $roles == 'Admin') {
                                $func = true;
                            } elseif ($head == 1 && $roles == 'Head Of Lab SRS') {
                                $func = true;
                            } elseif ($admin == 0 && $roles == 'Head Of Lab SRS') {
                                $func = true;
                            } elseif ($record->status === 'Rejected') {
                                $func = true;
                            } elseif ($record->status === 'Draft') {
                                $func = true;
                            } else {
                                $func = false;
                            }

                            return $func;
                        })
                        ->icon(fn(TrackSampel $record): string => checkIconApproved($record))
                        ->color(fn(TrackSampel $record): string => checkColorApproved($record))
                        ->modalHeading(fn(TrackSampel $record) => "Verifikasi Kupa " . $record->kode_track)
                        ->modalSubmitActionLabel('Submit')
                        ->form([
                            Select::make('status')
                                ->options([
                                    'Approved' => 'Approved',
                                    'Rejected' => 'Rejected',
                                ])
                                ->required()
                        ])
                        ->successNotification(null)
                        ->using(function (TrackSampel $record, array $data): TrackSampel {

                            if ($record->status_timestamp != null) {
                                $status_timestamp = $record->status_timestamp . ' , ' . Carbon::now()->format('Y-m-d H:i:s') . ' , ';
                            } else {
                                $status_timestamp = Carbon::now()->format('Y-m-d H:i:s');
                            }
                            $state = $data['status'];
                            $admin = $record->approveby_admin;
                            $head = $record->approveby_head;
                            $statusadmin = $admin;
                            $statushead = $head;
                            $userRole = auth()->user()->roles[0]->name;


                            if ($userRole === 'Admin') {
                                if ($state === 'Approved' && $head == 0) {
                                    $statusadmin = 1;
                                    $statusdata = 'Waiting Head Approval';
                                } elseif ($state === 'Approved' && $head == 1) {
                                    $statusadmin = 1;
                                    $statusdata = 'Approved';
                                } else {
                                    $statusadmin = 0;
                                    $statusdata = 'Rejected';
                                }
                            } elseif ($userRole === 'Head Of Lab SRS') {
                                if ($state === 'Approved' && $admin == 0) {
                                    $statushead = 1;
                                    $statusdata = 'Rejected';
                                } elseif ($state === 'Approved' && $admin == 1) {
                                    $statushead = 1;
                                    $statusdata = 'Approved';
                                } else {
                                    $statushead = 0;
                                    $statusdata = 'Rejected';
                                }
                            }
                            // dd($statusdata);
                            try {
                                DB::beginTransaction();
                                $id = $record->id;
                                $trackSampel = TrackSampel::find($id);
                                $trackSampel->approveby_admin = $statusadmin;
                                $trackSampel->approveby_head = $statushead;
                                $trackSampel->status = $statusdata;
                                $trackSampel->status_changed_by_id = auth()->user()->id;
                                $trackSampel->status_approved_by_role = auth()->user()->roles[0]->name;
                                $trackSampel->status_timestamp = $status_timestamp;
                                $trackSampel->save();

                                DB::commit();
                                Notification::make()
                                    ->success()
                                    ->title('Verifikasi Berhasil')
                                    ->body("Kupa " . $record->kode_track . " telah di-" . $statusdata)
                                    ->send();
                            } catch (\Throwable $th) {
                                DB::rollBack();

                                Notification::make()
                                    ->title('Error ' . $th->getMessage())
                                    ->danger()
                                    ->color('danger')
                                    ->send();
                            }
                            return $record;
                        })
                ])->tooltip('Actions'),
            ]);
    }

    private function renderpdfdata($id)
    {
        $data = $id;
        // dd($data);
        $pdf = PDF::loadView('pdfview.vrdata', []);

        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'landscape');
        return $pdf->stream('testiing');
    }

    public function render(): View
    {
        return view('livewire.history-kupa');
    }
}
