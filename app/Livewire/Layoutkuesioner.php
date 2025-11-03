<?php

namespace App\Livewire;

use App\Models\Kuesionerpertanyaan;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Models\Layoutkue;
use Filament\Tables\Actions\Action;

class Layoutkuesioner extends Component  implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {

        // dd($combinedArray);
        return $table
            ->query(Layoutkue::query())
            ->columns([
                TextColumn::make('label'),
                TextColumn::make('list_pertanyaan')
                    ->badge()
                    ->separator('$')
                    ->listWithLineBreaks()
                    ->state(function (Layoutkue $record) {
                        $id_pertanyan = explode(',', $record->list_pertanyaan);
                        $data = Kuesionerpertanyaan::wherein('id', $id_pertanyan)->get();
                        // dd($data);
                        $data_pertanyaan = [];
                        foreach ($data as $key => $value) {
                            $data_pertanyaan[] = $value->label;
                        }
                        // dd($data_pertanyaan);
                        return implode('$', $data_pertanyaan);
                    })
            ])
            ->headerActions([
                CreateAction::make('Pertanyaan')
                    ->model(Layoutkue::class)
                    ->modalWidth('7xl')
                    ->createAnother(false)
                    ->closeModalByClickingAway(false)
                    ->successNotification(null)
                    ->form([
                        Repeater::make('layout')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->placeholder('Contoh Data Diri')
                                    ->label('Nama Header'),
                                Select::make('pertanyaan')
                                    ->multiple()
                                    ->required()
                                    ->options(function () {
                                        $taken_pertanyaan = Layoutkue::pluck('list_pertanyaan')->toArray();

                                        $combinedArray = collect($taken_pertanyaan)
                                            ->flatMap(function ($item) {
                                                return explode(',', $item);
                                            })
                                            ->toArray();

                                        $data = Kuesionerpertanyaan::query()
                                            ->whereNotIn('id', $combinedArray)
                                            ->with('Tipe')
                                            ->get();

                                        $option = $data->mapWithKeys(function ($item) {
                                            return [$item->id => $item->label . ' (' . $item->Tipe->nama . ')'];
                                        })->toArray();

                                        return $option;
                                    })
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            ])
                            ->columns(2)
                    ])
                    ->using(function (array $data, string $model, CreateAction $action): Layoutkue {
                        // dd($data);

                        $parametersToInsert = [];
                        foreach ($data as $key => $value) {
                            foreach ($value as $key1 => $value1) {
                                $check_label = Layoutkue::where('label', $value1['name'])->first();
                                if ($check_label) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Header ' . $value1['name'] . ' sudah ada')
                                        ->danger()
                                        ->send();
                                    $action->halt();
                                }
                                // dd($value1);
                                $parametersToInsert[] = [
                                    'label' => $value1['name'],
                                    'list_pertanyaan' => implode(',', $value1['pertanyaan']),
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

                            return new Layoutkue();
                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->danger()
                                ->title('Error')
                                ->color('danger')
                                ->body($e)
                                ->send();

                            return new Layoutkue();
                        }
                    }),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('delete')
                    ->action(function (Layoutkue $record) {
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
                    ->modalHeading('Delete Layout')
                    ->modalButton('Yes'),
            ])
            ->bulkActions([
                // ...
            ]);
    }
    public function render()
    {
        return view('livewire.layoutkuesioner');
    }
}
