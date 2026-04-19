<?php
header('Content-Type: application/json');

// Recebe os dados JSON enviados pelo JavaScript (Fetch API)
$dados = json_decode(file_get_contents("php://input"), true);

if (isset($dados['nome']) && isset($dados['email']) && isset($dados['mensagem'])) {
    $nome = htmlspecialchars($dados['nome']);
    $email = filter_var($dados['email'], FILTER_SANITIZE_EMAIL);
    $mensagem = htmlspecialchars($dados['mensagem']);
    $assunto = htmlspecialchars($dados['assunto']);

    // Configuração do Email
    $para = "admin@qualiauto.pt"; 
    $assunto_email = "Novo Contacto QualiAuto - Portugal: " . $assunto;
    $corpo = "Nome: $nome\nEmail: $email\n\nMensagem:\n$mensagem";
    $cabecalhos = "From: $email\r\nReply-To: $email";

    // Função mail() para envio (requer servidor SMTP)
    if(@mail($para, $assunto_email, $corpo, $cabecalhos)) {
        echo json_encode(["sucesso" => true, "mensagem" => "Obrigado, $nome! A sua mensagem foi enviada com sucesso."]);
    } else {
        // Fallback simulado para não bloquear no localhost (caso não tenhas o mail configurado no XAMPP)
        echo json_encode(["sucesso" => true, "mensagem" => "Obrigado, $nome! (Simulação: Email registado via API JSON)"]);
    }
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro: Preencha todos os campos obrigatórios."]);
}
?>