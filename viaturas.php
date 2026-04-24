<?php 
include 'header.php'; 
$isAdmin = (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin') ? 'true' : 'false';
?>
<main class="container my-5">
    <h1 class="mb-4 text-danger"><i class="fas fa-car"></i> O Nosso Stock em Portugal</h1>
    <div id="msg-feedback"></div>
    <div class="table-responsive">
        <table class="table table-hover table-striped shadow-sm align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Fotografia</th>
                    <th>Marca / Modelo</th>
                    <th>Preço</th>
                    <th>Ano</th>
                    <th id="th-acoes" style="display: none;">Ações</th>
                </tr>
            </thead>
            <tbody id="tabela-viaturas">
                <tr><td colspan="5" class="text-center py-4 text-muted">A carregar viaturas...</td></tr>
            </tbody>
        </table>
    </div>
</main>
<script>
const isAdmin = <?php echo $isAdmin; ?>;
window.addEventListener('DOMContentLoaded', () => {
    if(isAdmin) document.getElementById('th-acoes').style.display = 'table-cell';
    carregarViaturas();
});

function carregarViaturas() {
    fetch('API/api_get_allViatura.php')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('tabela-viaturas');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Não existem viaturas em stock.</td></tr>';
                return;
            }
            data.forEach(carro => {
                let precoFormatado = new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' }).format(carro.preco);
                let botoes = isAdmin ? `<td><a href="editarViatura.php?id=${carro.id}" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i> Editar</a> <button onclick="apagarViatura(${carro.id})" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Apagar</button></td>` : '';
                tbody.innerHTML += `<tr><td class="text-center"><img src="IMG/${carro.imagem}" class="img-thumbnail shadow-sm" style="width: 240px; height: 160px; object-fit: cover;"></td><td class="fw-bold">${carro.modelo}</td><td class="fw-bold text-success">${precoFormatado}</td><td><span class="badge bg-secondary">${carro.ano}</span></td>${botoes}</tr>`;
            });
        });
}

function apagarViatura(id) {
    if (confirm("Tem a certeza que deseja apagar esta viatura?")) {
        fetch('API/api_delete_viatura.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        }).then(res => res.json()).then(data => {
            document.getElementById("msg-feedback").innerHTML = `<div class='alert alert-${data.sucesso ? 'success' : 'danger'}'>${data.mensagem}</div>`;
            if (data.sucesso) carregarViaturas();
        });
    }
}
</script>
<?php include 'footer.php'; ?>