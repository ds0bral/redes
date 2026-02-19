<?php
include 'header.php';
verificar_admin(); // Apenas administradores podem aceder

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelo = trim($_POST['modelo']);
    $preco = floatval($_POST['preco']);
    $ano = intval($_POST['ano']);
    $nome_imagem = "default.png"; // Imagem por defeito

    // Processamento do Upload da Imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png'];

        if (in_array($extensao, $extensoes_permitidas)) {
            // Gera um nome único para não haver ficheiros substituídos por engano
            $nome_imagem = uniqid() . "." . $extensao;
            $caminho_destino = "IMG/" . $nome_imagem;

            // Move a imagem da pasta temporária para a pasta IMG
            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_destino);
        } else {
            $msg = "<div class='alert alert-danger'>Apenas são permitidas imagens JPG, JPEG, PNG ou WEBP.</div>";
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
                <input type="text" name="modelo" class="form-control" required placeholder="Ex: TESLA - Model 3">
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
                <input type="file" name="imagem" class="form-control" accept="image/*">
            </div>
            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-outline-secondary">Voltar</a>
                <button type="submit" class="btn btn-danger">Guardar Viatura</button>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>