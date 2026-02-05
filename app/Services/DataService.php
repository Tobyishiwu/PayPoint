<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class DataService {
    public function getPlans($serviceID) {
        return Http::withHeaders([
            'api-key' => config('services.vtpass.api_key'),
            'public-key' => config('services.vtpass.public_key'),
        ])->get(config('services.vtpass.url')."/service-variations", ['serviceID' => $serviceID])->json();
    }

    public function pay($data) {
        return Http::withHeaders([
            'api-key' => config('services.vtpass.api_key'),
            'secret-key' => config('services.vtpass.secret_key'),
        ])->post(config('services.vtpass.url')."/pay", $data)->json();
    }
}
