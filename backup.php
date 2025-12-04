<?php
require_once 'backend/config/auth_check.php';
require_once 'backend/config/db.php';

$page_title = "Database Backup";
$additional_css = ['frontend/assets/css/dashboard.css'];
include 'frontend/assets/includes/header.php';
?>

<div class="dashboard-container">
    <?php include 'frontend/assets/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'frontend/assets/includes/topbar.php'; ?>
        
        
        <div class="dashboard-content">
            <div class="card">
                <div class="card-header">
                    <h3>Create Database Backup</h3>
                </div>
                <div class="card-body">
                    <p>Create a backup of your database to ensure data safety and prevent data loss.</p>
                    <button class="btn btn-primary" onclick="createBackup()">
                        <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#settings"></use></svg>
                        Create Backup
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Restore Database</h3>
                </div>
                <div class="card-body">
                    <p>Restore database from a previous backup file. <strong>Warning:</strong> This will replace all current data.</p>
                    <div class="form-group">
                        <label for="backupFile">Select Backup File</label>
                        <input type="file" id="backupFile" accept=".sql" style="margin-bottom: 15px;">
                    </div>
                    <button class="btn btn-warning" onclick="restoreBackup()">
                        <svg class="icon" style="width: 16px; height: 16px; margin-right: 8px;"><use href="frontend/assets/svg/icons.svg#settings"></use></svg>
                        Restore Backup
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Backup History</h3>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>File Name</th>
                                <th>Size</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="backupHistory">
                            <tr>
                                <td colspan="4" class="text-center loading">
                                    <div class="spinner"></div>
                                    Loading backup history...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function createBackup() {
    if (confirm('Are you sure you want to create a database backup?')) {
        fetch('backend/admin/backup/create_backup.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup created successfully!');
                loadBackupHistory();
            } else {
                alert('Error creating backup: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating backup');
        });
    }
}

function restoreBackup() {
    const fileInput = document.getElementById('backupFile');
    if (!fileInput.files[0]) {
        alert('Please select a backup file');
        return;
    }
    
    if (confirm('Are you sure you want to restore the database? This will overwrite current data.')) {
        const formData = new FormData();
        formData.append('backup_file', fileInput.files[0]);
        
        fetch('backend/admin/backup/restore_backup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Database restored successfully!');
            } else {
                alert('Error restoring backup: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error restoring backup');
        });
    }
}

function loadBackupHistory() {
    fetch('backend/admin/backup/get_backup_history.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('backupHistory');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No backups found</td></tr>';
                return;
            }
            
            data.forEach(backup => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td>${backup.date}</td>
                    <td>${backup.filename}</td>
                    <td>${backup.size}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="downloadBackup('${backup.filename}')">Download</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteBackup('${backup.filename}')">Delete</button>
                    </td>
                `;
            });
        })
        .catch(error => {
            console.error('Error loading backup history:', error);
        });
}

function downloadBackup(filename) {
    window.open(`backend/admin/backup/download_backup.php?file=${filename}`, '_blank');
}

function deleteBackup(filename) {
    if (confirm('Are you sure you want to delete this backup?')) {
        fetch('backend/admin/backup/delete_backup.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ filename: filename })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup deleted successfully!');
                loadBackupHistory();
            } else {
                alert('Error deleting backup: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting backup');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadBackupHistory();
});
</script>

<?php include 'frontend/assets/includes/footer.php'; ?>
