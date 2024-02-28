<div>

    <form wire:submit.prevent="save" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Informasi Sampel & Pelanggan
                </h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Peringatan untuk crosscheck ulang seluruh
                    data yang ingin akan dimasukkan ke sistem!</p>

                {{-- tanggal memo --}}
                <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="tanggal_memo" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                            Memo <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="datetime-local" wire:model="tanggal_memo" id="tanggal_memo" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('tanggal_memo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Jenis
                            Sampel <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <select wire:model="jenis_sampel" wire:change="ChangeFieldParamAndNomorLab" autocomplete="jenis_sampel" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
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
                        <label for="jumlah_sampel" class="block text-sm font-medium leading-6 text-gray-900">Jumlah Sampel <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="number" wire:model="jumlah_sampel" wire:change="getlabstatus" autocomplete="given-name" value="{{ old('jumlah_sampel') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('jumlah_sampel')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Asal
                            Sampel <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <select id="asal_sampel" wire:model="asal_sampel" autocomplete="asal_sampel" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600  sm:text-sm sm:leading-6">
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
                            Kupa <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="number" wire:model="nomor_kupa" id="nomor_kupa" autocomplete="given-name" value="{{ old('nomor_kupa') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('nomor_kupa')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <div class="sm:col-span-2">
                        <label for="nomor_lab" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                            Lab <span style="color:red">*</span></label>
                        @if ($jumlah_sampel > 1)
                        <div class="mt-2 grid grid-cols-2 gap-4">

                            <div class="col-span-1">
                                <input type="text" wire:model="nomor_lab_left" id="nomor_lab_left" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                @error('nomor_lab_left')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <input type="text" wire:model="nomor_lab_right" id="nomor_lab_right" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                @error('nomor_lab_right')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @else
                        <div class="mt-2">
                            <input type="text" wire:model="nomor_lab_left" id="nomor_lab_left" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('nomor_lab_left')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                    </div>

                    <div class="sm:col-span-2">
                        <label for="nama_pengirim" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            Pengirim <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="text" wire:model="nama_pengirim" id="nama_pengirim" value="{{ old('nama_pengirim') }}" autocomplete="given-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6" {{-- placeholder="Nama Pelanggan" --}}>
                            @error('nama_pengirim')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="departemen" class="block text-sm font-medium leading-6 text-gray-900">Nama
                            Departemen /
                            Perusahaan <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="text" wire:model="departemen" id="departemen" autocomplete="given-name" value="{{ old('departemen') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('departemen')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="kode_sampel" class="block text-sm font-medium leading-6 text-gray-900">Kode
                            Sampel <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="text" wire:model="kode_sampel" id="kode_sampel" autocomplete="given-name" value="{{ old('kode_sampel') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
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
                            <input type="text" wire:model="kemasan_sampel" id="kemasan_sampel" autocomplete="given-name" value="{{ old('kemasan_sampel') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('kemasan_sampel')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Kondisi
                            Sampel</label>
                        <div class="mt-2">
                            <select id="kondisi_sampel" wire:model="kondisi_sampel" autocomplete="kondisi_sampel" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600  sm:text-sm sm:leading-6">
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
                        <label for="nomor_surat" class="block text-sm font-medium leading-6 text-gray-900">Nomor Surat
                            <span style="color:red">*</span>
                        </label>
                        <div class="mt-2">
                            <input type="text" wire:model="nomor_surat" id="nomor_surat" autocomplete="given-name" value="{{ old('nomor_surat') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('nomor_surat')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="estimasi" class="block text-sm font-medium leading-6 text-gray-900">Estimasi
                            Kupa <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="date" wire:model="estimasi" id="estimasi" autocomplete="given-name" value="{{ old('estimasi') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('estimasi')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="tanggal_terima" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                            Penerimaan<span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="date" wire:model="tanggal_terima" id="tanggal_terima" autocomplete="given-name" value="{{ old('tanggal_terima') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                            @error('tanggal_terima')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Tujuan</label>
                        <div class="mt-2">
                            <input type="text" wire:model="tujuan" id="tujuan" autocomplete="given-name" value="{{ old('tujuan') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('tujuan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Skala Prioritas
                            Sampel <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <select id="skala_prioritas" wire:model="skala_prioritas" autocomplete="skala_prioritas" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600  sm:text-sm sm:leading-6">
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
                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                            Hp <span style="color:red">*</span></label>
                        <div class="mt-2">
                            <input type="number" wire:model="no_hp" id="no_hp" autocomplete="given-name" value="{{ old('no_hp') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('no_hp')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="mt-2 grid grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <div class="flex h-6 items-center">
                                    <input id="personel" wire:model="personel" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
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
                                    <input id="alat" wire:model="alat" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
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
                                    <input id="bahan" wire:model="bahan" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
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


                    <div class="sm:col-span-4">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Email
                                    (To) <span style="color:red">*</span></label>
                                <div class="mt-2">
                                    <input type="text" wire:model="emailTo" id="emailTo" autocomplete="given-name" value="{{ old('emailTo') }}" placeholder="Ex: Imam@gmail.com; Kiky@gmail.com. Beri tanda pemisah ';'" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                    @error('emailTo')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Email
                                    (Cc) <span style="color:red">*</span></label>
                                <div class="mt-2">
                                    <input type="text" wire:model="emailCc" id="emailCc" autocomplete="given-name" value="{{ old('emailCc') }}" placeholder="Ex: Imam@gmail.com; Kiky@gmail.com. Beri tanda pemisah ';'" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                    @error('emailCc')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Diskon %</label>
                        <div class="mt-2">
                            <input type="number" wire:model="discount" id="discount" autocomplete="given-name" value="{{ old('discount') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @error('discount')
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

                    <div class="sm:col-span-3">
                        <div class="grid grid-cols-2 gap-6">

                            <div class="col-span-1">
                                <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Pilih
                                    Parameter <span style="color:red">*</span></label>
                                <div class="mt-2">
                                    <select wire:model="val_parameter" autocomplete="country-name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600  sm:text-sm sm:leading-6">
                                        @foreach ($parameterAnalisisOptions as $key => $items)
                                        <option value="{{$items['id']}}">{{$items['nama_parameter_full']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label for="last-name" class="block text-sm font-medium leading-6 text-white ">
                                    |</label>
                                <div class="mt-2">
                                    <button class="rounded-md bg-slate-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600" wire:click.prevent="addParameter">
                                        + Tambah Parameter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="sm:col-span-6">
                        {{-- @if (count($formData) > 1)
                        <p class="sm:col-span-6">Rekap Biaya Per Parameter</p>
                        @endif --}}
                        @error('formData')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @foreach ($formData as $index => $item)

                        <div class="grid grid-cols-5 gap-5 mb-2 p-2 border border-dashed border-gray-900/25 rounded-lg" wire:key="form-{{ $index }}">

                            <div class="sm:col-span-2 ">

                                <label class="block text-sm font-medium leading-6 text-gray-900">
                                    {{$item['nama_parameter']}} </label>
                                <div class="mt-2">
                                    <p>{{ $item['list_metode'] }}</p>
                                </div>
                            </div>

                            <div class="sm:col-span-2">

                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-sm font-medium leading-6 text-gray-900">
                                            {{$item['jumlahsample']}} </label>

                                        <div class="mt-2">
                                            <input type="number" autocomplete="given-name" wire:change="gethargasample({{ $index }})" wire:model="formData.{{ $index }}.jumlah_sampel" class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium leading-6 text-gray-900">
                                            {{$item['hargassample']}}</label>
                                        <div class="mt-2">
                                            <input type="number" autocomplete="given-name" wire:change="updateHargaSampel()" wire:model="formData.{{ $index }}.harga_sampel" class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium leading-6 text-gray-900">
                                            {{$item['totaljudul']}} </label>
                                        <div class="mt-2">
                                            <input type="text" autocomplete="given-name" wire:model="formData.{{ $index }}.totalharga" disabled class="block w-full rounded-md border-0 text-slate-400 font-semibold shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="sm:col-span-1">


                                <label class="block text-sm font-medium leading-6 text-white">
                                    |</label>

                                <div class="mt-2">
                                    <button class="rounded-md bg-red-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600" wire:click.prevent="removeParameter({{ $index }})">
                                        Hapus Parameter
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>



                    <div class="sm:col-span-6  ">

                        <div class="col-span-full">
                            <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Upload
                                Foto
                                Sampel</label>
                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                <div class="text-center flex flex-col items-center">
                                    <!-- Conditional SVG or Image -->
                                    <div id="image-container">
                                        <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <!-- Placeholder SVG content -->
                                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <!-- End of Conditional SVG or Image -->
                                    <div class="mt-4 text-sm leading-6 text-gray-600">
                                        @if ($foto_sampel)
                                        <img class="mx-auto h-50 w-50" src="{{ $foto_sampel->temporaryUrl() }}" alt="Preview Image">
                                        {{-- @else
                                        <p class="text-red-500" wire:loading.remove>Invalid file type. Please select an
                                            image.</p> --}}
                                        @endif
                                        <label for="foto_sampel" class="relative cursor-pointer rounded-md bg-white font-semibold text-emerald-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-600 focus-within:ring-offset-2 hover:text-emerald-500">
                                            <span>Upload a file</span>
                                            <input id="foto_sampel" wire:model="foto_sampel" type="file" class="sr-only" accept=".png, .jpg, .jpeg, .jpg">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                        <p class="text-xs  leading-5 text-gray-600">PNG, JPG, GIF up to 5MB</p>

                                        <!-- Image preview -->


                                        <!-- Remove button -->
                                        @if ($foto_sampel)
                                        <div class="flex justify-center mt-2">
                                            <button id="remove-button" type="button" wire:click="resetFotoSampel" class="text-sm font-semibold leading-6 text-red-600 cursor-pointer">Remove
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
                    <button type="submit" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                        Simpan Progress
                        <div wire:loading>
                            <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB" />
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor" />
                            </svg>
                        </div>
                    </button>
                </div>


            </div>
        </div>
    </form>
</div>