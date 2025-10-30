<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the backup system.
    | You can modify these settings to customize backup behavior.
    |
    */

    'storage_path' => storage_path('app/backups'),

    'retention' => [
        'daily' => env('BACKUP_RETENTION_DAILY', 7),    // Keep 7 daily backups
        'monthly' => env('BACKUP_RETENTION_MONTHLY', 12), // Keep 12 monthly backups
        'yearly' => env('BACKUP_RETENTION_YEARLY', 5),   // Keep 5 yearly backups
    ],

    'schedule' => [
        'daily_time' => env('BACKUP_DAILY_TIME', '02:00'),
        'monthly_day' => env('BACKUP_MONTHLY_DAY', 1),
        'monthly_time' => env('BACKUP_MONTHLY_TIME', '03:00'),
        'yearly_month' => env('BACKUP_YEARLY_MONTH', 1),
        'yearly_day' => env('BACKUP_YEARLY_DAY', 1),
        'yearly_time' => env('BACKUP_YEARLY_TIME', '04:00'),
    ],

    'database' => [
        'enabled' => env('BACKUP_DATABASE_ENABLED', true),
        'compress' => env('BACKUP_DATABASE_COMPRESS', true),
    ],

    'files' => [
        'enabled' => env('BACKUP_FILES_ENABLED', true),
        'include_storage' => env('BACKUP_FILES_INCLUDE_STORAGE', true),
        'include_public' => env('BACKUP_FILES_INCLUDE_PUBLIC', true),
        'public_directories' => [
            'images',
            // Add more public directories to backup here
        ],
    ],

    'notifications' => [
        'enabled' => env('BACKUP_NOTIFICATIONS_ENABLED', false),
        'email' => env('BACKUP_NOTIFICATION_EMAIL'),
        'on_success' => env('BACKUP_NOTIFY_ON_SUCCESS', false),
        'on_failure' => env('BACKUP_NOTIFY_ON_FAILURE', true),
    ],
];
