<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"), true);
if (isset($dados['user']) && isset($dados['pass'])) {
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE username = ?");
    $stmt->execute([trim($dados['user'])]);
    $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilizador && password_verify($dados['pass'], $utilizador['password'])) {
        $_SESSION['sessao_ativa'] = true;
        $_SESSION['user_id'] = $utilizador['username'];
        $_SESSION['perfil'] = $utilizador['perfil'];
        echo json_encode(["sucesso" => true]);
    } else { echo json_encode(["sucesso" => false, "mensagem" => "Dados incorretos."]); }
}
?>