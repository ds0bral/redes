<?php 
include 'header.php'; 
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') { header("Location: viaturas.php"); exit; }
?>
<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 500px;">
        <h3 class="text-center mb-4 text-primary"><i class="fas fa-edit"></i> Editar Viatura</h3>
        <div id="msg-feedback"></div>
        
        <form id="form-editar-viatura" enctype="multipart/form-data">
            <input type="hidden" id="id_viatura" name="id">
            
            <div class="mb-3 text-center">
                <img id="img-atual" src="" alt="Imagem Atual" class="img-thumbnail shadow-sm" style="max-height: 150px; display: none;">
                <p class="text-muted mt-1 small" id="img-atual-text">A carregar imagem atual...</p>
            </div>

            <div class="mb-3">
                <label class="form-label">Marca / Modelo</label>
                <input type="text" id="modelo" name="modelo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Preço (€)</label>
                <input type="number" step="0.01" id="preco" name="preco" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ano</label>
                <input type="number" id="ano" name="ano" class="form-control" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Nova Fotografia (Opcional)</label>
                <div id="drop-zone-edit" class="border border-secondary border-2 rounded p-3 text-center" style="border-style: dashed !important; cursor: pointer;">
                    <i class="fas fa-image fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0 small" id="drop-text-edit">Arraste uma nova imagem para substituir ou clique</p>
                </div>
                <input type="file" name="imagem" id="imagem-input-edit" class="d-none" accept="image/*">
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="viaturas.php" class="btn btn-outline-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Atualizar Viatura</button>
            </div>
        </form>
    </div>
</main>

<script>
// --- LÓGICA DE DRAG AND DROP (EDITAR) ---
const dropZoneEdit = document.getElementById('drop-zone-edit');
const fileInputEdit = document.getElementById('imagem-input-edit');
const dropTextEdit = document.getElementById('drop-text-edit');

dropZoneEdit.addEventListener('click', () => fileInputEdit.click());

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZoneEdit.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }

['dragenter', 'dragover'].forEach(eventName => {
    dropZoneEdit.addEventListener(eventName, () => dropZoneEdit.classList.add('bg-light', 'border-primary'), false);
});
['dragleave', 'drop'].forEach(eventName => {
    dropZoneEdit.addEventListener(eventName, () => dropZoneEdit.classList.remove('bg-light', 'border-primary'), false);
});

dropZoneEdit.addEventListener('drop', (e) => {
    let dt = e.dataTransfer;
    let files = dt.files;
    if (files.length > 0) {
        fileInputEdit.files = files;
        updateDropZoneUIEdit(files[0]);
    }
}, false);

fileInputEdit.addEventListener('change', function() {
    if (this.files.length > 0) updateDropZoneUIEdit(this.files[0]);
});

function updateDropZoneUIEdit(file) {
    dropTextEdit.innerHTML = `<span class="text-primary fw-bold"><i class="fas fa-check"></i> Nova imagem pronta: ${file.name}</span>`;
}

// --- CARREGAR DADOS INICIAIS E SUBMETER VIA FETCH ---
const id = new URLSearchParams(window.location.search).get('id');

if (id) {
    fetch(`API/api_get_viatura.php?id=${id}`)
    .then(res => res.json())
    .then(carro => {
        document.getElementById('id_viatura').value = carro.id;
        document.getElementById('modelo').value = carro.modelo;
        document.getElementById('preco').value = carro.preco;
        document.getElementById('ano').value = carro.ano;
        
        if(carro.imagem) {
            document.getElementById('img-atual').src = `IMG/${carro.imagem}`;
            document.getElementById('img-atual').style.display = 'inline-block';
            document.getElementById('img-atual-text').style.display = 'none';
        }
    });
}

document.getElementById("form-editar-viatura").addEventListener("submit", function(e) {
    e.preventDefault();
    const msgDiv = document.getElementById("msg-feedback");
    msgDiv.innerHTML = "<div class='alert alert-info'>A atualizar...</div>";

    fetch('API/api_update_viatura.php', { 
        method: 'POST', 
        body: new FormData(this) 
    })
    .then(res => res.json())
    .then(data => {
        msgDiv.innerHTML = `<div class='alert alert-${data.sucesso ? 'success' : 'danger'}'>${data.mensagem}</div>`;
        if(data.sucesso) setTimeout(() => window.location.href = 'viaturas.php', 1500);
    })
    .catch(() => msgDiv.innerHTML = "<div class='alert alert-danger'>Erro de comunicação com o servidor.</div>");
});
</script>
<?php include 'footer.php'; ?>