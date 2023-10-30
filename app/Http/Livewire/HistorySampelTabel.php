<?php

namespace App\Http\Livewire;

use App\Models\TrackSampel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class HistorySampelTabel extends PowerGridComponent
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
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\TrackSampel>
     */
    public function datasource(): Builder
    {
        return TrackSampel::query();
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
            ->addColumn('tanggal_penerimaan_formatted', fn (TrackSampel $model) => Carbon::parse($model->tanggal_penerimaan)->format('d/m/Y H:i:s'))
            ->addColumn('jenis_sampel', function (TrackSampel $model) {
                return $model->jenisSampel->nama;
            })
            ->addColumn('asal_sampel')
            ->addColumn('nomor_kupa')
            ->addColumn('nama_pengirim')
            ->addColumn('departemen')
            ->addColumn('kode_sampel')
            ->addColumn('estimasi_formatted', fn (TrackSampel $model) => Carbon::parse($model->estimasi)->format('d/m/Y H:i:s'))
            ->addColumn('tujuan')
            ->addColumn('parameter_analisis')
            ->addColumn('progress', function (TrackSampel $model) {
                return $model->progressSampel->nama ?? '-';
            })
            ->addColumn('last_update', function (TrackSampel $model) {
                $lastUpdates = explode(', ', $model->last_update);
                $lastUpdate = end($lastUpdates);
                $formattedDate = Carbon::parse($lastUpdate)->format('d/m/Y H:i:s');
                return $formattedDate;
            })
            ->addColumn('admin')
            ->addColumn('no_hp')
            ->addColumn('email')
            ->addColumn('foto_sampel');
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
            Column::make('Id', 'id')->sortable(),
            Column::make('Tanggal penerimaan', 'tanggal_penerimaan_formatted', 'tanggal_penerimaan')
                ->sortable(),

            Column::make('Jenis sampel', 'jenis_sampel')
                ->sortable()
                ->searchable(),

            Column::make('Kode Track', 'kode_track')
                ->sortable()
                ->searchable(),

            Column::make('Asal sampel', 'asal_sampel')
                ->sortable()
                ->searchable(),

            Column::make('Nomor kupa', 'nomor_kupa'),
            Column::make('Nama pengirim', 'nama_pengirim')
                ->sortable()
                ->searchable(),

            Column::make('Departemen', 'departemen')
                ->sortable()
                ->searchable(),

            Column::make('Kode sampel', 'kode_sampel')
                ->sortable()
                ->searchable(),

            Column::make('Estimasi', 'estimasi_formatted', 'estimasi')
                ->sortable(),

            Column::make('Tujuan', 'tujuan')
                ->sortable()
                ->searchable(),

            Column::make('Parameter analisis', 'parameter_analisis')
                ->sortable()
                ->searchable(),

            Column::make('Progress', 'progress')
                ->sortable()
                ->searchable(),

            Column::make('Last update', 'last_update')
                ->sortable(),

            Column::make('Admin', 'admin'),
            Column::make('No hp', 'no_hp')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),



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
            // Filter::datetimepicker('tanggal_penerimaan'),

            // Filter::inputText('asal_sampel')->operators(['contains']),
            // Filter::inputText('nama_pengirim')->operators(['contains']),
            // Filter::inputText('departemen')->operators(['contains']),
            // Filter::inputText('kode_sample')->operators(['contains']),
            // Filter::datetimepicker('estimasi'),
            // Filter::inputText('tujuan')->operators(['contains']),
            // Filter::inputText('parameter_analisis')->operators(['contains']),
            // Filter::inputText('progress')->operators(['contains']),
            // Filter::datetimepicker('last_update'),
            // Filter::inputText('no_hp')->operators(['contains']),
            // Filter::inputText('email')->operators(['contains']),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid TrackSampel Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
        return [
            // Button::make('edit', view('icons.edit-icon'))
            //     ->class('bg-slate-200 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
            //     ->route('history_sampel.edit', function (\App\Models\TrackSampel $model) {
            //         return ['history_sampel' => $model->id];
            //     }),
            // Button::make('edit', view('icons.edit-icon'))
            //     ->class('bg-slate-800 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
            //     ->route('history_sampel.edit', function (\App\Models\TrackSampel $model) {
            //         return ['history_sampel' => $model->id];
            //     }),
            Button::make('edit', view('icons.edit-icon'))
                ->class('bg-slate-700 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->route('history_sampel.edit', fn (\App\Models\TrackSampel $model) => ['history_sampel' => $model->id]),

            // Button::make('edit', view('icons.edit-icon'))
            //     ->class('bg-slate-800 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
            //     ->route('history_sampel.edit', fn (\App\Models\TrackSampel $model) => ['history_sampel' => $model->id])


            // Button::make('destroy', 'Delete')
            //     ->class('bg-gray-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
            //     ->route('history_sampel.destroy', function (\App\Models\TrackSampel $model) {
            //         return ['history_sampel' => $model->id];
            //     })
            //     ->method('delete')
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid TrackSampel Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($track-sampel) => $track-sampel->id === 1)
                ->hide(),
        ];
    }
    */
}
