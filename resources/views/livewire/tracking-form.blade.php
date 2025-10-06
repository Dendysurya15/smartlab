<div class="w-full">
    <!-- Tracking Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form wire:submit.prevent="search" class="space-y-4">
            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Tracking <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                    wire:model="kode"
                    id="kode"
                    placeholder="Contoh: SRS2025"
                    class="w-full px-4 py-3 rounded-lg border transition-all duration-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('kode') border-red-500 @else border-gray-300 @enderror"
                    required>
                @error('kode')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    Masukkan kode unik sistem untuk melacak progress sampel Anda
                </p>
            </div>

            <button type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center"
                wire:loading.attr="disabled"
                wire:target="search">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                <svg wire:loading.remove wire:target="search" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span wire:loading.remove wire:target="search">Track Sampel</span>
                <span wire:loading wire:target="search">Mencari...</span>
            </button>
        </form>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Siap Melacak Progress Sampel</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        Masukkan kode tracking di atas untuk melihat status progress pengerjaan sampel Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    @if($error)
    <div class="mt-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $error }}
        </div>
    </div>
    @endif

    <!-- Tracking Results -->
    @if($trackSampel)
    <div class="mt-8 space-y-6">

        <!-- Sample Information -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-slate-800">Informasi Sampel</h2>
                <div class="flex items-center space-x-3">
                    @if($trackSampel)
                    <button wire:click="downloadKupa"
                        wire:loading.attr="disabled"
                        wire:target="downloadKupa"
                        class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                        <div wire:loading wire:target="downloadKupa" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                        <svg wire:loading.remove wire:target="downloadKupa" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="downloadKupa">Download KUPA</span>
                        <span wire:loading wire:target="downloadKupa">Menyiapkan...</span>
                    </button>

                    @if($trackSampel->sertifikasi)
                    <button wire:click="downloadCertificate"
                        wire:loading.attr="disabled"
                        wire:target="downloadCertificate"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                        <div wire:loading wire:target="downloadCertificate" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                        <svg wire:loading.remove wire:target="downloadCertificate" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="downloadCertificate">Download Certificate</span>
                        <span wire:loading wire:target="downloadCertificate">Menyiapkan...</span>
                    </button>
                    @endif
                    @endif
                    <button wire:click="clear" class="text-sm text-slate-500 hover:text-slate-700 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tutup
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kode Tracking</label>
                    <p class="text-lg text-slate-800 font-semibold">{{ $kode }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nomor Surat</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->nomor_surat ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal Terima</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->tanggal_terima ? \Carbon\Carbon::parse($trackSampel->tanggal_terima)->format('d/m/Y') : '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nomor KUPA</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->nomor_kupa ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Departemen</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->departemen ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jenis Sampel</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->jenisSampel->nama ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jumlah Sampel</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->jumlah_sampel ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Sample Photos -->
        @if($trackSampel && $trackSampel->foto_sampel)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Foto Sampel</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                $fotoSampel = [];
                $fotoSampelData = $trackSampel->foto_sampel;
                if (is_string($fotoSampelData) && !empty($fotoSampelData)) {
                $fotos = array_filter(explode('%\/', $fotoSampelData));
                $fotoSampel = array_map(function ($foto) {
                return trim($foto, '"\'');
                }, $fotos);
                } elseif (is_array($fotoSampelData)) {
                $fotoSampel = $fotoSampelData;
                }
                @endphp

                @foreach($fotoSampel as $photo)
                @php
                $cleanPhoto = ltrim(trim($photo), '/');
                $photoPathPublic = 'storage/' . $cleanPhoto;
                $photoPathPrivate = 'storage/app/private/' . $cleanPhoto;
                $imageSrc = file_exists(public_path($photoPathPublic)) ? asset($photoPathPublic) : asset($photoPathPrivate);
                @endphp
                <div class="relative group">
                    <img src="{{ $imageSrc }}"
                        alt="Foto Sampel"
                        class="w-full h-48 object-cover rounded-lg shadow-md group-hover:shadow-lg transition-shadow">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Progress Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-slate-800 mb-6">Progress Pengerjaan</h2>

            @if(count($progressData) > 0)
            <div class="space-y-4">
                @foreach($progressData as $progress)
                <div class="flex items-start space-x-4 p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="flex-shrink-0">
                        @if(isset($progress['status']) && $progress['status'] === 'checked')
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        @else
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-slate-800">{{ $progress['text'] ?? 'Progress' }}</h3>
                        @if(isset($progress['time']) && $progress['time'])
                        <p class="text-sm text-slate-500 mt-1">
                            Selesai: {{ \Carbon\Carbon::parse($progress['time'])->format('d/m/Y H:i') }}
                        </p>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        @if(isset($progress['status']) && $progress['status'] === 'checked')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Selesai
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Dalam Proses
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-800 mb-2">Belum Ada Progress</h3>
                <p class="text-slate-600">Progress pengerjaan sampel akan muncul di sini setelah dimulai.</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>