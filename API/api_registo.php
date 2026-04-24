<?php
include '../config.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"), true);
if (isset($dados['user']) && isset($dados['pass'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilizadores WHERE username = ?");
    $stmt->execute([trim($dados['user'])]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(["sucesso" => false, "mensagem" => "Utilizador já existe."]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilizadores (username, password, perfil) VALUES (?, ?, 'user')");
        if ($stmt->execute([trim($dados['user']), password_hash($dados['pass'], PASSWORD_DEFAULT)])) {
            echo json_encode(["sucesso" => true, "mensagem" => "Registo com sucesso!"]);
        } else { echo json_encode(["sucesso" => false, "mensagem" => "Erro na base de dados."]); }
    }
}
?>