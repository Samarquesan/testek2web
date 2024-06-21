$(document).ready(function() {
    $('.arquivosForm').addEventListener('submit', function(e) {
        e.preventDefault(); 

        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('.feedback').html('Enviando arquivo...').removeClass('success error').addClass('loading');
            },
            success: function(response) {
                $('.feedback').html(response).removeClass('loading').addClass('success');
                loadFiles(); 
            },
            error: function() {
                $('.feedback').html('Ocorreu um erro ao enviar o arquivo.').removeClass('loading').addClass('error');
            }
        });
    });

    
    loadFiles();
});

function loadFiles() {
    $.ajax({
        url: 'arquivos.php',
        method: 'GET',
        success: function(response) {
            $('.entradas').html(response);
        },
        error: function() {
            $('.entradas').html('Erro ao carregar arquivos.');
        }
    });
}
