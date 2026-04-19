<?php include 'header.php'; ?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-danger">Bem-vindo à QualiAuto</h1>
        <p class="lead text-muted">Conheça as nossas viaturas mais recentes em Portugal.</p>
    </div>

    <section id="galeria-destaques" class="row g-4 justify-content-center">
        <div class="col-12 text-center" id="loading-galeria">
            <p class="text-muted">A carregar viaturas...</p>
        </div>
    </section>

    <section id="lancamento" class="mt-5 p-5 bg-light rounded-3 text-center border">
        <h2 class="text-danger"><i class="fas fa-rocket"></i> Próximo Grande Lançamento</h2>
        <div id="contador" class="display-5 fw-bold mt-3">A carregar...</div>
    </section>
</main>

<?php include 'footer.php'; ?>