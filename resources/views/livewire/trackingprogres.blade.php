<div>

    <form wire:submit.prevent="save">
        <div class="space-y-4">
            <div>
                <x-label for="kode">{{ __('Kode') }} <span class="text-rose-500">*</span>
                </x-label>

                <x-input type="text" wire:model="progressid" id="kode" name="kode" :value="old('kode')" required autofocus autocomplete="kode" />
            </div>
        </div>
        <div class="flex items-center mt-6">
            <button type="submit"
                class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">
                Submit
                <div wire:loading>
                    <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB" />
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor" />
                    </svg>
                </div>
            </button>

            @if ($resultData !== null && $resultData !== 'kosong')
            @if ($sertifikat)
            <button wire:click="downloadSertifikat" class="ml-4 text-blue-500 hover:text-blue-700">
                Download Certificate
            </button>
            @else
            <button disabled class="ml-4 text-gray-400 cursor-not-allowed">
                Certificate Not Available
            </button>
            @endif

            <button wire:click="downloadPdf"
                class="ml-4 text-blue-500 hover:text-blue-700">
                Download PDF
            </button>

            <button wire:click="downloadExcel"
                class="ml-4 text-blue-500 hover:text-blue-700">
                Download Excel
            </button>
            @endif
        </div>
    </form>

    @if ($resultData !== null && $resultData !== 'kosong')
    <div id="progress-list" class="max-h-[400px] overflow-y-auto p-4 border border-gray-200 rounded-lg bg-white mt-4">
        @foreach ($resultData as $key => $value)
        <div class="mb-2">
            <h1 class="text-base font-bold tracking-tight text-gray-900 dark:text-white">
                {{$value['text']}}
                @if ($value['status'] === 'checked')
                ✅
                @else
                <span class="text-gray-400 text-sm">On Progress</span>
                @endif
            </h1>
            <p class="font-normal text-sm text-gray-700 dark:text-gray-400">
                @if ($value['status'] === 'checked')
                {{ $value['time'] ?? ' -❕' }}
                @else
                -
                @endif
            </p>
        </div>
        @endforeach
    </div>
    @elseif ($resultData === 'kosong')
    <div class="mt-4">
        <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">
            Kode Sampel Tidak Valid ❌
        </h5>
        <p class="font-normal text-sm text-gray-700 dark:text-gray-400">
            Harap periksa dan cek kembali kode yang Anda masukkan.
        </p>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mt-4 text-red-500">
        {{ session('error') }}
    </div>
    @endif

</div>