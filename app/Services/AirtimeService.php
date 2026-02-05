<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AirtimeService
{
    protected $baseUrl;
    protected $apiKey;
    protected $secretKey;

    public function __construct()
    {
        // Pulling from config/services.php
        $this->baseUrl = config('services.vtpass.url', 'https://sandbox.vtpass.com/api');
        $this->apiKey = config('services.vtpass.api_key');
        $this->secretKey = config('services.vtpass.secret_key');
    }

    /**
     * Process Airtime Purchase for PayPoint
     */
    public function purchase($network, $phone, $amount)
    {
        /** * FIX 1: Request ID Format
         * VTPass requires: YYYYMMDDHHIISS + alphanumeric (at least 12 chars total)
         * We use Carbon to ensure the Lagos/Nigeria timezone (GMT+1)
         */
        $requestId = Carbon::now('Africa/Lagos')->format('YmdHis') . bin2hex(random_bytes(6));

        /**
         * FIX 2: Service ID Mapping
         * VTPass uses lowercase slugs like 'mtn', 'glo', etc.
         */
        $serviceID = strtolower($network);

        try {
            /**
             * FIX 3: Key Naming (The "Invalid Arguments" Fix)
             * We MUST use 'serviceID' (camelCase) instead of 'service_id'.
             */
            $payload = [
                'request_id' => (string) $requestId,
                'serviceID'  => (string) $serviceID,
                'amount'     => (int) $amount,
                'phone'      => (string) $phone,
            ];

            $response = Http::withHeaders([
                'api-key'    => $this->apiKey,
                'secret-key' => $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->post($this->baseUrl . '/pay', $payload);

            $result = $response->json();

            // Log the attempt for PayPoint audit trails
            Log::info("PayPoint Airtime Attempt", [
                'request_id' => $requestId,
                'status'     => $result['code'] ?? 'Unknown'
            ]);

            // If it's still failing, the log will now capture the EXACT reason
            if ($response->failed() || (isset($result['code']) && $result['code'] !== '000')) {
                Log::error("VTPass Argument Error Captured", [
                    'payload_sent' => $payload,
                    'api_error'    => $result
                ]);
            }

            // Return the requestId in the result so the Controller can save it as a reference
            $result['requestId'] = $requestId;

            return $result;

        } catch (\Exception $e) {
            Log::error("PayPoint Critical API Error: " . $e->getMessage());
            return [
                'code' => '999',
                'response_description' => 'Service connection failed.'
            ];
        }
    }
}
