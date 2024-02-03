<div>



    <form wire:submit.prevent="save" method="POST" enctype="multipart/form-data">
        @if ($successSubmit)
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            Record berhasil di simpan
        </div>
        @endif

        @if ($errorSubmit)
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">{{ $msgError }}</span>
        </div>
        @endif
        @method('PUT') {{-- This is used to specify that this is an update request --}}
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="col-span-1 md:col-span-1">
                <label for="jns_sam" class="block text-sm font-medium leading-6 text-gray-900">Jenis
                    Sampel</label>
                <div class="mt-2">
                    <select wire:model="jenis_sampel" wire:change="datatabel" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        @foreach ($getparameters as $item)
                        <option value="{{$item['id']}}">{{$item['nama']}}
                        </option>
                        @endforeach
                    </select>


                </div>
                <div class="mt-2">
                    <button class="rounded-md bg-slate-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600" wire:click.prevent="addParameter">
                        + Tambah Parameter
                    </button>
                </div>

            </div>


            <!-- Assuming the foreach loop starts here -->
            @foreach($parameters as $parameterIndex => $parameter)

            <div class="sm:col-span-3 mt-4">
                @error("parameters.$parameterIndex.methods")
                <span class="text-red-500">Pastikan Semua Metode Terisi</span>
                @enderror

                <div class="col-span-1 md:col-span-1">
                    <div class="flex">
                        <div class="mt-2">
                            <input type="text" wire:model="parameters.{{ $parameterIndex }}.nama" placeholder="Masukan Nama Parameter" @if($isDisabled) disabled @endif class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6" required>
                        </div>
                        <div class="mt-2 ml-2">
                            <input type="number" wire:model="parameters.{{ $parameterIndex }}.hargaparams" placeholder="Masukan Harga" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6" required>

                        </div>
                        <div class="mt-2 ml-2">
                            <input type="text" wire:model="parameters.{{ $parameterIndex }}.namathod" placeholder="Nama Metode" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6" required>

                        </div>
                        <div class="mt-2 ml-2">
                            <input type="text" wire:model="parameters.{{ $parameterIndex }}.satuatod" placeholder="Nama Satuan" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6" required>

                        </div>
                        <div class="ml-4 mt-2">
                            <!-- Your button here -->


                            <button class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600" wire:click.prevent="deleteParameter({{ $parameterIndex }})">
                                Hapus
                            </button>

                        </div>
                    </div>
                    @error("parameters.$parameterIndex.nama")
                    <span class="text-red-500">Harap Masukan Nama Parameter</span>
                    @enderror



                    <!-- End Metode -->
                </div>

            </div>
            @endforeach
            <!-- Assuming the foreach loop ends here -->




        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>

            <button type="submit" @if($isDisabled) disabled @endif class="rounded-md @if($isDisabled) bg-grey-400 @else bg-emerald-600 @endif px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Simpan Parameter</button>
        </div>
    </form>

</div>