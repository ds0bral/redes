<?php
include '../config.php';
header('Content-Type: application/json');
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') { exit(json_encode(["sucesso" => false])); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nome_img = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../IMG/' . $nome_img);
        $stmt = $pdo->prepare("UPDATE viaturas SET modelo=?, preco=?, ano=?, imagem=? WHERE id=?");
        $exec = $stmt->execute([$_POST['modelo'], $_POST['preco'], $_POST['ano'], $nome_img, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("UPDATE viaturas SET modelo=?, preco=?, ano=? WHERE id=?");
        $exec = $stmt->execute([$_POST['modelo'], $_POST['preco'], $_POST['ano'], $_POST['id']]);
    }
    echo json_encode(["sucesso" => $exec, "mensagem" => $exec ? "Atualizado com sucesso!" : "Erro."]);
}
?>