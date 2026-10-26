
<?php
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    http_response_code(400);
    echo json_encode([
        'error' => 'Digite o nome de uma série.'
    ]);
    exit;
}

$url = 'https://api.tvmaze.com/search/shows?q='
     . urlencode($q);

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 15
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $status !== 200) {
    http_response_code(502);
    echo json_encode([
        'error' => 'Erro ao consultar a API.'
    ]);
    exit;
}

echo $response;
