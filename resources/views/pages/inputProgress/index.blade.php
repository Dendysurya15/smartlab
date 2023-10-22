<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div
            class="col-span-full xl:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
            <header class="flex px-5 py-4 bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <h2 class="font-bold text-slate-200 dark:text-slate-100">INPUT PENGERJAAN PROGRESS SAMPEL BARU</h2>
            </header>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div class="p-5">
                <form action="{{route('input_progress.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">Informasi Sampel & Pelanggan
                            </h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Peringatan untuk crosscheck ulang seluruh
                                data yang ingin akan dimasukkan ke sistem!</p>

                            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <label for="tanggal_penerimaan"
                                        class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                                        Penerimaan</label>
                                    <div class="mt-2">
                                        <input type="date" name="tanggal_penerimaan" id="tanggal_penerimaan"
                                            value="{{ old('tanggal_penerimaan', \Carbon\Carbon::now()->toDateString()) }}"
                                            autocomplete="given-name"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('tanggal_penerimaan')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="last-name"
                                        class="block text-sm font-medium leading-6 text-gray-900">Jenis Sampel</label>
                                    <div class="mt-2">
                                        <select id="jns_sam" name="jns_sam" autocomplete="jns_sam"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                            @foreach ($jenis_sampel as $key => $item)
                                            <option value="{{ $item }}" @if (old('jns_sam')===$item) selected @endif>
                                                ({{ $key }}) {{ $item }}
                                            </option>
                                            @endforeach

                                        </select>
                                        @error('jns_sam')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="last-name"
                                        class="block text-sm font-medium leading-6 text-gray-900">Asal Sampel</label>
                                    <div class="mt-2">
                                        <select id="asal_sam" name="asal_sam" autocomplete="asal_sam"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                            <option value="Internal" @if (old('asal_sam')==='Internal' ) selected
                                                @endif>Internal</option>
                                            <option value="Eksternal" @if (old('asal_sam')==='Eksternal' ) selected
                                                @endif>Eksternal</option>
                                        </select>
                                        @error('asal_sam')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="no_kupa" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                                        Kupa</label>
                                    <div class="mt-2">
                                        <input type="number" name="no_kupa" id="no_kupa" autocomplete="given-name"
                                            value="{{ old('no_kupa') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('no_kupa')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="no_lab" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                                        Lab</label>
                                    <div class="mt-2">
                                        <input type="text" name="no_lab" id="no_lab" autocomplete="given-name"
                                            value="{{ old('no_lab') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('no_lab')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="nama_pelanggan"
                                        class="block text-sm font-medium leading-6 text-gray-900">Nama Pengirim</label>
                                    <div class="mt-2">
                                        <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                                            value="{{ old('nama_pelanggan') }}" autocomplete="given-name"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6"
                                            {{-- placeholder="Nama Pelanggan" --}}>
                                        @error('nama_pelanggan')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="departemen"
                                        class="block text-sm font-medium leading-6 text-gray-900">Nama Departemen /
                                        Perusahaan</label>
                                    <div class="mt-2">
                                        <input type="text" name="departemen" id="departemen" autocomplete="given-name"
                                            value="{{ old('departemen') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('departemen')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="kode_sampel"
                                        class="block text-sm font-medium leading-6 text-gray-900">Kode Sampel</label>
                                    <div class="mt-2">
                                        <input type="text" name="kode_sampel" id="kode_sampel" autocomplete="given-name"
                                            value="{{ old('kode_sampel') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('kode_sampel')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="no_surat"
                                        class="block text-sm font-medium leading-6 text-gray-900">Nomor Surat
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" name="no_surat" id="no_surat" autocomplete="given-name"
                                            value="{{ old('no_surat') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                                        @error('no_surat')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="estimasi"
                                        class="block text-sm font-medium leading-6 text-gray-900">Estimasi Kupa</label>
                                    <div class="mt-2">
                                        <input type="date" name="estimasi" id="estimasi" autocomplete="given-name"
                                            value="{{ old('estimasi') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                                        @error('estimasi')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="last-name"
                                        class="block text-sm font-medium leading-6 text-gray-900">Tujuan</label>
                                    <div class="mt-2">
                                        <input type="text" name="tujuan" id="tujuan" autocomplete="given-name"
                                            value="{{ old('tujuan') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('tujuan')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="sm:col-span-2">
                                    <label for="" class="block text-sm font-medium leading-6 text-gray-900">Parameter
                                        Analisis</label>
                                    <div class="mt-2">

                                        {{-- <select name="countries" id="countries" multiple
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                            <option value="1">Afghanistan</option>
                                            <option value="2">Australia</option>
                                            <option value="3">Germany</option>
                                            <option value="4">Canada</option>
                                            <option value="5">Russia</option>
                                        </select> --}}

                                        <div x-data @tags-update=""
                                            data-tags='@json(old("parameter_analisis") ? explode(";", old("parameter_analisis")) : [])'
                                            class="block w-full rounded-md border-0  text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                            <div x-data="tagSelect()" x-init="init('parentEl')"
                                                @click.away="clearSearch()" @keydown.escape="clearSearch()">
                                                <div class="relative" @keydown.enter.prevent="addTag(textInput)">
                                                    <input x-model="textInput" x-ref="textInput"
                                                        @input="search($event.target.value)"
                                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                        placeholder="Masukkan Parameter">
                                                    <div :class="[open ? 'block' : 'hidden']">
                                                        <div class="absolute z-40 left-0 mt-2 w-full">
                                                            <div class="py-1 text-sm bg-white rounded shadow-lg ">
                                                                <a @click.prevent="addTag(textInput)"
                                                                    class="block py-1 px-5 cursor-pointer hover:bg-slate-200 hover:text-white">Tambah
                                                                    Parameter "<span class="font-semibold"
                                                                        x-text="textInput"></span>"</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="parameter_analisis"
                                                        id="parameter_analisis" value="{{old('parameter_analisis')}}">

                                                    <!-- selections -->
                                                    <template x-for="(tag, index) in tags">
                                                        <div
                                                            class="bg-slate-800 text-slate-200 inline-flex items-center text-sm rounded mt-2 mr-1">
                                                            <span class="ml-2 mr-1 leading-relaxed truncate max-w-xs"
                                                                x-text="tag"></span>
                                                            <button @click.prevent="removeTag(index)"
                                                                class="w-6 h-8 inline-block align-middle text-gray-500 hover:text-gray-600 focus:outline-none">
                                                                <svg class="w-6 h-6 fill-current mx-auto"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24">
                                                                    <path fill-rule="evenodd"
                                                                        d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    @error('parameter_analisis')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="last-name"
                                        class="block text-sm font-medium leading-6 text-gray-900">Nomor Hp</label>
                                    <div class="mt-2">
                                        <input type="text" name="no_hp" id="no_hp" autocomplete="given-name"
                                            value="{{ old('no_hp') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('no_hp')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="last-name"
                                        class="block text-sm font-medium leading-6 text-gray-900">Email Departemen /
                                        Perusahaan</label>
                                    <div class="mt-2">
                                        <input type="email" name="email" id="email" autocomplete="given-name"
                                            value="{{ old('email') }}"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                                        @error('email')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-span-full">
                                    <label for="cover-photo"
                                        class="block text-sm font-medium leading-6 text-gray-900">Upload Foto
                                        Sampel</label>
                                    <div
                                        class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                        <div class="text-center flex flex-col items-center">
                                            <!-- Conditional SVG or Image -->
                                            <div id="image-container">
                                                <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24"
                                                    fill="currentColor" aria-hidden="true">
                                                    <!-- Placeholder SVG content -->
                                                    <path fill-rule="evenodd"
                                                        d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <!-- End of Conditional SVG or Image -->
                                            <div class="mt-4 text-sm leading-6 text-gray-600">
                                                <label for="file-upload"
                                                    class="relative cursor-pointer rounded-md bg-white font-semibold text-emerald-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-600 focus-within:ring-offset-2 hover:text-emerald-500">
                                                    <span>Upload a file</span>
                                                    <input id="file-upload" name="file-upload" type="file"
                                                        class="sr-only" accept=".png, .jpg, .jpeg, .jpg">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                                <p class="text-xs  leading-5 text-gray-600">PNG, JPG, GIF
                                                    up to 10MB</p>
                                                <!-- Remove button -->
                                                <div class="flex justify-center mt-2">
                                                    <button id="remove-button" type="button"
                                                        class="text-sm font-semibold leading-6 text-red-600 cursor-pointer"
                                                        style="display: none;">Remove Image</button>
                                                </div>
                                                @error('file-upload')
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
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function () {
  // Get the value of parameter_analisis directly from the hidden input
  const parameterAnalisis = document.getElementById('parameter_analisis').value;

  // Now you can use the parameterAnalisis variable in your JavaScript code
  console.log('Old parameter_analisis value:', parameterAnalisis);
});

    function tagSelect() {
  return {
    open: false,
    textInput: '',
    tags: [],
    init() {
      this.tags = JSON.parse(this.$el.parentNode.getAttribute('data-tags'));
    },
    addTag(tag) {
      tag = tag.trim();
      if (tag != "" && !this.hasTag(tag)) {
        this.tags.push(tag);
        this.updateTagsInput();
      }
      this.clearSearch();
      this.$refs.textInput.focus();
      this.fireTagsUpdateEvent();
    },
    fireTagsUpdateEvent() {
      this.$el.dispatchEvent(new CustomEvent('tags-update', {
        detail: { tags: this.tags },
        bubbles: true,
      }));
    },
    hasTag(tag) {
      var tag = this.tags.find(e => {
        return e.toLowerCase() === tag.toLowerCase();
      });
      return tag != undefined;
    },
    removeTag(index) {
      this.tags.splice(index, 1);
      this.updateTagsInput();
      this.fireTagsUpdateEvent();
    },
    search(q) {
      if (q.includes(",")) {
        q.split(",").forEach(function (val) {
          this.addTag(val);
        }, this);
      }
      this.toggleSearch();
    },
    clearSearch() {
      this.textInput = '';
      this.toggleSearch();
    },
    toggleSearch() {
      this.open = this.textInput != '';
    },
    updateTagsInput() {
      const tagsInput = document.getElementById('parameter_analisis');
      const hiddenInputValue = document.getElementById('hiddenInputValue');
      if (tagsInput) {
        tagsInput.value = this.tags.join(';');
      }
    }
  }
}


    const fileInput = document.getElementById('file-upload');
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
</script>