<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use App\Models\TrackSampel;

class InputSertifikat extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('file')
                    ->multiple()
                    ->acceptedFileTypes(['application/pdf'])
                    ->label('File Sertifikat')
                    ->columnSpanFull()
                    ->directory('sertifikat')
                    // ->maxSize(5024)
                    ->maxFiles(5)
                    ->visibility('private')
                    ->disk('private')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        // dd($this->id_sertifikat);
        $form = $this->form->getState();

        $file = implode(',', $form['file']);
        dd($file);

        // dd($form);
        $insert = TrackSampel::find($this->id_sertifikat);
        if ($insert->sertifikasi !== null) {
            $filepath = storage_path('app/private/' . $insert->sertifikasi);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
        $insert->sertifikasi = $form['file'];
        $insert->save();
        $this->form->fill();
        $this->dispatch('close-modal', id: 'add-sertifikat');
        Notification::make()
            ->success()
            ->title('Success')
            ->body('Sertifikat berhasil diunggah')
            ->send();
    }
    public function render()
    {
        return view('livewire.input-sertifikat');
    }
}
