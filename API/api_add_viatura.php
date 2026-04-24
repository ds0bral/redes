<?php
session_start();
include '../config.php';
header('Content-Type: application/json');
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') { exit(json_encode(["sucesso" => false])); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_img = "default.png";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nome_img = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../IMG/' . $nome_img);
    }
    
    $stmt = $pdo->prepare("INSERT INTO viaturas (modelo, preco, ano, imagem) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$_POST['modelo'], $_POST['preco'], $_POST['ano'], $nome_img])) {
        echo json_encode(["sucesso" => true, "mensagem" => "Viatura adicionada!"]);
    } else { echo json_encode(["sucesso" => false, "mensagem" => "Erro ao guardar."]); }
}
?>