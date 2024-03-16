<?php

namespace App\Livewire;

use App\Models\TrackSampel;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;

class AddImage extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    protected $listeners = ['triggerAddFoto' => 'push_foto_to_other_component'];
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('foto_sampel')
                    ->label('Uplod Foto Sampel (Maks 5 Foto)')
                    ->image()
                    ->imageEditor()
                    ->imageEditorEmptyFillColor('#000000')
                    ->multiple()
                    ->maxFiles(5)
                    ->disk('s3')
                    ->directory('doc_files')->storeFileNamesIn('files')
                    ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg'])
            ])
            ->statePath('data')
            ->model(TrackSampel::class);
    }

    public function push_foto_to_other_component()
    {


        dd($this->data);
        $this->dispatch("arr_foto", $this->data);
    }

    public function render()
    {
        return view('livewire.add-image');
    }
}
