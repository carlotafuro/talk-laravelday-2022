<?php

//
// -------------------------------------------------------------------------------------------------------------------------- //
//
// You can use cache:clear command to clear your cache including your rate limits, like so:
//
//    php artisan cache:clear
//
// -------------------------------------------------------------------------------------------------------------------------- //
//       
//       Current time: 2022-11-09 06:53:36 (UTC) | 1667976816
//       
//       response->user_data->email: sibilla.bernardi@accomodati.it
//       
//       - response header: HTTP/1.1 200 OK
//       - response header: X-RateLimit-Limit: 5
//       - response header: X-RateLimit-Remaining: 1
//       
//       --------------------------------------------------------------------------------
//       
//       Current time: 2022-11-09 06:53:36 (UTC) | 1667976816
//       
//       response->user_data->email: sibilla.bernardi@accomodati.it
//       
//       - response header: HTTP/1.1 200 OK
//       - response header: X-RateLimit-Limit: 5
//       - response header: X-RateLimit-Remaining: 0
//       
//       --------------------------------------------------------------------------------
//       
//       Current time: 2022-11-09 06:53:36 (UTC) | 1667976816
//       
//       errore in url: https://laravel.accomodati.it/api/user_profile
//       
//       - response header: HTTP/1.1 429 Too Many Requests
//       - response header: Retry-After: 57
//       - response header: X-RateLimit-Limit: 5
//       - response header: X-RateLimit-Remaining: 0
//       - response header: X-RateLimit-Reset: 1667976876
//       
//       X-RateLimit-Reset: 2022-11-09 06:54:36 (UTC) | 1667976876
//       
// -------------------------------------------------------------------------------------------------------------------------- //
//

$url = 'https://laravel.accomodati.it/api/user_profile';

$content = json_encode([
    'username'    => 'sibilla.bernardi@accomodati.it',
    'password'    => 'demopassword',
]);

$http_headers = implode("\r\n", [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode('remote_api_user:123456789'),
    'Content-Length: ' . strlen($content),
]);

$http_contex = stream_context_create([
    'http' => [
        'method'  => 'POST',
        'content' => $content,
        'header'  => $http_headers,
    ],
]);

$current_time = time();

for ($i = 0; $i < 10; $i++) {

    echo "\n" . str_repeat('-', 80) . "\n";

    echo "\n" . 'Current time: ' . date('Y-m-d H:i:s (e)', $current_time) . ' | ' . $current_time . "\n\n";

    if ($response = @file_get_contents($url, false, $http_contex)) {

        // echo 'response:' . "\n\n" . $response . "\n\n";

        $response_obj = json_decode($response);

        if (isset($response_obj->user_data->email)) {
            echo 'response->user_data->email: ' . $response_obj->user_data->email . "\n\n";
        }

        // echo 'response:' . "\n\n" . json_encode($response_obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n\n";

    } else {

        echo 'errore in url: ' . $url . "\n\n";
    }

    // echo print_r($http_response_header, 1) . "\n";

    asort($http_response_header);

    foreach ($http_response_header as $current_http_response_header) {

        if (
            strpos(strtolower($current_http_response_header), 'http')        === 0 or
            strpos(strtolower($current_http_response_header), 'date_')       === 0 or
            strpos(strtolower($current_http_response_header), 'x-rate')      === 0 or
            strpos(strtolower($current_http_response_header), 'retry-after') === 0 or
            1 === 0
        ) {
            echo '- response header: ' . $current_http_response_header . "\n";
        }
    }

    foreach ($http_response_header as $current_http_response_header) {

        if (strpos(strtolower($current_http_response_header), strtolower('X-RateLimit-Reset')) === 0) {

            $current_http_response_header = substr($current_http_response_header, strlen('X-RateLimit-Reset'));

            $current_http_response_header = trim($current_http_response_header, ' :');

            echo "\n" . 'X-RateLimit-Reset: ' . date('Y-m-d H:i:s (e)', $current_http_response_header) . ' | ' . $current_http_response_header . "\n";
        }
    }
}

echo "\n" . str_repeat('-', 80) . "\n";
