<div>
    @if(session()->has('message'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
        <p>{{ session('message') }}</p>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form wire:submit.prevent="update({{ $data[0]['id'] }})" method="POST" enctype="multipart/form-data">

        @csrf
        <div class="space-y-12">

            <div class="grid grid-cols-2">
                <div class="m-8 mt-2 shadow-md">
                    <label for="nama Param" class="block text-sm font-medium leading-6 text-gray-900">
                        Nama Paremeter</label>
                    <input placeholder="{{$data[0]['parameter_analisis']['nama']}}" type="text" wire:model="" id="" placeholder="123-45-678" disabled class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                </div>
                <div class="m-8 mt-2 shadow-md">
                    <label for="nama_metdho" class="block text-sm font-medium leading-6 text-gray-900">
                        Nama Metode
                    </label>
                    <input placeholder="{{$data[0]['nama']}}" type="text" wire:model="namamtd" id="your_id_here" value="" maxlength="50" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">
                </div>

                <div class="m-8 mt-2 shadow-md w-1/3">
                    <label for="harga" class="block text-sm font-medium leading-6 text-gray-900">
                        Harga Metode</label>
                    <input placeholder="{{$data[0]['harga']}}" type="text" wire:model="hargamtd" id="" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                </div>
                <div class="m-8 mt-2 shadow-md w-1/3">
                    <label for="satuan" class="block text-sm font-medium leading-6 text-gray-900">Kode
                        Satuan Metode</label>
                    <input placeholder="{{$data[0]['satuan']}}" type="text" wire:model="satuanmtd" id="" value="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6">

                </div>


            </div>





            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('system.index') }}" class="rounded-md bg-white-200 px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-orange-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Cancel</a>

                <button type="button" wire:click="delete({{ $data[0]['id'] }})" class="rounded-md bg-white-200 px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Hapus Data</button>
                <button type="submit" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Simpan
                    Progress</button>
            </div>
        </div>
    </form>
</div>