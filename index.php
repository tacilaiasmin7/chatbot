<?php

// Define o tipo de conteúdo da resposta como texto simples (text/plain) e a codificação de caracteres como UTF-8.
header("Content-Type: text/plain; charset=UTF-8");

// Define a chave da API para acessar a API do Google (Gemini).
$apiKey = "AIzaSyBmIJnMLDJ-QUDuwDXfwzJC7PQ3EDGj-oE"; // coloque sua API KEY aqui

// Tenta pegar a mensagem que o usuário enviou. Se não encontrar, vai usar uma string vazia como padrão.
$prompt = $_POST["mensagem"] ?? "";

// Se a mensagem não foi enviada (se a variável $prompt estiver vazia), o código exibe uma mensagem e para a execução.
if (!$prompt) {
    echo "Nenhuma mensagem recebida."; // Mostra uma mensagem de erro
    exit;  // Interrompe a execução do código
}

// Cria a URL da API do Google com o modelo "gemini-2.0-flash" e a chave da API.
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;

// Monta os dados que serão enviados para a API. A mensagem do usuário será passada aqui.
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt] // O texto enviado pelo usuário
            ]
        ]
    ]
];

// Converte o array PHP ($data) para o formato JSON, que é o formato esperado pela API.
$payload = json_encode($data);

// Inicia uma sessão cURL para enviar a requisição para a API do Google.
$ch = curl_init($url);

// Define que a resposta da requisição cURL será retornada como string, não exibida diretamente.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Define que estamos fazendo uma requisição do tipo POST, pois estamos enviando dados.
curl_setopt($ch, CURLOPT_POST, true);

// Define o cabeçalho HTTP para informar que estamos enviando dados no formato JSON.
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

// Define os dados que serão enviados na requisição (o corpo da requisição) no formato JSON.
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Executa a requisição cURL e armazena a resposta da API na variável $response.
$response = curl_exec($ch);

// Fecha a sessão cURL, liberando os recursos.
curl_close($ch);

// Decodifica a resposta JSON da API para um array PHP para que possamos manipulá-lo.
$json = json_decode($response, true);

// Verifica se o campo com a resposta do Google existe no array decodificado.
if (isset($json["candidates"][0]["content"]["parts"][0]["text"])) {
    // Se o campo existe, mostramos o texto gerado pela API.
    echo $json["candidates"][0]["content"]["parts"][0]["text"];
} else {
    // Se não encontrar o texto gerado, mostramos uma mensagem de erro.
    echo "Erro ao gerar resposta.";
}

?>
