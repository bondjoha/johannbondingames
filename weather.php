<?php
function getWeather($address) 
{
    $apiKey = '2d98671851c8719dc908c170ba142d01';

    // Try to extract just the city name from the address
    // Example: "The Mall, Floriana FRN 1478, Malta" → "Floriana"
    $parts = explode(',', $address);
    $city = trim($parts[0]); // use first part of address as city
    $city = urlencode($city);

    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&appid={$apiKey}";
    $response = @file_get_contents($url);

    if ($response === false) 
    {
        error_log("Weather API failed for city: $city ($address)");
        return 
        [
            'temp' => 'N/A',
            'description' => 'No data',
            'icon' => '01d'
        ];
    }

    $data = json_decode($response, true);

    if (!$data || !isset($data['main']['temp'])) 
    {
        error_log("Weather data missing for city: $city");
        return 
        [
            'temp' => 'N/A',
            'description' => 'No data',
            'icon' => '01d'
        ];
    }

    return 
    [
        'temp' => round($data['main']['temp']),
        'description' => $data['weather'][0]['description'],
        'icon' => $data['weather'][0]['icon']
    ];
}
