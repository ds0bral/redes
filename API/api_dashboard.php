<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

$response = [];

// Base stats
$response['total_viaturas'] = $pdo->query("SELECT COUNT(*) FROM viaturas")->fetchColumn();
$response['total_users'] = $pdo->query("SELECT COUNT(*) FROM utilizadores")->fetchColumn();

// Graficos (Só admin)
if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin') {
    $graficos = ['anos' => [], 'viaturasPorAno' => [], 'anosAvg' => [], 'precoMedioPorAno' => []];

    $stmt = $pdo->query("SELECT ano, COUNT(*) AS total FROM viaturas GROUP BY ano ORDER BY ano ASC");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $graficos['anos'][] = (int)$r['ano'];
        $graficos['viaturasPorAno'][] = (int)$r['total'];
    }

    $stmt = $pdo->query("SELECT ano, AVG(preco) AS avg_preco FROM viaturas GROUP BY ano ORDER BY ano ASC");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $graficos['anosAvg'][] = (int)$r['ano'];
        $graficos['precoMedioPorAno'][] = round((float)$r['avg_preco'], 2);
    }
    $response['graficos'] = $graficos;
}

echo json_encode($response);
?>