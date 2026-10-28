<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['program'] ?? '';
    $language = $_POST['language'] ?? '';

    // Map language names to Piston supported languages if needed
    $langMap = [
        'c++' => 'cpp',
        'c' => 'c',
        'python' => 'python3',
        'php' => 'php',
        'java' => 'java',
        'javascript' => 'javascript',
        'html' => 'html',
        'css' => 'css'
    ];
    $pistonLang = $langMap[strtolower($language)] ?? 'text';

    $data = [
        "language" => $pistonLang,
        "source" => $code
    ];

    $ch = curl_init("https://emkc.org/api/v2/piston/execute");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);

    if (isset($json['run'])) {
        $stdout = $json['run']['stdout'] ?? '';
        $stderr = $json['run']['stderr'] ?? '';
        echo json_encode(['output' => $stdout . $stderr]);
    } else {
        echo json_encode(['output' => "Error executing code"]);
    }
}
