<?php

namespace App\Livewire;

use App\Models\Resultkuesioner;
use Carbon\Carbon;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Filament\Notifications\Notification;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Support\Facades\Storage;

class Kuesionerdata extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public $dataURL;

    public $options = [
        '1' => 'Tidak puas',
        '2' => 'Kurang puas',
        '3' => 'Puas',
        '4' => 'Sangat puas',
    ];
    public $description = [
        '1' => 'Tidak puas',
        '2' => 'Kurang puas',
        '3' => 'Puas',
        '4' => 'Sangat puas',
    ];

    public function mount(): void
    {
        $this->form->fill();
    }
    public function decline(): void
    {
        $this->dispatch('close-modal', id: 'transactionModal');
    }


    public function accpetsubmit(): void
    {
        // Assuming $this->dataURL is your Data URI
        $dataURL = $this->dataURL;
        if (empty($dataURL) || $dataURL == null || $dataURL == 'null') {
            Notification::make()
                ->title("Gagal")
                ->body("Harap berikan tanda tangan anda terlebih dahulu.")
                ->danger()
                ->send();
            return;
        }

        // dd($dataURL);
        // // Extract the base64 encoded data from the Data URI
        list($type, $data) = explode(';', $dataURL);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        // Generate a unique filename
        $filename = uniqid() . '.jpg';

        // Define the path where you want to store the image (directly in public)
        $filePath = 'public/' . $filename;

        // Store the image in the specified directory
        Storage::put($filePath, $data);

        // Other processing logic
        $ip = request()->ip();
        $attemptsKey = "captcha_attempts:{$ip}";
        Cache::forget($attemptsKey);

        // Success logic here
        Cache::forget($attemptsKey);
        $data = $this->form->getState();

        // Insert the result into the database
        DB::beginTransaction();
        try {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key]['key'] = $key;
                $result[$key]['value'] = $value;
            }
            $result = json_encode($result);

            $insert = new Resultkuesioner();
            $insert->result = $result;
            $insert->datetime = Carbon::now();
            // Save the filename to the database
            $insert->signature = $filename;
            $insert->save();

            DB::commit();

            Notification::make()
                ->title("Sukses")
                ->body("Berhasil disimpan")
                ->success()
                ->send();

            $this->dispatch('thankyou');
            $this->dispatch('close-modal', id: 'transactionModal');
        } catch (\Throwable $th) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title('Error')
                ->color('danger')
                ->body($th->getMessage())
                ->send();

            $this->dispatch('close-modal', id: 'transactionModal');
        }
    }


    public function create(): void
    {
        $this->dispatch('open-modal', id: 'transactionModal');
    }




    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make(layoutkuesioner())
                    ->submitAction(new HtmlString('
                    <button id="save" type="submit" class="focus:outline-none text-black bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>
                    '))
            ])
            ->statePath('data');
    }



    public function render()
    {
        return view('livewire.kuesionerdata');
    }
}
