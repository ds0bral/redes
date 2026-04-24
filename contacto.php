<?php include 'header.php'; ?>

<main class="container my-5">
    <h1>Fale Connosco</h1>
    <div class="row mt-4">
        <div class="col-md-6">
            <form id="form-contacto" class="card p-4 shadow-sm">
                <div id="msg-feedback"></div>
                
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" id="nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assunto</label>
                    <select id="assunto" class="form-select">
                        <option value="Informações">Informações</option>
                        <option value="Vendas">Vendas</option>
                        <option value="Suporte">Suporte</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mensagem</label>
                    <textarea id="mensagem" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100">Enviar Mensagem</button>
            </form>
        </div>
        <div class="col-md-6 mt-4 mt-md-0">
            <div class="card h-100 shadow-sm p-2">
                <iframe src="https://maps.google.com/maps?q=Viseu&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div>
</main>

<script>
document.getElementById("form-contacto").addEventListener("submit", function (e) {
    e.preventDefault();
    const msgFeedback = document.getElementById("msg-feedback");
    msgFeedback.innerHTML = "<div class='alert alert-info'>A processar o envio do email...</div>";

    const dados = {
        nome: document.getElementById("nome").value,
        email: document.getElementById("email").value,
        assunto: document.getElementById("assunto").value,
        mensagem: document.getElementById("mensagem").value
    };

    fetch('API/api_contacto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            msgFeedback.innerHTML = `<div class='alert alert-success'>${data.mensagem}</div>`;
            document.getElementById("form-contacto").reset();
        } else {
            msgFeedback.innerHTML = `<div class='alert alert-danger'>${data.mensagem}</div>`;
        }
    })
    .catch(() => {
        msgFeedback.innerHTML = "<div class='alert alert-danger'>Erro ao comunicar com o servidor de email.</div>";
    });
});
</script>

<?php include 'footer.php'; ?>