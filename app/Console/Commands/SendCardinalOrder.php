<?php

namespace App\Console\Commands;

use App\Mail\PurchaseOrderFailedMail;
use App\Models\PurchaseOrder;
use App\Notifications\PurchaseOrderFailedNotification;
use App\Notifications\SystemNotifier;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use mysqli;
use phpseclib3\Net\SFTP;


class SendCardinalOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cardinal:upload';
    protected $description = 'Process and upload pending EDI files for Purchase Orders from Cardinal Health';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending EDI uploads...');

        try {
            // Call the service to upload pending EDI files
            $this->ediUploadService();
            $this->info('EDI upload process completed successfully.');
        } catch (Exception $e) {
            $this->error('Failed to upload EDI files: ' . $e->getMessage());
        }
    }

    private function ediUploadService()
    {
        $pos = PurchaseOrder::where('is_order_placed', false)
            ->whereHas('purchaseSupplier', fn($q) => $q->where('supplier_slug', 'cardinal_health'))
            ->get();

        foreach ($pos as $po) {
            $this->uploadEdiFile($po);
        }
    }
    public function uploadEdiFile(PurchaseOrder $po)
    {
        try {
            $response = $this->getPo850($po->purchase_oder_number);
            $responseData = json_decode($response->getContent(), true);
            if ($responseData['status'] == false) {
                $po->note = "Order is not Placed. Contact Support.";
                $po->is_order_placed = false;
            } else {
                $po->note = "Order placed Successful.";
                $po->is_order_placed = true;
            }
            $po->save();

        } catch (\Throwable $e) {
            Log::error("EDI Upload failed for PO: {$po->purchase_oder_number} — " . $e->getMessage());
        }
    }
    public function getPo850($reference_no)
    {
        Log::info('Entered Cardinal fnction....');
        $connection = new mysqli(
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            config('database.connections.mysql.port')
        );
        $query = "SELECT purchase_orders.id, purchase_orders.purchase_oder_number, purchase_orders.ship_to_location_id, purchase_orders.ship_to_number,purchase_orders.bill_to_number,purchase_orders.supplier_id, locations.name as warehouse_name, locations.address as warehouse_address, locations.state as warehouse_state, locations.city as warehouse_city, locations.pin as warehouse_postal_code, suppliers.supplier_phone as phone_number, units.unit_code, products.product_code as product_code, products.cost as product_cost, products.product_name as product_name, purchase_orders.created_at, purchase_order_details.quantity as qty FROM purchase_orders INNER JOIN locations ON purchase_orders.ship_to_location_id = locations.id INNER JOIN suppliers ON purchase_orders.supplier_id = suppliers.id INNER JOIN purchase_order_details ON purchase_orders.id = purchase_order_details.purchase_order_id INNER JOIN products ON purchase_order_details.product_id = products.id INNER JOIN units ON purchase_order_details.unit_id = units.id WHERE purchase_orders.purchase_oder_number = ?";

        $statement = $connection->prepare($query);
        $statement->bind_param("s", $reference_no);

        try {

            Log::info('Entered Try Block...');
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $isaSenderID = env('HEALTHSHADE_ISA_HEADER', '8179650267');
                $isaReceiverID = config('services.cah.binary_address', '01067950097_SFTP');
                $isaDateFormat = "ymd";
                $isaTimeFormat = "Hi";
                $segmentCount = 11;

                $isaHeader = "ISA*" . str_pad("00", 2, "0") . "*" . str_pad("", 10) . "*" . str_pad("00", 2, "0") . "*" . str_pad("", 10) . "*" . str_pad("12", 2) . "*" . str_pad($isaSenderID, 15) . "*" . str_pad("01", 2) . "*" . str_pad($isaReceiverID, 15) . "*" . Carbon::now()->format($isaDateFormat) . "*" . Carbon::now()->format($isaTimeFormat) . "*U*00401*000000001*0*P*<~\n";
                $ediBuilder = $isaHeader;
                $ediBuilder .= "GS*PO*" . $isaSenderID . "*" . $isaReceiverID . "*" . Carbon::now()->format("Ymd") . "*" . Carbon::now()->format("Hi") . "*1421*X*004010~\n";
                $ediBuilder .= "ST*850*0001~\n";

                while ($row = $result->fetch_assoc()) {
                    $createdAt = Carbon::parse($row["created_at"] ?? "");
                    $formattedDate = $createdAt->format("Ymd");

                    $ediBuilder .= "BEG*00*SA*" . $row["purchase_oder_number"] . "**" . $formattedDate . "~\n";
                    // $ediBuilder .= "REF*IT**~\r\n";
                    $ediBuilder .= "PER*OC**TE*" . $row["phone_number"] . "~\n";
                    $ediBuilder .= "N1*ST*" . $row["warehouse_name"] . "*91*" . $row["ship_to_number"] . "~\n";
                    $ediBuilder .= "N3*" . $row["warehouse_address"] . "*" . $row["warehouse_state"] . "~\n";
                    $ediBuilder .= "N4*" . $row["warehouse_city"] . "**" . $row["warehouse_postal_code"] . "~\n";
                    $ediBuilder .= "N1*BY*" . $row["warehouse_name"] . "*91*" . $row["bill_to_number"] . "~\n";
                    $ediBuilder .= "N3*" . $row["warehouse_address"] . "*" . $row["warehouse_state"] . "~\n";
                    $ediBuilder .= "N4*" . $row["warehouse_city"] . "**" . $row["warehouse_postal_code"] . "~\n";

                    $lineNum = 1;
                    do {
                        $ediBuilder .= "PO1*" . $lineNum . "*" . $row["qty"] . "*" . $row["unit_code"] . "*" . $row["product_cost"] . "**VC*" . $row["product_code"] . "~\n";
                        $ediBuilder .= "PID*F****" . $row["product_name"] . "~\n";
                        $lineNum++;
                        $segmentCount += 2;
                    } while ($row = $result->fetch_assoc());

                    $value = $lineNum - 1;
                    $ediBuilder .= "CTT*" . $value . "~\n";
                    $ediBuilder .= "SE*" . $segmentCount . "*0001~\n";
                    $ediBuilder .= "GE*1*1421~\n";
                    $ediBuilder .= "IEA*1*000000001~\n";

                    $ediString = $ediBuilder;

                    Log::info('EDI String is prepared...');
                    Log::info($ediString);
                    Log::info('Function call to upload850 made...');
                    $response = $this->upload850($ediString, $reference_no);
                    $responseData = json_decode($response->getContent(), true);
                    if ($responseData['status'] == false) {
                        return response()->json([
                            'status' => false,
                            'message' => $responseData['message']
                        ]);
                    } else {
                        return response()->json([
                            'status' => true,
                            'message' => $responseData['message']
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'message' => "No record found with custom ID: " . $reference_no,
                    'status' => false
                ]);
            }
        } catch (Exception $ex) {
            $notificationEmails = config('app.email');
            $message = "An error occurred while generating EDI file of Ref no: " . $reference_no . "  Error - " . $ex->getMessage();

            $purchaseOrder = PurchaseOrder::where('purchase_oder_number', $reference_no)->first();
            $organization = $purchaseOrder ? $purchaseOrder->organization : null;

            $notifier = new SystemNotifier($notificationEmails);

            if ($organization) {
                $notifier->notify(new PurchaseOrderFailedNotification(
                    $organization,
                    $message
                ));
            }

            return response()->json([
                'message' => $message,
                'status' => false
            ]);
        }
    }

    public function upload850($ediString, $reference_no)
    {
        try {
            Log::info('Entered upload850 method...');

            $sftp = new SFTP(
                config('services.cah.host'),   
                config('services.cah.port', 22)
            );

            if (!$sftp->login(config('services.cah.username'), config('services.cah.password'))) {
                Log::error('SFTP login failed');
                throw new Exception('SFTP login failed');
            }

            $remotePath = "/custom/TOCARDINAL/".$reference_no. ".txt"; 
            Log::info("Attempting to upload to: {$remotePath}");

            if ($sftp->put($remotePath, $ediString)) {
                Log::info("Upload successful to path: {$remotePath}");
                Log::info("Current working directory: " . $sftp->pwd());

                // ✅ Confirm file is listed in target directory
                $targetDir = dirname($remotePath);
                $files = $sftp->nlist($targetDir);
                Log::info("Files in directory '{$targetDir}':", $files);

                return response()->json([
                    'message' => 'File uploaded successfully',
                    'status' => true
                ]);
            } else {
                Log::error("File upload failed to path: {$remotePath}");
                throw new Exception('File upload failed');
            }

        } catch (Exception $e) {
            Log::error('Error in upload850', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            $referenceMessage = "An error occurred while uploading EDI file of Ref no: {$reference_no} - Error: {$e->getMessage()}";

            $purchaseOrder = PurchaseOrder::where('purchase_oder_number', $reference_no)->first();
            $organization = $purchaseOrder?->organization;

            $notifier = new SystemNotifier(config('app.email'));

            if ($organization) {
                $notifier->notify(new PurchaseOrderFailedNotification(
                    $organization,
                    $referenceMessage
                ));
            }

            return response()->json([
                'message' => $referenceMessage,
                'status' => false
            ]);
        }
    }


}
