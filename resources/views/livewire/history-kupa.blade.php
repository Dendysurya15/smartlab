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
</div>