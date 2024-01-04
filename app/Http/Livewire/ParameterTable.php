<?php

namespace App\Http\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use App\Models\MetodeAnalisis;
use App\Models\ParameterAnalisis;

final class ParameterTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Eloquent, Query Builder or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder
     */
    public $jenisSampel; // Define the property to receive the selected ID

    protected $listeners = ['filterData'];

    public function filterData($id)
    {
        $this->jenisSampel = $id;
        $this->refreshGrid(); // Refresh the PowerGrid data with the new ID


    }

    public function datasource(): Builder
    {

        if ($this->jenisSampel) {
            return ParameterAnalisis::query()->where('id_jenis_sampel', $this->jenisSampel);
        } else {
            return ParameterAnalisis::query();
        }
    }


    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('nama_parameter')
            ->addColumn('metode_analisis')
            ->addColumn('harga')
            ->addColumn('satuan');
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Nama Parameter', 'nama_parameter')
                ->searchable()
                ->sortable(),
            Column::make('Nama Metode', 'metode_analisis')
                ->searchable()
                ->sortable(),
            Column::make('Harga Metode', 'harga')
                ->searchable()
                ->sortable(),
            Column::make('Satuan Metode', 'satuan')
                ->searchable()
                ->sortable(),



        ];
    }


    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            // Filter::inputText('nama')->operators(['contains']),
        ];
    }


    /**
     * PowerGrid Action Rules.
     *
     * @return array<int, RuleActions>
     */


    public function actions(): array
    {
        return [
            // Button::make('edit', view('icons.edit-icon'))
            //     ->class('p-2 mr-2 border rounded hover:bg-slate-100 text-emerald-500 hover:text-emerald-900')
            //     ->route('system.edit', ['system' => 'id'])
            //     ->target('_self'), // This targets the current window
            // Button::make('delete', view('icons.delete-icon'))
            //     ->class('p-2 mr-2 border rounded hover:bg-slate-100 text-red-500 hover:text-red-900')
            //     // ->route('delete-data', ['id' => 'id'])
            //     // ->method('POST'),
            //     ->emit('deleteConfirmDiscount', ['id' => 'id']),
            // ->class('bg-transparent border-0 text-red-500 text-sm p-0 cursor-pointer'),
            // Button::make('delete', view('icons.delete-icon')), // the Font Awesome icon code is sent as the second parameter.
            // ->class('bg-slate-700 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm  ')

            // Other buttons...
        ];
    }
}
