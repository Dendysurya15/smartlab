<form wire:submit.prevent="save" method="POST" enctype="multipart/form-data" wire:loading.class="opacity-50">

    @method('PUT') {{-- This is used to specify that this is an update request --}}
    @csrf {{-- Include the CSRF token for security --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="col-span-1 md:col-span-1">
            <label for="tanggal_penerimaan" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                Penerimaan</label>
            <div class="mt-2">

                <input type="date" wire:model="tanggal" autocomplete="given-name"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="jns_sam" class="block text-sm font-medium leading-6 text-gray-900">Jenis
                Sampel</label>
            <div class="mt-2">
                <input type="text" name="asal_sampel" id="asal_sampel" wire:model="nama_jenis_sampel"
                    autocomplete="given-name" value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6"
                    disabled>
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
                        <label for="file-upload"
                            class="relative cursor-pointer rounded-md bg-white font-semibold text-emerald-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-600 focus-within:ring-offset-2 hover:text-emerald-500">
                            <span> </span>

                        </label>


                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Progress</label>
            <div class="mt-2">
                <select wire:model="get_progress"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600  sm:text-sm sm:leading-6">
                    @foreach ($listProgress as $key => $items)
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
                <input type="text" name="asal_sampel" id="asal_sampel" wire:model="asal_sampel"
                    autocomplete="given-name" value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="no_kupa" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                Kupa</label>
            <div class="mt-2">
                <input type="number" name="no_kupa" id="no_kupa" wire:model="no_kupa" autocomplete="given-name" value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="nomor_lab" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                Lab <span style="color:red">*</span></label>
            <div class="mt-2 grid grid-cols-2 gap-4">
                <div class="col-span-1">
                    <input type="text" wire:model="nomor_lab_left" id="nomor_lab_left" autocomplete="given-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                    @error('nomor_lab_left')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-1">
                    <input type="text" wire:model="nomor_lab_right" id="nomor_lab_right" autocomplete="given-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                    @error('nomor_lab_right')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="nama_pelanggan" class="block text-sm font-medium leading-6 text-gray-900">Nama
                Pengirim</label>
            <div class="mt-2">
                <input type="text" name="nama_pengirim" id="nama_pengirim" wire:model="nama_pengirim" value=""
                    autocomplete="given-name"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6"
                    {{-- placeholder="Nama Pelanggan" --}}>

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="departemen" class="block text-sm font-medium leading-6 text-gray-900">Nama
                Departemen /
                Perusahaan</label>
            <div class="mt-2">
                <input type="text" name="departemen" id="departemen" wire:model="departemen" autocomplete="given-name"
                    value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="kode_sampel" class="block text-sm font-medium leading-6 text-gray-900">Kode
                Sampel</label>
            <div class="mt-2">
                <input type="text" name="kode_sampel" id="kode_sampel" wire:model="kode_sampel"
                    autocomplete="given-name" value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="kemasan_sampel" class="block text-sm font-medium leading-6 text-gray-900">Kemasan
                Sampel
            </label>
            <div class="mt-2">
                <input type="text" wire:model="kemasan_sampel" id="kemasan_sampel" autocomplete="given-name"
                    value="{{ old('kemasan_sampel') }}"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                @error('kemasan_sampel')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Kondisi
                Sampel</label>
            <div class="mt-2">
                <select id="kondisi_sampel" wire:model="kondisi_sampel" autocomplete="kondisi_sampel"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600  sm:text-sm sm:leading-6">
                    <option value="Normal" @if (old('kondisi_sampel')==='Normal' ) selected @endif>
                        Normal</option>
                    <option value="Abnormal" @if (old('kondisi_sampel')==='Abnormal' ) selected @endif>
                        Abnormal
                    </option>
                </select>
                @error('kondisi_sampel')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>


        <div class="col-span-1 md:col-span-1">
            <label for="jumlah_sampel" class="block text-sm font-medium leading-6 text-gray-900">Jumlah
                Sampel <span style="color:red">*</span>
            </label>
            <div class="mt-2">
                <input type="number" wire:model="jumlah_sampel" id="jumlah_sampel" autocomplete="given-name"
                    value="{{ old('jumlah_sampel') }}"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                @error('jumlah_sampel')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-span-1 md:col-span-1">
            <label for="no_surat" class="block text-sm font-medium leading-6 text-gray-900">Nomor Surat
            </label>
            <div class="mt-2">
                <input type="text" name="nomor_surat" id="nomor_surat" wire:model="nomor_surat"
                    autocomplete="given-name" value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">


            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="estimasi" class="block text-sm font-medium leading-6 text-gray-900">Estimasi
                Kupa</label>
            <div class="mt-2">
                <input type="date" autocomplete="given-name" wire:model="estimasi"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="tgl_pengantaran_sampel" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                Pengantaran Sampel
                Kupa <span style="color:red">*</span></label>
            <div class="mt-2">
                <input type="date" wire:model="tgl_pengantaran_sampel" id="tgl_pengantaran_sampel"
                    autocomplete="given-name" value="{{ old('tgl_pengantaran_sampel') }}"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                @error('tgl_pengantaran_sampel')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Tujuan</label>
            <div class="mt-2">
                <input type="text" name="tujuan" id="tujuan" wire:model="tujuan" autocomplete="given-name" value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Skala Prioritas
                Sampel <span style="color:red">*</span></label>
            <div class="mt-2">
                <select id="skala_prioritas" wire:model="skala_prioritas" autocomplete="skala_prioritas"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600  sm:text-sm sm:leading-6">
                    <option value="Normal" @if (old('skala_prioritas')==='Normal' ) selected @endif>
                        Normal</option>
                    <option value="Tinggi" @if (old('skala_prioritas')==='Tinggi' ) selected @endif>
                        Tinggi</option>
                </select>
                @error('skala_prioritas')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="col-span-1 md:col-span-1">
            <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                Hp</label>
            <div class="mt-2">
                <input type="number" name="no_hp" id="no_hp" wire:model="no_hp" autocomplete="given-name" value=""
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
            </div>

        </div>
        <div class="col-span-2 md:col-span-2">
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-1">
                    <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Email
                        (To) <span style="color:red">*</span></label>
                    <div class="mt-2">
                        <input type="text" wire:model="emailTo" id="emailTo" autocomplete="given-name"
                            value="{{ old('emailTo') }}"
                            placeholder="Ex: Imam@gmail.com; Kiky@gmail.com. Beri tanda pemisah ';'"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                        @error('emailTo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-span-1">
                    <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Email
                        (Cc) <span style="color:red">*</span></label>
                    <div class="mt-2">
                        <input type="text" wire:model="emailCc" id="emailCc" autocomplete="given-name"
                            value="{{ old('emailCc') }}"
                            placeholder="Ex: Imam@gmail.com; Kiky@gmail.com. Beri tanda pemisah ';'"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                        @error('emailCc')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

        </div>

        <div class="col-span-2 md:col-span-2">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <div class="flex h-6 items-center">
                        <input id="personel" wire:model="personel" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    </div>
                    <div class="text-sm leading-6">
                        <label for="personel" class="font-medium text-gray-900">Personel</label>
                        <p class="text-gray-500 text-xs">(Tersedia dan Kompeten)</p>
                        @error('personel')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="flex h-6 items-center">
                        <input id="alat" wire:model="alat" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    </div>
                    <div class="text-sm leading-6">
                        <label for="alat" class="font-medium text-gray-900">Alat</label>
                        <p class="text-gray-500 text-xs">(Tersedia dan Baik)</p>
                        @error('alat')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="flex h-6 items-center">
                        <input id="bahan" wire:model="bahan" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    </div>
                    <div class="text-sm leading-6">
                        <label for="bahan" class="font-medium text-gray-900">Bahan</label>
                        <p class="text-gray-500 text-xs">(Tersedia dan Baik)</p>
                        @error('bahan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-full mt-6">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Pengujian Sampel
            </h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">Peringatan untuk crosscheck ulang
                seluruh
                data yang ingin akan dimasukkan ke sistem!</p>
        </div>

        <div class="col-span-2 md:col-span-2">
            <div class="grid grid-cols-2 gap-6">

                <div class="col-span-1">
                    <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Pilih
                        Parameter <span style="color:red">*</span></label>
                    <div class="mt-2">
                        <select wire:model="val_parameter" autocomplete="country-name"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600  sm:text-sm sm:leading-6">
                            @foreach ($parameterAnalisisOptions as $key => $items)
                            <option value="{{$key}}">{{$items}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-span-1">
                    <label for="last-name" class="block text-sm font-medium leading-6 text-white ">
                        |</label>
                    <div class="mt-2">
                        <button
                            class="rounded-md bg-slate-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                            wire:click.prevent="addParameter">
                            + Tambah Parameter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="sm:col-span-6  rounded-lg border border-dashed border-gray-900/25 mt-8">
        @foreach($oldform as $index => $parameter)
        <div class="grid grid-cols-5 gap-5 mt-4">

            <div class="sm:col-span-2">

                <label class="block text-sm font-medium leading-6 text-gray-900"> {{$parameter['nama_parameters']}}
                </label>

                <div class="mt-2 ml-4 text-sm">
                    @foreach ($parameter['jenis_analisis'] as $key => $data)
                    <li>{{ $data }}</li>
                    @endforeach
                </div>

            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Jumlah Sampel</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.jumlah"
                        wire:change="totalsampelold({{ $index }})" placeholder="Parameter jumlah"
                        class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                </div>


                <label class="block text-sm font-medium leading-6 text-gray-900"> Subtotal</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.subtotal" placeholder="Parameter jumlah"
                        disabled
                        class="block w-full rounded-md border-0 text-slate-400 font-semibold shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                </div>



            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Harga</label>

                <div class="mt-2">
                    <input type="text" wire:change="updateHargaSampel()" wire:model.defer="oldform.{{ $index }}.harga"
                        placeholder="Parameter jumlah"
                        class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                </div>
                <label class="block text-sm font-medium leading-6 text-gray-900"> {{ $oldform[$index]['judulppn']
                    }}</label>

                <div class="mt-2">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.ppn" wire:change="ppnold({{ $index }})"
                        placeholder="Parameter jumlah"
                        class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                </div>


            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium leading-6 text-gray-900"> Total</label>

                <div class="mt-2 ">
                    <input type="text" wire:model.defer="oldform.{{ $index }}.harga_total"
                        placeholder="Parameter jumlah" disabled
                        class="block w-full rounded-md border-0 text-slate-400 font-semibold shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                </div>
                <label class="block text-sm font-medium leading-6 text-white">|</label>
                <div class="mt-2">
                    <button
                        class="rounded-md bg-red-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                        wire:click.prevent="hapusItem({{ $index }})">
                        Hapus Parameter
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div> --}}

    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
        <button type="submit" wire:loading.remove
            class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
            Update Progress
        </button>
        <button type="button" wire:loading
            class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm cursor-not-allowed">

            Update Progress
            <svg aria-hidden="true" id="loading"
                class="ml-3 inline w-5 h-5 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-gray-600"
                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor" />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill" />
            </svg>
        </button>
    </div>
    @if ($successSubmit)
    <div class="p-4 mb-4 mt-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
        role="alert">
        Record berhasil diupdate dengan kode track<span class="font-medium"> {{$msgSuccess}}</span>
    </div>
    @endif

    @if ($errorSubmit)
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">{{ $msgError }}</span>
    </div>
    @endif


    <div class="mt-6 flex items-center gap-x-6">
        <button wire:click="exportExcel"
            class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Export
            Excel</button>
    </div>


</form>