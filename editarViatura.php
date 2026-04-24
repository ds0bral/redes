<?php 
include 'header.php'; 
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') { header("Location: viaturas.php"); exit; }
?>
<main class="container my-5">
    <h2>Editar Viatura</h2>
    <div id="msg-feedback"></div>
    <form id="form-editar-viatura" enctype="multipart/form-data">
        <input type="hidden" id="id_viatura" name="id">
        <div class="mb-3"><label>Modelo</label><input type="text" id="modelo" name="modelo" class="form-control" required></div>
        <div class="mb-3"><label>Preço (€)</label><input type="number" step="0.01" id="preco" name="preco" class="form-control" required></div>
        <div class="mb-3"><label>Ano</label><input type="number" id="ano" name="ano" class="form-control" required></div>
        <div class="mb-3">
            <img id="img-atual" src="" style="width: 150px; display: none;" class="mb-2 border"><br>
            <label>Substituir Imagem (Opcional)</label><input type="file" name="imagem" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</main>
<script>
const id = new URLSearchParams(window.location.search).get('id');
if (id) {
    fetch(`API/api_get_viatura.php?id=${id}`).then(res => res.json()).then(carro => {
        document.getElementById('id_viatura').value = carro.id;
        document.getElementById('modelo').value = carro.modelo;
        document.getElementById('preco').value = carro.preco;
        document.getElementById('ano').value = carro.ano;
        if(carro.imagem) {
            document.getElementById('img-atual').src = `IMG/${carro.imagem}`;
            document.getElementById('img-atual').style.display = 'block';
        }
    });
}
document.getElementById("form-editar-viatura").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch('API/api_update_viatura.php', { method: 'POST', body: new FormData(this) })
    .then(res => res.json())
    .then(data => {
        document.getElementById("msg-feedback").innerHTML = `<div class='alert alert-${data.sucesso ? 'success' : 'danger'}'>${data.mensagem}</div>`;
        if(data.sucesso) setTimeout(() => window.location.href = 'viaturas.php', 1500);
    });
});
</script>
<?php include 'footer.php'; ?>