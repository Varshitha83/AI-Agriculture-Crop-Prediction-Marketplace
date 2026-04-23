<?php

// Get user message
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data['message'] ?? '';

// 🔑 Your Gemini API Key
$apiKey = "AIzaSyAltEDff_HR3y8yGcMOKfme5JfIFsUCPZU"; // 👈 paste your key

// ✅ Correct Gemini API URL
$url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// ✅ Request body (VERY IMPORTANT FORMAT)
$postData = [
    "contents" => [
        [
            "parts" => [
                ["text" => $userMessage]
            ]
        ]
    ]
];

// ✅ Initialize CURL
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

// ✅ Execute request
$response = curl_exec($ch);

// ❌ Handle CURL error
if (curl_errno($ch)) {
    echo json_encode(["reply" => "Curl Error: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// ❌ If no response
if ($response === FALSE) {
    echo json_encode(["reply" => "Error connecting to AI"]);
    exit;
}

// ✅ Decode response
$result = json_decode($response, true);

// ✅ Extract reply safely
if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $result['candidates'][0]['content']['parts'][0]['text'];
}
elseif (isset($result['candidates'][0]['output'])) {
    $reply = $result['candidates'][0]['output'];
}
else {
    $reply = json_encode($result); // DEBUG to see actual response
}

// ✅ Send response
echo json_encode(["reply" => $reply]);

?>