<?php

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class TimelineWeatherService
{
    private $endpoint;
    private $token;

    public function __construct()
    {
        $this->endpoint = config('services.timeline.endpoint');
        $this->token = config('services.timeline.api_key');
    }

    public function getWeatherData(string $location)
    {
        $url = $this->endpoint . $location;

        $response = Http::get($url, [
            'key' => $this->token,
            'unitGroup' => 'metric',
        ])->json();

        $weather_data = [
            'address' => $response['resolvedAddress'],
            'avg_temperature' => $response['days'][0]['temp'],
            'max_temperature' => $response['days'][0]['tempmax'],
            'windspeed' => $response['days'][0]['windspeed'],
            'cloudcover' => $response['days'][0]['cloudcover'],
            'rainfall' => $response['days'][0]['precip'],
        ];
        
        return $weather_data;     
    }
}