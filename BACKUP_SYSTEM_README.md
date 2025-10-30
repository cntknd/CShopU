# Backup System Documentation

## Overview

This Laravel application now includes a comprehensive backup system that provides:

- **Automatic Backups**: Daily, monthly, and yearly scheduled backups
- **Manual Backups**: On-demand backup creation through the admin interface
- **Database Backups**: Complete MySQL database dumps with compression
- **File Backups**: Storage and public directory backups in ZIP format
- **Retention Management**: Automatic cleanup of old backups based on configurable retention policies
- **Admin Interface**: Easy-to-use web interface for backup management

## Features

### 1. Automatic Backup Scheduling

The system automatically creates backups at the following intervals:

- **Daily Backups**: Every day at 2:00 AM (configurable)
- **Monthly Backups**: 1st of each month at 3:00 AM (configurable)
- **Yearly Backups**: January 1st at 4:00 AM (configurable)

### 2. Backup Types

- **Complete Backup**: Both database and files
- **Database Only**: MySQL database dump with compression
- **Files Only**: Storage and public directories in ZIP format

### 3. Retention Policy

- **Daily Backups**: Keeps 7 most recent (configurable)
- **Monthly Backups**: Keeps 12 most recent (configurable)
- **Yearly Backups**: Keeps 5 most recent (configurable)

## Installation & Setup

### 1. Prerequisites

Ensure you have the following installed:
- MySQL database
- `mysqldump` command available in system PATH
- PHP with `zip` and `zlib` extensions

### 2. Directory Structure

The backup system creates the following directory structure:
```
storage/app/backups/
├── database/          # Database backup files
├── files/            # File backup archives
└── .gitignore        # Excludes backups from version control
```

### 3. Configuration

Edit `config/backup.php` to customize backup settings:

```php
'retention' => [
    'daily' => 7,      // Keep 7 daily backups
    'monthly' => 12,   // Keep 12 monthly backups
    'yearly' => 5,     // Keep 5 yearly backups
],
```

### 4. Environment Variables

Add these to your `.env` file for customization:

```env
# Backup Retention
BACKUP_RETENTION_DAILY=7
BACKUP_RETENTION_MONTHLY=12
BACKUP_RETENTION_YEARLY=5

# Backup Schedule
BACKUP_DAILY_TIME=02:00
BACKUP_MONTHLY_DAY=1
BACKUP_MONTHLY_TIME=03:00
BACKUP_YEARLY_MONTH=1
BACKUP_YEARLY_DAY=1
BACKUP_YEARLY_TIME=04:00

# Backup Features
BACKUP_DATABASE_ENABLED=true
BACKUP_DATABASE_COMPRESS=true
BACKUP_FILES_ENABLED=true
BACKUP_FILES_INCLUDE_STORAGE=true
BACKUP_FILES_INCLUDE_PUBLIC=true
```

## Usage

### 1. Admin Interface

Access the backup management interface at: `/admin/backups`

Features available:
- View all backups organized by type (Manual, Daily, Monthly, Yearly)
- Create new manual backups
- Download backup files
- Delete old backups
- Clean up backups beyond retention policy
- View backup statistics

### 2. Manual Backup Creation

1. Navigate to Admin → Backups
2. Click "Create Backup"
3. Select backup type (Complete, Database Only, or Files Only)
4. Click "Create Backup"

### 3. Command Line Usage

#### Create Manual Backups
```bash
# Create daily backup
php artisan backup:daily

# Create monthly backup
php artisan backup:monthly

# Create yearly backup
php artisan backup:yearly
```

#### Run Scheduler
```bash
# Run the scheduler (for automatic backups)
php artisan schedule:run

# Or set up a cron job to run every minute:
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## File Structure

### Controllers
- `app/Http/Controllers/Admin/BackupController.php` - Web interface controller

### Services
- `app/Services/BackupService.php` - Core backup logic and file management

### Commands
- `app/Console/Commands/BackupDaily.php` - Daily backup command
- `app/Console/Commands/BackupMonthly.php` - Monthly backup command
- `app/Console/Commands/BackupYearly.php` - Yearly backup command

### Views
- `resources/views/admin/backups/index.blade.php` - Backup management interface

### Configuration
- `config/backup.php` - Backup system configuration
- `app/Console/Kernel.php` - Scheduled task configuration

## Backup File Naming

Backup files follow this naming convention:
- Database: `database_{type}_{timestamp}.sql`
- Files: `files_{type}_{timestamp}.zip`

Where:
- `{type}` = manual, daily, monthly, or yearly
- `{timestamp}` = YYYY-MM-DD_HH-mm-ss

## Security Considerations

1. **File Permissions**: Backup files are stored with restricted permissions (0755)
2. **Access Control**: Only admin users can access backup management
3. **Version Control**: Backup files are excluded from Git via `.gitignore`
4. **Database Security**: Database credentials are handled securely through Laravel's configuration

## Troubleshooting

### Common Issues

1. **mysqldump not found**
   - Ensure MySQL client tools are installed
   - Add MySQL bin directory to system PATH

2. **Permission denied errors**
   - Check directory permissions for `storage/app/backups/`
   - Ensure web server can write to backup directories

3. **Backup creation fails**
   - Check database connection settings
   - Verify disk space availability
   - Check PHP error logs

### Debugging

Enable detailed logging by adding to your `.env`:
```env
LOG_LEVEL=debug
```

## Maintenance

### Regular Tasks

1. **Monitor Disk Space**: Backup files can consume significant disk space
2. **Test Restores**: Periodically test backup restoration procedures
3. **Review Retention**: Adjust retention policies based on storage capacity
4. **Update Documentation**: Keep backup procedures updated

### Monitoring

The system provides statistics including:
- Total number of backups
- Total storage used
- Backup success/failure rates
- Recent backup activity

## Support

For issues or questions regarding the backup system:
1. Check the Laravel logs in `storage/logs/`
2. Verify configuration settings in `config/backup.php`
3. Test manual backup creation through the admin interface
4. Review system requirements and prerequisites

## Future Enhancements

Potential improvements for future versions:
- Email notifications for backup success/failure
- Cloud storage integration (AWS S3, Google Drive, etc.)
- Incremental backup support
- Backup encryption
- Automated restore functionality
- Backup verification and integrity checks
