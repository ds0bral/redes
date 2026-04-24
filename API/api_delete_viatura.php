<?php
session_start();
include '../config.php';
header('Content-Type: application/json');
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') { exit(json_encode(["sucesso" => false])); }

$dados = json_decode(file_get_contents("php://input"), true);
if (isset($dados['id'])) {
    $stmt = $pdo->prepare("SELECT imagem FROM viaturas WHERE id = ?");
    $stmt->execute([$dados['id']]);
    $img = $stmt->fetchColumn();
    if ($img && $img != 'default.png') @unlink('../IMG/' . $img);

    $stmt = $pdo->prepare("DELETE FROM viaturas WHERE id = ?");
    echo json_encode(["sucesso" => $stmt->execute([$dados['id']]), "mensagem" => "Viatura apagada."]);
}
?>