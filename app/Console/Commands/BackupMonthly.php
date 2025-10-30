<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackupService;

class BackupMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create monthly backup of database and files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly backup...');
        
        $backupService = new BackupService();
        
        // Create complete backup
        $result = $backupService->createCompleteBackup('monthly');
        
        if (isset($result['database']['success']) && $result['database']['success'] && 
            isset($result['files']['success']) && $result['files']['success']) {
            
            $this->info('Monthly backup completed successfully!');
            $this->info('Database backup: ' . $result['database']['filename']);
            $this->info('Files backup: ' . $result['files']['filename']);
            
            // Clean up old backups
            $backupService->cleanupOldBackups();
            $this->info('Old backups cleaned up.');
            
        } else {
            $this->error('Monthly backup failed!');
            if (isset($result['database']['error'])) {
                $this->error('Database error: ' . $result['database']['error']);
            }
            if (isset($result['files']['error'])) {
                $this->error('Files error: ' . $result['files']['error']);
            }
            return 1;
        }
        
        return 0;
    }
}
