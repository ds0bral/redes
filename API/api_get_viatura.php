<?php
include '../config.php';
header('Content-Type: application/json');
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM viaturas WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}
?>