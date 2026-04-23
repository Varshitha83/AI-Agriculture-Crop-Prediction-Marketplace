<?php
// quote_api.php – backend endpoint for homepage quotes

header('Content-Type: application/json');

require_once __DIR__ . '/config.php';

if (!isset($OPENAI_API_KEY) || strpos($OPENAI_API_KEY, 'sk-') !== 0) {
    http_response_code(500);
    echo json_encode(['error' => 'OpenAI API key not configured']);
    exit;
}

$prompt = "You generate short motivational quotes about agriculture and farming. "
        . "Reply in this exact format: Quote text - Author name.";

// Build request payload
$payload = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        [
            "role" => "system",
            "content" => $prompt
        ]
    ],
    "max_tokens"  => 60,
    "temperature" => 0.8
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
    $error = curl_error($ch);
    curl_close($ch);
    http_response_code(500);
    echo json_encode(['error' => 'Curl error: ' . $error]);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// If OpenAI returns an error code, forward it
if ($httpCode < 200 || $httpCode >= 300) {
    http_response_code($httpCode);
    echo $response;
    exit;
}

// Decode and return only what we need
$data = json_decode($response, true);
$content = $data['choices'][0]['message']['content'] ?? null;

echo json_encode([
    'content' => $content
]);
