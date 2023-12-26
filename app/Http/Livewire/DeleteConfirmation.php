<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DeleteConfirmation extends Component
{
    public $itemId;
    public $showModal = false;
    protected $listeners = ['deleteConfirmDiscount', 'confirmDelete'];

    public function deleteConfirmDiscount($id)
    {
        // $this->itemId = $id;
        // $this->dispatchBrowserEvent('showDeleteConfirmationModal');

        // $this->dispatchBrowserEvent('showAlert', ['message' => $message]);
        $this->dispatchBrowserEvent('showSweetAlert', [
            'title' => 'Are you sure?',
            'text' => 'You won\'t be able to revert this!',
            'icon' => 'warning',
            'confirmButtonText' => 'Yes, delete it!',
            'onConfirmed' => 'confirmDelete',
            'id' => $id,
        ]);
    }



    public function confirmDelete($data)
    {
        $this->dispatchBrowserEvent('showDeleteSuccessAlert', [
            'title' => 'Data Deleted!',
            'text' => 'The data has been successfully deleted.',
            'icon' => 'success',
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'reloadPage',
        ]);
        // $this->redirect(route('delete-data', ['id' => $data['id']]));
        // $this->emit('dataDeleted');
    }

    public function reloadPageAfterDelete()
    {
        // Reload the page after the user clicks "OK" in SweetAlert
        $this->emit('dataDeleted');
    }

    public function render()
    {
        return view('livewire.delete-confirmation');
    }
}
