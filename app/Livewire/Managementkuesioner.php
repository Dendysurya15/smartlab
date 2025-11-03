<?php

namespace App\Livewire;

use App\Models\Resultkuesioner;
use Carbon\Carbon;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class Managementkuesioner extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Resultkuesioner::query()->orderBy('id', 'desc'))
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('nama')
                    ->label('Nama Peserta')
                    ->state(function (Resultkuesioner $data) {
                        $data = json_decode($data->result);
                        $nama = '-';
                        foreach ($data as $key => $values) {
                            if ($key == 22) {
                                $nama = $values->value;
                            }
                        }
                        return $nama;
                    }),
                TextColumn::make('datetime'),
            ])
            ->filters([
                SelectFilter::make('tahun')
                    ->label('Tahun terima')
                    ->options(function () {
                        $year = Resultkuesioner::query()
                            ->selectRaw('YEAR(datetime) as year')
                            ->distinct()
                            ->pluck('year')
                            ->mapWithKeys(fn($item) => [$item => $item]);
                        return $year;
                    })
                    ->default(Carbon::now()->format('Y'))
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value'] !== null) {
                            return $query->whereRaw('YEAR(datetime) = ?', [$data['value']]);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                // aa 
                // Action::make('check_result')
                //     ->label('edit')
                //     ->icon('heroicon-o-eye')
                //     ->color('success')
                //     ->icon('heroicon-o-document-arrow-down')
                //     ->form([
                //         Fieldset::make('Detail Responden')
                //             ->schema(function (Resultkuesioner $data) {
                //                 return Generateresult($data->id);
                //             })->columns(3)

                //     ])
                //     ->modalSubmitActionLabel('Update')
                //     ->action(function (Resultkuesioner $record, Action $action, array $data) {

                //         $formattedData = [];

                //         foreach ($data as $key => $value) {
                //             $formattedData[$key] = [
                //                 'key' => $key,
                //                 'value' => $value,
                //             ];
                //         }
                //         $record->result = json_encode($formattedData);
                //         $record->save();
                //         if ($record) {
                //             Notification::make()
                //                 ->success()
                //                 ->title('Update berhasil')
                //                 ->body("Kuesioner berhasil diupdate")
                //                 ->send();
                //         } else {
                //             Notification::make()
                //                 ->danger()
                //                 ->title('Update gagal')
                //                 ->body("Kuesioner gagal diupdate")
                //                 ->send();
                //         }
                //         // dd($formattedData);
                //     }),

                Action::make('export_logbook_pdf')
                    ->label('PDF')
                    ->url(function (Resultkuesioner $record) {
                        $carbonDate = Carbon::parse($record->datetime);
                        $dates_final = $carbonDate->format('F');
                        $year_final = $carbonDate->format('Y');

                        $filename = 'Hasil Kuesioner ' . ' Bulan ' . $dates_final . ' tahun ' . $year_final;
                        return route('exportpdf_kuesiner', ['id' => $record->id, 'filename' => $filename]);
                    })
                    ->icon('heroicon-o-document-arrow-down')
                    ->openUrlInNewTab()
                    ->color('warning')
                    ->visible(auth()->user()->can('export_form_monitoring_kupa'))
                    ->size('xs'),

            ])
            ->bulkActions([
                BulkAction::make('export_logbook')
                    ->label('PDF')
                    ->button()
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('success')
                    ->action(function ($records) {
                        $data = $records->pluck('id');
                        $data = $data->implode('$');
                        $filename = 'Hasil Kuesioner';
                        return redirect()->route('exportpdf_kuesiner', ['id' => $data, 'filename' => $filename])->with('target', '_blank');
                    })
            ]);
    }

    public function render()
    {
        return view('livewire.managementkuesioner');
    }
}
