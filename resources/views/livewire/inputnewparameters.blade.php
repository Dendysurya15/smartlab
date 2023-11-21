<div>
    <form wire:submit.prevent="save" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="col-span-1 md:col-span-1">
                <label for="jns_sam" class="block text-sm font-medium leading-6 text-gray-900">Jenis
                    Sampel</label>
                <div class="mt-2">
                    <select wire:model="jenis_sampel" autocomplete="jenis_sampel" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        @foreach ($getparameters as $item)
                        <option value="{{$item['id']}}">{{$item['nama']}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2">
                    <button class="rounded-md bg-slate-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600" wire:click.prevent="addParameter">
                        + Tambah Parameter
                    </button>


                </div>
            </div>


            <!-- Assuming the foreach loop starts here -->
            @foreach($parameters as $parameterIndex => $parameter)

            <div class="sm:col-span-3 mt-4">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex">
                        <div class="mt-2">
                            <input type="text" wire:model="parameters.{{ $parameterIndex }}.nama" placeholder="Masukan Nama Parameter" @if($isDisabled) disabled @endif>

                        </div>
                        <div class="ml-4 mt-2">
                            <!-- Your button here -->
                            <button id="tambahMetodeButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click.prevent="addMetode({{ $parameterIndex }})">
                                + Metode
                            </button>

                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" wire:click.prevent="deleteParameter({{ $parameterIndex }})">
                                - Parameter
                            </button>
                        </div>
                    </div>
                    @error("parameters.$parameterIndex.nama")
                    <span class="text-red-500">Harap Masukan Nama Parameter</span>
                    @enderror



                    @foreach($metode[$parameterIndex] ?? [] as $methodIndex => $method)
                    <!-- Metode -->

                    <div class="flex flex-wrap mt-4">

                        <div class="flex-1 sm:w-1/3">
                            <p>Masukan Metode Parameter: {{ $method['nama'] }}</p>
                        </div>
                        <!-- Metode A -->
                        <div class="flex-1 sm:w-1/3">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Nama Metode</label>
                            <div class="mt-2">
                                <input type="text" wire:model="metode.{{ $parameterIndex }}.{{ $methodIndex }}.namamethod" placeholder="Masukan Nama Metode">
                            </div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Harga</label>
                            <div class="mt-2">
                                <input type="text" wire:model="metode.{{ $parameterIndex }}.{{ $methodIndex }}.harga" placeholder="Masukan Harga Metode">
                            </div>

                        </div>

                        <!-- Metode B -->
                        <div class="flex-1 sm:w-1/3 mt-4 sm:mt-0">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Nama Metode</label>
                            <div class="mt-2">
                                <input type="text" placeholder="Parameter Harga" disabled>
                            </div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Total</label>
                            <div class="mt-2">
                                <input type="text" placeholder="Parameter total" disabled>
                            </div>
                        </div>

                        <!-- Metode C -->
                        <div class="flex-1 sm:w-1/3 mt-4 sm:mt-0">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Metode C</label>
                            <div class="mt-2">
                                <input type="text" placeholder="Parameter Harga" disabled>
                            </div>
                            <button class="bg-red-500 mt-4 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" wire:click.prevent="deleteMetode({{ $parameterIndex }}, {{ $methodIndex }})">
                                _ Metode
                            </button>
                        </div>
                        <!-- Add more metodes as needed -->
                    </div>

                    @endforeach
                    <!-- End Metode -->
                </div>

            </div>
            @endforeach
            <!-- Assuming the foreach loop ends here -->




        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
            <button type="submit" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Simpan
                Progress</button>
        </div>
    </form>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const namaParameterInput = document.getElementById('namaParameterInput');
        const tambahMetodeButton = document.getElementById('tambahMetodeButton');

        tambahMetodeButton.addEventListener('click', function() {
            namaParameterInput.disabled = true;
        });
    });
</script>