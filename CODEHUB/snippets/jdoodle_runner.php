<?php
// JDoodle Credentials
$clientId = "YOUR_CLIENT_ID";         
$clientSecret = "YOUR_CLIENT_SECRET"; 

// Retrieve POST data
$script = $_POST["code"] ?? "";
$language = $_POST["language"] ?? "";
$versionIndex = $_POST["versionIndex"] ?? "0";

// Map local language names to JDoodle's expected names
$langMap = [
    "php"     => "php",
    "python"  => "python3",
    "java"    => "java",
    "c"       => "c",
    "cpp"     => "cpp17"
];

// Fallback if not found
$jdoodleLang = $langMap[$language] ?? $language;

// Prepare API request payload
$data = [
    "clientId" => $clientId,
    "clientSecret" => $clientSecret,
    "script" => $script,
    "language" => $jdoodleLang,
    "versionIndex" => $versionIndex
];

// Send POST request to JDoodle
$curl = curl_init("https://api.jdoodle.com/v1/execute");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

// Handle response
header("Content-Type: text/plain");

if ($error) {
    echo "cURL Error: " . $error;
} else {
    $decoded = json_decode($response, true);
    if (isset($decoded["output"])) {
        echo "=== JDoodle Execution Result ===\n\n";
        echo $decoded["output"];
    } else {
        echo "Error: " . $response;
    }
}
?>
