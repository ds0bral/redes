<?php
include 'header.php';
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u_login = trim($_POST['user']);
    $p_login = $_POST['pass'];

    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE username = ?");
    $stmt->execute([$u_login]);
    $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o utilizador existe e a password está correta
    if ($utilizador && password_verify($p_login, $utilizador['password'])) {
        $_SESSION['sessao_ativa'] = true;
        $_SESSION['user_id'] = $utilizador['username'];
        $_SESSION['perfil'] = $utilizador['perfil'];

        header("Location: dashboard.php");
        exit();
    } else {
        $erro = "<div class='alert alert-danger'>Dados incorretos.</div>";
    }
}
?>

<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Login</h3>
        <?php echo $erro; ?>
        <form method="POST">
            <div class="mb-1">
                <label class="form-label">Utilizador</label>
                <input type="text" name="user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Entrar</button>
        </form>
        <p class="text-center mt-3"><small>Ainda não tem conta? <a href="registo.php">Registe-se</a></small></p>
    </div>
</main>

<?php include 'footer.php'; ?>