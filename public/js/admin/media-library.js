// ============================
// UNIVERSAL API CLIENT
// ============================
class ApiClient {
    constructor(baseURL = '/api/admin') {
        this.baseURL = baseURL;
        this.token = window.adminToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    getHeaders(contentType = 'application/json') {
        const headers = {
            'Accept': 'application/json',
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        if (contentType && contentType !== 'multipart/form-data') {
            headers['Content-Type'] = contentType;
        }

        return headers;
    }

    async request(method, endpoint, data = null, params = null) {
        const url = new URL(`${this.baseURL}${endpoint}`, window.location.origin);
        
        if (params) {
            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined) {
                    url.searchParams.append(key, params[key]);
                }
            });
        }

        const options = {
            method: method,
            headers: this.getHeaders(),
            credentials: 'same-origin'
        };

        if (data) {
            if (data instanceof FormData) {
                delete options.headers['Content-Type']; // Let browser set it
                options.body = data;
            } else {
                options.body = JSON.stringify(data);
            }
        }

        try {
            const response = await fetch(url.toString(), options);
            
            if (response.status === 401) {
                // Token expired, redirect to login
                window.location.href = '/admin/login';
                return;
            }

            if (response.status === 422) {
                const errors = await response.json();
                throw { type: 'validation', errors: errors.errors };
            }

            if (!response.ok) {
                const error = await response.json();
                throw { type: 'api', message: error.message || 'Request failed' };
            }

            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    get(endpoint, params = null) {
        return this.request('GET', endpoint, null, params);
    }

    post(endpoint, data = null, params = null) {
        return this.request('POST', endpoint, data, params);
    }

    put(endpoint, data = null, params = null) {
        return this.request('PUT', endpoint, data, params);
    }

    delete(endpoint, data = null, params = null) {
        return this.request('DELETE', endpoint, data, params);
    }
}

// ============================
// MEDIA API SERVICE
// ============================
class MediaApiService {
    constructor() {
        this.api = new ApiClient();
    }

    // Get media list
    getMedia(params = {}) {
        const defaultParams = {
            per_page: window.mediaConfig.perPage,
            sort: window.mediaConfig.currentSort,
            search: window.mediaConfig.currentSearch
        };
        
        return this.api.get('/media', { ...defaultParams, ...params });
    }

    // Upload files
    uploadFiles(files) {
        const formData = new FormData();
        Array.from(files).forEach(file => {
            formData.append('files[]', file);
        });
        
        return this.api.post('/media/upload', formData);
    }

    // Delete single media
    deleteMedia(id) {
        return this.api.delete(`/media/${id}`);
    }

    // Bulk delete
    bulkDelete(ids) {
        return this.api.post('/media/bulk-delete', { ids });
    }

    // Rename media
    renameMedia(id, name) {
        return this.api.put(`/media/${id}`, { name });
    }
}

// ============================
// MEDIA LIBRARY APP
// ============================
class MediaLibrary {
    constructor() {
        this.api = new MediaApiService();
        this.table = null;
        this.uploadQueue = [];
        this.uploading = false;
        
        this.init();
    }

    init() {
        this.initTable();
        this.initUpload();
        this.initSearch();
        this.initSort();
        this.initBulkActions();
        this.initExport();
        this.initColumnVisibility();
    }

    // ============================
    // TABULATOR INITIALIZATION
    // ============================
    initTable() {
        this.table = new Tabulator("#mediaTable", {
            ajaxURL: window.mediaConfig.baseUrl,
            ajaxParams: {
                per_page: window.mediaConfig.perPage,
                sort: window.mediaConfig.currentSort,
                search: window.mediaConfig.currentSearch
            },
            ajaxResponse: (url, params, response) => {
                // Update pagination info
                this.updatePaginationInfo(response.meta);
                return response.data;
            },
            ajaxError: (xhr, textStatus, errorThrown) => {
                this.showToast('error', 'Failed to load media: ' + errorThrown);
            },
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "remote",
            paginationSize: window.mediaConfig.perPage,
            paginationSizeSelector: [10, 25, 50, 100],
            movableColumns: true,
            columns: [
                {
                    title: "<input type='checkbox' id='selectAll'>",
                    field: "id",
                    formatter: "rowSelection",
                    titleFormatter: "rowSelection",
                    hozAlign: "center",
                    headerSort: false,
                    width: 50,
                    responsive: 0
                },
                {
                    title: "Preview",
                    field: "thumbnail_url",
                    width: 100,
                    hozAlign: "center",
                    responsive: 0,
                    formatter: (cell) => {
                        const row = cell.getRow().getData();
                        if (row.is_image) {
                            return `<div class="w-16 h-12 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                <img src="${row.thumbnail_url || row.url}" alt="${row.name}" class="max-w-full max-h-full object-contain">
                            </div>`;
                        }
                        return `<div class="w-16 h-12 bg-gray-100 rounded flex items-center justify-center">
                            <i class="fas fa-file text-gray-400 text-xl"></i>
                        </div>`;
                    }
                },
                {
                    title: "Name",
                    field: "name",
                    width: 300,
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search name...",
                    headerFilterFunc: "like",
                    formatter: (cell) => {
                        const row = cell.getRow().getData();
                        return `<div class="flex flex-col">
                            <span class="font-medium text-gray-900">${row.name}</span>
                            <span class="text-sm text-gray-500">${row.file_name}</span>
                        </div>`;
                    },
                    cellClick: (e, cell) => {
                        const row = cell.getRow().getData();
                        this.showPreview(row);
                    }
                },
                {
                    title: "Type",
                    field: "mime_type",
                    width: 120,
                    responsive: 1,
                    formatter: (cell) => {
                        const mime = cell.getValue();
                        const type = mime.split('/')[0];
                        const icon = type === 'image' ? 'fa-image' : 
                                    type === 'application' ? 'fa-file-alt' : 
                                    'fa-file';
                        const color = type === 'image' ? 'text-blue-500' : 'text-gray-500';
                        
                        return `<div class="flex items-center space-x-2">
                            <i class="fas ${icon} ${color}"></i>
                            <span>${mime}</span>
                        </div>`;
                    }
                },
                {
                    title: "Size",
                    field: "size_formatted",
                    width: 100,
                    hozAlign: "right",
                    responsive: 1,
                    sorter: "number",
                    sorterParams: { field: "size" }
                },
                {
                    title: "Dimensions",
                    field: "dimensions",
                    width: 120,
                    responsive: 2,
                    formatter: (cell) => {
                        const dim = cell.getValue();
                        return dim ? `<span class="px-2 py-1 bg-gray-100 rounded text-sm">${dim}</span>` : '-';
                    }
                },
                {
                    title: "Uploaded",
                    field: "created_at_formatted",
                    width: 150,
                    responsive: 2,
                    sorter: "string",
                    sorterParams: { field: "created_at" }
                },
                {
                    title: "Actions",
                    field: "id",
                    width: 150,
                    hozAlign: "center",
                    responsive: 0,
                    headerSort: false,
                    formatter: (cell) => {
                        const id = cell.getValue();
                        const row = cell.getRow().getData();
                        
                        return `<div class="flex space-x-2 justify-center">
                            <button onclick="mediaLibrary.downloadFile(${id})" class="text-emerald-600 hover:text-emerald-900" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button onclick="mediaLibrary.renameFile(${id}, '${row.name.replace(/'/g, "\\'")}')" class="text-blue-600 hover:text-blue-900" title="Rename">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="mediaLibrary.deleteFile(${id})" class="text-rose-600 hover:text-rose-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                    }
                }
            ],
            rowFormatter: (row) => {
                row.getElement().classList.add('hover:bg-gray-50');
            }
        });
    }

    // ============================
    // UPLOAD FUNCTIONALITY
    // ============================
    initUpload() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const browseBtn = document.getElementById('browseFilesBtn');
        const topUploadBtn = document.getElementById('topUploadBtn');

        // Browse files
        browseBtn.addEventListener('click', () => fileInput.click());
        topUploadBtn.addEventListener('click', () => fileInput.click());
        
        // File input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleFiles(e.target.files);
            }
        });

        // Drag and drop
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-indigo-400', 'bg-indigo-50');
        });

        dropzone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
            
            if (e.dataTransfer.files.length > 0) {
                this.handleFiles(e.dataTransfer.files);
            }
        });
    }

    handleFiles(files) {
        const validFiles = Array.from(files).filter(file => {
            const maxSize = 3 * 1024 * 1024; // 3MB
            const validTypes = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 
                'image/webp', 'image/svg+xml', 'application/pdf',
                'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain'
            ];

            if (file.size > maxSize) {
                this.showToast('error', `${file.name} exceeds 3MB limit`);
                return false;
            }

            if (!validTypes.includes(file.type)) {
                this.showToast('error', `${file.name} has unsupported file type`);
                return false;
            }

            return true;
        });

        if (validFiles.length === 0) {
            this.showToast('warning', 'No valid files to upload');
            return;
        }

        this.startUpload(validFiles);
    }

    async startUpload(files) {
        const progressSection = document.getElementById('uploadProgress');
        const uploadBar = document.getElementById('uploadBar');
        const uploadCount = document.getElementById('uploadCount');
        const uploadList = document.getElementById('uploadList');

        // Show progress section
        progressSection.classList.remove('hidden');
        uploadList.innerHTML = '';
        
        // Add files to list
        files.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
            item.innerHTML = `
                <div class="flex items-center space-x-3">
                    <i class="fas fa-file text-gray-400"></i>
                    <span class="text-sm truncate max-w-xs">${file.name}</span>
                </div>
                <div class="file-status text-sm" data-index="${index}">
                    <span class="text-gray-500">Waiting...</span>
                </div>
            `;
            uploadList.appendChild(item);
        });

        uploadCount.textContent = `0/${files.length}`;
        uploadBar.style.width = '0%';

        try {
            this.showToast('info', `Uploading ${files.length} file(s)...`);
            
            const response = await this.api.uploadFiles(files);
            
            // Update UI
            uploadBar.style.width = '100%';
            uploadCount.textContent = `${files.length}/${files.length}`;
            
            // Update status for each file
            files.forEach((file, index) => {
                const statusEl = document.querySelector(`.file-status[data-index="${index}"]`);
                if (statusEl) {
                    statusEl.innerHTML = '<span class="text-emerald-600"><i class="fas fa-check mr-1"></i>Uploaded</span>';
                }
            });

            this.showToast('success', 'Files uploaded successfully');
            
            // Reload table after 1 second
            setTimeout(() => {
                this.table.setData();
                progressSection.classList.add('hidden');
                document.getElementById('fileInput').value = '';
            }, 1000);

        } catch (error) {
            this.showToast('error', 'Upload failed: ' + (error.message || 'Unknown error'));
            uploadBar.style.backgroundColor = '#ef4444';
        }
    }

    // ============================
    // SEARCH FUNCTIONALITY
    // ============================
    initSearch() {
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;

        searchInput.addEventListener('keyup', (e) => {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                window.mediaConfig.currentSearch = e.target.value;
                this.table.setData();
            }, 500);
        });
    }

    // ============================
    // SORT FUNCTIONALITY
    // ============================
    initSort() {
        const sortBtn = document.getElementById('sortBtn');
        const sortMenu = document.getElementById('sortMenu');
        const sortOptions = document.querySelectorAll('.sort-option');

        // Toggle sort menu
        sortBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sortMenu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!sortMenu.contains(e.target) && e.target !== sortBtn) {
                sortMenu.classList.add('hidden');
            }
        });

        // Handle sort selection
        sortOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                const sortValue = option.getAttribute('data-sort');
                
                // Update all checkmarks
                sortOptions.forEach(opt => {
                    const icon = opt.querySelector('i.fa-check');
                    icon.classList.add('opacity-0');
                });
                
                // Show checkmark for selected
                const icon = option.querySelector('i.fa-check');
                icon.classList.remove('opacity-0');
                
                // Update sort
                window.mediaConfig.currentSort = sortValue;
                this.table.setData();
                
                // Update button text
                const sortText = option.textContent.trim();
                sortBtn.innerHTML = `<i class="fas fa-sort mr-2"></i>${sortText}`;
                
                // Close menu
                sortMenu.classList.add('hidden');
            });
        });
    }

    // ============================
    // BULK ACTIONS
    // ============================
    initBulkActions() {
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

        bulkDeleteBtn.addEventListener('click', () => {
            const selectedRows = this.table.getSelectedRows();
            const selectedIds = selectedRows.map(row => row.getData().id);

            if (selectedIds.length === 0) {
                this.showToast('warning', 'Please select at least one file');
                return;
            }

            this.confirmBulkDelete(selectedIds);
        });
    }

    async confirmBulkDelete(ids) {
        const result = await Swal.fire({
            title: `Delete ${ids.length} file(s)?`,
            html: `<div class="text-left">
                <p class="text-gray-600">You are about to delete ${ids.length} file(s).</p>
                <div class="mt-4 p-3 bg-rose-50 border border-rose-200 rounded-lg">
                    <p class="text-sm text-rose-700">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        This action cannot be undone.
                    </p>
                </div>
            </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Delete ${ids.length} file(s)`,
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
        });

        if (result.isConfirmed) {
            try {
                await this.api.bulkDelete(ids);
                this.showToast('success', `${ids.length} file(s) deleted successfully`);
                this.table.deselectRow();
                this.table.setData();
            } catch (error) {
                this.showToast('error', 'Delete failed: ' + error.message);
            }
        }
    }

    // ============================
    // EXPORT FUNCTIONALITY
    // ============================
    initExport() {
        const exportOptions = document.querySelectorAll('.export-option');

        exportOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                const format = option.getAttribute('data-export');
                
                switch (format) {
                    case 'csv':
                        this.table.download("csv", "media.csv");
                        break;
                    case 'xlsx':
                        this.table.download("xlsx", "media.xlsx", { sheetName: "Media" });
                        break;
                    case 'print':
                        window.print();
                        break;
                }
            });
        });
    }

    // ============================
    // COLUMN VISIBILITY
    // ============================
    initColumnVisibility() {
        const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
        const columnMenu = document.createElement('div');
        columnMenu.className = 'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden';
        columnMenu.id = 'columnMenu';

        const columns = this.table.getColumnDefinitions();

        columns.forEach((column, index) => {
            if (index === 0) return; // skip checkbox column

            const field = column.field;
            const col = this.table.getColumn(field);
            
            if (!col) return;

            const columnBtn = document.createElement('button');
            columnBtn.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
            columnBtn.innerHTML = `
                <input type="checkbox" class="mr-2" ${col.isVisible() ? 'checked' : ''}>
                ${column.title}
            `;

            columnBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                e.preventDefault();

                col.toggle();

                setTimeout(() => {
                    const checkbox = columnBtn.querySelector('input');
                    checkbox.checked = col.isVisible();
                }, 10);
            });

            columnMenu.appendChild(columnBtn);
        });

        // Toggle menu
        columnVisibilityBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            columnMenu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!columnMenu.contains(e.target) && e.target !== columnVisibilityBtn) {
                columnMenu.classList.add('hidden');
            }
        });

        columnVisibilityBtn.parentElement.appendChild(columnMenu);
    }

    // ============================
    // MEDIA ACTIONS
    // ============================
    async deleteFile(id) {
        const result = await Swal.fire({
            title: 'Delete File?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
        });

        if (result.isConfirmed) {
            try {
                await this.api.deleteMedia(id);
                this.showToast('success', 'File deleted successfully');
                this.table.setData();
            } catch (error) {
                this.showToast('error', 'Delete failed: ' + error.message);
            }
        }
    }

    async renameFile(id, currentName) {
        const { value: newName } = await Swal.fire({
            title: 'Rename File',
            input: 'text',
            inputLabel: 'New name',
            inputValue: currentName,
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'Name is required';
                }
                if (value.length > 255) {
                    return 'Name must be less than 255 characters';
                }
            }
        });

        if (newName) {
            try {
                await this.api.renameMedia(id, newName);
                this.showToast('success', 'File renamed successfully');
                this.table.setData();
            } catch (error) {
                this.showToast('error', 'Rename failed: ' + error.message);
            }
        }
    }

    downloadFile(id) {
        const row = this.table.getRow(id).getData();
        const link = document.createElement('a');
        link.href = row.url;
        link.download = row.file_name;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    showPreview(row) {
        Swal.fire({
            title: row.name,
            html: `<div class="text-center">
                ${row.is_image ? 
                    `<img src="${row.url}" alt="${row.name}" class="max-w-full max-h-96 mx-auto rounded-lg">` : 
                    `<div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                        <i class="fas fa-file text-gray-400 text-6xl"></i>
                    </div>`
                }
                <div class="mt-4 text-left">
                    <p><strong>File Name:</strong> ${row.file_name}</p>
                    <p><strong>Type:</strong> ${row.mime_type}</p>
                    <p><strong>Size:</strong> ${row.size_formatted}</p>
                    ${row.dimensions ? `<p><strong>Dimensions:</strong> ${row.dimensions}</p>` : ''}
                    <p><strong>Uploaded:</strong> ${row.created_at_formatted}</p>
                </div>
            </div>`,
            width: 600,
            showConfirmButton: false,
            showCloseButton: true
        });
    }

    // ============================
    // UTILITY FUNCTIONS
    // ============================
    updatePaginationInfo(meta) {
        const paginationInfo = document.getElementById('paginationInfo');
        if (paginationInfo) {
            const start = (meta.current_page - 1) * meta.per_page + 1;
            const end = Math.min(meta.current_page * meta.per_page, meta.total);
            paginationInfo.innerHTML = `
                Showing ${start} to ${end} of ${meta.total} files
                ${meta.last_page > 1 ? `(Page ${meta.current_page} of ${meta.last_page})` : ''}
            `;
        }
    }

    showToast(type, message) {
        if (window.toastr) {
            toastr[type](message);
        } else {
            alert(message);
        }
    }

    // Refresh table
    refresh() {
        this.table.setData();
    }
}

// ============================
// INITIALIZE APP
// ============================
document.addEventListener('DOMContentLoaded', function() {
    window.mediaLibrary = new MediaLibrary();
});