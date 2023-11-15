<form wire:submit.prevent="save" method="POST" enctype="multipart/form-data">
    @if ($successSubmit)
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        Record berhasil diupdate dengan kode track<span class="font-medium"> {{$msgSuccess}}</span>
    </div>
    @endif

    @if ($errorSubmit)
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">{{ $msgError }}</span>
    </div>
    @endif
    @method('PUT') {{-- This is used to specify that this is an update request --}}
    @csrf {{-- Include the CSRF token for security --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="col-span-1 md:col-span-1">
            <label for="tanggal_penerimaan" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                Penerimaan</label>
            <div class="mt-2">

                <input type="date" wire:model="tanggal" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

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
                        <img src="{{ $foto_sampel }}" alt="Image Description">
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
                <select wire:model="get_progress" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
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
                <input type="date" autocomplete="given-name" wire:model="estimasikupa" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

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
                <input type="text" name="email" id="email" wire:model="email" autocomplete="given-name" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
            </div>


        </div>

    </div>

    <div class="sm:col-span-6  rounded-lg border border-dashed border-gray-900/25 mt-8">
        @foreach($oldform as $index => $parameter)
        <div class="grid grid-cols-5 gap-5 mt-4">

            <div class="sm:col-span-2">

                <label class="block text-sm font-medium leading-6 text-gray-900"> {{$item['nama_parameter']}} </label>

                <div class="mt-2 ml-6">
                    @foreach ($parameter['jenis_analiss'] as $key => $data)
                    <li>{{ $data }}</li>
                    @endforeach
                </div>

            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Jumlah Sampel</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.jumlah" wire:change="totalsampelold({{ $index }})" placeholder="Parameter jumlah">
                </div>

                <label class="block text-sm font-medium leading-6 text-gray-900"> {{ $oldform[$index]['judulppn'] }}</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.ppn" wire:change="ppnold({{ $index }})" placeholder="Parameter jumlah">
                </div>


            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Harga</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.harga" placeholder="Parameter jumlah" disabled>
                </div>

                <label class="block text-sm font-medium leading-6 text-gray-900"> Total</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.harga_total" placeholder="Parameter jumlah" disabled>
                </div>

            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Subtotal</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.subtotal" placeholder="Parameter jumlah" disabled>
                </div>

                <button class="text-red-500 mt-10" wire:click.prevent="hapusItem({{ $index }})">Hapus</button>
            </div>
        </div>
        @endforeach
    </div>

    <div class="sm:col-span-6  rounded-lg border border-dashed border-gray-900/25 mt-4">
        @foreach($parameters as $index => $parameter)
        <div class="grid grid-cols-5 gap-5 mt-4">

            <div class="sm:col-span-2">

                <label class="block text-sm font-medium leading-6 text-gray-900"> {{$item['nama_parameter']}} </label>

                <div class="mt-2 ml-6">
                    @foreach ($parameter['parametersanalisis'] as $key => $data)
                    <li>{{ $data }}</li>
                    @endforeach
                </div>

            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Jumlah Sampel</label>

                <div class="mt-2">
                    <input type="text" wire:model="parameters.{{ $index }}.jumlah" wire:change="totalsampel({{ $index }})" placeholder="Parameter jumlah">
                </div>

                <label class="block text-sm font-medium leading-6 text-gray-900"> {{ $parameters[$index]['judulppn'] }}</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="parameters.{{ $index }}.ppn" wire:change="changeppn({{ $index }})" placeholder="Parameter ppn">
                </div>
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Harga Sampel </label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="parameters.{{ $index }}.harga" placeholder="Parameter Harga" disabled>
                </div>
                <label class="block text-sm font-medium leading-6 text-gray-900"> Total </label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="parameters.{{ $index }}.total" placeholder="Parameter total" disabled>
                </div>
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Sub Total </label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="parameters.{{ $index }}.sub_total" placeholder="Parameter sub_total" disabled>
                </div>
                <button class="text-red-500 mt-10" wire:click.prevent="removeParameter({{ $index }})">Hapus</button>
            </div>




        </div>
        @endforeach
    </div>


    <div class="sm:col-span-3 mt-4">
        <div class="grid grid-cols-4 gap-4"> <!-- Increased the number of columns for the delete button -->
            <div class="col-span-1 flex items-center">
                <button class="rounded-md bg-slate-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600" wire:click.prevent="addParameter">
                    + Tambah Parameter
                </button>
            </div>

            <div class="col-span-2 mb-6">
                <p>Parameter Analisis</p>
                <select wire:model="val_parameter">
                    @foreach ($parameterAnalisisOptions as $key => $items)
                    <option value="{{$key}}">{{$items}}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>


    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
        <button type="submit" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Simpan Progress</button>
    </div>

    <div class="mt-6 flex items-center gap-x-6">
        <button wire:click="exportExcel" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Export Excel</button>
    </div>
</form>