<?php
include 'header.php';
if (!isset($_SESSION['sessao_ativa'])) {
    header("Location: login.php");
    exit();
}
$isAdmin = ($_SESSION['perfil'] === 'admin') ? 'true' : 'false';
?>

<main class="container my-5">
    <div class="bg-white p-5 rounded shadow">
        <h1 class="text-danger mb-4">Painel de Controlo</h1>
        <div class="alert alert-success">
            Bem-vindo de volta, <strong><?php echo htmlspecialchars($_SESSION['user_id']); ?></strong>!
        </div>

        <div class="row mt-4 g-3">
            <div class="col-md-4">
                <div class="card p-3 bg-light text-center border-primary shadow-sm">
                    <h5 class="text-primary"><i class="fas fa-users"></i> Utilizadores</h5>
                    <h2 class="fw-bold" id="tot-users">...</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-light text-center border-danger shadow-sm">
                    <h5 class="text-danger"><i class="fas fa-car"></i> Viaturas em Stock</h5>
                    <h2 class="fw-bold" id="tot-viaturas">...</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-light">
                    <h5><i class="fas fa-id-card"></i> O Teu Perfil</h5>
                    <p class="mb-1">Tipo: <span class="badge bg-primary text-uppercase"><?php echo $_SESSION['perfil']; ?></span></p>
                </div>
            </div>
        </div>

        <?php if ($_SESSION['perfil'] === 'admin'): ?>
            <div class="mt-5 border-top pt-4">
                <h4 class="mb-3">Estatísticas (Gráficos)</h4>
                <div class="row g-4">
                    <div class="col-lg-6"><div class="card p-3 shadow-sm"><canvas id="chartViaturasAno" height="120"></canvas></div></div>
                    <div class="col-lg-6"><div class="card p-3 shadow-sm"><canvas id="chartPrecoMedio" height="120"></canvas></div></div>
                </div>
            </div>
            <div class="mt-5 border-top pt-4">
                <a href="adicionarViatura.php" class="btn btn-danger"><i class="fas fa-plus"></i> Adicionar Viatura</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    fetch('API/api_dashboard.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('tot-users').innerText = data.total_users;
            document.getElementById('tot-viaturas').innerText = data.total_viaturas;

            if (<?php echo $isAdmin; ?> && data.graficos) {
                new Chart(document.getElementById('chartViaturasAno'), {
                    type: 'bar',
                    data: { labels: data.graficos.anos, datasets: [{ label: 'Nº de viaturas', data: data.graficos.viaturasPorAno }] },
                    options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
                });

                new Chart(document.getElementById('chartPrecoMedio'), {
                    type: 'line',
                    data: { labels: data.graficos.anosAvg, datasets: [{ label: 'Preço médio (€)', data: data.graficos.precoMedioPorAno, tension: 0.25 }] },
                    options: { responsive: true, scales: { y: { beginAtZero: true } } }
                });
            }
        });
});
</script>

<?php include 'footer.php'; ?>