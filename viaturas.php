<?php 
include 'header.php'; 

// Busca as viaturas à Base de Dados
$stmt = $pdo->query("SELECT * FROM viaturas ORDER BY ano DESC");
$stock_viaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container my-5">
    <h1 class="mb-4 text-danger"><i class="fas fa-car"></i> O Nosso Stock</h1>

    <?php
    if (isset($_GET['msg']) && $_GET['msg'] == 'apagado') {
        echo "<div class='alert alert-success'>Viatura eliminada com sucesso!</div>";
    }
    ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped shadow-sm align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Marca / Modelo</th>
                    <th>Preço</th>
                    <th>Ano</th>
                    <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin'): ?>
                        <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (count($stock_viaturas) > 0): ?>
                    <?php foreach($stock_viaturas as $carro): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($carro['modelo']); ?></td>
                        <td class="fw-bold text-success"><?php echo number_format($carro['preco'], 2, ',', ' '); ?> &euro;</td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($carro['ano']); ?></span></td>
                        
                        <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin'): ?>
                            <td>
                                <a href="editarViatura.php?id=<?php echo $carro['id']; ?>" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="apagarViatura.php?id=<?php echo $carro['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem a certeza que deseja apagar esta viatura?');">
                                    <i class="fas fa-trash"></i> Apagar
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-4">Não existem viaturas em stock neste momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'footer.php'; ?>