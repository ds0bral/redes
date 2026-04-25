<?php 
include 'header.php'; 
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') { header("Location: viaturas.php"); exit; }
?>
<main class="container my-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="width: 500px;">
        <h3 class="text-center mb-4 text-danger"><i class="fas fa-plus-circle"></i> Adicionar Viatura</h3>
        <div id="msg-feedback"></div>
        <form id="form-add-viatura" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Marca / Modelo</label>
                <input type="text" name="modelo" class="form-control" required placeholder="Ex: Audi A5">
            </div>
            <div class="mb-3">
                <label class="form-label">Preço (€)</label>
                <input type="number" step="0.01" name="preco" class="form-control" required placeholder="Ex: 25000">
            </div>
            <div class="mb-3">
                <label class="form-label">Ano</label>
                <input type="number" name="ano" class="form-control" required placeholder="Ex: 2024">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Fotografia da Viatura</label>
                <div id="drop-zone-add" class="border border-secondary border-2 rounded p-4 text-center" style="border-style: dashed !important; cursor: pointer;">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                    <p class="text-muted mb-0" id="drop-text-add">Arraste e largue a imagem aqui ou clique para selecionar</p>
                </div>
                <input type="file" name="imagem" id="imagem-input-add" class="d-none" accept="image/*" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-outline-secondary">Voltar</a>
                <button type="submit" class="btn btn-danger">Guardar Viatura</button>
            </div>
        </form>
    </div>
</main>

<script>
// --- LÓGICA DE DRAG AND DROP ---
const dropZoneAdd = document.getElementById('drop-zone-add');
const fileInputAdd = document.getElementById('imagem-input-add');
const dropTextAdd = document.getElementById('drop-text-add');

// Clique para abrir
dropZoneAdd.addEventListener('click', () => fileInputAdd.click());

// Evitar comportamento padrão
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZoneAdd.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Efeito visual ao arrastar
['dragenter', 'dragover'].forEach(eventName => {
    dropZoneAdd.addEventListener(eventName, () => dropZoneAdd.classList.add('bg-light', 'border-danger'), false);
});
['dragleave', 'drop'].forEach(eventName => {
    dropZoneAdd.addEventListener(eventName, () => dropZoneAdd.classList.remove('bg-light', 'border-danger'), false);
});

// Ao largar a imagem
dropZoneAdd.addEventListener('drop', (e) => {
    let dt = e.dataTransfer;
    let files = dt.files;
    if (files.length > 0) {
        fileInputAdd.files = files; // Atualiza o input invisível
        updateDropZoneUIAdd(files[0]);
    }
}, false);

// Ao escolher imagem clicando
fileInputAdd.addEventListener('change', function() {
    if (this.files.length > 0) {
        updateDropZoneUIAdd(this.files[0]);
    }
});

function updateDropZoneUIAdd(file) {
    dropTextAdd.innerHTML = `<span class="text-success fw-bold"><i class="fas fa-check"></i> Ficheiro selecionado: ${file.name}</span>`;
}

// --- SUBMISSÃO VIA FETCH API ---
document.getElementById("form-add-viatura").addEventListener("submit", function(e) {
    e.preventDefault();
    const msgDiv = document.getElementById("msg-feedback");
    msgDiv.innerHTML = "<div class='alert alert-info'>A guardar...</div>";

    fetch('API/api_add_viatura.php', { 
        method: 'POST', 
        body: new FormData(this) 
    })
    .then(res => res.json())
    .then(data => {
        msgDiv.innerHTML = `<div class='alert alert-${data.sucesso ? 'success' : 'danger'}'>${data.mensagem}</div>`;
        if (data.sucesso) {
            this.reset();
            dropTextAdd.innerHTML = "Arraste e largue a imagem aqui ou clique para selecionar"; // Repõe texto original
        }
    })
    .catch(() => msgDiv.innerHTML = "<div class='alert alert-danger'>Erro de comunicação com o servidor.</div>");
});
</script>
<?php include 'footer.php'; ?>