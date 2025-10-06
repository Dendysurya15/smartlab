<div class="space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
        <p>{{ session('message') }}</p>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Landing Page Settings</h2>
            <p class="text-gray-600 dark:text-gray-400">Kelola konten dinamis untuk halaman landing page</p>
        </div>
        <button wire:click="showAddForm"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Setting
        </button>
    </div>

    <!-- Settings Groups -->
    @foreach($settings as $group => $groupSettings)
    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-slate-200 dark:border-slate-700">
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white capitalize">
                {{ str_replace('_', ' ', $group) }}
            </h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($groupSettings as $setting)
                <div class="border border-slate-200 dark:border-slate-600 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-slate-900 dark:text-white">{{ $setting['label'] }}</h4>
                        <div class="flex space-x-2">
                            <button wire:click="showEditForm({{ $setting['id'] }})"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $setting['id'] }})"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus setting ini?')"
                                class="text-red-600 hover:text-red-800 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if($setting['type'] === 'image' && $setting['value'])
                    <div class="mb-2">
                        <img src="{{ Storage::url($setting['value']) }}"
                            alt="{{ $setting['label'] }}"
                            class="w-full h-32 object-cover rounded">
                    </div>
                    @elseif($setting['type'] === 'textarea')
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ Str::limit($setting['value'], 100) }}</p>
                    @else
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $setting['value'] }}</p>
                    @endif

                    @if($setting['description'])
                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $setting['description'] }}</p>
                    @endif

                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">{{ $setting['type'] }}</span>
                        <span class="text-xs text-gray-500">{{ $setting['sort_order'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    <!-- Gallery Images Section -->
    @if(count($galleryImages) > 0)
    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-slate-200 dark:border-slate-700">
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Gallery Images</h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($galleryImages as $index => $image)
                <div class="relative group">
                    <img src="{{ $image['url'] }}"
                        alt="Gallery Image"
                        class="w-full h-24 object-cover rounded">
                    <button wire:click="removeGalleryImage({{ $index }})"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')"
                        class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Add/Edit Form Modal -->
    @if($showForm)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="cancel">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-slate-800" wire:click.stop>
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ $editingId ? 'Edit Setting' : 'Tambah Setting Baru' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Key *</label>
                            <input type="text" wire:model="form.key"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                                placeholder="unique_key">
                            @error('form.key') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group *</label>
                            <select wire:model="form.group"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                <option value="general">General</option>
                                <option value="hero">Hero Section</option>
                                <option value="features">Features</option>
                                <option value="gallery">Gallery</option>
                                <option value="announcements">Announcements</option>
                                <option value="contact">Contact</option>
                                <option value="footer">Footer</option>
                            </select>
                            @error('form.group') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label *</label>
                        <input type="text" wire:model="form.label"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                            placeholder="Human readable label">
                        @error('form.label') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type *</label>
                            <select wire:model="form.type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                <option value="text">Text</option>
                                <option value="textarea">Text Area</option>
                                <option value="image">Image Upload</option>
                                <option value="url">URL</option>
                                <option value="email">Email</option>
                                <option value="number">Number</option>
                                <option value="boolean">Yes/No</option>
                            </select>
                            @error('form.type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                            <input type="number" wire:model="form.sort_order"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                                min="0">
                            @error('form.sort_order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($form['type'] === 'image')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Image</label>
                        <input type="file" wire:model="newGalleryImage" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                        @error('newGalleryImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @elseif($form['type'] === 'textarea')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                        <textarea wire:model="form.value" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                            placeholder="{{ $form['placeholder'] }}"></textarea>
                        @error('form.value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @elseif($form['type'] === 'boolean')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                        <select wire:model="form.value"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('form.value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                        <input type="{{ $form['type'] === 'number' ? 'number' : 'text' }}" wire:model="form.value"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                            placeholder="{{ $form['placeholder'] }}">
                        @error('form.value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea wire:model="form.description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                            placeholder="Optional description"></textarea>
                        @error('form.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Placeholder</label>
                        <input type="text" wire:model="form.placeholder"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                            placeholder="Placeholder text">
                        @error('form.placeholder') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="form.is_active" id="is_active"
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Active
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="cancel"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition duration-200">
                            {{ $editingId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>