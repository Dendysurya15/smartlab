<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div
            class="col-span-full xl:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
            <header class="flex px-5 py-4 bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <h2 class="font-bold text-slate-200 dark:text-slate-100">DETAIL PROGRESS SAMPLE {{$sampel->kode_track}}
                </h2>
            </header>
            @if (session('success'))

            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                role="alert">
                <span class="font-medium"> {{ session('success') }}</span>
            </div>
            @endif
            {{-- <div class="p-5">
                @livewire('editprogress',['sample' => $sampel->id])
            </div> --}}
        </div>
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // Add Form
        // Delete Form
        $('body').on('click', '.delete-form', function() {
            $(this).closest('form').remove();
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const jnsSampelDropdown = document.getElementById('jns_sam');
        const progressDropdown = document.getElementById('progress');

        jnsSampelDropdown.addEventListener('change', function() {
            const selectedValue = jnsSampelDropdown.value;

            fetch(`/get-progress-options?jenis_sampel=${selectedValue}`)
                .then(response => response.json())
                .then(data => {
                    progressDropdown.innerHTML = '';

                    data.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.textContent = option;
                        progressDropdown.appendChild(optionElement);
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });




    function tagSelect() {
        return {
            open: false,
            textInput: '',
            tags: [],
            init() {
                this.tags = JSON.parse(this.$el.parentNode.getAttribute('data-tags'));
            },
            addTag(tag) {
                tag = tag.trim();
                if (tag != "" && !this.hasTag(tag)) {
                    this.tags.push(tag);
                    this.updateTagsInput();
                }
                this.clearSearch();
                this.$refs.textInput.focus();
                this.fireTagsUpdateEvent();
            },
            fireTagsUpdateEvent() {
                this.$el.dispatchEvent(new CustomEvent('tags-update', {
                    detail: {
                        tags: this.tags
                    },
                    bubbles: true,
                }));
            },
            hasTag(tag) {
                var tag = this.tags.find(e => {
                    return e.toLowerCase() === tag.toLowerCase();
                });
                return tag != undefined;
            },
            removeTag(index) {
                this.tags.splice(index, 1);
                this.updateTagsInput();
                this.fireTagsUpdateEvent();
            },
            search(q) {
                if (q.includes(",")) {
                    q.split(",").forEach(function(val) {
                        this.addTag(val);
                    }, this);
                }
                this.toggleSearch();
            },
            clearSearch() {
                this.textInput = '';
                this.toggleSearch();
            },
            toggleSearch() {
                this.open = this.textInput != '';
            },
            updateTagsInput() {
                const tagsInput = document.getElementById('parameter_analisis');
                const hiddenInputValue = document.getElementById('hiddenInputValue');
                if (tagsInput) {
                    tagsInput.value = this.tags.join(',');
                }
            }
        }
    }


    const fileInput = document.getElementById('file-upload');
    const imageContainer = document.getElementById('image-container');
    const removeButton = document.getElementById('remove-button');

    fileInput.addEventListener('change', function() {
        const file = fileInput.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const fileURL = URL.createObjectURL(file);
                imageContainer.innerHTML = `<img class="mx-auto h-50 w-50" src="${fileURL}" alt="Preview Image">`;
                removeButton.style.display = 'block';
            } else {
                imageContainer.innerHTML = '<p class="text-red-500">Invalid file type. Please select an image.</p>';
                removeButton.style.display = 'none';
            }
        } else {
            imageContainer.innerHTML = `
                <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                </svg>`;
            removeButton.style.display = 'none';
        }
    });

    removeButton.addEventListener('click', function() {
        fileInput.value = ''; // Clear the file input
        imageContainer.innerHTML = `
            <svg class="mx-auto h-50 w-50 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
            </svg>`;
        removeButton.style.display = 'none';
    });
</script>