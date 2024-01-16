<div>
    <section class="">
        <div class="">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex items-center justify-between d p-4">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                    fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            {{-- <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                placeholder="Search" required=""> --}}
                            <input type="text" id="small-input" wire:model.live.debounce.300ms="search"
                                class="block w-full text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pl-10 p-2"
                                placeholder="Search">
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900">User Type :</label>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="">All</option>
                                <option value="0">User</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class=" text-gray-700 uppercase bg-gray-50 " style="font-size: 10px">
                            <tr>
                                <th scope="col" class="px-4 py-3">id</th>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('tanggal_penerimaan')">
                                    <button class="flex">
                                        Tanggal
                                        @if ($sortBy !== 'tanggal_penerimaan')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="ml-1 w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @elseif($sortDir === 'ASC')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="ml-1 w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>

                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="ml-1 w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>

                                        @endif


                                    </button>

                                </th>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('jenis_sampel')">Jenis Sampel
                                </th>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('nama_pengirim')">Nama
                                    Pengirim</th>
                                <th scope="col" class="px-4 py-3">Departemen</th>
                                <th scope="col" class="px-4 py-3">Jenis Sampel</th>
                                <th scope="col" class="px-4 py-3">Kode Track</th>
                                <th scope="col" class="px-4 py-3">Progress</th <th scope="col" class="px-4 py-3">
                                <th scope="col" class="px-4 py-3">Progress</th>
                                <th scope="col" class="px-4 py-3">Jenis Sampel</th>
                                <th scope="col" class="px-4 py-3">Kode Track</th>
                                <th scope="col" class="px-4 py-3">Progress</th <th scope="col" class="px-4 py-3">
                                <th scope="col" class="px-4 py-3">Progress</th>
                                <th scope="col" class="px-4 py-3">Jenis Sampel</th>
                                <th scope="col" class="px-4 py-3">Kode Track</th>
                                <th scope="col" class="px-4 py-3">Progress</th <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 10px">
                            @foreach ($datas as $data)


                            <tr class="border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$data->id}}</th>
                                <td class="px-4 py-3">{{$data->tanggal_penerimaan}}</td>
                                <td class="px-4 py-3 text-green-500">
                                    {{$data->jenis_sampel}}</td>
                                <td class="px-4 py-3">{{$data->nama_pengirim}}</td>
                                <td class="px-4 py-3">{{$data->departemen}}</td>
                                <td class="px-4 py-3 text-green-500">
                                    {{$data->jenis_sampel}}</td>
                                <td class="px-4 py-3">{{$data->kode_track}}</td>
                                <td class="px-4 py-3">{{$data->progress}}</td>
                                <td class="px-4 py-3 text-green-500">
                                    {{$data->jenis_sampel}}</td>
                                <td class="px-4 py-3">{{$data->kode_track}}</td>
                                <td class="px-4 py-3">{{$data->progress}}</td>
                                <td class="px-4 py-3">{{$data->progress}}</td>
                                <td class="px-4 py-3 text-green-500">
                                    {{$data->jenis_sampel}}</td>
                                <td class="px-4 py-3">{{$data->kode_track}}</td>
                                <td class="px-4 py-3">{{$data->progress}}</td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button class="px-3 py-1 bg-red-500 text-white rounded">X</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="py-4 px-3">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                            <select wire:model.live='perPage'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                    </div>
                    {{$datas->links()}}
                </div>
            </div>
        </div>
    </section>

</div>