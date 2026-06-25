<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$response = Illuminate\Support\Facades\Http::withHeaders([
    'key' => config('services.rajaongkir.api_key')
])->get('https://rajaongkir.komerce.id/api/v1/destination/city');

$data = $response->json();
$cities = $data['data'] ?? [];
$bekasiCities = array_filter($cities, function($city) {
    return stripos($city['name'], 'bekasi') !== false;
});

print_r($bekasiCities);
