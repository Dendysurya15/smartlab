<div>


    <!--
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
    <div class="">
        <h2 class="text-lg pb-5 font-semibold leading-7 text-gray-900">Tabel Pengguna Sistem Smartlab ( Role dan
            Permission )</h2>
        {{ $this->table }}
    </div>
    @can('create_new_user')
    <form wire:submit.prevent="save" method="POST">
        @csrf
        <div class="pt-5 ">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Buat User Baru</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">Berikut ini merupakan form untuk menambahkan user admin atau
                role lain ke sistem Smartlab. Mohon pastikan untuk mengisi data dengan benar !</p>

            <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                <div class="sm:col-span-2 sm:col-start-1">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nama User</label>
                    <div class="mt-2">
                        <input type="text" wire:model="name" id="name" autocomplete="address-level2" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email User</label>
                    <div class="mt-2">
                        <input type="email" wire:model="email" id="email" autocomplete="address-level1" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password
                        User</label>
                    <div class="mt-2">
                        <input type="text" wire:model="password" id="password" autocomplete="address-level1" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="postal-code" class="block text-sm font-medium leading-6 text-gray-900">Role User</label>
                    <div class="mt-2">
                        <select wire:model="role_user" autocomplete="role_user"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                            @foreach ($roles as $item)
                            <option value="{{$item}}">{{$item}}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <div class=" flex items-center justify-start pt-6   ">
            <button type="submit"
                class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Tambah
                User Baru</button>
        </div>


        @if ($successSubmit)
        <div class="p-4 mb-4 mt-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            User baru berhasil ditambahkan di sistem !
        </div>
        @endif

        @if ($errorSubmit)
        <div class="p-4 mb-4 mt-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="font-medium">{{ $msgError }}</span>
        </div>
        @endif
    </form>

    @endcan




</div>