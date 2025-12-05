<?php
$weatherApiKey = '71319b7186840af88458c99538e8ab9e';
$hotel['weather'] = [];

if (!empty($hotel['Latitude']) && !empty($hotel['Longitude'])) {

    $latitude  = $hotel['Latitude'];
    $longitude = $hotel['Longitude'];

    $url = "https://api.openweathermap.org/data/2.5/forecast?lat={$latitude}&lon={$longitude}&units=metric&appid={$weatherApiKey}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if ($response === false) 
    {
        // cURL error
        error_log("Weather API cURL error: " . curl_error($ch));
    } 
    else 
    {
        $data = json_decode($response, true);

        if (!empty($data['list'])) 
        {
            $dailyWeather = [];
            foreach ($data['list'] as $entry) 
            {
                $date = gmdate('Y-m-d', $entry['dt']);
                if (!isset($dailyWeather[$date])) 
                {
                    $dailyWeather[$date] = 
                    [
                        'temp_min' => $entry['main']['temp_min'],
                        'temp_max' => $entry['main']['temp_max'],
                        'weather'  => $entry['weather'][0],
                    ];
                } 
                else 
                {
                    $dailyWeather[$date]['temp_min'] = min($dailyWeather[$date]['temp_min'], $entry['main']['temp_min']);
                    $dailyWeather[$date]['temp_max'] = max($dailyWeather[$date]['temp_max'], $entry['main']['temp_max']);
                }
            }
            // Keep only next 5 days
            $hotel['weather'] = array_slice($dailyWeather, 0, 5, true);
        } 
        else 
        {
            error_log("Weather API returned empty list for hotel ID: {$hotel['Hotel_Id']}");
        }
    }

    curl_close($ch);
}

return $hotel;
