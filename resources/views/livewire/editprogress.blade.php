<form wire:submit.prevent="save" method="POST" enctype="multipart/form-data">
    @method('PUT') {{-- This is used to specify that this is an update request --}}
    @csrf {{-- Include the CSRF token for security --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="col-span-1 md:col-span-1">
            <label for="tanggal_penerimaan" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                Penerimaan</label>
            <div class="mt-2">

                <input type="date" wire:model="tanggal" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                @error('tanggal_penerimaan')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="jns_sam" class="block text-sm font-medium leading-6 text-gray-900">Jenis
                Sampel</label>
            <div class="mt-2">
                <select wire:model="jenis_sampel" wire:change="ChangeFieldParamAndNomorLab" autocomplete="jenis_sampel" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
                    @foreach ($jenisSampelOptions as $item)
                    <option value="{{$item->id}}">{{$item->nama}}
                    </option>
                    @endforeach
                </select>
                @error('jenis_sampel')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>
        </div>

        <div class="col-span-1 md:col-span-1 row-span-2">
            <div class="flex justify-center rounded-lg px-6 py-5">
                <div class="text-center flex flex-col items-center">
                    <!-- Conditional SVG or Image -->


                    <div id="image-container">

                    </div>

                    <div class="mt-4 text-sm leading-6 text-gray-600">
                        <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-emerald-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-600 focus-within:ring-offset-2 hover:text-emerald-500">
                            <span> </span>

                        </label>


                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Progress</label>
            <div class="mt-2">
                <select wire:model="get_progress" wire:change="changeautoprogress" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
                    @foreach ($prameterproggres as $key => $items)
                    <option value="{{$key}}">{{$items}}</option>
                    @endforeach
                </select>
                @error('jenis_sampel')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Asal
                Sampel</label>
            <div class="mt-2">
                <input type="text" name="asal_sampel" id="asal_sampel" wire:model="asal_sampel" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="no_kupa" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                Kupa</label>
            <div class="mt-2">
                <input type="number" name="no_kupa" id="no_kupa" wire:model="no_kupa" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="no_lab" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                Lab</label>
            <div class="mt-2">
                <input type="text" name="nomor_lab" wire:model="nomor_lab" id="nomor_lab" wire:model="nomor_lab" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="nama_pelanggan" class="block text-sm font-medium leading-6 text-gray-900">Nama
                Pengirim</label>
            <div class="mt-2">
                <input type="text" name="nama_pengirim" id="nama_pengirim" wire:model="nama_pengirim" value="" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6" {{-- placeholder="Nama Pelanggan" --}}>

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="departemen" class="block text-sm font-medium leading-6 text-gray-900">Nama
                Departemen /
                Perusahaan</label>
            <div class="mt-2">
                <input type="text" name="departemen" id="departemen" wire:model="departemen" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="kode_sampel" class="block text-sm font-medium leading-6 text-gray-900">Kode
                Sampel</label>
            <div class="mt-2">
                <input type="text" name="kode_sampel" id="kode_sampel" wire:model="kode_sampel" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="no_surat" class="block text-sm font-medium leading-6 text-gray-900">Nomor Surat
            </label>
            <div class="mt-2">
                <input type="text" name="nomor_surat" id="nomor_surat" wire:model="nomor_surat" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">


            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="estimasi" class="block text-sm font-medium leading-6 text-gray-900">Estimasi
                Kupa</label>
            <div class="mt-2">
                <input type="date" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Tujuan</label>
            <div class="mt-2">
                <input type="text" name="tujuan" id="tujuan" wire:model="tujuan" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                Hp</label>
            <div class="mt-2">
                <input type="number" name="no_hp" id="no_hp" wire:model="no_hp" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
            </div>

        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Email
                Departemen /
                Perusahaan</label>
            <div class="mt-2">
                <input type="text" name="departemen" id="departemen" wire:model="departemen" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
            </div>


        </div>

    </div>




    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
        <button type="submit" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Simpan
            Progress</button>
    </div>

</form>