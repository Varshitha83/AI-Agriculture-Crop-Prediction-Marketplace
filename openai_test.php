<?php
// openai_test.php – simple debug for your API key

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';

echo "<pre>";

if (!isset($OPENAI_API_KEY) || strlen($OPENAI_API_KEY) < 20) {
    echo "❌ OPENAI_API_KEY is missing or too short in config.php\n";
    exit;
}

echo "Using key that starts with: " . htmlspecialchars(substr($OPENAI_API_KEY, 0, 8)) . "...\n\n";

$payload = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        [
            "role" => "system",
            "content" => "Say: This is a test from my PHP agriculture portal."
        ]
    ],
    "max_tokens"  => 20,
    "temperature" => 0.7
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        "Content-Type: application/json",
        "Authorization: Bearer " . $OPENAI_API_KEY
    ],
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_TIMEOUT        => 20
]);

$response = curl_exec($ch);

if ($response === false) {
    echo "❌ cURL error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP status code: $httpCode\n\n";
echo "Raw response:\n";
echo $response;
?>
