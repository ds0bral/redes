<?php include 'header.php'; ?>
<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Criar Conta</h3>
        <div id="msg-registo"></div>
        <form id="form-registo">
            <div class="mb-3">
                <label class="form-label">Utilizador</label>
                <input type="text" id="user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" id="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Registar</button>
        </form>
        <p class="text-center mt-3"><small>Já tem conta? <a href="login.php">Login</a></small></p>
    </div>
</main>
<script>
document.getElementById("form-registo").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch('API/api_registo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user: document.getElementById("user").value, pass: document.getElementById("pass").value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.sucesso) {
            document.getElementById("msg-registo").innerHTML = `<div class='alert alert-success'>${data.mensagem} <a href='login.php'>Faça Login</a>.</div>`;
            this.reset();
        } else {
            document.getElementById("msg-registo").innerHTML = `<div class='alert alert-danger'>${data.mensagem}</div>`;
        }
    });
});
</script>
<?php include 'footer.php'; ?>