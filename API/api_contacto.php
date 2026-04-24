<?php
header('Content-Type: application/json');

// Recebe os dados JSON enviados pelo Javascript
$dados = json_decode(file_get_contents("php://input"), true);

if (isset($dados['nome']) && isset($dados['email']) && isset($dados['mensagem'])) {
    $nome = htmlspecialchars($dados['nome']);
    $email = filter_var($dados['email'], FILTER_SANITIZE_EMAIL);
    $mensagem = htmlspecialchars($dados['mensagem']);
    $assunto = htmlspecialchars($dados['assunto']);

    // Configuração base do email nativo do PHP
    $para = "admin@qualiauto.pt"; 
    $assunto_email = "Novo Contacto QualiAuto - Portugal: " . $assunto;
    $corpo = "Nome: $nome\nEmail: $email\n\nMensagem:\n$mensagem";
    
    // Cabeçalhos para o email saber de quem vem
    $cabecalhos = "From: webmaster@qualiauto.pt\r\n";
    $cabecalhos .= "Reply-To: $email\r\n";
    $cabecalhos .= "X-Mailer: PHP/" . phpversion();

    // A função mail() vai enviar o email para o Mail Catcher do Laragon
    if(mail($para, $assunto_email, $corpo, $cabecalhos)) {
        echo json_encode(["sucesso" => true, "mensagem" => "Obrigado, $nome! A sua mensagem foi enviada com sucesso."]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Ocorreu um erro no servidor ao tentar enviar a mensagem."]);
    }
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Por favor, preencha todos os campos obrigatórios."]);
}
?>