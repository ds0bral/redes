<?php
include 'config.php';
include 'header.php';
verificar_admin(); 

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelo = trim($_POST['modelo']);
    $preco = floatval($_POST['preco']);
    $ano = intval($_POST['ano']);
    $nome_imagem = "default.png";

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png'];

        if (in_array($extensao, $extensoes_permitidas)) {
            $nome_imagem = uniqid() . "." . $extensao;
            $caminho_destino = "IMG/" . $nome_imagem;
            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_destino);
        } else {
            $msg = "<div class='alert alert-danger'>Apenas são permitidas imagens JPG, JPEG, PNG.</div>";
        }
    }

    if (empty($msg) && !empty($modelo) && $preco > 0 && $ano > 1900) {
        $stmt = $pdo->prepare("INSERT INTO viaturas (modelo, preco, ano, imagem) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$modelo, $preco, $ano, $nome_imagem])) {
            $msg = "<div class='alert alert-success'>Viatura adicionada com sucesso! <a href='viaturas.php'>Ver Stock</a></div>";
        } else {
            $msg = "<div class='alert alert-danger'>Erro ao adicionar viatura.</div>";
        }
    } elseif (empty($msg)) {
        $msg = "<div class='alert alert-warning'>Por favor, preencha todos os campos corretamente.</div>";
    }
}
?>

<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 500px;">
        <h3 class="text-center mb-4 text-danger"><i class="fas fa-plus-circle"></i> Adicionar Viatura</h3>
        <?php echo $msg; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Marca / Modelo</label>
                <input type="text" name="modelo" class="form-control" required placeholder="Ex: Audi A5">
            </div>
            <div class="mb-3">
                <label class="form-label">Preço (&euro;)</label>
                <input type="number" step="0.01" name="preco" class="form-control" required placeholder="Ex: 25000">
            </div>
            <div class="mb-3">
                <label class="form-label">Ano</label>
                <input type="number" name="ano" class="form-control" required placeholder="Ex: 2024">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Fotografia da Viatura</label>
                <div id="drop-zone" class="border border-secondary border-2 rounded p-4 text-center" style="border-style: dashed !important; cursor: pointer;" onclick="document.getElementById('imagem-input').click();">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Arraste e largue a imagem aqui ou clique para selecionar</p>
                </div>
                <input type="file" name="imagem" id="imagem-input" class="d-none" accept="image/*">
            </div>

            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-outline-secondary">Voltar</a>
                <button type="submit" class="btn btn-danger">Guardar Viatura</button>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>