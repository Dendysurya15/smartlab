<div>
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar" class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64   shrink-0 bg-slate-800 p-4 transition-all duration-200 ease-in-out" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'" @click.outside="sidebarOpen = false" @keydown.escape.window="sidebarOpen = false" x-cloak="lg">



        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">Halaman</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Halaman</span>
                </h3>

                <ul class="mt-3">

                    <!-- Tracking Sampel -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['history_sampel', 'input_progress'])){{ 'bg-slate-900' }}@endif" x-data="{ open: {{ in_array(Request::segment(1), ['history_sampel', 'input_progress']) ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['history_sampel.index','input_progress.index'])){{ 'hover:text-slate-200' }}@endif" href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 h-6 w-6" height="1em" viewBox="0 0 512 512">
                                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path class="fill-current @if(in_array(Request::segment(1), ['history_sampel', 'input_progress'])){{ 'text-emerald-600' }}@else{{ 'text-slate-400' }}@endif" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />

                                    </svg>
                                    {{-- <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                                        <path
                                            class="fill-current @if(in_array(Request::segment(1), ['dashboard'])){{ 'text-indigo-500' }}@else{{ 'text-slate-400' }}@endif"
                                    d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0z" />
                                    <path class="fill-current @if(in_array(Request::segment(1), ['dashboard'])){{ 'text-indigo-600' }}@else{{ 'text-slate-600' }}@endif" d="M12 3c-4.963 0-9 4.037-9 9s4.037 9 9 9 9-4.037 9-9-4.037-9-9-9z" />
                                    <path class="fill-current @if(in_array(Request::segment(1), ['dashboard'])){{ 'text-indigo-200' }}@else{{ 'text-slate-400' }}@endif" d="M12 15c-1.654 0-3-1.346-3-3 0-.462.113-.894.3-1.285L6 6l4.714 3.301A2.973 2.973 0 0112 9c1.654 0 3 1.346 3 3s-1.346 3-3 3z" />
                                    </svg> --}}
                                    <span class="text-sm font-medium ml-3  duration-200">Tracking
                                        Sampel</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2  duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['history_sampel.index','input_progress.index'])){{ 'rotate-180' }}@endif" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class=" 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['history_sampel.index', 'input_progress.index'])){{ 'hidden' }}@endif" :class="open ? '!block' : 'hidden'">
                                @can('input_kupa')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('input_progress.index')){{ '!text-emerald-500' }}@endif" href="{{ route('input_progress.index') }}">
                                        <span class="text-sm font-medium  duration-200">Input
                                            Progress</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view_history_kupa')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('history_sampel.index')){{ '!text-emerald-500' }}@endif" href="{{route('history_sampel.index')}}">
                                        <span class="text-sm font-medium  duration-200">History</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    {{-- Monitoring Bahan Kimia --}}
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['inbox'])){{ 'bg-slate-900' }}@endif">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['inbox'])){{ 'hover:text-slate-200' }}@endif" href="#0">
                            <div class="flex items-center">
                                <svg class="shrink-0 h-6 w-6" height="1em" viewBox="0 0 448 512">
                                    <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path class="fill-current @if(in_array(Request::segment(1), ['inbox'])){{ 'text-indigo-500' }}@else{{ 'text-slate-600' }}@endif" d="M288 0H160 128C110.3 0 96 14.3 96 32s14.3 32 32 32V196.8c0 11.8-3.3 23.5-9.5 33.5L10.3 406.2C3.6 417.2 0 429.7 0 442.6C0 480.9 31.1 512 69.4 512H378.6c38.3 0 69.4-31.1 69.4-69.4c0-12.8-3.6-25.4-10.3-36.4L329.5 230.4c-6.2-10.1-9.5-21.7-9.5-33.5V64c17.7 0 32-14.3 32-32s-14.3-32-32-32H288zM192 196.8V64h64V196.8c0 23.7 6.6 46.9 19 67.1L309.5 320h-171L173 263.9c12.4-20.2 19-43.4 19-67.1z" />
                                    <path class="fill-current @if(in_array(Request::segment(1), ['inbox'])){{ 'text-indigo-300' }}@else{{ 'text-slate-400' }}@endif" d="M288 0H160 128C110.3 0 96 14.3 96 32s14.3 32 32 32V196.8c0 11.8-3.3 23.5-9.5 33.5L10.3 406.2C3.6 417.2 0 429.7 0 442.6C0 480.9 31.1 512 69.4 512H378.6c38.3 0 69.4-31.1 69.4-69.4c0-12.8-3.6-25.4-10.3-36.4L329.5 230.4c-6.2-10.1-9.5-21.7-9.5-33.5V64c17.7 0 32-14.3 32-32s-14.3-32-32-32H288zM192 196.8V64h64V196.8c0 23.7 6.6 46.9 19 67.1L309.5 320h-171L173 263.9c12.4-20.2 19-43.4 19-67.1z" />
                                </svg>
                                <span class="text-sm font-medium ml-3  duration-200">Stok
                                    Bahan Kimia</span>
                            </div>
                        </a>
                    </li>
                    <!-- System -->
                    {{-- @can('edit_kupa') --}}
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['system', 'roles'])){{ 'bg-slate-900' }}@endif" x-data="{ open: {{ in_array(Request::segment(1), ['system', 'roles']) ? 1 : 0 }} }">
                        <a class="block text-slate-200 hover:text-white truncate transition duration-150 @if(in_array(Request::segment(1), ['system'])){{ 'hover:text-slate-200' }}@endif" href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">

                                        <path class="fill-current @if(in_array(Request::segment(1), ['system', 'roles'])){{ 'text-emerald-600' }}@else{{ 'text-slate-400' }}@endif" d="m23.72 12 .229.686A.984.984 0 0 1 24 13v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-8c0-.107.017-.213.051-.314L.28 12H8v4h8v-4H23.72ZM13 0v7h3l-4 5-4-5h3V0h2Z" />
                                    </svg>

                                    <span class="text-sm font-medium ml-3  duration-200">System
                                    </span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2  duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['system.index'])){{ 'rotate-180' }}@endif" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>



                        <div class=" 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['system.index', 'roles'])){{ 'hidden' }}@endif" :class="open ? '!block' : 'hidden'">

                                @can('view_halaman_parameter_analisis')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('system.index')){{ '!text-emerald-500' }}@endif" href="{{ route('system.index') }}">
                                        <span class="text-sm font-medium  duration-200">Input
                                            Parameter Analisis</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view_role_management')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('roles')){{ '!text-emerald-500' }}@endif" href="{{route('roles')}}">
                                        <span class="text-sm font-medium  duration-200">Roles
                                            Management</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('excelsettings')){{ '!text-emerald-500' }}@endif" href="{{route('excelsettings')}}">
                                        <span class="text-sm font-medium  duration-200">Edit Header Excel</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('Managementkuesioner')){{ '!text-emerald-500' }}@endif" href="{{route('Managementkuesioner')}}">
                                        <span class="text-sm font-medium  duration-200">Management Kuesioner</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if(Route::is('landing-page-settings')){{ '!text-emerald-500' }}@endif" href="{{route('landing-page-settings')}}">
                                        <span class="text-sm font-medium  duration-200">Landing Page Settings</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>

                    </li>
                    {{-- @endcan --}}
                </ul>
            </div>

        </div>

    </div>
</div>