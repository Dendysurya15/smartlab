<div>
    {{ $this->table }}

    <x-filament::modal id="upload-sertifikat" width="md">
        <x-slot name="header">
            Upload Sertifikat
        </x-slot>

        <form wire:submit="create">
            {{ $this->form }}

            <div class="mt-4 flex justify-end">
                <x-filament::button type="submit">
                    Upload
                </x-filament::button>
            </div>
        </form>
    </x-filament::modal>

    <x-filament::modal id="generate-bulk-pdf" width="md" :close-by-clicking-away="false">
        <x-slot name="header">
            Export bulk Kupa
        </x-slot>
        <p class="text-sm text-gray-500">Apakah anda ingin meng-export {{$count}} data?</p>
        <form wire:submit="generateBulkPdf">
            <div class="mt-4 flex justify-end gap-x-3 items-center">
                <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="generateBulkPdf">
                    Generate
                </x-filament::button>
            </div>
        </form>
    </x-filament::modal>



</div>