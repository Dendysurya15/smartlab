<?php



namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MetodeAnalisis;
use App\Models\ParameterAnalisis;

class CrudParameter extends Component
{

    public $getid;
    public $namamtd;
    public $hargamtd;
    public $satuanmtd;

    protected $rules = [
        'namamtd' => 'required',
        'hargamtd' => 'numeric',
        'satuanmtd' => 'required',
    ];

    public function render()
    {
        $id = $this->getid;
        $data = MetodeAnalisis::where('id', $id)->with('parameterAnalisis')->get()->toArray();

        return view('livewire.crud-parameter', [
            'data' => $data
        ]);
    }

    public function update($id)
    {
        $validatedData = $this->validate([
            'namamtd' => 'required',
            'hargamtd' => 'required',
            'satuanmtd' => 'required',
        ]);

        $record = MetodeAnalisis::findOrFail($id);
        $record->nama = $this->namamtd;
        $record->harga = $this->hargamtd;
        $record->satuan = $this->satuanmtd;

        if ($record->save()) {
            return redirect()->to('system')->with('message', 'Parameter berhasil di Update!');
        } else {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }
    }


    public function delete($id)
    {
        $record = MetodeAnalisis::findOrFail($id);

        if ($record->delete()) {
            return redirect()->to('system')->with('message', 'Parameter berhasil di hapus!');
        } else {
            session()->flash('error', 'Failed to delete record. Please try again.');
        }
    }
}
