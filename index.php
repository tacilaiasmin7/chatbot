<?php

header("Contetnt Type: application/json");



$mensagem = $_POST["mensagem"] ?? "";





$api_key = "AIzaSyAUcQ_PjAZLEZRqzytpdYUnr23dftTHTSg";

$prompt = $_POST["mensagem"] ?? "";

if (!$prompt) {
    echo "Nenhuma mensagem recebida.";
    exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini=2.0-flash:generateContent?key=" . "api_key";


$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt]
            ]
        ]
    ]
];

$payload = json_encode($data);

// CURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);
curl_close($ch);

// Decodifica a resposta da API
$json = json_decode($response, true);

if (isset($json["candidates"][0]["content"]["parts"][0]["text"])) {
    echo $json["candidates"][0]["content"]["parts"][0]["text"];
} else {
    echo "Erro ao gerar resposta.";
}
?>