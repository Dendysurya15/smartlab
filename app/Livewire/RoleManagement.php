<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Spatie\Permission\Models\Permission;

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
                    EditAction::make(),
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
