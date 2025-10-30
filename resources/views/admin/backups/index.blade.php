@can('admin-access')
@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <h1 class="page-title">💾 Backup Management</h1>
    <p class="page-subtitle">Manage your system backups and data protection.</p>
</div>
<div class="backup-actions mb-4">
    <div class="row">
        <div class="col-md-8">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBackupModal"><i class="bi bi-plus-circle me-2"></i>Create Backup</button>
                <button type="button" class="btn btn-warning" onclick="cleanupBackups()" data-original-text="<i class='bi bi-trash me-2'></i>Cleanup Old Backups"><i class="bi bi-trash me-2"></i>Cleanup Old Backups</button>
                <button type="button" class="btn btn-info" onclick="refreshBackups()" data-original-text="<i class='bi bi-arrow-clockwise me-2'></i>Refresh"><i class="bi bi-arrow-clockwise me-2"></i>Refresh</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="backup-stats text-end">
                <small class="text-muted"><span id="total-backups">-</span> total backups | <span id="total-size">-</span> total size</small>
            </div>
        </div>
    </div>
</div>
<ul class="nav nav-tabs mb-4" id="backupTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab"><i class="bi bi-hand-index me-2"></i>Manual Backups</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily" type="button" role="tab"><i class="bi bi-calendar-day me-2"></i>Daily Backups</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">
            <i class="bi bi-calendar-month me-2"></i>Monthly Backups
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly" type="button" role="tab">
            <i class="bi bi-calendar-year me-2"></i>Yearly Backups
        </button>
    </li>
</ul>

<!-- Backup Content -->
<div class="tab-content" id="backupTabsContent">
    @foreach(['manual', 'daily', 'monthly', 'yearly'] as $type)
    <div class="tab-pane fade {{ $type === 'manual' ? 'show active' : '' }}" id="{{ $type }}" role="tabpanel">
        <div class="backup-list">
            @if(count($groupedBackups[$type]) > 0)
                <div class="table-responsive">
                    <table class="table-modern table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Filename</th>
                                <th>Size</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedBackups[$type] as $backup)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $backup['category'] === 'database' ? 'primary' : 'success' }}">
                                        {{ ucfirst($backup['category']) }}
                                    </span>
                                </td>
                                <td>
                                    <code>{{ $backup['filename'] }}</code>
                                </td>
                                <td>{{ \App\Services\BackupService::formatBytes($backup['size']) }}</td>
                                <td>{{ \Carbon\Carbon::parse($backup['created_at'])->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.backups.download', ['filename' => $backup['filename'], 'category' => $backup['category']]) }}" 
                                           class="btn btn-outline-modern btn-sm">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger-modern btn-sm" 
                                                onclick="deleteBackup('{{ $backup['filename'] }}', '{{ $backup['category'] }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="text-muted mt-3">No {{ $type }} backups found</h5>
                    <p class="text-muted">Create your first backup to get started.</p>
                </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.backups.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="backupType" class="form-label-modern">Backup Type</label>
                        <select class="form-control-modern" id="backupType" name="type" required>
                            <option value="complete">Complete Backup (Database + Files)</option>
                            <option value="database">Database Only</option>
                            <option value="files">Files Only</option>
                        </select>
                    </div>
                    <div class="alert-modern alert-info-modern">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Complete backup</strong> includes both database and files. This is recommended for full system recovery.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Backup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this backup? This action cannot be undone.</p>
                <div class="alert-modern alert-warning-modern">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Deleting this backup will permanently remove it from the system.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash me-2"></i>Delete Backup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.backup-actions {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.backup-stats {
    padding-top: 0.5rem;
}

.nav-tabs .nav-link {
    border: none;
    color: #666;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #800000;
    border-bottom: 2px solid #800000;
    background: none;
}

.backup-list {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #333;
}

.table td {
    border: none;
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-size: 0.75rem;
}

code {
    font-size: 0.85rem;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Button improvements */
.btn {
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn:not(:disabled):hover {
    transform: translateY(-1px);
}

.btn:not(:disabled):active {
    transform: translateY(0);
}

/* Loading state */
.btn.loading {
    pointer-events: none;
}

/* Prevent double clicks */
.btn-group .btn {
    min-width: 80px;
}

/* Alert positioning */
.alert.position-fixed {
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Modal improvements */
.modal-content {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}
</style>

<script>
let deleteFilename = '';
let deleteCategory = '';
let isLoading = false;
let lastClickTime = 0;
const CLICK_DEBOUNCE_TIME = 1000; // 1 second

// Utility function to show loading state
function setButtonLoading(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Loading...';
    } else {
        button.disabled = false;
        button.innerHTML = button.getAttribute('data-original-text');
    }
}

// Utility function to show alerts
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function deleteBackup(filename, category) {
    deleteFilename = filename;
    deleteCategory = category;
    
    // Use Bootstrap 5 modal API instead of jQuery
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteBackupModal'));
    deleteModal.show();
}

function cleanupBackups() {
    const currentTime = Date.now();
    if (isLoading || (currentTime - lastClickTime) < CLICK_DEBOUNCE_TIME) return;
    
    lastClickTime = currentTime;
    
    if (confirm('Are you sure you want to cleanup old backups? This will delete backups beyond the retention policy.')) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.setAttribute('data-original-text', originalText);
        
        setButtonLoading(button, true);
        isLoading = true;
        
        // Create AbortController for timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
        
        fetch('{{ route("admin.backups.cleanup") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            signal: controller.signal
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('Old backups cleaned up successfully!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Cleanup failed: ' + (data.message || 'Unknown error'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.name === 'AbortError') {
                showAlert('Request timed out. Please try again.', 'warning');
            } else {
                showAlert('Cleanup failed: ' + error.message, 'danger');
            }
        })
        .finally(() => {
            clearTimeout(timeoutId);
            setButtonLoading(button, false);
            isLoading = false;
        });
    }
}

function refreshBackups() {
    const currentTime = Date.now();
    if (isLoading || (currentTime - lastClickTime) < CLICK_DEBOUNCE_TIME) return;
    
    lastClickTime = currentTime;
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.setAttribute('data-original-text', originalText);
    
    setButtonLoading(button, true);
    isLoading = true;
    
    setTimeout(() => {
        location.reload();
    }, 500);
}

function loadBackupStats() {
    // Create AbortController for timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
    
    fetch('{{ route("admin.backups.stats") }}', {
        headers: {
            'Accept': 'application/json'
        },
        signal: controller.signal
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('total-backups').textContent = data.total || 0;
        document.getElementById('total-size').textContent = formatBytes(data.total_size || 0);
    })
    .catch(error => {
        console.error('Error loading stats:', error);
        if (error.name === 'AbortError') {
            document.getElementById('total-backups').textContent = 'Timeout';
            document.getElementById('total-size').textContent = 'Timeout';
        } else {
            document.getElementById('total-backups').textContent = 'Error';
            document.getElementById('total-size').textContent = 'Error';
        }
    })
    .finally(() => {
        clearTimeout(timeoutId);
    });
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Global error handler
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    showAlert('An error occurred. Please refresh the page.', 'danger');
});

// Global unhandled promise rejection handler
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
    showAlert('An error occurred. Please refresh the page.', 'danger');
    e.preventDefault();
});

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Prevent multiple initializations
    if (window.backupSystemInitialized) return;
    window.backupSystemInitialized = true;
    
    // Load stats
    loadBackupStats();
    
    // Setup delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (isLoading) return;
            
            const button = this;
            const originalText = button.innerHTML;
            button.setAttribute('data-original-text', originalText);
            
            setButtonLoading(button, true);
            isLoading = true;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.backups.destroy") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            const filenameField = document.createElement('input');
            filenameField.type = 'hidden';
            filenameField.name = 'filename';
            filenameField.value = deleteFilename;
            
            const categoryField = document.createElement('input');
            categoryField.type = 'hidden';
            categoryField.name = 'category';
            categoryField.value = deleteCategory;
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            form.appendChild(filenameField);
            form.appendChild(categoryField);
            
            document.body.appendChild(form);
            form.submit();
        });
    }
    
    // Setup modal event listeners
    const createBackupModal = document.getElementById('createBackupModal');
    if (createBackupModal) {
        createBackupModal.addEventListener('hidden.bs.modal', function() {
            // Reset form when modal is closed
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
        });
    }
    
    const deleteBackupModal = document.getElementById('deleteBackupModal');
    if (deleteBackupModal) {
        deleteBackupModal.addEventListener('hidden.bs.modal', function() {
            // Reset delete variables when modal is closed
            deleteFilename = '';
            deleteCategory = '';
        });
    }
});
</script>

@endsection
@endcan
