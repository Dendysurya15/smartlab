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
        return TrackSampel::query()
            ->orderBy('tanggal_penerimaan', 'desc');
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
        return [
            'jenisSampel' => ['nama'],
        ];
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
            ->addColumn('tanggal_penerimaan_formatted', fn (TrackSampel $model) => Carbon::parse($model->tanggal_penerimaan)->format('d/m/Y'))
            ->addColumn('jenis_sampel', function (TrackSampel $model) {
                return $model->jenisSampel->nama;
            })
            ->addColumn('asal_sampel')
            ->addColumn('nomor_kupa')
            ->addColumn('nama_pengirim')
            ->addColumn('departemen')
            ->addColumn('kode_sampel')
            ->addColumn('estimasi_formatted', fn (TrackSampel $model) => Carbon::parse($model->estimasi)->format('d/m/Y'))
            ->addColumn('tujuan')
            ->addColumn('parameter_analisis')
            ->addColumn('progress', function (TrackSampel $model) {
                return $model->progressSampel->nama ?? '-';
            })
            ->addColumn('last_update', function (TrackSampel $model) {
                $lastUpdates = explode(',', $model->last_update);
                $lastUpdate = end($lastUpdates);
                $formattedDate = Carbon::parse($lastUpdate)->format(' H:i d/m/Y');
                return $formattedDate;
            })
            ->addColumn('admin', function (TrackSampel $model) {
                return $model->user->name;
            })
            ->addColumn('no_hp')
            ->addColumn('emailTo')
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
            // Column::make('No', 'id')->sortable(),
            Column::make('Id', 'id')
                ->sortable(),
            Column::make('Tanggal Penerimaan', 'tanggal_penerimaan_formatted', 'tanggal_penerimaan')
                ->sortable(),

            Column::make('Jenis Sampel', 'jenis_sampel')
                ->sortable()
                ->searchable(),

            Column::make('Kode Track', 'kode_track')
                ->sortable(),

            Column::make('Last update', 'last_update')
                ->sortable(),

            Column::make('Progress', 'progress')
                ->sortable(),



            Column::make('Nomor kupa', 'nomor_kupa'),
            Column::make('Nama pengirim', 'nama_pengirim')
                ->searchable()
                ->sortable(),

            Column::make('Departemen', 'departemen')
                ->sortable(),

            Column::make('Kode sampel', 'kode_sampel')
                ->sortable(),

            Column::make('Estimasi', 'estimasi_formatted', 'estimasi')
                ->sortable(),

            Column::make('Tujuan', 'tujuan')
                ->sortable(),

            Column::make('Parameter analisis', 'parameter_analisisid')
                ->sortable(),

            Column::make('Asal sampel', 'asal_sampel')
                ->sortable(),

            Column::make('Admin', 'admin'),
            Column::make('No hp', 'no_hp')
                ->sortable()
                ->field('no_hp'),

            Column::make('Email', 'emailTo')
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
            Button::make('edit', view('icons.edit-icon'))
                ->class('p-2 mr-2 border rounded hover:bg-slate-100 text-emerald-500 hover:text-emerald-900')
                // ->class('bg-transparent border-0 text-red-500 text-sm p-0 cursor-pointer'),
                // Button::make('delete', view('icons.delete-icon')), // the Font Awesome icon code is sent as the second parameter.
                // ->class('bg-slate-700 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm  ')
                ->route('history_sampel.edit', ['history_sampel' => 'id']),
            // ->target('_self')

            Button::make('edit', view('icons.delete-icon'))->class('p-2 mr-2 border rounded hover:bg-slate-50 text-red-500'),


            // Button::make('delete') // the Font Awesome icon code is sent as the second parameter.
            //     // ->class('bg-slate-700 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm  ')
            //     // ->route('history_sampel.edit', ['history_sampel' => 'id'])
            //     ->target('_self')
            //     ->tooltip('Delete Record')
            // Button::make('edit', view('icons.edit-icon'))
            //     ->class('bg-slate-700 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
            //     ->route('history_sampel.edit', fn (\App\Models\TrackSampel $model) => ['history_sampel' => $model->id]),
            // Sets the button class to spicy and caption with emoji
            // Rule::button('order-dish')
            //     ->when(fn ($dish) => $dish->is_spicy == true)
            //     ->slot('Order 🔥 🔥 🔥')
            //     ->setAttribute('class', 'bg-orange-400'),
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
