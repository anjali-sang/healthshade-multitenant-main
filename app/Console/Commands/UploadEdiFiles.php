<?php 

namespace App\Console\Commands;

use App\Mail\PurchaseOrderFailedMail;
use App\Services\EdiUploadService;
use Illuminate\Console\Command;

class UploadEdiFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edi:upload';
    protected $description = 'Process and upload pending EDI files for Purchase Orders';

    protected $ediUploadService;

    /**
     * Create a new command instance.
     *
     * @param  EdiUploadService  $ediUploadService
     * @return void
     */
    public function __construct(EdiUploadService $ediUploadService)
    {
        parent::__construct();
        $this->ediUploadService = $ediUploadService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending EDI uploads...');

        try {
            // Call the service to upload pending EDI files
            $this->ediUploadService->uploadPendingEdiFiles();
            $this->info('EDI upload process completed successfully.');
        } catch (\Exception $e) {
            $this->error('Failed to upload EDI files: ' . $e->getMessage());
        }
    }
}
