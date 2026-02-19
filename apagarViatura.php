<?php
include 'config.php';
verificar_admin(); // Apenas administradores podem apagar

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM viaturas WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: viaturas.php?msg=apagado");
exit();
?>