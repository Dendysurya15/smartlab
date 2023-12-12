<?php

namespace App\Http\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use App\Models\MetodeAnalisis;

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
            Header::make()->showSearchInput(),
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

        // dd($id);
    }

    public function datasource(): Builder
    {
        if ($this->jenisSampel) {
            return DB::connection('mysql')->table('metode_analisis')
                ->select('metode_analisis.*', 'parameter_analisis.nama as nama_params', 'jenis_sampel.nama as jenis_sampel')
                ->join('parameter_analisis', 'parameter_analisis.id', '=', 'metode_analisis.id_parameter')
                ->join('jenis_sampel', 'jenis_sampel.id', '=', 'parameter_analisis.id_jenis_sampel')
                ->where('jenis_sampel.id', $this->jenisSampel);
        } else {
            // Default query when no ID is selected
            return DB::connection('mysql')->table('metode_analisis')
                ->select('metode_analisis.*', 'parameter_analisis.nama as nama_params', 'jenis_sampel.nama as jenis_sampel')
                ->join('parameter_analisis', 'parameter_analisis.id', '=', 'metode_analisis.id_parameter')
                ->join('jenis_sampel', 'jenis_sampel.id', '=', 'parameter_analisis.id_jenis_sampel');
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
            ->addColumn('nama')
            ->addColumn('nama_params')
            ->addColumn('jenis_sampel')
            ->addColumn('harga')
            ->addColumn('satuan')
            /** Example of custom column using a closure **/
            ->addColumn('nama_lower', fn ($model) => strtolower(e($model->nama)));
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
                ->sortable(),
            Column::make('Jenis Sample', 'jenis_sampel')
                ->sortable(),
            Column::make('Nama Parameter', 'nama_params')
                ->sortable(),
            Column::make('Nama Metode', 'nama')
                ->sortable(),
            Column::make('Harga Metode', 'harga')
                ->editOnClick()
                ->sortable(),
            Column::make('Satuan Metode', 'satuan')
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
            Button::make('edit', view('icons.edit-icon'))
                ->class('p-2 mr-2 border rounded hover:bg-slate-100 text-emerald-500 hover:text-emerald-900')
                ->route('system.edit', ['system' => 'id'])
                ->target('_self'), // This targets the current window
            // Other buttons...
        ];
    }
}
