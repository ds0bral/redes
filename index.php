<?php 
include 'header.php'; 

// Ir buscar as 2 viaturas inseridas mais recentemente
$stmt = $pdo->query("SELECT * FROM viaturas ORDER BY id DESC LIMIT 2");
$ultimas_viaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-danger">Bem-vindo à QualiAuto</h1>
        <p class="lead text-muted">Conheça os nossos destaques da semana.</p>
    </div>

    <section id="galeria-destaques" class="row g-4 justify-content-center">
        <?php if (count($ultimas_viaturas) > 0): ?>
            <?php foreach ($ultimas_viaturas as $carro): ?>
                <div class="col-md-4">
                    <div class="item-galeria">
                        <img src="IMG/<?php echo htmlspecialchars($carro['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($carro['modelo']); ?>" style="object-fit: cover; height: 220px;">
                        <div class="card-body text-center bg-light p-3">
                            <p class="card-text fw-bold mb-0"><?php echo htmlspecialchars($carro['modelo']); ?> (<?php echo htmlspecialchars($carro['ano']); ?>)</p>
                            <span class="text-danger fw-bold"><?php echo number_format($carro['preco'], 0, ',', ' '); ?> &euro;</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">Ainda não existem viaturas em destaque.</p>
            </div>
        <?php endif; ?>
    </section>

    <section id="lancamento" class="mt-5 p-5 bg-light rounded-3 text-center border">
        <h2 class="text-danger"><i class="fas fa-rocket"></i> Próximo Grande Lançamento</h2>
        <div id="contador" class="display-5 fw-bold mt-3">A carregar...</div>
    </section>
</main>

<?php include 'footer.php'; ?>