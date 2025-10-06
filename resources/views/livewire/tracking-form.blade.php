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
                <button wire:click="clear" class="text-sm text-slate-500 hover:text-slate-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Tutup
                </button>
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
                    <label class="block text-sm font-medium text-gray-500">Tanggal Registrasi</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->tanggal_registrasi ? \Carbon\Carbon::parse($trackSampel->tanggal_registrasi)->format('d/m/Y') : '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Estimasi KUPA</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->estimasi_kup ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Departemen</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->departement ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jenis Sampel</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->jenis_sampel ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jumlah Sampel</label>
                    <p class="text-lg text-slate-800">{{ $trackSampel->jumlah_sampel ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Progress Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-slate-800 mb-6">Progress Pengerjaan</h2>

            @if(count($progressData) > 0)
            <div class="space-y-4">
                @foreach($progressData as $progress)
                <div class="flex items-start space-x-4 p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="flex-shrink-0">
                        @if(isset($progress['status']) && ($progress['status'] === 'completed' || $progress['status'] === 'checked'))
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
                        <h3 class="text-lg font-medium text-slate-800">{{ $progress['text'] ?? $progress['nama_progress'] ?? 'Progress' }}</h3>
                        @if(isset($progress['waktu_selesai']) && $progress['waktu_selesai'])
                        <p class="text-sm text-slate-500 mt-1">
                            Selesai: {{ \Carbon\Carbon::parse($progress['waktu_selesai'])->format('d/m/Y H:i') }}
                        </p>
                        @elseif(isset($progress['created_at']) && $progress['created_at'])
                        <p class="text-sm text-slate-500 mt-1">
                            Dibuat: {{ \Carbon\Carbon::parse($progress['created_at'])->format('d/m/Y H:i') }}
                        </p>
                        @endif
                        @if(isset($progress['keterangan']) && $progress['keterangan'])
                        <p class="text-sm text-slate-600 mt-2">{{ $progress['keterangan'] }}</p>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        @if(isset($progress['status']) && ($progress['status'] === 'completed' || $progress['status'] === 'checked'))
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