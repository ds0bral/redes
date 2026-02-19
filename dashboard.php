<?php
include 'header.php';
verificar_autenticacao();

// Buscar Estatísticas à Base de Dados
$total_viaturas = $pdo->query("SELECT COUNT(*) FROM viaturas")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM utilizadores")->fetchColumn();
?>

<main class="container my-5">
    <div class="bg-white p-5 rounded shadow">
        <h1 class="text-danger mb-4">Painel de Controlo</h1>

        <div class="alert alert-success">
            Bem-vindo de volta, <strong><?php echo htmlspecialchars($_SESSION['user_id']); ?></strong>!
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-3 bg-light text-center border-primary shadow-sm">
                    <h5 class="text-primary"><i class="fas fa-users"></i> Total Utilizadores</h5>
                    <h2 class="fw-bold"><?php echo $total_users; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-light text-center border-danger shadow-sm">
                    <h5 class="text-danger"><i class="fas fa-car"></i> Viaturas em Stock</h5>
                    <h2 class="fw-bold"><?php echo $total_viaturas; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-light">
                    <h5><i class="fas fa-id-card"></i> O Teu Perfil</h5>
                    <p class="mb-1">Tipo de Conta: <span
                            class="badge bg-primary text-uppercase"><?php echo htmlspecialchars($_SESSION['perfil']); ?></span>
                    </p>
                </div>
            </div>
        </div>

        <?php if ($_SESSION['perfil'] === 'admin'): ?>
            <div class="mt-5 border-top pt-4">
                <h4>Ferramentas de Gestão (Administrador)</h4>
                <br>
                <a href="adicionarViatura.php" class="btn btn-danger"><i class="fas fa-plus"></i> Adicionar Viatura</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>