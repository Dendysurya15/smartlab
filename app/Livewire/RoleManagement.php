<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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



        $role = Role::pluck('name')->toArray();

        // dd($role);
        return $table
            // ->query(TrackSampel::query())
            ->columns([
                TextColumn::make('Name')->sortable(),
            ])

            ->defaultSort('name', 'asc');
    }

    public function render(): View
    {
        return view('livewire.role-management');
    }
}
