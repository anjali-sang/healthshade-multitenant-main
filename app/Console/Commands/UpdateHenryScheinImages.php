<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpseclib3\Net\SFTP;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class UpdateHenryScheinImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'henryschein:update-images';
    protected $description = 'Update Henry Schein product images using EDI 832 data';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Entered to EDI832 command...');

        $remoteFolder = '/edipricecatalog832/';
        $localPath = storage_path('app/edi/henryschein/');

        // Ensure the local directory exists
        if (!file_exists($localPath)) {
            mkdir($localPath, 0775, true);
            Log::info("Created local directory: $localPath");
        }

        $sftp = new SFTP(
            config('services.sftp.host'),
            config('services.sftp.port')
        );

        if (
            !$sftp->login(
                config('services.sftp.username'),
                config('services.sftp.password')
            )
        ) {
            Log::error('SFTP login failed.');
            return Command::FAILURE;
        }

        Log::info('SFTP login successful.');

        if (!$sftp->chdir($remoteFolder)) {
            Log::error("Failed to change to remote directory: $remoteFolder");
            return Command::FAILURE;
        }

        Log::info("Changed directory to $remoteFolder");

        $files = $sftp->nlist();
        Log::info("Found " . count($files) . " file(s) in $remoteFolder");

        foreach ($files as $file) {
            if (in_array($file, ['.', '..']) || $sftp->is_dir($file)) {
                continue;
            }

            $remoteFile = $remoteFolder . $file;
            $localFile = $localPath . $file;

            $fileData = $sftp->get($remoteFile);
            if ($fileData === false) {
                Log::error("Failed to download $file from SFTP.");
                continue;
            }

            try {
                file_put_contents($localFile, $fileData);
                Log::info("Downloaded $file to $localFile");
            } catch (\Exception $e) {
                Log::error("Error writing $file locally: " . $e->getMessage());
                continue;
            }

            if (config('app.env') == 'production') {
                if ($sftp->delete($remoteFile)) {
                    Log::info("Deleted $file from SFTP server.");
                } else {
                    Log::warning("Could not delete $file from SFTP.");
                }
            } else {
                Log::info("Skipping deletion of $file (env: " . env('APP_ENV') . ")");
            }
        }

        // Now process all local files
        $this->processLocalFiles($localPath);
    }
    private function processLocalFiles($localPath)
    {
        $files = glob($localPath . '*.832');

        foreach ($files as $filePath) {
            Log::info("Parsing EDI file: $filePath");
            $this->parseEDI832File($filePath);
            unlink($filePath); // Delete after processing
            Log::info("Deleted local file after parsing: $filePath");
        }
    }
    private function parseEDI832File($filePath)
    {
        $content = file_get_contents($filePath);
        if (!$content) {
            Log::warning("EDI file is empty or unreadable: $filePath");
            return;
        }

        $segments = explode('~', $content);

        $currentProductCode = null;
        $imageUrl = null;

        foreach ($segments as $segment) {
            $elements = explode('*', $segment);
            $tag = $elements[0];

            switch ($tag) {
                case 'LIN':
                    $currentProductCode = $elements[3] ?? null;
                    // Log::info("Found product code: $currentProductCode");
                    break;

                case 'REF':
                    if ($elements[1] === 'LI' && str_contains($elements[3], 'http')) {
                        $imageUrl = $elements[3];
                        // Log::info("Found image URL for product code $currentProductCode: $imageUrl");
                        // Save to DB now
                        if ($currentProductCode && $imageUrl) {
                            \DB::table('products')
                                ->where('product_code', $currentProductCode)
                                ->update(['image' => $imageUrl]);

                            Log::info("Updated image for product code: $currentProductCode");
                        }

                        // Reset after save
                        $currentProductCode = null;
                        $imageUrl = null;
                    }
                    break;
            }
        }
    }


}
