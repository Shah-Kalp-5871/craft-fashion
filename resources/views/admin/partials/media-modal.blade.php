<!-- Media Modal -->
<div id="media-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeMediaModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block w-full max-w-5xl overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800" id="modal-title">Select Media</h3>
                <button type="button" onclick="closeMediaModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <!-- Search and Upload -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div class="relative flex-1 max-w-md">
                        <input type="text" id="media-search" placeholder="Search media..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div>
                        <input type="file" id="media-upload-input" multiple class="hidden" onchange="handleFileUpload(event)">
                        <button type="button" onclick="document.getElementById('media-upload-input').click()" 
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center">
                            <i class="fas fa-upload mr-2"></i>Upload New
                        </button>
                    </div>
                </div>

                <!-- Media Grid -->
                <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-[50vh] overflow-y-auto p-1">
                    <div class="col-span-full text-center py-20 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                        <p>Loading media...</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div id="media-pagination" class="flex justify-center space-x-2 mt-6"></div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="closeMediaModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">Cancel</button>
                <button type="button" onclick="confirmMediaSelection()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold">Select</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentMode = 'main';
    let selectedImages = [];
    let currentMediaData = null;
    let onSelectCallback = null;

    window.mediaModal = {
        open: function(options = {}) {
            currentMode = options.mode || 'main';
            onSelectCallback = options.onSelect || null;
            selectedImages = [];
            
            document.getElementById('media-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            loadMedia(1);
        }
    };

    function closeMediaModal() {
        document.getElementById('media-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    async function loadMedia(page = 1, search = '') {
        const grid = document.getElementById('media-grid');
        try {
            const response = await axios.get(`{{ route('admin.media.data') }}`, {
                params: { page, search }
            });

            currentMediaData = response.data;
            renderMediaGrid(response.data.data);
            renderPagination(response.data);
        } catch (error) {
            console.error('Media load error:', error);
            grid.innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Error loading media.</div>';
        }
    }

    function renderMediaGrid(media) {
        const grid = document.getElementById('media-grid');
        if (!media || media.length === 0) {
            grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">No media found.</div>';
            return;
        }

        grid.innerHTML = media.map(item => {
            const isSelected = selectedImages.some(img => img.id === item.id);
            return `
                <div class="relative border rounded-lg overflow-hidden cursor-pointer group hover:shadow-md transition ${isSelected ? 'ring-2 ring-indigo-500' : ''}"
                     onclick="toggleImageSelection(${item.id}, '${item.url || item.path}')">
                    <img src="${item.url || item.path}" class="w-full h-32 object-cover">
                    <div class="p-2 text-xs truncate bg-white border-t">${item.file_name || item.name}</div>
                    ${isSelected ? '<div class="absolute top-2 right-2 bg-indigo-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px]">✓</div>' : ''}
                </div>
            `;
        }).join('');
    }

    function renderPagination(data) {
        const pagination = document.getElementById('media-pagination');
        if (!data.links || data.links.length <= 3) {
            pagination.innerHTML = '';
            return;
        }

        pagination.innerHTML = data.links.map(link => {
            if (!link.url) return `<span class="px-3 py-1 text-gray-400">${link.label}</span>`;
            
            const active = link.active ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100';
            const pageMatch = link.url.match(/page=(\d+)/);
            const page = pageMatch ? pageMatch[1] : 1;
            
            return `
                <button type="button" onclick="loadMedia(${page}, document.getElementById('media-search').value)"
                        class="px-3 py-1 rounded-lg border border-gray-200 ${active} transition font-medium">
                    ${link.label.replace('&laquo;', '').replace('&raquo;', '')}
                </button>
            `;
        }).join('');
    }

    function toggleImageSelection(id, url) {
        if (currentMode === 'main') {
            selectedImages = [{ id, url }];
        } else {
            const index = selectedImages.findIndex(img => img.id === id);
            if (index === -1) selectedImages.push({ id, url });
            else selectedImages.splice(index, 1);
        }
        renderMediaGrid(currentMediaData.data);
    }

    function confirmMediaSelection() {
        if (selectedImages.length === 0) {
            alert('Please select at least one image');
            return;
        }

        if (onSelectCallback) {
            onSelectCallback(currentMode === 'main' ? selectedImages[0] : selectedImages);
        }
        closeMediaModal();
    }

    async function handleFileUpload(event) {
        const files = event.target.files;
        if (!files.length) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        try {
            await axios.post('{{ route("admin.media.upload") }}', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            loadMedia(1);
        } catch (error) {
            alert('Upload failed');
        }
    }
</script>
