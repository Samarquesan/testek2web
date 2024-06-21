<?php
$pastaRaiz = "arquivos/";
$confirmadoEnvio = 1;
$mensagemErro = "";


if (!file_exists($pastaRaiz)) {
    mkdir($pastaRaiz, 0777, true);
}

$pastaArquivo = $pastaRaiz . basename($_FILES["arquivo"]["name"]);
$formato = strtolower(pathinfo($pastaArquivo, PATHINFO_EXTENSION));


$confirmacao = mime_content_type($_FILES["arquivo"]["tmp_name"]);
if (strpos($confirmacao, "audio/") === 0 || strpos($confirmacao, "video/") === 0 || strpos($confirmacao, "image/") === 0) {
    $mensagemErro .= "Formato de arquivo não suportado! ";
    $confirmadoEnvio = 0;
}


if (file_exists($pastaArquivo)) {
    $mensagemErro .= "Arquivo já registrado! ";
    $confirmadoEnvio = 0;
}


if ($_FILES["arquivo"]["size"] > 50000000) {
    $mensagemErro .= "Arquivo excede o tamanho máximo suportado! ";
    $confirmadoEnvio = 0;
}


$formatoAceito = ["mp3", "wav", "wma", "mp4", "wmv", "mpg", "mov", "jpg", "jpeg", "png", "gif"];
if (!in_array($formato, $formatoAceito)) {
    $mensagemErro .= "O formato do arquivo não é compatível! ";
    $confirmadoEnvio = 0;
}


if ($confirmadoEnvio == 0) {
    echo "O arquivo não pode ser enviado! ";
    echo $mensagemErro;
} else {
    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $pastaArquivo)) {
        $envios = json_decode(file_get_contents('arquivos.json'), true) ?: [];
        $novoEnvio = [
            'nomeDocumento' => basename($_FILES["arquivo"]["name"]),
            'description' => $_POST['datalhesArquivo'],
            'type' => $confirmacao
        ];
        $envios[] = $novoEnvio;
        file_put_contents('arquivos.json', json_encode($envios));
        echo "O arquivo " . htmlspecialchars(basename($_FILES["arquivo"]["name"])) . " foi enviado com sucesso!";
        header("Location: index.html");
        exit(); 
    } else {
        echo "Erro ao enviar arquivo!";
    }
}
?>
