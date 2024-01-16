<?php

namespace App\Http\Livewire;

use App\Models\TrackSampel;
use Livewire\Component;
use Livewire\WithPagination;

class HistoryKupaTable extends Component
{
    use WithPagination;
    public $perPage = 5;
    public $search  = '';

    public $sortBy = 'id';
    public $sortDir = 'DESC';

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }
    public function render()
    {
        return view(
            'livewire.history-kupa-table',
            ['datas' => TrackSampel::search($this->search)
                ->orderBy($this->sortBy, $this->sortDir)
                ->paginate($this->perPage)]
        );
    }
}
