<?php
include 'header.php';
verificar_autenticacao();

// Estatísticas base (para todos)
$total_viaturas = $pdo->query("SELECT COUNT(*) FROM viaturas")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM utilizadores")->fetchColumn();

// Só calcula dados dos gráficos se for admin
$anos = $viaturasPorAno = $anosAvg = $precoMedioPorAno = [];
if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin') {

    // 1) Viaturas por ano
    $stmt = $pdo->query("SELECT ano, COUNT(*) AS total FROM viaturas GROUP BY ano ORDER BY ano ASC");
    $rowsAno = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rowsAno as $r) {
        $anos[] = (int)$r['ano'];
        $viaturasPorAno[] = (int)$r['total'];
    }

    // 2) Preço médio por ano
    $stmt = $pdo->query("SELECT ano, AVG(preco) AS avg_preco FROM viaturas GROUP BY ano ORDER BY ano ASC");
    $rowsAvg = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rowsAvg as $r) {
        $anosAvg[] = (int)$r['ano'];
        $precoMedioPorAno[] = round((float)$r['avg_preco'], 2);
    }
}

// Dados para JS (só vão ser usados no admin)
$jsAnos = json_encode($anos, JSON_UNESCAPED_UNICODE);
$jsViaturasPorAno = json_encode($viaturasPorAno, JSON_UNESCAPED_UNICODE);

$jsAnosAvg = json_encode($anosAvg, JSON_UNESCAPED_UNICODE);
$jsPrecoMedio = json_encode($precoMedioPorAno, JSON_UNESCAPED_UNICODE);
?>

<main class="container my-5">
    <div class="bg-white p-5 rounded shadow">
        <h1 class="text-danger mb-4">Painel de Controlo</h1>

        <div class="alert alert-success">
            Bem-vindo de volta, <strong><?php echo htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); ?></strong>!
        </div>

        <div class="row mt-4 g-3">
            <div class="col-md-4">
                <div class="card p-3 bg-light text-center border-primary shadow-sm">
                    <h5 class="text-primary"><i class="fas fa-users"></i> Total Utilizadores</h5>
                    <h2 class="fw-bold"><?php echo (int)$total_users; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-light text-center border-danger shadow-sm">
                    <h5 class="text-danger"><i class="fas fa-car"></i> Viaturas em Stock</h5>
                    <h2 class="fw-bold"><?php echo (int)$total_viaturas; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-light">
                    <h5><i class="fas fa-id-card"></i> O Teu Perfil</h5>
                    <p class="mb-1">
                        Tipo de Conta:
                        <span class="badge bg-primary text-uppercase">
                            <?php echo htmlspecialchars($_SESSION['perfil'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin'): ?>
            <!-- GRÁFICOS (só admin) -->
            <div class="mt-5 border-top pt-4">
                <h4 class="mb-3">Estatísticas (Gráficos)</h4>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card p-3 shadow-sm">
                            <h6 class="mb-3"><i class="fas fa-chart-bar"></i> Viaturas por Ano</h6>
                            <canvas id="chartViaturasAno" height="120"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card p-3 shadow-sm">
                            <h6 class="mb-3"><i class="fas fa-chart-line"></i> Preço Médio por Ano</h6>
                            <canvas id="chartPrecoMedio" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart.js (CDN) + JS (só admin) -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const anos = <?php echo $jsAnos; ?>;
                const viaturasPorAno = <?php echo $jsViaturasPorAno; ?>;

                const anosAvg = <?php echo $jsAnosAvg; ?>;
                const precoMedio = <?php echo $jsPrecoMedio; ?>;

                new Chart(document.getElementById('chartViaturasAno'), {
                    type: 'bar',
                    data: { labels: anos, datasets: [{ label: 'Nº de viaturas', data: viaturasPorAno }] },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: true } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });

                new Chart(document.getElementById('chartPrecoMedio'), {
                    type: 'line',
                    data: { labels: anosAvg, datasets: [{ label: 'Preço médio (€)', data: precoMedio, tension: 0.25 }] },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: true } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            </script>

            <!-- Ferramentas de gestão (admin) -->
            <div class="mt-5 border-top pt-4">
                <h4>Ferramentas de Gestão (Administrador)</h4>
                <br>
                <a href="adicionarViatura.php" class="btn btn-danger">
                    <i class="fas fa-plus"></i> Adicionar Viatura
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>