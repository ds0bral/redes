<?php
include '../config.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM viaturas ORDER BY ano DESC");
    $viaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($viaturas);
} catch (PDOException $e) {
    echo json_encode(["erro" => "Erro ao carregar viaturas."]);
}
?>