<?php
include 'header.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação de Token
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf_token']) {
        die("Erro de segurança: Token inválido.");
    }

    $novo_user = trim($_POST['user']);
    $nova_pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    // Verifica se o utilizador já existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilizadores WHERE username = ?");
    $stmt->execute([$novo_user]);
    
    if ($stmt->fetchColumn() > 0) {
        $msg = "<div class='alert alert-danger'>O nome de utilizador já existe.</div>";
    } else {
        // Insere na BD com o perfil 'user'
        $stmt = $pdo->prepare("INSERT INTO utilizadores (username, password, perfil) VALUES (?, ?, 'user')");
        if ($stmt->execute([$novo_user, $nova_pass])) {
            $msg = "<div class='alert alert-success'>Registo efetuado com sucesso! <a href='login.php'>Faça Login</a>.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Erro ao registar. Tente novamente.</div>";
        }
    }
}
?>

<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Criar Conta</h3>
        <?php echo $msg; ?>
        <form method="POST">
            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label class="form-label">Utilizador</label>
                <input type="text" name="user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Registar</button>
        </form>
        <p class="text-center mt-3"><small>Já tem conta? <a href="login.php">Login</a></small></p>
    </div>
</main>

<?php include 'footer.php'; ?>