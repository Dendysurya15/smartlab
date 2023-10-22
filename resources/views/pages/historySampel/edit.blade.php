<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div
            class="col-span-full xl:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
            <header class="flex px-5 py-4 bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <h2 class="font-bold text-slate-200 dark:text-slate-100">DETAIL PROGRESS SAMPLE</h2>
            </header>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the first column (form field 1) -->
                        <input type="text">
                    </div>

                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2) -->
                        <input type="text">
                    </div>

                    <div class="col-span-1 md:col-span-1 row-span-2">
                        <div class="flex justify-center rounded-lg px-6 py-5">
                            <div class="text-center flex flex-col items-center">
                                <!-- Conditional SVG or Image -->


                                <div id="image-container">
                                    @if($sampel->foto_sample)
                                    <img src="{{ asset('storage/uploads/' . $sampel->foto_sample) }}"
                                        alt="Sample Image">
                                    @else
                                    <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <!-- Placeholder SVG content -->
                                        <!-- ... -->
                                    </svg>
                                    @endif
                                </div>

                                <div class="mt-4 text-sm leading-6 text-gray-600">
                                    <label for="file-upload"
                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-emerald-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-600 focus-within:ring-offset-2 hover:text-emerald-500">
                                        <span>{{$sampel->foto_sample}} </span>

                                    </label>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the first column (form field 1, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>

                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>
                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>
                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>
                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>

                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>

                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>
                    <div class="col-span-1 md:col-span-1">
                        <!-- Content for the second column (form field 2, row 2) -->
                        <input type="text" placeholder="row 2">
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>