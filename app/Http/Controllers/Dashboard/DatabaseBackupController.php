<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\FormatBytes;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;


class DatabaseBackupController extends Controller
{

    /**
     * Display a listing of database backup files.
     *
     * Retrieves all backup files from the local storage disk's 'KawaNime/' directory,
     * formats their names, sizes, and last modified dates, and returns a JSON response
     * containing the list of backups.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $disk = Storage::disk('local');

        $files = $disk->files('KawaNime/');

        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => FormatBytes::formatBytes($disk->size($file)),
                'date' => Carbon::parse($disk->lastModified($file))->toDateString(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $backups,
        ], 200);
    }


    /**
     * Create a new database backup.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        try {
            Artisan::call('backup:run --only-db');
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'data' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified backup file from storage.
     *
     * @param string $fileName
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($fileName)
    {

        try {
            $disk = Storage::disk('local');
            $dir = $disk->files('KawaNime/');
            $file = $dir[array_search($fileName, $dir)];

            $disk->delete($file);

            return response()->json([
                'success' => true,
                'data' => 'Backup berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Download a database backup file.
     *
     * @param string $file
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($file)
    {
        if (!preg_match('/^[\w\-]+\.zip$/', $file)) {
            return abort(400, 'Invalid filename');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        $path = 'KawaNime/' . $file;

        if (!$disk->exists($path)) {
            return abort(404, 'Backup file not found.');
        }

        return $disk->download($path);
    }
}
