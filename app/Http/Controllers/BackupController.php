<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use ZipArchive;

class BackupController extends Controller
{
    public function backupSourceCode()
    {
        $zip = new ZipArchive();

        $zipName = 'SIMPAN_SourceCode_' . date('Y-m-d_H-i-s') . '.zip';

        $zipPath = storage_path('app/' . $zipName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        $rootPath = base_path();

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {

            if ($file->isDir()) {
                continue;
            }

            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Folder yang tidak ikut dibackup
            if (
                str_contains($relativePath, 'vendor') ||
                str_contains($relativePath, 'node_modules') ||
                str_contains($relativePath, '.git') ||
                str_contains($relativePath, 'storage/logs') ||
                str_contains($relativePath, 'bootstrap/cache')
            ) {
                continue;
            }

            $zip->addFile($filePath, $relativePath);
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function backupDatabase()
{
    $fileName = 'SIMPAN_Database_' . date('Y-m-d_H-i-s') . '.sql';

    $filePath = storage_path('app/' . $fileName);

    $mysqldump = '"C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe"';

    $command = $mysqldump .
        ' --user=root' .
        ' --host=127.0.0.1' .
        ' --port=3306' .
        ' piutang > "' . $filePath . '"';

    exec($command, $output, $result);

    if ($result !== 0 || !file_exists($filePath)) {
        return back()->with(
            'error',
            'Backup database gagal.'
        );
    }

    return response()->download($filePath)
        ->deleteFileAfterSend(true);
}
}