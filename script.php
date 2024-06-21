<?php
// Diretório onde os arquivos serão salvos
ini_set('file_uploads', '1');

// Configurar o tamanho máximo de upload
ini_set('upload_max_filesize', '20M');

// Configurar o tamanho máximo do POST (deve ser maior que upload_max_filesize)
ini_set('post_max_size', '24M');

$diretorioUpload = 'arquivos/';
// Diretório onde os arquivos serão salvos
$diretorioUpload = 'arquivos/';

// Verifica se o diretório existe, se não, cria-o
if (!is_dir($diretorioUpload)) {
    mkdir($diretorioUpload, 0755, true);
}

$mensagemErro = '';
$confirmadoEnvio = 1;

if (isset($_FILES["arquivo"]) && $_FILES["arquivo"]["error"] === UPLOAD_ERR_OK) {
    $arquivoTmpNome = $_FILES["arquivo"]["tmp_name"];
    $nomeArquivo = basename($_FILES["arquivo"]["name"]);
    $caminhoArquivo = $diretorioUpload . $nomeArquivo;

    // Verifica se o arquivo temporário existe
    if (file_exists($arquivoTmpNome)) {
        $confirmacao = mime_content_type($arquivoTmpNome);
        if ($confirmacao === false) {
            $mensagemErro .= "Não foi possível determinar o tipo MIME do arquivo. ";
            $confirmadoEnvio = 0;
        } else {
            // Verifica o tipo MIME do arquivo
            if (strpos($confirmacao, "audio/") === 0 || strpos($confirmacao, "img/") === 0) {
                $mensagemErro .= "Formato de arquivo não suportado! ";
                $confirmadoEnvio = 0;
            } else {
                // Move o arquivo para o diretório de uploads
                if (move_uploaded_file($arquivoTmpNome, $caminhoArquivo)) {
                    echo "O arquivo foi enviado com sucesso.";
                } else {
                    $mensagemErro .= "Falha ao mover o arquivo para o diretório de uploads. ";
                    $confirmadoEnvio = 0;
                }
            }
        }
    } else {
        $mensagemErro .= "O arquivo temporário não foi encontrado. ";
        $confirmadoEnvio = 0;
    }
} else {
    $mensagemErro .= "Nenhum arquivo foi enviado ou houve um erro no upload. ";
    if (isset($_FILES["arquivo"]["error"])) {
        switch ($_FILES["arquivo"]["error"]) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $mensagemErro .= "O arquivo enviado excede o limite de tamanho. ";
                break;
            case UPLOAD_ERR_PARTIAL:
                $mensagemErro .= "O upload do arquivo foi feito parcialmente. ";
                break;
            case UPLOAD_ERR_NO_FILE:
                $mensagemErro .= "Nenhum arquivo foi enviado. ";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $mensagemErro .= "Pasta temporária ausente. ";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $mensagemErro .= "Falha ao escrever o arquivo no disco. ";
                break;
            case UPLOAD_ERR_EXTENSION:
                $mensagemErro .= "Uma extensão PHP interrompeu o upload do arquivo. ";
                break;
            default:
                $mensagemErro .= "Erro desconhecido no upload do arquivo. ";
                break;
        }
    }
}

if ($confirmadoEnvio === 0) {
    echo $mensagemErro;
}
?>
