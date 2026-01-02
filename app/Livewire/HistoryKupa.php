<?php

namespace App\Livewire;

use App\Events\Smartlabsnotification;
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
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\MonitoringKupabulk;
use App\Exports\LogbookBulkExport;
use App\Exports\pdfpr;
use App\Mail\EmailPelanggan;
use App\Models\DepartementTrack;
use App\Models\Invoice;
use App\Models\Progress;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Enums\FiltersLayout;
use App\Models\JenisSampel;
use App\Models\ProgressPengerjaan;
use App\Jobs\Generatebulkpdfpr;
use App\Jobs\SendEmailPelangganJob;

class HistoryKupa extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;
    public ?array $data = [];
    public $openurl;
    public $rolesAuthUser;
    public $id_sertifikat;
    public $count;
    public $filename;
    public $dataexport;

    public function mount()
    {
        // $this->roles = Role::where('name', '<>', 'superuser')->orderBy('alur_approved')->pluck('name');
        $this->form->fill();
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
                    ->visible(auth()->user()->can('send_invoice'))
                    ->size('xs'),
                SelectColumn::make('progress')
                    ->label('Progres detail sampel')
                    ->visible(auth()->user()->can('edit_kupa'))
                    ->disabled(fn(TrackSampel $record) => $record->approveby_admin == 0 || $record->progress == 7)
                    ->default(fn(TrackSampel $record) => $record->progress)
                    ->selectablePlaceholder(false)
                    ->options(Progress::query()->pluck('nama', 'id'))
                    ->afterStateUpdated(function ($record, $state) {
                        // // dd($state, $record);
                        if ($state == 7) {
                            $this->id_sertifikat = $record->id;
                            $this->dispatch('open-modal', id: 'upload-sertifikat');
                        }
                        $nomor_hp = explode(',', $record->no_hp);
                        $progress_data = Progress::where('id', $state)->get()->pluck('nama') ?? null;
                        $dataToInsert2 = [];

                        if (check_invalid_value($nomor_hp)) {
                            foreach ($nomor_hp as $data) {
                                $nomorHp = str_replace('+', '', $data);

                                // Validate the phone number (example: must be numeric and 10-15 digits)
                                if (preg_match('/^\d{10,15}$/', $nomorHp)) {
                                    $dataToInsert2[] = [
                                        'no_surat' => $record->nomor_surat,
                                        'nama_departemen' => $record->departemen,
                                        'jenis_sampel' => $record->jenisSampel->nama,
                                        'jumlah_sampel' => $record->jumlah_sampel,
                                        'progresss' => $progress_data[0] ?? null,
                                        'kodesample' => $record->kode_track,
                                        'penerima' =>  str_replace('+', '', $nomorHp),
                                        'tanggal_registrasi' => $record->tanggal_terima,
                                        'estimasi' => $record->estimasi,
                                        'type' => 'update',
                                        'tanggal_rilis_sertifikat' => $record->tanggal_rilis_sertifikat,
                                    ];
                                }
                            }

                            // dd($dataToInsert2);
                            if (!empty($dataToInsert2)) {
                                event(new Smartlabsnotification($dataToInsert2));
                            }
                        }
                        // dd($dataToInsert2);
                        $emailAddresses = !empty($record->emailTo) ? explode(',', $record->emailTo) : null;
                        $emailcc = !empty($record->emailCc) ? explode(',', $record->emailCc) : null;
                        if ($emailAddresses !== null) {
                            $emailData = [
                                'nomor_surat' => $record->nomor_surat,
                                'departement' => $record->departemen,
                                'jenis_sampel' => $record->jenisSampel->nama,
                                'jumlah_sampel' => $record->jumlah_sampel,
                                'progress' => $progress_data[0] ?? null,
                                'kode_tracking_sampel' => $record->kode_track,
                                'id' => null,
                                'tanggal_registrasi' => $record->tanggal_terima,
                                'estimasi_kup' => $record->estimasi
                            ];
                            SendEmailPelangganJob::dispatch($emailAddresses, $emailcc, $emailData);
                        }

                        $id = $record->id;
                        $trackSampel = TrackSampel::find($id);

                        $date = Carbon::now()->format('Y-m-d H:i:s');
                        $data_progres = json_decode($record->last_update, true);
                        $progress = $data_progres;
                        $current = [
                            'jenis_sampel' => $record->jenis_sampel,
                            'progress' => ($record->progress ?? "0") == "0" ? "4" : ($record->progress ?? null),
                            'updated_at' => $date
                        ];


                        // Check if the progress already exists in the $progress array
                        $exists = false;
                        foreach ($progress as $item) {
                            if ($item['jenis_sampel'] == $current['jenis_sampel'] && $item['progress'] == $current['progress']) {
                                $exists = true;
                                break;
                            }
                        }

                        // Add the $current array to $progress only if it doesn't already exist
                        if (!$exists) {
                            $progress[] = $current;
                        }

                        // dd($progress, $current, $progress);
                        $current = json_encode($progress);
                        if ($state == 7) {
                            $trackSampel->tanggal_rilis_sertifikat = Carbon::now();
                        }
                        $trackSampel->last_update = $current;
                        $trackSampel->save();

                        if ($trackSampel) {

                            Notification::make()
                                ->title("Progress Sampel Berhasil Diupdate")
                                ->body("Record dengan kode " . $record->kode_track . "  berhasil update")
                                ->success()
                                ->send();
                        } else {

                            Notification::make()
                                ->title("Error di update")
                                ->body("Record dengan kode " . $record->kode_track . "  gagal update")
                                ->danger()
                                ->send();
                        }
                    }),
                TextColumn::make('nomor_kupa')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('nomor_lab')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->state(function (TrackSampel $record) {
                        $year = substr($record->lab_label_tahun, -2);
                        $nolab = explode('$', $record->nomor_lab);
                        // $year = Carbon::parse($record->tanggal_terima)->format('y');
                        $kode_sampel = $record->jenisSampel->kode;

                        $nolab = explode('$', $record->nomor_lab);
                        // $year = Carbon::parse($record->tanggal_terima)->format('y');
                        $kode_sampel = $record->jenisSampel->kode;

                        $labkiri = $year . $kode_sampel . '.' . formatLabNumber($nolab[0]);
                        $labkanan = isset($nolab[1]) ? $year . $kode_sampel . '.' . formatLabNumber($nolab[1]) : '??';

                        return $labkiri . '-' . $labkanan;
                    })
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
                                // return $track->status . ' by ' . ($roles->isNotEmpty() ? $roles->implode(', ') : 'No Role');
                                return $track->status . ' by Manager Lab';
                            } else {
                                // return $track->status;
                                if ($track->status == 'Waiting Head Approval') {
                                    return 'Waiting Manager Lab Approval';
                                } else {
                                    return $track->status;
                                }
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
                TextColumn::make('sertifikasi')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->state(function (TrackSampel $track) {
                        if ($track->progress == 7) {
                            if ($track->sertifikasi == null) {
                                return 'Sertifikasi Belum Diupload';
                            } else {
                                return 'Sertifikasi Sudah Diupload';
                            }
                        }
                        return 'Progress belum selesai';
                    })
                    ->color(function (TrackSampel $track) {
                        if ($track->progress != 7) {
                            return 'info'; // Progress belum selesai
                        }

                        if ($track->sertifikasi == null) {
                            return 'danger'; // Sertifikasi Belum Diupload
                        }

                        return 'success'; // Sertifikasi Sudah Diupload
                    })
                    ->sortable()
                    ->size('xs'),
                TextColumn::make('tanggal_rilis_sertifikat')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(function (TrackSampel $track) {
                        return tanggal_indo($track->tanggal_rilis_sertifikat, false, false, true);
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
                // TextColumn::make('invoice')
                //     ->label('Detail Invoice')
                //     ->toggleable(isToggledHiddenByDefault: false)
                //     ->badge()
                //     ->color(fn(string $state): string => match ($state) {
                //         'Update' => 'warning',
                //         'Invoice Send Pending' => 'warning',
                //         'Invoice Send' => 'success',
                //         'Default' => 'grey',
                //         default => 'danger',
                //     })
                //     ->state(function (TrackSampel $record) {
                //         if ($record->asal_sampel == 'Eksternal') {
                //             if ($record->invoice_status == 0) {
                //                 return  'Update';
                //             } else if ($record->invoice_status == 1) {
                //                 return  'Invoice Send Pending';
                //             } else if ($record->invoice_status == 2) {
                //                 return  'Invoice Send';
                //             } else {
                //                 return 'Invalid Status';
                //             }
                //         } else {
                //             return 'Default';
                //         }
                //     })
                //     ->size('xs'),
            ])->striped()
            ->recordClasses(fn(TrackSampel $record) => match ($record->progressSampel->nama) {
                'Recheck' => 'bg-yellow-100',
                'Rilis Sertifikat' => 'bg-green-100',
                default => null,
            })
            ->filters([
                SelectFilter::make('tahun')
                    ->label('Tahun terima')
                    ->options(function () {
                        // $year = TrackSampel::query()
                        //     ->selectRaw('YEAR(tanggal_terima) as year')
                        //     ->distinct()
                        //     ->pluck('year')
                        //     ->mapWithKeys(fn($item) => [$item => $item]);

                        $year = TrackSampel::query()
                            ->whereNotNull('lab_label_tahun')
                            ->where('lab_label_tahun', '!=', '')
                            ->distinct()
                            ->orderBy('lab_label_tahun', 'desc')
                            ->pluck('lab_label_tahun', 'lab_label_tahun');


                        // dd($year);

                        return $year;
                    })
                    ->default(Carbon::now()->format('Y'))
                    ->query(function (Builder $query, array $data): Builder {
                        // if ($data['value'] !== null) {
                        //     dd($data, $query->where('lab_label_tahun', $data['value']));
                        // }

                        return $query->where('lab_label_tahun', $data['value']);
                    }),
                SelectFilter::make('departemen')
                    ->label('Departement')
                    ->multiple()
                    ->options(DepartementTrack::query()->pluck('nama', 'nama'))
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
                SelectFilter::make('Jenis Sampel')
                    ->label('Jenis Sampel')
                    ->options(JenisSampel::query()->pluck('nama', 'nama'))
                    ->relationship('jenisSampel', 'nama')
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                        'Waiting Admin Approval' => 'Waiting Admin Approval',
                        'Waiting Head Approval' => 'Waiting Head Approval',
                        'Draft' => 'Draft',
                    ])
                    ->preload(),
                SelectFilter::make('progress')
                    ->label('Progress')
                    ->options(ProgressPengerjaan::query()->pluck('nama', 'id'))
                    ->preload(),
                // SelectFilter::make('progress')
                //     ->label('Sertifikat')
                //     ->options([
                //         'done' => 'Sertifikasi Sudah Diupload',
                //         'not_done' => 'Sertifikasi Belum Diupload'
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         // dd($data);
                //         // if ($data['value'] !== null) {
                //         //     dd($data, $query);
                //         // }

                //         switch ($data['value'] == "done") {
                //             case 'value':
                //                 return $query->where('progress', 7);
                //                 break;

                //             default:
                //                 return $query->whereNot('progress', 7);
                //                 break;
                //         }
                //     })
                //     ->preload(),
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
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->bulkActions([
                // In your bulk action for KUPA PDF export
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->label('Hapus Kupa')
                    ->icon('heroicon-m-trash')
                    ->visible(auth()->user()->can('hapus_kupa'))
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
                        ->modalButton('Export Log Book')
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
                            // dd($data);
                            // Concatenate strings and variables using the concatenation operator (.)
                            $filename = 'PR Kupa ' . $jenis_sample_final . ' Bulan ' . $dates_final . ' tahun ' . $year_final . '.xlsx';
                            return Excel::download(new pdfpr($data), $filename);
                        }),
                    // ... existing code ...
                    BulkAction::make('export_pr_pdf_jobs')
                        ->label('PDF')
                        ->button()
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('warning')
                        ->deselectRecordsAfterCompletion()
                        // ->closeModalByClickingAway(false)
                        ->action(function (Collection $records) {



                            $recordIds = [];
                            $totalItems = 0;

                            $records->each(function ($record) use (&$recordIds, &$totalItems) {
                                if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                                    $recordIds[] = $record->id;
                                    $totalItems++;
                                }
                            });

                            $this->dataexport = implode('$', $recordIds);
                            $this->filename = 'kupa_export_' . now()->format('Y-m-d_H-i-s');
                            $this->count = count($recordIds);
                            // // Show processing notification
                            // Notification::make()
                            //     ->title('Processing PDF Export')
                            //     ->body("Generating PDF for {$totalItems} items. This may take a few minutes...")
                            //     ->persistent()
                            //     ->warning()
                            //     ->send();

                            // // Redirect to download endpoint
                            // return redirect()->route('download.bulk.pdf', [
                            //     'data' => $data,
                            //     'filename' => $filename
                            // ]);
                            $this->dispatch('open-modal', id: 'generate-bulk-pdf', data: [
                                'dataexport' => $this->dataexport,
                                'filename' => $this->filename,
                                'count' => $this->count
                            ]);
                        })
                ])->button()
                    ->color('info')
                    ->label('Export Log Book'),
                ActionGroup::make([
                    BulkAction::make('export_kupa_pdf')
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

                            // dd($data);

                            return redirect()->route('exporpdfkupa', ['id' => $data, 'filename' => 'bulk'])->with('target', '_blank');
                        }),
                ])->button()
                    ->color('info')
                    ->label('Export Kupa'),
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
                BulkAction::make('Approve')
                    ->label('Approve')
                    ->button()
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->modalSubmitActionLabel('Submit')
                    ->visible(auth()->user()->can('verify_kupa'))
                    ->form([
                        Select::make('status')
                            ->options([
                                'Approved' => 'Approved',
                                'Rejected' => 'Rejected',
                            ])
                            ->required()
                    ])
                    ->successNotification(null)
                    ->action(function (Collection $records, array $data) {
                        try {
                            DB::beginTransaction();

                            $records->each(function ($record) use ($data) {
                                $state = $data['status'];
                                $admin = $record->approveby_admin;
                                $head = $record->approveby_head;
                                $statusadmin = $admin;
                                $statushead = $head;
                                $userRole = auth()->user()->roles[0]->name;

                                if ($record->status_timestamp != null) {
                                    $status_timestamp = $record->status_timestamp . ' , ' . Carbon::now()->format('Y-m-d H:i:s') . ' , ';
                                } else {
                                    $status_timestamp = Carbon::now()->format('Y-m-d H:i:s');
                                }

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

                                $record->update([
                                    'approveby_admin' => $statusadmin,
                                    'approveby_head' => $statushead,
                                    'status' => $statusdata,
                                    'status_changed_by_id' => auth()->user()->id,
                                    'status_approved_by_role' => auth()->user()->roles[0]->name,
                                    'status_timestamp' => $status_timestamp,
                                ]);
                            });

                            DB::commit();

                            Notification::make()
                                ->success()
                                ->title('Bulk Verification Success')
                                ->body('Selected records have been processed successfully')
                                ->send();
                        } catch (\Throwable $th) {
                            DB::rollBack();

                            Notification::make()
                                ->title('Error ' . $th->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
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
                        ->visible(auth()->user()->can('verify_kupa'))
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
                        }),
                    EditAction::make('edit_invoice')
                        ->label(fn(TrackSampel $record): string => $record->invoice_status == '0' ? 'Edit Invoice' : 'Invoice Uptodate')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->disabled(
                            fn(TrackSampel $record) =>
                            $record->asal_sampel == 'Internal' ? true : ($record->invoice_status == '0' ? false : true)
                        )
                        // ->hidden(fn(TrackSampel $record): string => $record->asal_sampel == 'Internal')
                        ->visible(fn($record) => auth()->user()->can('send_invoice') && $record->asal_sampel === 'Eksternal')
                        ->modalHeading(fn(TrackSampel $record) => "Edit Invoice " . $record->kode_track)
                        ->modalSubmitActionLabel('Submit')
                        ->form([
                            Fieldset::make('Detail')
                                ->label('Pastikan mengisi detail pelanggan dengan benar')
                                ->schema([
                                    Select::make('list')
                                        ->label('List Detail Pelanggan')
                                        ->columnSpanFull()
                                        ->searchable()
                                        ->live(debounce: 500)
                                        ->required()
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            // dd($state);
                                            if ($state !== '0') {
                                                $invoice = Invoice::find($state);
                                                // dd($invoice, $state);
                                                $set('nama_pelanggan', $invoice->nama);
                                                $set('alamat_pelanggan', $invoice->address);
                                                $set('npwp', $invoice->npwp_id);
                                            } else {
                                                $set('nama_pelanggan', '');
                                                $set('alamat_pelanggan', '');
                                                $set('npwp', '');
                                            }
                                        })
                                        ->options(function () {
                                            $data = Invoice::query()->pluck('nama', 'id')->toArray();
                                            $default = [0 => 'Tidak Ada'];
                                            return $default + $data;
                                        }),
                                    TextInput::make('nama_pelanggan')
                                        ->label('Nama Pelanggan')
                                        ->readOnly(fn(Get $get) => $get('list') !== '0' ? true : false)
                                        ->maxLength(100)
                                        ->placeholder('Silahkan pilih detail pelanggan mengisi manual')
                                        ->required(),
                                    TextInput::make('alamat_pelanggan')
                                        ->label('Alamat Pelanggan')
                                        ->maxLength(200)
                                        ->placeholder('Silahkan pilih detail pelanggan mengisi manual')
                                        ->readOnly(fn(Get $get) => $get('list') !== '0' ? true : false)
                                        ->required(),
                                    TextInput::make('npwp')
                                        ->label('NPWP')
                                        ->placeholder('Silahkan pilih detail pelanggan mengisi manual')
                                        ->readOnly(fn(Get $get) => $get('list') !== '0' ? true : false)
                                        ->maxLength(200)
                                        ->required(),
                                ])
                                ->columns(3),
                            Fieldset::make('invoice')
                                ->label('Detail Invoice')
                                ->schema([
                                    DatePicker::make('tanggal_invoice')
                                        ->label('Tanggal Invoice')
                                        ->required(),
                                    TextInput::make('no_kontrak')
                                        ->label('No. Kontrak')
                                        ->maxLength(200),
                                    DatePicker::make('tanggal_kontrak')
                                        ->label('Tanggal Kontrak'),
                                    TextInput::make('pembayaran')
                                        ->label('Pembayaran')
                                        ->maxLength(200)
                                        ->required(),
                                    TextInput::make('statuss')
                                        ->label('Status Pajak / Kurs')
                                        ->maxLength(200)
                                        ->required(),
                                ])
                                ->columns(3)
                        ])
                        ->successNotification(null)
                        ->using(function (TrackSampel $record, array $data): TrackSampel {

                            // dd($data);
                            // dd($statusdata);
                            try {
                                DB::beginTransaction();
                                $invoice = new Invoice();
                                $invoice->nama = $data['nama_pelanggan'];
                                $invoice->address = $data['alamat_pelanggan'];
                                $invoice->npwp_id = $data['npwp'];
                                $invoice->created_by = auth()->user()->id;
                                $invoice->save();
                                $id_data = $invoice->id;

                                $record->invoice_id = $id_data;
                                $record->no_kontrak = $data['no_kontrak'];
                                $record->tanggal_kontrak = $data['tanggal_kontrak'];
                                $record->tanggal_invoice = $data['tanggal_invoice'];
                                $record->pembayaran = $data['pembayaran'];
                                $record->status_pajak = $data['statuss'];
                                $record->invoice_status = 1;
                                $record->save();

                                DB::commit();
                                Notification::make()
                                    ->success()
                                    ->title('Verifikasi Berhasil')
                                    ->body('Invoice berhasil di verifikasi')
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
                        }),
                    Action::make('send_invoice')
                        ->label(fn(TrackSampel $record): string => $record->invoice_status !== '2' ? 'Send Invoice' : 'Invoice Sended')
                        ->action(function (TrackSampel $records) {
                            // dd($records);
                            $nomor_hp = $records->no_hp;
                            $nomor_hp = explode(',', $nomor_hp);
                            $dataToInsert2 = [];
                            foreach ($nomor_hp as $data) {
                                $nomor_hp = numberformat_excel($data);

                                if ($nomor_hp !== 'Error') {
                                    $dataToInsert2[] = [
                                        'no_surat' => $records->nomor_surat,
                                        'nama_departemen' => $records->departemen,
                                        'jenis_sampel' => $records->jenisSampel->nama,
                                        'jumlah_sampel' => $records->jumlah_sampel,
                                        'progresss' => $records->progressSampel->nama,
                                        'kodesample' => $records->kode_track,
                                        'penerima' =>  str_replace('+', '', $data),
                                        'type' => 'input',
                                        'asal' => $records->asal_sampel,
                                        'id_invoice' => $records->id,
                                        'tanggal_registrasi' => $records->tanggal_terima,
                                        'tanggal_rilis_sertifikat' => $records->tanggal_rilis_sertifikat,
                                        'estimasi' => $records->estimasi,
                                    ];
                                }
                            }
                            $emailAddresses = !empty($records->emailTo) ? explode(',', $records->emailTo) : null;
                            $emailcc = !empty($records->emailCc) ? explode(',', $records->emailCc) : null;

                            if (!empty($dataToInsert2)) {
                                event(new Smartlabsnotification($dataToInsert2));
                            }

                            // dd($progress, $progress_state);
                            // if ($emailAddresses !== null) {
                            //     Mail::to($emailAddresses)
                            //         ->cc($emailcc)
                            //         ->send(new EmailPelanggan($records->nomor_surat, $records->departemen, $records->jenisSampel->nama, $records->jumlah_sampel, $records->progressSampel->nama, $records->kode_track, $records->id, $records->tanggal_terima, $records->estimasi));
                            // }
                            if ($emailAddresses !== null) {
                                $emailData = [
                                    'nomor_surat' => $records->nomor_surat,
                                    'departement' => $records->departemen,
                                    'jenis_sampel' => $records->jenisSampel->nama,
                                    'jumlah_sampel' => $records->jumlah_sampel,
                                    'progress' => $records->progressSampel->nama,
                                    'kode_tracking_sampel' => $records->kode_track,
                                    'id' => $records->id,
                                    'tanggal_registrasi' => $records->tanggal_terima,
                                    'estimasi_kup' => $records->estimasi
                                ];
                                SendEmailPelangganJob::dispatch($emailAddresses, $emailcc, $emailData);
                            }
                            $records->invoice_status = 2;
                            $records->save();
                            return Notification::make()
                                ->success()
                                ->title('Invoice Berhasil Dikirim')
                                ->body('Invoice berhasil dikirim ke pelanggan')
                                ->send();
                        })
                        ->icon('heroicon-o-document-chart-bar')
                        ->disabled(function (TrackSampel $record) {
                            if ($record->asal_sampel === 'Eksternal') {

                                if ($record->invoice_status == 1) {
                                    return false;
                                } else {
                                    return true;
                                }
                            } else {
                                return true;
                            }
                        })
                        ->openUrlInNewTab()
                        ->color('success')
                        ->visible(fn($record) => auth()->user()->can('send_invoice') && $record->asal_sampel === 'Eksternal')
                        ->size('xs'),
                    Action::make('download_invoice')
                        ->label('Download Invoice')
                        ->action(function (TrackSampel $record) {
                            $client = new Client();
                            // dd($record);
                            if ($record->id !== null) {
                                // Make a GET request to the API with query parameters
                                $response = $client->get('https://management.srs-ssms.com/api/invoices_smartlabs', [
                                    'query' => [
                                        'email' => 'j',
                                        'password' => 'j',
                                        'id_data' => $record->id,
                                    ],
                                ]);

                                $responseData = json_decode($response->getBody()->getContents(), true);

                                if (isset($responseData['pdf'])) {
                                    // Decode the base64 PDF
                                    $pdfContent = base64_decode($responseData['pdf']);
                                    $pdfFilename = 'invoice_' .
                                        str_replace(['/', '\\'], '_', $record->jenisSampel->nama) . '_' .
                                        str_replace(['/', '\\'], '_', $record->nomor_surat) . '.pdf';

                                    // Return the PDF as a download
                                    return response()->streamDownload(function () use ($pdfContent) {
                                        echo $pdfContent;
                                    }, $pdfFilename, [
                                        'Content-Type' => 'application/pdf',
                                        'Content-Disposition' => 'attachment; filename="' . $pdfFilename . '"',
                                    ]);
                                }
                            }

                            return Notification::make()
                                ->success()
                                ->title('Success')
                                ->body('Invoice berhasil diunduh')
                                ->send();
                        })
                        ->icon('heroicon-o-document-chart-bar')
                        ->color('success')
                        ->visible(fn($record) => auth()->user()->can('send_invoice') && $record->asal_sampel === 'Eksternal')
                        ->size('xs'),
                    Action::make('reupload_sertifikat')
                        ->label('Reupload Sertifkat')
                        ->action(function (TrackSampel $record) {

                            if ($record->progress == 7) {
                                $this->id_sertifikat = $record->id;
                                $this->dispatch('open-modal', id: 'upload-sertifikat');

                                return;
                            }

                            return Notification::make()
                                ->title("Progress belum selesai tidak bisa upload sertifikat")
                                ->warning()
                                ->send();
                        })
                        ->icon('heroicon-o-document-chart-bar')
                        ->color('success')
                        ->visible(fn($record) => $record->progress == 7)
                        ->size('xs'),
                    Action::make('update_date_sertifikat')
                        ->label('Update Tanggal Rilis Sertifikat')
                        ->action(function (TrackSampel $record) {

                            if ($record->progress == 7) {
                                $last_update = json_decode($record->last_update, true);
                                $result = collect($last_update)->firstWhere('progress', 7)['updated_at'] ?? Carbon::now();

                                $tanggal_rilis_sertifikat = Carbon::parse($result);
                                $record->tanggal_rilis_sertifikat = $tanggal_rilis_sertifikat;
                                $record->save();

                                return Notification::make()
                                    ->success()
                                    ->title('Success')
                                    ->body('Tanggal rilis sertifikat berhasil diupdate')
                                    ->send();
                            }

                            return Notification::make()->title("Progress belum selesai tidak bisa update tanggal rilis sertifikat")->warning()->send();
                        })
                        ->visible(fn($record) => $record->progress == 7 && $record->tanggal_rilis_sertifikat == null)
                        ->icon('heroicon-o-document-chart-bar')
                        ->color('success')
                        // ->visible(fn($recor,d) => auth()->user()->can('send_invoice') && $record->asal_sampel === 'Eksternal')
                        ->size('xs'),
                ])->tooltip('Actions'),
            ]);
    }

    public function generateBulkPdf()
    {
        // dd($this->dataexport);
        $data = $this->dataexport;
        $filename = $this->filename;

        // Generate PDF synchronously
        $pdf = app(Generatebulkpdfpr::class, [
            'data' => $data,
            'filename' => $filename,
            'user_id' => auth()->id(),
            'totalItems' => count(explode('$', $data)),
            'random_task_id' => generateRandomString(10) . date('YmdHis')
        ])->handle();

        // Return PDF download response
        $this->dispatch('close-modal', id: 'generate-bulk-pdf');
        return response()->download($pdf['filepath'], $pdf['filename']);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('file')
                    ->multiple()
                    ->label('File Sertifikat')
                    ->columnSpanFull()
                    ->directory('sertifikat')
                    ->preserveFilenames()
                    ->maxFiles(5)
                    ->maxSize(5024)
                    ->visibility('private')
                    ->disk('private')
                    ->required(),
            ])
            ->statePath('data');
    }




    public function create(): void
    {
        $form = $this->form->getState();
        $insert = TrackSampel::find($this->id_sertifikat);

        // Delete old file if exists
        // if ($insert->sertifikasi !== null) {
        //     try {
        //         $oldFiles = explode(',', $insert->sertifikasi);
        //         foreach ($oldFiles as $oldFile) {
        //             $filepath = storage_path('app/private/' . $oldFile);
        //             if (is_file($filepath) && file_exists($filepath)) {
        //                 unlink($filepath);
        //             }
        //         }
        //     } catch (\Exception $e) {
        //         // Log error but continue with save
        //         \Log::error('Error deleting old files: ' . $e->getMessage());
        //     }
        // }

        // Continue with save regardless of deletion success/failure
        $insert->sertifikasi = implode(',', $form['file']);
        $insert->save();

        $this->form->fill();
        $this->dispatch('close-modal', id: 'upload-sertifikat');

        Notification::make()
            ->success()
            ->title('Success')
            ->body('Sertifikat berhasil diunggah')
            ->send();
    }

    public function render(): View
    {
        return view('livewire.history-kupa');
    }
}
