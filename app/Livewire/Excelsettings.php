<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\ExcelManagement;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class Excelsettings extends Component  implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {

        return $table
            ->query(ExcelManagement::query())
            ->headerActions([
                CreateAction::make()
                    ->model(ExcelManagement::class)
                    ->form([
                        Section::make()
                            ->description('Tambahkan user baru')
                            ->schema([
                                TextInput::make('nama')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('jabatan')
                                    ->required()
                                    ->options([
                                        'Petugas Penerima Sampel' => 'Petugas Penerima Sampel',
                                        'Penyelia' => 'Penyelia',
                                        'Petugas Preparasi' => 'Petugas Preparasi',
                                        'Staff Kimia & Lingkungan' => 'Staff Kimia & Lingkungan',
                                    ]),
                                Select::make('status')
                                    ->required()
                                    ->options([
                                        '1' => 'Aktif',
                                        '0' => 'Tidak Aktif',
                                    ])
                            ])
                            ->columns(3)

                    ])
                    ->successNotification(null)
                    ->using(function (array $data, string $model): ExcelManagement {
                        $check = ExcelManagement::where('jabatan', $data['jabatan'])->first();

                        if ($check != null) {
                            if ($check->status == 1 && $data['status'] == 1) {
                                Notification::make()
                                    ->warning()
                                    ->title('Perhatian')
                                    ->body('User lain dengan Jabatan ' . $data['jabatan'] . ' yang sama ini masih memiliki status aktif.')
                                    ->send();
                                return $check;
                            } else if ($check->status == 1 && $data['status'] == 0) {
                                Notification::make()
                                    ->success()
                                    ->title('User berhasil di tambahkan')
                                    ->body('User dengan Jabatan ' . $data['jabatan'] . ' ini berhasil di tambahkan.')
                                    ->send();
                                return $model::create($data);
                            } else if ($check->status == 0 && $data['status'] == 1) {
                                Notification::make()
                                    ->success()
                                    ->title('User berhasil di tambahkan')
                                    ->body('User dengan Jabatan ' . $data['jabatan'] . ' ini berhasil di tambahkan.')
                                    ->send();
                                return $model::create($data);
                            } else {
                                Notification::make()
                                    ->success()
                                    ->title('User berhasil di tambahkan')
                                    ->body('User dengan Jabatan ' . $data['jabatan'] . ' ini berhasil di tambahkan.')
                                    ->send();
                                return $model::create($data);
                            }
                        } else {
                            Notification::make()
                                ->success()
                                ->title('User berhasil di tambahkan')
                                ->body('User dengan Jabatan ini berhasil di tambahkan.')
                                ->send();
                            return $model::create($data);
                        }
                        // dd($check, $data['jabatan']);

                    })
            ])
            ->columns([
                TextColumn::make('nama'),
                TextColumn::make('jabatan'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function (ExcelManagement $track) {

                        return $track->status ? 'Aktif' : 'Tidak Aktif';
                    })
                    ->color(function (ExcelManagement $track) {
                        $result = '';
                        switch ($track->status) {
                            case '1':
                                $result = 'success';
                                break;
                            default:
                                $result = 'gray';
                        }
                        return $result;
                    })
            ])

            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->model(ExcelManagement::class)
                        ->form([
                            TextInput::make('nama')
                                ->required()
                                ->maxLength(255),

                            Select::make('jabatan')
                                ->required()
                                ->options([
                                    'Petugas Penerima Sampel' => 'Petugas Penerima Sampel',
                                    'Penyelia' => 'Penyelia',
                                    'Petugas Preparasi' => 'Petugas Preparasi',
                                    'Staff Kimia & Lingkungan' => 'Staff Kimia & Lingkungan',
                                ]),
                            Select::make('status')
                                ->required()
                                ->options([
                                    '1' => 'Aktif',
                                    '0' => 'Tidak Aktif',
                                ])
                        ])
                        ->successNotification(null)
                        ->using(function (ExcelManagement $record, array $data): ExcelManagement {


                            $records = ExcelManagement::where('jabatan', $data['jabatan'])->get();
                            $get_status = [];
                            foreach ($records as $key => $value) {
                                $get_status[] = $value->status;
                            }

                            $status = array_sum($get_status);

                            // dd($status, $data, $status >= 1 && $data['status'] == 1);

                            if ($status >= 1 && $data['status'] == 1) {
                                Notification::make()
                                    ->warning()
                                    ->title('Perhatian')
                                    ->body('User lain dengan Jabatan ' . $data['jabatan'] . ' yang sama masih memiliki status aktif silahkan ganti status user lain ke tidak aktif terlebih dahulu.')
                                    ->send();
                                // dd('1');
                                return $record;
                            } else if ($status >= 1 && $data['status'] == 0) {
                                Notification::make()
                                    ->success()
                                    ->title('User berhasil di tambahkan')
                                    ->body('User dengan Jabatan ' . $data['jabatan'] . 'ini berhasil di ubah.')
                                    ->send();
                                // dd('2');
                                $record->update($data);
                                return $record;
                            } else if ($status == 0 && $data['status'] == 1) {
                                Notification::make()
                                    ->success()
                                    ->title('User berhasil di tambahkan')
                                    ->body('User dengan Jabatan  ' . $data['jabatan'] . ' ini berhasil di ubah.')
                                    ->send();
                                // dd('3');
                                $record->update($data);
                                return $record;
                            } else {
                                // dd('4');
                                return $record;
                            }
                            // dd('5');

                            // $record->update($data);
                            // return $record;
                        }),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('User deleted')
                                ->body('User berhasil di hapus'),
                        ),
                ]),
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    public function render(): View
    {
        return view('livewire.excelsettings');
    }
}
