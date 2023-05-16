<?php

header("Access-Control-Allow-Origin: http://localhost:4200");
header ("Access-Control-Allow-Credentials: true ");
header("Access-Control-Allow-Headers: Content-Type");

function obterEndereco($latitude, $longitude, $chaveAPI) {
    if (empty($latitude) || empty($longitude)) {
        return 'Latitude e longitude não estão definidas.';
    }

    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&key=" . $chaveAPI;
    $resposta = file_get_contents($url);

    if ($resposta === false) {
        return 'Erro ao fazer a solicitação para a API do Google Maps.';
    }

    $dados = json_decode($resposta, true);

    if (isset($dados['status']) && $dados['status'] === 'OK' && isset($dados['results'][0]['formatted_address'])) {
        return $dados['results'][0]['formatted_address'];
    } else {
        return 'Não foi possível obter o endereço. Verifique os dados de latitude e longitude.';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $postData = file_get_contents('php://input');
    $dados = json_decode($postData, true);

 
    $latitude = $dados['latitude'] ?? null;
    $longitude = $dados['longitude'] ?? null;
    $chaveAPI = 'CHAVE_API';

    $endereco = obterEndereco($latitude, $longitude, $chaveAPI);
    echo $endereco;
} else {
    echo 'Requisição inválida.';
}

?>


