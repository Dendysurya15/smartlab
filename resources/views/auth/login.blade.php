<x-authentication-layout>
    <h1 class="text-3xl text-slate-800 dark:text-slate-700 font-bold mb-6">{{ __('Selamat Datang!') }} </h1>
    <h1 class="italic mb-4">Silahkan masukkan username dan password untuk masuk ke sistem web <span
            class="text-emerald-600 font-medium">Smart Lab</span>!</h1>
    @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
    @endif
    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-4">
            <div>
                {{--
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" type="email" name="email" :value="old('email')" required autofocus /> --}}
                <div>
                    <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm"></span>
                        </div>
                        <input type="text" name="email" id="price"
                            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6"
                            placeholder="Masukkan Email">
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
                        <input type="password" name="password" id="price"
                            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6"
                            placeholder="Masukkan Password">
                    </div>
                </div>
                {{--
                <x-jet-input id="password" type="password" name="password" required autocomplete="current-password" />
                --}}
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            {{-- @if (Route::has('password.request'))
            <div class="mr-1">
                <a class="text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            </div>
            @endif --}}
            <x-button>
                {{ __('Masuk') }}
            </x-button>
        </div>
    </form>
    <x-validation-errors class="mt-4" />
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-slate-200 dark:border-slate-700">
        <div class="text-sm text-center">
            {{ __('Copyright @ Digital Architect 2023') }}
        </div>
        <!-- Warning -->
        {{-- <div class="mt-5">
            <div class="bg-amber-100 dark:bg-amber-400/30 text-amber-600 dark:text-amber-400 px-3 py-2 rounded">
                <svg class="inline w-3 h-3 shrink-0 fill-current" viewBox="0 0 12 12">
                    <path
                        d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                </svg>
                <span class="text-sm">
                    To support you during the pandemic super pro features are free until March 31st.
                </span>
            </div>
        </div> --}}
    </div>
</x-authentication-layout>