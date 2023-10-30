<div>
    @if ($successSubmit)
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
        role="alert">
        Record berhasil disimpan dengan kode track<span class="font-medium"> {{$msgSuccess}}</span>
    </div>
    @endif

    @if ($errorSubmit)
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">{{ $msgError }}</span>
    </div>
    @endif
    <form wire:submit.prevent="save" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Informasi Sampel & Pelanggan
                </h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Peringatan untuk crosscheck ulang seluruh
                    data yang ingin akan dimasukkan ke sistem!</p>

                {{-- tanggal penerimaan --}}
                <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="tanggal_penerimaan"
                            class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                            Penerimaan</label>
                        <div class="mt-2">
                            <input type="date" wire:model="tanggal_penerimaan" id="tanggal_penerimaan"
                                autocomplete="given-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('tanggal_penerimaan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Jenis
                            Sampel</label>
                        <div class="mt-2">
                            <select id="jenis_sampel" wire:model="jenis_sampel" autocomplete="jenis_sampel"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
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


                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Asal
                            Sampel</label>
                        <div class="mt-2">
                            <select id="asal_sampel" wire:model="asal_sampel" autocomplete="asal_sampel"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option value="Internal" @if (old('asal_sampel')==='Internal' ) selected @endif>Internal
                                </option>
                                <option value="Eksternal" @if (old('asal_sampel')==='Eksternal' ) selected @endif>
                                    Eksternal
                                </option>
                            </select>
                            @error('asal_sampel')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="nomor_kupa" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                            Kupa</label>
                        <div class="mt-2">
                            <input type="number" wire:model="nomor_kupa" id="nomor_kupa" autocomplete="given-name"
                                value="{{ old('nomor_kupa') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('nomor_kupa')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="nomor_lab" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                            Lab</label>
                        <div class="mt-2">
                            <input type="text" wire:model="nomor_lab" id="nomor_lab" autocomplete="given-name"
                                value="{{ old('nomor_lab') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('nomor_lab')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="nama_pengirim" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            Pengirim</label>
                        <div class="mt-2">
                            <input type="text" wire:model="nama_pengirim" id="nama_pengirim"
                                value="{{ old('nama_pengirim') }}" autocomplete="given-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6"
                                {{-- placeholder="Nama Pelanggan" --}}>
                            @error('nama_pengirim')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="departemen" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            Departemen /
                            Perusahaan</label>
                        <div class="mt-2">
                            <input type="text" wire:model="departemen" id="departemen" autocomplete="given-name"
                                value="{{ old('departemen') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('departemen')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="kode_sampel" class="block text-sm font-medium leading-6 text-gray-900">Kode
                            Sampel</label>
                        <div class="mt-2">
                            <input type="text" wire:model="kode_sampel" id="kode_sampel" autocomplete="given-name"
                                value="{{ old('kode_sampel') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('kode_sampel')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>



                    <div class="sm:col-span-2">
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

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Kondisi
                            Sampel</label>
                        <div class="mt-2">
                            <select id="kondisi_sampel" name="kondisi_sampel" autocomplete="kondisi_sampel"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
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


                    <div class="sm:col-span-2">
                        <label for="jumlah_sampel" class="block text-sm font-medium leading-6 text-gray-900">Jumlah
                            Sampel
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

                    <div class="sm:col-span-2">
                        <label for="nomor_surat" class="block text-sm font-medium leading-6 text-gray-900">Nomor Surat
                        </label>
                        <div class="mt-2">
                            <input type="text" wire:model="nomor_surat" id="nomor_surat" autocomplete="given-name"
                                value="{{ old('nomor_surat') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('nomor_surat')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="estimasi" class="block text-sm font-medium leading-6 text-gray-900">Estimasi
                            Kupa</label>
                        <div class="mt-2">
                            <input type="date" wire:model="estimasi" id="estimasi" autocomplete="given-name"
                                value="{{ old('estimasi') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('estimasi')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Tujuan</label>
                        <div class="mt-2">
                            <input type="text" wire:model="tujuan" id="tujuan" autocomplete="given-name"
                                value="{{ old('tujuan') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('tujuan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                            Hp</label>
                        <div class="mt-2">
                            <input type="text" wire:model="no_hp" id="no_hp" autocomplete="given-name"
                                value="{{ old('no_hp') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('no_hp')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Email
                            Departemen /
                            Perusahaan</label>
                        <div class="mt-2">
                            <input type="email" wire:model="email" id="email" autocomplete="given-name"
                                value="{{ old('email') }}"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex h-6 items-center">
                            <input id="personel" wire:model="personel" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        </div>
                        <div class="text-sm leading-6">
                            <label for="personel" class="font-medium text-gray-900">Personel</label>
                            <p class="text-gray-500">(Tersedia dan Kompeten)</p>
                            @error('personel')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex h-6 items-center">
                            <input id="alat" wire:model="alat" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        </div>
                        <div class="text-sm leading-6">
                            <label for="alat" class="font-medium text-gray-900">Alat</label>
                            <p class="text-gray-500">(Tersedia dan Baik)</p>
                            @error('alat')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex h-6 items-center">
                            <input id="bahan" wire:model="bahan" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        </div>
                        <div class="text-sm leading-6">
                            <label for="bahan" class="font-medium text-gray-900">Bahan</label>
                            <p class="text-gray-500">(Tersedia dan Baik)</p>
                            @error('bahan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Skala Prioritas
                            Sampel</label>
                        <div class="mt-2">
                            <select id="skala_prioritas" wire:model="skala_prioritas" autocomplete="skala_prioritas"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
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

                    <div class="col-span-full">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Pengujian Sampel
                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Peringatan untuk crosscheck ulang
                            seluruh
                            data yang ingin akan dimasukkan ke sistem!</p>
                    </div>

                    @foreach ($inputans as $index =>$inputan)

                    <div class="sm:col-span-1">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Satuan
                        </label>
                        <div class="mt-2">
                            <input type="text" name="inputans[{{$index}}][satuan_default]" autocomplete="given-name"
                                wire:model="inputans.{{$index}}.satuan_default" placeholder="%, ppm, me/100gr"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300  focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('satuan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Parameter
                            Analisis</label>
                        <div class="mt-2">
                            <select name="inputans[{{$index}}][parameter_id]"
                                wire:model="inputans.{{$index}}.parameter_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">

                                @foreach ($parameterAnalisisOptions as $item)
                                <option value="{{$item->id}}">{{$item->nama}}</option>
                                @endforeach

                            </select>
                            @error('asal_sam')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Metode
                            Analisis</label>
                        <div class="mt-2">
                            <select name="inputans[{{$index}}][metode_id]" wire:model="inputans.{{$index}}.metode_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">

                                @foreach ($metodeAnalisisOptions as $item)
                                <option value="{{$item->id}}">{{$item->nama}}</option>
                                @endforeach

                            </select>
                            @error('asal_sam')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="jumlah_per_parameter"
                            class="block text-sm font-medium leading-6 text-gray-900">Jumlah
                            Sampel
                        </label>
                        <div class="mt-2">
                            <input type="number" name="inputans[{{$index}}][jumlah_per_parameter]"
                                autocomplete="given-name" wire:model="inputans.{{$index}}.jumlah_per_parameter" class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset
                            ring-gray-300  focus:ring-2 focus:ring-inset focus:ring-emerald-600
                            sm:text-sm sm:leading-6">

                            @error('jumlah_per_parameter')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2 flex flex-col justify-between">
                        <label class="block text-sm font-medium leading-6 text-gray-900"></label>
                        <div class="mt-2">
                            <button
                                class="rounded-md bg-red-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                                wire:click.prevent="removeParameter({{$index}})">Hapus</button>
                        </div>
                    </div>

                    @endforeach
                    <div class="sm:col-span-2 flex flex-col justify-between">
                        <div class="mt-2">
                            <button
                                class="rounded-md bg-slate-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                                wire:click.prevent="addParameter">
                                + Tambah Parameter</button>
                        </div>
                    </div>


                    <div class="col-span-full">
                        <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Upload Foto
                            Sampel</label>
                        <div
                            class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                            <div class="text-center flex flex-col items-center">
                                <!-- Conditional SVG or Image -->
                                <div id="image-container">
                                    <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <!-- Placeholder SVG content -->
                                        <path fill-rule="evenodd"
                                            d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <!-- End of Conditional SVG or Image -->
                                <div class="mt-4 text-sm leading-6 text-gray-600">
                                    @if ($foto_sampel)
                                    <img class="mx-auto h-50 w-50" src="{{ $foto_sampel->temporaryUrl() }}"
                                        alt="Preview Image">
                                    {{-- @else
                                    <p class="text-red-500" wire:loading.remove>Invalid file type. Please select an
                                        image.</p> --}}
                                    @endif
                                    <label for="foto_sampel"
                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-emerald-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-600 focus-within:ring-offset-2 hover:text-emerald-500">
                                        <span>Upload a file</span>
                                        <input id="foto_sampel" wire:model="foto_sampel" type="file" class="sr-only"
                                            accept=".png, .jpg, .jpeg, .jpg">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                    <p class="text-xs  leading-5 text-gray-600">PNG, JPG, GIF up to 5MB</p>

                                    <!-- Image preview -->


                                    <!-- Remove button -->
                                    @if ($foto_sampel)
                                    <div class="flex justify-center mt-2">
                                        <button id="remove-button" type="button" wire:click="resetFotoSampel"
                                            class="text-sm font-semibold leading-6 text-red-600 cursor-pointer">Remove
                                            Image</button>
                                    </div>
                                    @endif

                                    @error('foto_sampel')
                                    <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>


                            </div>
                        </div>

                    </div>


                </div>
            </div>


            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                <button type="submit"
                    class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Simpan
                    Progress</button>
            </div>
    </form>
</div>
{{--
<script>
    const fileInput = document.getElementById('foto_sampel');
    const imageContainer = document.getElementById('image-container');
    const removeButton = document.getElementById('remove-button');

    fileInput.addEventListener('change', function() {
        const file = fileInput.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const fileURL = URL.createObjectURL(file);
                imageContainer.innerHTML = `<img class="mx-auto h-50 w-50" src="${fileURL}" alt="Preview Image">`;
                removeButton.style.display = 'block';
            } else {
                imageContainer.innerHTML = '<p class="text-red-500">Invalid file type. Please select an image.</p>';
                removeButton.style.display = 'none';
            }
        } else {
            imageContainer.innerHTML = `
                <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                </svg>`;
            removeButton.style.display = 'none';
        }
    });

    removeButton.addEventListener('click', function() {
        fileInput.value = ''; // Clear the file input
        imageContainer.innerHTML = `
            <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
            </svg>`;
        removeButton.style.display = 'none';
    });
    // new MultiSelectTag('countries')  // id
</script> --}}