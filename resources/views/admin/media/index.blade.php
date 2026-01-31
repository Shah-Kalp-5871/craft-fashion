{{-- resources/views/admin/media/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Media Library')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Media Library</h2>
                <p class="text-gray-600">Upload and manage your media files</p>
            </div>
            <div class="flex space-x-3">
                <button id="topUploadBtn" class="btn-primary">
                    <i class="fas fa-upload mr-2"></i>Upload Files
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-xl p-8 hover:border-indigo-400 transition-colors duration-200"
            id="dropzone">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
            <p class="text-lg font-medium text-gray-800 mb-2">Drag & drop files here</p>
            <p class="text-gray-500 mb-4">or</p>
            <button onclick="document.getElementById('fileInput').click()" class="btn-primary mb-2">
                <i class="fas fa-folder-open mr-2"></i>Browse Files
            </button>
            <p class="text-sm text-gray-500">Supports: JPG, PNG, GIF, WEBP, SVG (Max 3MB)</p>
            <input type="file" id="fileInput" multiple class="hidden" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
        </div>

        <!-- Upload Progress -->
        <div id="uploadProgress" class="hidden mt-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Uploading...</span>
                <span id="uploadCount" class="text-sm text-gray-500">0/0</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="uploadBar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%">
                </div>
            </div>
            <div id="uploadList" class="mt-3 space-y-2 max-h-40 overflow-y-auto"></div>
        </div>

        <!-- Bulk Alt Text (Optional) -->
        <div id="bulkAltContainer" class="hidden mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Alternative Text for All Files</label>
            <div class="flex space-x-2">
                <input type="text" id="bulkAltText" placeholder="Describe all images (optional)"
                    class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <button onclick="clearBulkAltText()" class="btn-secondary">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">This text will be applied to all uploaded files</p>
        </div>
    </div>

    <!-- Media Library Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Media Files</h3>
        </div>
        <div class="p-6">
            <!-- Loading State -->
            <div id="loadingState" class="hidden text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <p class="mt-2 text-gray-500">Loading media files...</p>
            </div>

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <div class="order-2 sm:order-1">
                    <div class="relative" style="width: 260px;">
                        <input type="text" id="searchInput" placeholder="Search media..."
                            class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 order-1 sm:order-2">
                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <button id="sortBtn" class="btn-secondary">
                            <i class="fas fa-sort mr-2"></i>Sort
                        </button>
                        <div id="sortMenu"
                            class="absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden">
                            <button data-sort="created_at_desc"
                                class="sort-option w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center">
                                <i class="fas fa-check mr-2 text-indigo-600"></i>Newest First
                            </button>
                            <button data-sort="created_at_asc"
                                class="sort-option w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center">
                                <i class="fas fa-check mr-2 opacity-0"></i>Oldest First
                            </button>
                            <button data-sort="name_asc"
                                class="sort-option w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center">
                                <i class="fas fa-check mr-2 opacity-0"></i>Name A → Z
                            </button>
                            <button data-sort="name_desc"
                                class="sort-option w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center">
                                <i class="fas fa-check mr-2 opacity-0"></i>Name Z → A
                            </button>
                            <button data-sort="size_desc"
                                class="sort-option w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center">
                                <i class="fas fa-check mr-2 opacity-0"></i>Size: Large → Small
                            </button>
                            <button data-sort="size_asc"
                                class="sort-option w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center">
                                <i class="fas fa-check mr-2 opacity-0"></i>Size: Small → Large
                            </button>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <button id="bulkDeleteBtn" class="btn-danger hidden">
                        <i class="fas fa-trash mr-2"></i>Delete Selected
                    </button>

                    <!-- Refresh -->
                    <button onclick="refreshData()" class="btn-secondary">
                        <i class="fas fa-redo mr-2"></i>Refresh
                    </button>
                </div>
            </div>

            <!-- Tabulator Table -->
            <div id="mediaTable"></div>

            <!-- Custom Pagination -->
            <div id="customPagination" class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-700" id="paginationInfo">
                    Showing 0 to 0 of 0 entries
                </div>
                <div class="flex space-x-2">
                    <button onclick="changePage(1)" id="firstPageBtn"
                        class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                    <button onclick="changePage(currentPage - 1)" id="prevPageBtn"
                        class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-left"></i>
                    </button>
                    <div id="pageNumbers" class="flex space-x-1">
                        <!-- Page numbers will be inserted here -->
                    </div>
                    <button onclick="changePage(currentPage + 1)" id="nextPageBtn"
                        class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-right"></i>
                    </button>
                    <button onclick="changePage(totalPages)" id="lastPageBtn"
                        class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>
                <div class="text-sm">
                    <select id="pageSizeSelect" class="border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-semibold text-lg" id="previewTitle">Preview</h3>
                <button onclick="hidePreview()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 overflow-auto max-h-[calc(90vh-100px)]">
                <div class="text-center">
                    <img id="previewImage" src="" alt=""
                        class="max-w-full max-h-[70vh] mx-auto rounded-lg hidden">
                    <div id="previewNonImage" class="hidden">
                        <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file text-gray-400 text-6xl"></i>
                        </div>
                        <div class="text-left max-w-md mx-auto">
                            <p><strong>File Name:</strong> <span id="previewFileName"></span></p>
                            <p><strong>Type:</strong> <span id="previewFileType"></span></p>
                            <p><strong>Size:</strong> <span id="previewFileSize"></span></p>
                            <p><strong>Uploaded:</strong> <span id="previewFileDate"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Base Tabulator container */
        #mediaTable {
            border: none !important;
            background: transparent !important;
            min-height: 400px;
        }

        /* Table holder - makes it fill available space */
        .tabulator-tableholder {
            background: transparent !important;
            border: none !important;
        }

        /* Header styling */
        .tabulator .tabulator-header {
            border: none !important;
            border-bottom: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
            font-weight: 600;
            color: #374151;
        }

        .tabulator .tabulator-col {
            background-color: #f9fafb !important;
            border-right: 1px solid #e5e7eb !important;
            padding: 12px 8px !important;
        }

        .tabulator .tabulator-col:last-child {
            border-right: none !important;
        }

        /* Row styling */
        .tabulator-row {
            border-bottom: 1px solid #f3f4f6 !important;
            transition: background-color 0.2s ease;
        }

        .tabulator-row.tabulator-selectable:hover {
            background-color: #f9fafb !important;
        }

        .tabulator-row.tabulator-selected {
            background-color: #e0e7ff !important;
        }

        /* Cell styling */
        .tabulator-cell {
            padding: 12px 8px !important;
            border-right: 1px solid #f3f4f6 !important;
            vertical-align: middle !important;
        }

        .tabulator-cell:last-child {
            border-right: none !important;
        }

        /* Remove default Tabulator borders */
        .tabulator,
        .tabulator-table,
        .tabulator-header-contents,
        .tabulator-headers {
            border: none !important;
        }

        /* Hide Tabulator's default pagination */
        .tabulator-footer {
            display: none !important;
        }

        /* Custom pagination styling */
        #customPagination {
            padding: 16px 0;
            border-top: 1px solid #e5e7eb;
        }

        .page-number {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .page-number:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        .page-number.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .page-number.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Loading state */
        #loadingState {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Global variables for pagination
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 10;
        let totalItems = 0;
        let mediaTable = null;
        let currentSort = 'created_at_desc';
        let currentSearch = '';

        // Configure Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Media module initialized');

            // Initialize Tabulator table
            initializeMediaTable();

            // Load initial data
            loadMediaData();

            // Setup event listeners
            setupEventListeners();

            // Load statistics (optional)
            // loadStatistics();
        });

        // Setup event listeners
        function setupEventListeners() {
            // File upload
            const fileInput = document.getElementById('fileInput');
            const dropzone = document.getElementById('dropzone');

            fileInput.addEventListener('change', handleFileSelect);

            // Drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropzone.classList.add('border-indigo-400', 'bg-indigo-50');
            }

            function unhighlight(e) {
                dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
            }

            dropzone.addEventListener('drop', handleDrop, false);

            // Bulk actions
            document.getElementById('bulkDeleteBtn').addEventListener('click', confirmBulkDelete);

            // Search with debounce
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value;
                    currentPage = 1;
                    loadMediaData();
                }, 500);
            });

            // Sort options
            document.querySelectorAll('.sort-option').forEach(option => {
                option.addEventListener('click', function() {
                    currentSort = this.getAttribute('data-sort');
                    currentPage = 1;

                    // Update sort menu UI
                    document.querySelectorAll('.sort-option').forEach(opt => {
                        const icon = opt.querySelector('.fas.fa-check');
                        if (opt.getAttribute('data-sort') === currentSort) {
                            icon.classList.remove('opacity-0');
                            icon.classList.add('text-indigo-600');
                        } else {
                            icon.classList.add('opacity-0');
                            icon.classList.remove('text-indigo-600');
                        }
                    });

                    // Hide sort menu
                    document.getElementById('sortMenu').classList.add('hidden');

                    // Load data with new sort
                    loadMediaData();
                });
            });

            // Toggle sort menu
            document.getElementById('sortBtn').addEventListener('click', function(e) {
                e.stopPropagation();
                document.getElementById('sortMenu').classList.toggle('hidden');
            });

            // Page size change
            document.getElementById('pageSizeSelect').addEventListener('change', function() {
                perPage = parseInt(this.value);
                currentPage = 1;
                loadMediaData();
            });

            // Top upload button
            document.getElementById('topUploadBtn').addEventListener('click', () => {
                fileInput.click();
            });

            // Close modals on outside click
            document.addEventListener('click', function(e) {
                const previewModal = document.getElementById('previewModal');
                if (!previewModal.classList.contains('hidden') && e.target === previewModal) {
                    hidePreview();
                }

                // Close sort menu on outside click
                const sortMenu = document.getElementById('sortMenu');
                const sortBtn = document.getElementById('sortBtn');
                if (!sortMenu.classList.contains('hidden') &&
                    !sortMenu.contains(e.target) &&
                    !sortBtn.contains(e.target)) {
                    sortMenu.classList.add('hidden');
                }
            });

            // Close modals on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const previewModal = document.getElementById('previewModal');
                    if (!previewModal.classList.contains('hidden')) {
                        hidePreview();
                    }
                }
            });
        }

        // Initialize Tabulator table
        function initializeMediaTable() {
            mediaTable = new Tabulator("#mediaTable", {
                data: [],
                layout: "fitColumns",
                maxHeight: "60vh",
                responsiveLayout: "hide",
                selectable: 1,
                columns: [{
                        title: "<input type='checkbox' id='selectAllMedia'>",
                        field: "id",
                        formatter: "rowSelection",
                        titleFormatter: "rowSelection",
                        hozAlign: "center",
                        headerSort: false,
                        width: 50,
                        cssClass: "select-checkbox"
                    },
                    {
                        title: "Preview",
                        field: "thumbnail_url",
                        width: 80,
                        hozAlign: "center",
                        headerSort: false,
                        formatter: function(cell) {
                            const data = cell.getRow().getData();
                            return `
                                <div class="w-12 h-12 mx-auto overflow-hidden rounded-lg bg-gray-100 flex items-center justify-center cursor-pointer hover:opacity-90" onclick="previewMedia(${data.id})">
                                    ${data.mime_type.startsWith('image/') ?
                                        `<img src="${data.thumbnail_url}" alt="${data.alt_text || data.file_name}" class="w-full h-full object-cover">` :
                                        `<i class="fas fa-file text-gray-400 text-xl"></i>`
                                    }
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Name",
                        field: "file_name",
                        widthGrow: 2,
                        formatter: function(cell) {
                            const data = cell.getRow().getData();
                            return `
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 truncate" title="${data.file_name}">${data.file_name}</span>
                                    ${data.alt_text ?
                                        `<span class="text-xs text-gray-500 truncate" title="${data.alt_text}">${data.alt_text}</span>` :
                                        ''
                                    }
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Type",
                        field: "mime_type",
                        width: 120,
                        formatter: function(cell) {
                            const type = cell.getValue();
                            const icon = type.startsWith('image/') ? 'fa-image' : 'fa-file';
                            const color = type.startsWith('image/') ? 'text-blue-500' : 'text-gray-500';
                            return `
                                <div class="flex items-center space-x-2">
                                    <i class="fas ${icon} ${color}"></i>
                                    <span>${type.split('/')[1] || type}</span>
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Size",
                        field: "size_formatted",
                        width: 100,
                        hozAlign: "right",
                        sorter: "number"
                    },
                    {
                        title: "Uploaded",
                        field: "created_at_formatted",
                        width: 150,
                        hozAlign: "center"
                    },
                    {
                        title: "Actions",
                        field: "id",
                        width: 120,
                        hozAlign: "center",
                        headerSort: false,
                        formatter: function(cell) {
                            const id = cell.getValue();
                            return `
                                <div class="flex space-x-2 justify-center">
                                   

                                    <button onclick="deleteMedia(${id})"
                                            class="p-1 text-rose-600 hover:text-rose-900 tooltip"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                rowFormatter: function(row) {
                    const rowEl = row.getElement();
                    rowEl.classList.add('hover:bg-gray-50');
                },
                rowSelectionChanged: function(data, rows) {
                    updateBulkActions(data.length);
                }
            });

            // Select all checkbox
            $(document).on('click', '#selectAllMedia', function() {
                if ($(this).is(':checked')) {
                    mediaTable.selectRow();
                } else {
                    mediaTable.deselectRow();
                }
            });
        }

        // Load media data
        async function loadMediaData() {
            try {
                showLoading(true);

                const [sortBy, sortDir = 'desc'] = currentSort.split('_').length > 2 ? [currentSort.split('_').slice(0,
                        -1).join('_'), currentSort.split('_').pop()] :
                    currentSort.split('_');

                const params = new URLSearchParams({
                    page: currentPage,
                    per_page: perPage,
                    sort_by: sortBy,
                    sort_dir: sortDir,
                    search: currentSearch
                });

                const response = await axios.get(`/admin/media/data?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (response.data.success) {
                    const data = response.data.data;
                    const meta = data.meta;

                    // Update table data
                    mediaTable.setData(data.data);

                    // Update pagination info
                    totalItems = meta.total;
                    totalPages = meta.last_page;
                    updatePaginationInfo(meta);
                    renderPageNumbers();

                    // Update bulk actions
                    updateBulkActions(0);
                }
            } catch (error) {
                console.error('Error loading media:', error);
                toastr.error('Failed to load media files');
            } finally {
                showLoading(false);
            }
        }

        // Update pagination info
        function updatePaginationInfo(meta) {
            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo && meta) {
                paginationInfo.innerHTML = `
                    Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total || 0} entries
                `;
            }

            // Update pagination button states
            updatePaginationButtons();
        }

        // Update pagination buttons
        function updatePaginationButtons() {
            const firstPageBtn = document.getElementById('firstPageBtn');
            const prevPageBtn = document.getElementById('prevPageBtn');
            const nextPageBtn = document.getElementById('nextPageBtn');
            const lastPageBtn = document.getElementById('lastPageBtn');

            // Disable buttons when appropriate
            firstPageBtn.disabled = currentPage === 1;
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages;
            lastPageBtn.disabled = currentPage === totalPages;

            // Update page size selector
            document.getElementById('pageSizeSelect').value = perPage;
        }

        // Render page numbers
        function renderPageNumbers() {
            const pageNumbersDiv = document.getElementById('pageNumbers');
            pageNumbersDiv.innerHTML = '';

            // Always show first page
            addPageButton(1, pageNumbersDiv);

            // Calculate range of pages to show
            let startPage = Math.max(2, currentPage - 2);
            let endPage = Math.min(totalPages - 1, currentPage + 2);

            // Adjust if we're near the start
            if (currentPage <= 3) {
                endPage = Math.min(totalPages - 1, 5);
            }

            // Adjust if we're near the end
            if (currentPage >= totalPages - 2) {
                startPage = Math.max(2, totalPages - 4);
            }

            // Add ellipsis after first page if needed
            if (startPage > 2) {
                addEllipsis(pageNumbersDiv);
            }

            // Add middle pages
            for (let i = startPage; i <= endPage; i++) {
                addPageButton(i, pageNumbersDiv);
            }

            // Add ellipsis before last page if needed
            if (endPage < totalPages - 1) {
                addEllipsis(pageNumbersDiv);
            }

            // Always show last page if there is more than one page
            if (totalPages > 1) {
                addPageButton(totalPages, pageNumbersDiv);
            }
        }

        function addPageButton(pageNum, container) {
            const button = document.createElement('button');
            button.className = `page-number ${pageNum === currentPage ? 'active' : ''}`;
            button.textContent = pageNum;
            button.onclick = () => changePage(pageNum);

            // Disable if it's the current page
            if (pageNum === currentPage) {
                button.disabled = true;
                button.classList.add('disabled');
            }

            container.appendChild(button);
        }

        function addEllipsis(container) {
            const span = document.createElement('span');
            span.className = 'page-number disabled';
            span.textContent = '...';
            span.style.cursor = 'default';
            container.appendChild(span);
        }

        // Change page
        function changePage(page) {
            if (page < 1 || page > totalPages || page === currentPage) {
                return;
            }

            currentPage = page;
            loadMediaData();

            // Scroll to top of table
            const tableContainer = document.querySelector('#mediaTable .tabulator-tableholder');
            if (tableContainer) {
                tableContainer.scrollTop = 0;
            }
        }

        // Show/hide loading
        function showLoading(show) {
            const loadingState = document.getElementById('loadingState');
            if (show) {
                loadingState.classList.remove('hidden');
                loadingState.classList.add('flex', 'flex-col', 'items-center');
            } else {
                loadingState.classList.add('hidden');
                loadingState.classList.remove('flex', 'flex-col', 'items-center');
            }
        }

        // Update bulk actions
        function updateBulkActions(selectedCount) {
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            if (selectedCount > 0) {
                bulkDeleteBtn.classList.remove('hidden');
                bulkDeleteBtn.innerHTML = `<i class="fas fa-trash mr-2"></i>Delete (${selectedCount})`;
            } else {
                bulkDeleteBtn.classList.add('hidden');
            }
        }

        function refreshData() {
            currentPage = 1;
            loadMediaData();
            toastr.info('Data refreshed');
        }

        // Preview media
        async function previewMedia(id) {
            try {
                const response = await axios.get(`/admin/media/${id}`);
                if (response.data.success) {
                    const media = response.data.data;
                    const previewModal = document.getElementById('previewModal');
                    const previewImage = document.getElementById('previewImage');
                    const previewNonImage = document.getElementById('previewNonImage');
                    
                    document.getElementById('previewTitle').textContent = media.file_name;
                    
                    if (media.mime_type.startsWith('image/')) {
                        previewImage.src = media.url;
                        previewImage.classList.remove('hidden');
                        previewNonImage.classList.add('hidden');
                    } else {
                        previewImage.classList.add('hidden');
                        previewNonImage.classList.remove('hidden');
                        document.getElementById('previewFileName').textContent = media.file_name;
                        document.getElementById('previewFileType').textContent = media.mime_type;
                        document.getElementById('previewFileSize').textContent = media.size_formatted;
                        document.getElementById('previewFileDate').textContent = media.created_at_formatted;
                    }
                    
                    previewModal.classList.remove('hidden');
                    previewModal.classList.add('flex');
                }
            } catch (error) {
                toastr.error('Failed to load media details');
            }
        }

        function hidePreview() {
            const previewModal = document.getElementById('previewModal');
            previewModal.classList.add('hidden');
            previewModal.classList.remove('flex');
        }

        // The rest of your functions (handleFileSelect, uploadFiles, editMedia, deleteMedia, etc.)
        // ... [Keep all your existing functions for file handling, uploads, etc.] ...

        // Handle file selection
        function handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                // Show bulk alt text container for multiple files
                if (files.length > 1) {
                    document.getElementById('bulkAltContainer').classList.remove('hidden');
                }
                uploadFiles(files);
            }
            // Reset input
            e.target.value = '';
        }

        // Handle drop
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                uploadFiles(files);
            }
        }

        // Upload files (simplified version)
        async function uploadFiles(files) {
            // Show upload progress
            showUploadProgress(files.length);

            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            // Add alt text if available
            const altText = document.getElementById('bulkAltText')?.value || '';
            if (altText) {
                formData.append('alt_text', altText);
            }

            try {
                const response = await axios.post('/admin/media/upload', formData, {

                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onUploadProgress: function(progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent
                            .total);
                        updateUploadProgress(percentCompleted, progressEvent.loaded, progressEvent.total);
                    }
                });

                if (response.data.success) {
                    toastr.success(`Successfully uploaded ${response.data.data.total_uploaded} file(s)`);

                    // Refresh data
                    refreshData();

                    // Clear bulk alt text
                    document.getElementById('bulkAltText').value = '';
                    document.getElementById('bulkAltContainer').classList.add('hidden');
                }
            } catch (error) {
                console.error('Upload error:', error);
                toastr.error('Upload failed');
            } finally {
                hideUploadProgress();
            }
        }

        // Show upload progress UI
        function showUploadProgress(totalFiles) {
            const progressDiv = document.getElementById('uploadProgress');
            const uploadList = document.getElementById('uploadList');

            progressDiv.classList.remove('hidden');
            uploadList.innerHTML = '';
            document.getElementById('uploadCount').textContent = `0/${totalFiles}`;
            document.getElementById('uploadBar').style.width = '0%';
        }

        function updateUploadProgress(percent, loaded, total) {
            document.getElementById('uploadBar').style.width = `${percent}%`;
            const loadedFormatted = formatBytes(loaded);
            const totalFormatted = formatBytes(total);
            document.getElementById('uploadCount').textContent = `${loadedFormatted}/${totalFormatted}`;
        }

        function hideUploadProgress() {
            setTimeout(() => {
                document.getElementById('uploadProgress').classList.add('hidden');
            }, 1000);
        }

        // Edit media
        async function editMedia(id) {
            try {
                // First get media details
                const response = await axios.get(`/admin/media/${id}`, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (response.data.success) {
                    const media = response.data.data;

                    Swal.fire({
                        title: 'Edit Media',
                        html: `
                            <div class="text-left">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">File Name</label>
                                    <input type="text" id="editFileName" class="swal2-input" value="${media.file_name}" readonly>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alternative Text</label>
                                    <input type="text" id="editAltText" class="swal2-input" value="${media.alt_text || ''}" placeholder="Describe this image...">
                                </div>
                                ${media.url ? `
                                                            <div class="mb-4 text-center">
                                                                <img src="${media.url}" alt="${media.alt_text || media.file_name}" class="max-w-full max-h-48 mx-auto rounded-lg">
                                                            </div>
                                                        ` : ''}
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Update',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            const altText = document.getElementById('editAltText').value;
                            return {
                                alt_text: altText.trim()
                            };
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const updateResponse = await axios.put(`/admin/media/${id}`, result
                                    .value, {
                                        headers: {
                                            'Content-Type': 'multipart/form-data',
                                        },
                                    });

                                if (updateResponse.data.success) {
                                    toastr.success(updateResponse.data.message);
                                    refreshData();
                                }
                            } catch (error) {
                                toastr.error('Failed to update media');
                            }
                        }
                    });
                }
            } catch (error) {
                toastr.error('Failed to load media details');
            }
        }

        // Delete media
        async function deleteMedia(id) {
            const result = await Swal.fire({
                title: 'Delete Media?',
                text: "This will permanently delete the media file. This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                const response = await axios.delete(`/admin/media/${id}`, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                });

                    if (response.data.success) {
                        toastr.success(response.data.message);
                        refreshData();
                    } else {
                        toastr.error(response.data.message || 'Failed to delete media');
                    }
                } catch (error) {
                    if (error.response?.status === 400) {
                        toastr.error(error.response.data.message || 'Cannot delete media in use');
                    } else {
                        toastr.error('Failed to delete media');
                    }
                }
            }
        }

        // Confirm bulk delete
        async function confirmBulkDelete() {
            const selectedRows = mediaTable.getSelectedRows();
            const selectedIds = selectedRows.map(row => row.getData().id);

            if (selectedIds.length === 0) {
                toastr.warning('Please select media files to delete');
                return;
            }

            const result = await Swal.fire({
                title: 'Delete Selected Media?',
                html: `You are about to delete <strong>${selectedIds.length}</strong> media file(s).<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Yes, delete ${selectedIds.length} file(s)`,
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.post('/admin/media/bulk-delete', {
                        ids: selectedIds
                    }, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    });

                    if (response.data.success) {
                        const deletedCount = response.data.data.deleted_count;
                        toastr.success(`Successfully deleted ${deletedCount} file(s)`);
                        refreshData();
                    }
                } catch (error) {
                    toastr.error('Failed to delete media files');
                }
            }
        }

        // Utility functions
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function clearBulkAltText() {
            document.getElementById('bulkAltText').value = '';
        }
    </script>
@endpush
