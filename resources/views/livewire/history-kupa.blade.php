<div>
    {{ $this->table }}

    <x-filament::modal id="add-sertifikat" width="md">
        <x-slot name="heading">
            <h2 class="text-lg font-medium leading-6 text-gray-900">
                Tambahkan Sertifikat
            </h2>
        </x-slot>

        <form wire:submit="create" class="space-y-4">
            {{ $this->form }}

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Submit
            </button>
        </form>

        <x-filament-actions::modals />

        {{-- Modal content --}}
    </x-filament::modal>

</div>