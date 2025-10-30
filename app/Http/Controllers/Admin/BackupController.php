<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display the backup management page
     */
    public function index()
    {
        $backups = $this->backupService->getBackupsList();
        
        // Group backups by type
        $groupedBackups = [
            'manual' => [],
            'daily' => [],
            'monthly' => [],
            'yearly' => []
        ];

        foreach ($backups as $backup) {
            $groupedBackups[$backup['type']][] = $backup;
        }

        return view('admin.backups.index', compact('groupedBackups'));
    }

    /**
     * Create a manual backup
     */
    public function create(Request $request)
    {
        $backupType = $request->input('type', 'complete'); // complete, database, files
        
        try {
            switch ($backupType) {
                case 'database':
                    $result = $this->backupService->createDatabaseBackup('manual');
                    break;
                case 'files':
                    $result = $this->backupService->createFilesBackup('manual');
                    break;
                case 'complete':
                default:
                    $result = $this->backupService->createCompleteBackup('manual');
                    break;
            }

            if (isset($result['database']) && $result['database']['success']) {
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Backup created successfully!');
            } elseif (isset($result['files']) && $result['files']['success']) {
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Backup created successfully!');
            } elseif (isset($result['success']) && $result['success']) {
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Backup created successfully!');
            } else {
                $error = $result['error'] ?? 'Unknown error occurred';
                return redirect()->route('admin.backups.index')
                    ->with('error', 'Backup failed: ' . $error);
            }

        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file
     */
    public function download(Request $request)
    {
        $filename = $request->input('filename');
        $category = $request->input('category');

        try {
            $filepath = $this->backupService->downloadBackup($filename, $category);
            
            return response()->download($filepath, $filename);
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Download failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a backup file
     */
    public function destroy(Request $request)
    {
        $filename = $request->input('filename');
        $category = $request->input('category');

        try {
            $deleted = $this->backupService->deleteBackup($filename, $category);
            
            if ($deleted) {
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Backup deleted successfully!');
            } else {
                return redirect()->route('admin.backups.index')
                    ->with('error', 'Backup not found or could not be deleted.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Clean up old backups
     */
    public function cleanup(Request $request)
    {
        try {
            $this->backupService->cleanupOldBackups();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Old backups cleaned up successfully!'
                ]);
            }
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Old backups cleaned up successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cleanup failed: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.backups.index')
                ->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }

    /**
     * Get backup statistics
     */
    public function stats()
    {
        $backups = $this->backupService->getBackupsList();
        
        $stats = [
            'total' => count($backups),
            'manual' => 0,
            'daily' => 0,
            'monthly' => 0,
            'yearly' => 0,
            'total_size' => 0
        ];

        foreach ($backups as $backup) {
            $stats[$backup['type']]++;
            $stats['total_size'] += $backup['size'];
        }

        return response()->json($stats);
    }
}
