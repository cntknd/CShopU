<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Exception;

class BackupService
{
    protected $backupPath;
    protected $maxDailyBackups = 7;
    protected $maxMonthlyBackups = 12;
    protected $maxYearlyBackups = 5;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->ensureBackupDirectoryExists();
    }

    /**
     * Create a database backup
     */
    public function createDatabaseBackup($type = 'manual')
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "database_{$type}_{$timestamp}.sql";
            $filepath = $this->backupPath . '/database/' . $filename;

            // Ensure database backup directory exists
            $this->ensureDirectoryExists($this->backupPath . '/database');

            // Get database configuration
            $config = config('database.connections.' . config('database.default'));
            
            // Create mysqldump command
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s',
                escapeshellarg($config['host']),
                escapeshellarg($config['port']),
                escapeshellarg($config['username']),
                escapeshellarg($config['password']),
                escapeshellarg($config['database']),
                escapeshellarg($filepath)
            );

            // Execute backup command
            $result = null;
            $output = [];
            exec($command, $output, $result);

            if ($result !== 0) {
                throw new Exception('Database backup failed: ' . implode("\n", $output));
            }

            // Compress the backup
            $this->compressFile($filepath);

            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => filesize($filepath),
                'type' => $type,
                'created_at' => Carbon::now()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => $type
            ];
        }
    }

    /**
     * Create a files backup (storage and public directories)
     */
    public function createFilesBackup($type = 'manual')
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "files_{$type}_{$timestamp}.zip";
            $filepath = $this->backupPath . '/files/' . $filename;

            // Ensure files backup directory exists
            $this->ensureDirectoryExists($this->backupPath . '/files');

            // Create temporary directory for files
            $tempDir = storage_path('app/temp_backup_' . $timestamp);
            $this->ensureDirectoryExists($tempDir);

            // Copy storage directory
            $this->copyDirectory(storage_path('app/public'), $tempDir . '/storage');

            // Copy public images directory
            if (is_dir(public_path('images'))) {
                $this->copyDirectory(public_path('images'), $tempDir . '/public_images');
            }

            // Create zip archive
            $this->createZipArchive($tempDir, $filepath);

            // Clean up temporary directory
            $this->deleteDirectory($tempDir);

            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => filesize($filepath),
                'type' => $type,
                'created_at' => Carbon::now()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => $type
            ];
        }
    }

    /**
     * Create a complete backup (database + files)
     */
    public function createCompleteBackup($type = 'manual')
    {
        $results = [];

        // Create database backup
        $dbResult = $this->createDatabaseBackup($type);
        $results['database'] = $dbResult;

        // Create files backup
        $filesResult = $this->createFilesBackup($type);
        $results['files'] = $filesResult;

        // Create backup metadata
        $metadata = [
            'type' => $type,
            'created_at' => Carbon::now()->toISOString(),
            'database' => $dbResult,
            'files' => $filesResult,
            'success' => $dbResult['success'] && $filesResult['success']
        ];

        // Save metadata
        $this->saveBackupMetadata($metadata);

        return $results;
    }

    /**
     * Clean up old backups based on retention policy
     */
    public function cleanupOldBackups()
    {
        $this->cleanupBackupsByType('daily', $this->maxDailyBackups);
        $this->cleanupBackupsByType('monthly', $this->maxMonthlyBackups);
        $this->cleanupBackupsByType('yearly', $this->maxYearlyBackups);
    }

    /**
     * Get list of available backups
     */
    public function getBackupsList()
    {
        $backups = [];

        // Get database backups
        $dbBackups = $this->getBackupsInDirectory($this->backupPath . '/database');
        foreach ($dbBackups as $backup) {
            $backups[] = array_merge($backup, ['category' => 'database']);
        }

        // Get files backups
        $filesBackups = $this->getBackupsInDirectory($this->backupPath . '/files');
        foreach ($filesBackups as $backup) {
            $backups[] = array_merge($backup, ['category' => 'files']);
        }

        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    /**
     * Download a backup file
     */
    public function downloadBackup($filename, $category)
    {
        $filepath = $this->backupPath . '/' . $category . '/' . $filename;
        
        if (!file_exists($filepath)) {
            throw new Exception('Backup file not found');
        }

        return $filepath;
    }

    /**
     * Delete a backup file
     */
    public function deleteBackup($filename, $category)
    {
        $filepath = $this->backupPath . '/' . $category . '/' . $filename;
        
        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return false;
    }

    // Private helper methods

    private function ensureBackupDirectoryExists()
    {
        $this->ensureDirectoryExists($this->backupPath);
        $this->ensureDirectoryExists($this->backupPath . '/database');
        $this->ensureDirectoryExists($this->backupPath . '/files');
    }

    private function ensureDirectoryExists($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    private function compressFile($filepath)
    {
        $compressedPath = $filepath . '.gz';
        $fp_out = gzopen($compressedPath, 'wb9');
        $fp_in = fopen($filepath, 'rb');
        
        while (!feof($fp_in)) {
            gzwrite($fp_out, fread($fp_in, 1024 * 512));
        }
        
        fclose($fp_in);
        gzclose($fp_out);
        
        // Remove original file
        unlink($filepath);
        
        // Rename compressed file
        rename($compressedPath, $filepath);
    }

    private function copyDirectory($src, $dst)
    {
        if (!is_dir($src)) {
            return;
        }

        $this->ensureDirectoryExists($dst);
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $target = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                $this->ensureDirectoryExists($target);
            } else {
                copy($item, $target);
            }
        }
    }

    private function createZipArchive($source, $destination)
    {
        $zip = new \ZipArchive();
        if ($zip->open($destination, \ZipArchive::CREATE) !== TRUE) {
            throw new Exception("Cannot create zip file: $destination");
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($source) + 1);
            
            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }

    private function cleanupBackupsByType($type, $maxBackups)
    {
        $dbBackups = $this->getBackupsInDirectory($this->backupPath . '/database', $type);
        $filesBackups = $this->getBackupsInDirectory($this->backupPath . '/files', $type);

        // Clean up database backups
        if (count($dbBackups) > $maxBackups) {
            $toDelete = array_slice($dbBackups, $maxBackups);
            foreach ($toDelete as $backup) {
                $this->deleteBackup($backup['filename'], 'database');
            }
        }

        // Clean up files backups
        if (count($filesBackups) > $maxBackups) {
            $toDelete = array_slice($filesBackups, $maxBackups);
            foreach ($toDelete as $backup) {
                $this->deleteBackup($backup['filename'], 'files');
            }
        }
    }

    private function getBackupsInDirectory($directory, $type = null)
    {
        $backups = [];
        
        if (!is_dir($directory)) {
            return $backups;
        }

        $files = glob($directory . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                $fileType = $this->extractTypeFromFilename($filename);
                
                if ($type === null || $fileType === $type) {
                    $backups[] = [
                        'filename' => $filename,
                        'filepath' => $file,
                        'size' => filesize($file),
                        'created_at' => date('Y-m-d H:i:s', filemtime($file)),
                        'type' => $fileType
                    ];
                }
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    private function extractTypeFromFilename($filename)
    {
        if (strpos($filename, '_daily_') !== false) {
            return 'daily';
        } elseif (strpos($filename, '_monthly_') !== false) {
            return 'monthly';
        } elseif (strpos($filename, '_yearly_') !== false) {
            return 'yearly';
        } else {
            return 'manual';
        }
    }

    private function saveBackupMetadata($metadata)
    {
        $metadataFile = $this->backupPath . '/metadata.json';
        $existingMetadata = [];
        
        if (file_exists($metadataFile)) {
            $existingMetadata = json_decode(file_get_contents($metadataFile), true) ?: [];
        }
        
        $existingMetadata[] = $metadata;
        
        file_put_contents($metadataFile, json_encode($existingMetadata, JSON_PRETTY_PRINT));
    }

    /**
     * Format bytes to human readable format
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
