<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\TrackSampel;
use Exception;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\FromView;

class RoleManagement extends Component implements HasTable, HasForms
{


    use InteractsWithTable;
    use InteractsWithForms;

    public $name;
    public $email;
    public $role_user;
    public $password;

    public bool $successSubmit = false;
    public string $msgSuccess;
    public bool $errorSubmit = false;
    public string $msgError;

    public function table(Table $table): Table
    {
        $roles = Role::pluck('name', 'id');
        $permissionsAll = Permission::pluck('name', 'id')->toArray();

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
                                ->options($roles)
                                ->live(),
                            ...array_map(function ($permission) {
                                return Checkbox::make($permission)->inline()->label($permission);
                            }, $permissionsAll),
                        ])
                        ->using(function (User $record, array $data) use ($permissionsAll): User {
                            $user = User::find($record['id']);


                            if (!empty($data['Roles'])) {
                                $user->roles()->detach();
                                $user->permissions()->detach();

                                $role = Role::where('id', $data['Roles'])->first();

                                $permissions = [];
                                foreach ($permissionsAll as $permission) {
                                    if (!empty($data[$permission])) {
                                        $permissions[] = $permission;
                                    }
                                }

                                $user->assignRole($role->name);
                                $role->syncPermissions($permissions);
                            }
                            return $record;
                        }),


                    DeleteAction::make(),
                ])->color('info')->icon('heroicon-m-ellipsis-horizontal'),
                // ...
            ])

            ->defaultSort('name', 'asc');
    }


    public function save()
    {
        try {
            DB::beginTransaction();
            $new_user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            if ($this->role_user) {
                // Find the role by name
                $role = Role::where('name', $this->role_user)->first();

                // Attach the role to the new user
                $new_user->assignRole($role);

                // Sync permissions associated with the role
                $new_user->syncPermissions($role->permissions);
            }

            DB::commit();
            $this->successSubmit = true;
            $this->reset(['name', 'email', 'password', 'role_user']);
        } catch (Exception $e) {
            DB::rollBack();
            // session()->flash('errorSubmit', 'An error occurred while saving the data. ' .  $e->getMessage());
            $this->msgError = 'An error occurred while saving the data: ' . $e->getMessage();
            // Set the error flag
            $this->errorSubmit = true;
        }
    }


    public function render(): View
    {
        $roles = Role::WhereNotIn('name', ['superuser'])->pluck('name', 'id');

        return view('livewire.role-management', ['roles' => $roles]);
    }
}
