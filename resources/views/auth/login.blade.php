<x-authentication-layout>
    <h1 class="text-3xl text-slate-800 dark:text-slate-700 font-bold mb-6">{{ __('Selamat Datang!') }} </h1>
    <h1 class="italic mb-4">Silahkan masukkan username dan password untuk masuk ke sistem web <span class="text-emerald-600 font-medium">Smart Lab</span>!</h1>
    @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
    @endif
    <!-- Form -->


    @livewire('trackingprogres')
    {{--
    <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="space-y-4">
        <div>
            <div>
                <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <div class="relative mt-2 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 sm:text-sm"></span>
                    </div>
                    <input type="text" name="email" class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-2 ring-inset ring-emerald-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-700 sm:text-sm sm:leading-6" placeholder="Masukkan Email">
                </div>
            </div>
        </div>
        <div>

            <div>
                <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <div class="relative mt-2 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 sm:text-sm"></span>
                    </div>
                    <input type="password" name="password" class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-2 ring-inset ring-emerald-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-700 sm:text-sm sm:leading-6" placeholder="Masukkan Password">
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-between mt-6">

    </div>
    <x-button>
        {{ __('Masuk') }}
    </x-button>
    </div>
    </form>
    --}}

    <x-validation-errors class="mt-4" />
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-slate-200 dark:border-slate-700">
        <div class="text-sm text-center">
            {{ __('Copyright @ Digital Architect 2023') }}
        </div>
        <!-- Warning -->
    </div>
</x-authentication-layout>