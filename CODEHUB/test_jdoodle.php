<?php
$clientId = "your_client_id";  // Replace with your real ID
$clientSecret = "your_client_secret"; // Replace with your real secret
$language = "python3";
$versionIndex = "3";
$code = "print('Hello from JDoodle')";

$ch = curl_init("https://api.jdoodle.com/v1/execute");

$data = [
    "clientId" => $clientId,
    "clientSecret" => $clientSecret,
    "script" => $code,
    "language" => $language,
    "versionIndex" => $versionIndex
];

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
curl_close($ch);

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
