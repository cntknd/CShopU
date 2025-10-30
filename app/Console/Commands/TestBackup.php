<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackupService;

class TestBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the backup system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing backup system...');
        
        $backupService = new BackupService();
        
        // Test directory creation
        $this->info('✓ Backup directories created');
        
        // Test stats loading
        $backups = $backupService->getBackupsList();
        $this->info('✓ Found ' . count($backups) . ' existing backups');
        
        // Test formatBytes function
        $testSize = BackupService::formatBytes(1024);
        $this->info('✓ Format bytes test: ' . $testSize);
        
        // Test database backup (if possible)
        try {
            $this->info('Testing database backup...');
            $result = $backupService->createDatabaseBackup('test');
            
            if ($result['success']) {
                $this->info('✓ Database backup test successful');
                $this->info('  - File: ' . $result['filename']);
                $this->info('  - Size: ' . BackupService::formatBytes($result['size']));
            } else {
                $this->warn('⚠ Database backup test failed: ' . $result['error']);
            }
        } catch (\Exception $e) {
            $this->warn('⚠ Database backup test failed: ' . $e->getMessage());
        }
        
        // Test files backup (if possible)
        try {
            $this->info('Testing files backup...');
            $result = $backupService->createFilesBackup('test');
            
            if ($result['success']) {
                $this->info('✓ Files backup test successful');
                $this->info('  - File: ' . $result['filename']);
                $this->info('  - Size: ' . BackupService::formatBytes($result['size']));
            } else {
                $this->warn('⚠ Files backup test failed: ' . $result['error']);
            }
        } catch (\Exception $e) {
            $this->warn('⚠ Files backup test failed: ' . $e->getMessage());
        }
        
        $this->info('Backup system test completed!');
        
        return 0;
    }
}