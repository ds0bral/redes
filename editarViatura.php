<?php 
include 'header.php'; 
verificar_admin(); // Apenas administradores podem aceder

$msg = "";
$carro = null;

// Ir buscar os dados atuais da viatura ao entrar na página
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM viaturas WHERE id = ?");
    $stmt->execute([$id]);
    $carro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$carro) {
        die("Viatura não encontrada.");
    }
} else {
    die("ID inválido.");
}

// Processar a atualização quando o formulário é submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelo = trim($_POST['modelo']);
    $preco = floatval($_POST['preco']);
    $ano = intval($_POST['ano']);
    $nome_imagem = $carro['imagem']; // Mantém a imagem atual por defeito se não enviar nenhuma nova

    // Processamento do Upload da Nova Imagem (Opcional)
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($extensao, $extensoes_permitidas)) {
            $nome_imagem = uniqid() . "." . $extensao;
            $caminho_destino = "IMG/" . $nome_imagem;
            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_destino);
        } else {
            $msg = "<div class='alert alert-danger'>Apenas são permitidas imagens JPG, JPEG, PNG ou WEBP.</div>";
        }
    }

    // Guardar as atualizações na Base de Dados
    if (empty($msg) && !empty($modelo) && $preco > 0 && $ano > 1900) {
        $stmt = $pdo->prepare("UPDATE viaturas SET modelo = ?, preco = ?, ano = ?, imagem = ? WHERE id = ?");
        if ($stmt->execute([$modelo, $preco, $ano, $nome_imagem, $id])) {
            
            // Atualiza a variável $carro para mostrar logo as alterações feitas no ecrã
            $carro['modelo'] = $modelo;
            $carro['preco'] = $preco;
            $carro['ano'] = $ano;
            $carro['imagem'] = $nome_imagem;
            
            $msg = "<div class='alert alert-success'>Viatura atualizada com sucesso! <a href='viaturas.php'>Ver Stock</a></div>";
        } else {
            $msg = "<div class='alert alert-danger'>Erro ao atualizar a viatura.</div>";
        }
    } elseif(empty($msg)) {
        $msg = "<div class='alert alert-warning'>Por favor, preencha todos os campos corretamente.</div>";
    }
}
?>

<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 500px;">
        <h3 class="text-center mb-4 text-primary"><i class="fas fa-edit"></i> Editar Viatura</h3>
        <?php echo $msg; ?>
        
        <form method="POST" enctype="multipart/form-data">
            
            <div class="mb-3 text-center">
                <img src="IMG/<?php echo htmlspecialchars($carro['imagem']); ?>" alt="Imagem Atual" class="img-thumbnail" style="max-height: 150px;">
            </div>

            <div class="mb-3">
                <label class="form-label">Marca / Modelo</label>
                <input type="text" name="modelo" class="form-control" required value="<?php echo htmlspecialchars($carro['modelo']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Preço (&euro;)</label>
                <input type="number" step="0.01" name="preco" class="form-control" required value="<?php echo htmlspecialchars($carro['preco']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Ano</label>
                <input type="number" name="ano" class="form-control" required value="<?php echo htmlspecialchars($carro['ano']); ?>">
            </div>
            <div class="mb-4">
                <label class="form-label">Nova Fotografia (Opcional)</label>
                <input type="file" name="imagem" class="form-control" accept="image/*">
                <small class="text-muted">Deixe este campo em branco se quiser manter a imagem atual.</small>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="viaturas.php" class="btn btn-outline-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Atualizar Viatura</button>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>