<?php
function api($api_name, $method = 'GET', $data = null) {
    $parsed = parse_url($api_name);
    $path = rtrim($parsed['path'], '/');
    $api_name_transformed = $path . '.php';
    if (isset($parsed['query'])) {
        $api_name_transformed .= '?' . $parsed['query'];
    }
    
    $url = "http://localhost/warid/eNilai/controller$api_name_transformed";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    if (in_array($method, ['POST', 'PUT']) && $data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
    }
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL Error: $error");
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $decoded = json_decode($response, true);
    
    if ($httpCode >= 400) {
        throw new Exception("HTTP Error: $httpCode, Response: $response");
    }
    
    return $decoded ?: $response;
}

?>