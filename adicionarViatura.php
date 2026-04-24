<?php 
include 'header.php'; 
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') { header("Location: viaturas.php"); exit; }
?>
<main class="container my-5">
    <h2>Adicionar Viatura</h2>
    <div id="msg-feedback"></div>
    <form id="form-add-viatura" enctype="multipart/form-data">
        <div class="mb-3"><label>Modelo</label><input type="text" name="modelo" class="form-control" required></div>
        <div class="mb-3"><label>Preço (€)</label><input type="number" step="0.01" name="preco" class="form-control" required></div>
        <div class="mb-3"><label>Ano</label><input type="number" name="ano" class="form-control" required></div>
        <div class="mb-3">
            <label>Imagem</label>
            <input type="file" name="imagem" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-danger">Guardar Viatura</button>
    </form>
</main>
<script>
document.getElementById("form-add-viatura").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch('API/api_add_viatura.php', { method: 'POST', body: new FormData(this) })
    .then(res => res.json())
    .then(data => {
        document.getElementById("msg-feedback").innerHTML = `<div class='alert alert-${data.sucesso ? 'success' : 'danger'}'>${data.mensagem}</div>`;
        if (data.sucesso) this.reset();
    });
});
</script>
<?php include 'footer.php'; ?>