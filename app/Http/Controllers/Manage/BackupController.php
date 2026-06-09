<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupController extends Controller
{
    public function database()
    {
        $backupDir = storage_path('app/backups');

        if (! File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = now()->format('Ymd_His');

        $sqlFile = "backup_{$timestamp}.sql";
        $zipFile = "backup_{$timestamp}.zip";

        $sqlPath = $backupDir.'/'.$sqlFile;
        $zipPath = $backupDir.'/'.$zipFile;

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg(env('DB_HOST')),
            escapeshellarg(env('DB_PORT')),
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($sqlPath)
        );

        exec($command, $output, $result);

        if ($result !== 0 || ! File::exists($sqlPath)) {
            return back()->with('error', 'Backup database gagal.');
        }

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        $zip->addFile($sqlPath, $sqlFile);
        $zip->close();

        File::delete($sqlPath);

        return response()
            ->download($zipPath, $zipFile)
            ->deleteFileAfterSend(true);
    }
}
