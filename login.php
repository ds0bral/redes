<?php include 'header.php'; ?>
<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Login</h3>
        <div id="msg-login"></div>
        <form id="form-login">
            <div class="mb-1">
                <label class="form-label">Utilizador</label>
                <input type="text" id="user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" id="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Entrar</button>
        </form>
        <p class="text-center mt-3"><small>Ainda não tem conta? <a href="registo.php">Registe-se</a></small></p>
    </div>
</main>
<script>
document.getElementById("form-login").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch('API/api_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user: document.getElementById("user").value, pass: document.getElementById("pass").value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.sucesso) window.location.href = "dashboard.php";
        else document.getElementById("msg-login").innerHTML = `<div class='alert alert-danger'>${data.mensagem}</div>`;
    });
});
</script>
<?php include 'footer.php'; ?>