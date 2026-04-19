<?php
include 'config.php';
header('Content-Type: application/json');

try {
    // Retorna as 2 últimas viaturas adicionadas ao stock
    $stmt = $pdo->query("SELECT * FROM viaturas ORDER BY id DESC LIMIT 2");
    $viaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($viaturas);
} catch (PDOException $e) {
    echo json_encode(["erro" => "Não foi possível carregar as viaturas."]);
}
?>