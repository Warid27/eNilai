<?php
class API
{
    private $baseUrl = '';
    private $headers = [];
    private $timeout = 30;

    public function __construct($baseUrl = '', $defaultHeaders = [])
    {
        $this->baseUrl = rtrim($baseUrl, '/') . '/'; // Ensure baseUrl ends with a slash
        $this->headers = $defaultHeaders;
    }

    // Generic request method
    public function request($method, $url, $config = [])
    {
        $ch = curl_init();

        // Build URL (handle baseUrl and query params)
        $fullUrl = $this->baseUrl . ltrim($url, '/'); // Remove leading slash from url
        if ($method === 'GET' && !empty($config['params'])) {
            $fullUrl .= '?' . http_build_query($config['params']);
        }

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['timeout'] ?? $this->timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        // Headers
        $headers = array_merge($this->headers, $config['headers'] ?? []);
        if (!isset($headers['Content-Type']) && in_array($method, ['POST', 'PUT'])) {
            $headers['Content-Type'] = 'application/json';
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(
                fn($k, $v) => "$k: $v",
                array_keys($headers),
                $headers
            ));
        }

        // Data for POST/PUT
        if (in_array($method, ['POST', 'PUT']) && isset($config['data'])) {
            $data = is_array($config['data']) ? json_encode($config['data']) : $config['data'];
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Handle JSON decoding
        $resultData = $response ? json_decode($response, true) : null;
        if ($response && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from server');
        }

        // Prepare Axios-like response
        $result = [
            'data' => $resultData,
            'status' => $httpCode,
            'statusText' => $this->getStatusText($httpCode),
            'error' => $error ?: null
        ];

        if ($httpCode >= 400 || $error) {
            $errorMessage = $error ?: ($resultData['error'] ?? "Request failed with status $httpCode");
            throw new Exception($errorMessage);
        }

        return $result;
    }

    // CRUD Methods
    public function get($url, $config = [])
    {
        return $this->request('GET', $url, $config);
    }

    public function post($url, $data = [], $config = [])
    {
        return $this->request('POST', $url, array_merge($config, ['data' => $data]));
    }

    public function put($url, $data = [], $config = [])
    {
        return $this->request('PUT', $url, array_merge($config, ['data' => $data]));
    }

    public function delete($url, $config = [])
    {
        return $this->request('DELETE', $url, $config);
    }

    // Helper to map HTTP status codes to text
    private function getStatusText($code)
    {
        $statusTexts = [
            200 => 'OK',
            201 => 'Created',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            404 => 'Not Found',
            409 => 'Conflict',
            500 => 'Internal Server Error'
        ];
        return $statusTexts[$code] ?? 'Unknown';
    }
}
?>