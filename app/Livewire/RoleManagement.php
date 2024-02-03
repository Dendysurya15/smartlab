<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\TrackSampel;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Permission;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Get;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Maatwebsite\Excel\Concerns\FromView;

class RoleManagement extends Component implements HasTable, HasForms
{


    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {



        $roles = Role::pluck('name');
        $permission = Permission::pluck('name');



        return $table
            ->query(User::query())
            ->columns([
                TextColumn::make('name')->sortable(),
                TextColumn::make('password')
                    ->getStateUsing(function (User $user) {
                        return implode('.', $user->getRoleNames()->toArray());
                    })
                    ->label('Role User')
                    ->badge()
                    ->separator('.')
                    ->color('success')
                    ->listWithLineBreaks()
                    ->sortable(),

                TextColumn::make('email')
                    ->getStateUsing(function (User $user) {
                        $permissionsString = implode('.', $user->getAllPermissions()->pluck('name')->toArray());
                        return $permissionsString;
                    })
                    ->color('success')
                    ->badge()
                    ->separator('.')
                    ->listWithLineBreaks()
                    ->label('Permission')
                    ->sortable(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->form([
                            Select::make('Roles')
                                ->options([
                                    'superuser' => 'superuser',
                                    'admin' => 'admin',
                                    'user' => 'user',
                                ])
                                ->live(),
                            Checkbox::make('view_rolemanagement')->inline(),
                            Checkbox::make('edit_data')->inline(),
                            Checkbox::make('view_dashboard')->inline(),
                            Checkbox::make('download')->inline(),
                        ])
                        ->using(function (User $record, array $data): User {
                            // Retrieve the user record
                            $user = User::find($record['id']);

                            // Empty all roles and permissions for the user
                            $user->roles()->detach();
                            $user->permissions()->detach();

                            if (!empty($data['Roles'])) {
                                // Create or retrieve the selected role
                                $role = Role::updateOrCreate(['name' => $data['Roles']]);

                                // Define permissions based on form input
                                $permissions = [];
                                if ($data['view_rolemanagement'] == true) {
                                    $permissions[] = Permission::updateOrCreate(['name' => 'view_rolemanagement']);
                                }
                                if ($data['edit_data'] == true) {
                                    $permissions[] = Permission::updateOrCreate(['name' => 'edit_data']);
                                }
                                if ($data['view_dashboard'] == true) {
                                    $permissions[] = Permission::updateOrCreate(['name' => 'view_dashboard']);
                                }
                                if ($data['download'] == true) {
                                    $permissions[] = Permission::updateOrCreate(['name' => 'download']);
                                }
                                // dd($permissions);
                                // Assign permissions to the role
                                $role->syncPermissions($permissions);

                                // Assign the role to the user
                                $user->assignRole($role);
                            }

                            // Return the modified user record
                            return $record;
                        }),


                    DeleteAction::make(),
                ])->color('info')->icon('heroicon-m-ellipsis-horizontal'),
                // ...
            ])

            ->defaultSort('name', 'asc');
    }



    public function render(): View
    {
        return view('livewire.role-management');
    }
}
