window.addEventListener("load", function () {

    // --- CÓDIGO COMUM A TODAS AS PÁGINAS ---
    const anoAtual = document.getElementById("anoAtual");
    if (anoAtual) {
        anoAtual.textContent = new Date().getFullYear();
    }

    // --- CÓDIGO DA PÁGINA: index.php (Fetch API para Viaturas JSON) ---
    const galeria = document.getElementById("galeria-destaques");
    const loadingGaleria = document.getElementById("loading-galeria");

    if (galeria && loadingGaleria) {
        fetch('API/api_viaturas.php')
            .then(response => response.json())
            .then(data => {
                loadingGaleria.style.display = 'none';

                if (data.length > 0) {
                    data.forEach(carro => {
                        let precoFormatado = new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' }).format(carro.preco);

                        galeria.innerHTML += `
                            <div class="col-md-4">
                                <div class="item-galeria">
                                    <img src="IMG/${carro.imagem}" class="card-img-top" alt="${carro.modelo}" style="object-fit: cover; height: 220px;">
                                    <div class="card-body text-center bg-light p-3">
                                        <p class="card-text fw-bold mb-0">${carro.modelo} (${carro.ano})</p>
                                        <span class="text-danger fw-bold">${precoFormatado}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    galeria.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Ainda não existem viaturas em destaque.</p></div>';
                }
            })
            .catch(error => {
                console.error('Erro ao carregar viaturas:', error);
                loadingGaleria.innerHTML = '<p class="text-danger">Erro ao carregar os dados.</p>';
            });
    }

    // --- CÓDIGO DA PÁGINA: index.php (Contador) ---
    const contadorElemento = document.getElementById("contador");
    if (contadorElemento) {
        let dataLancamento = new Date().getTime() + (2 * 24 * 60 * 60 * 1000);

        let timer = setInterval(function () {
            let agora = new Date().getTime();
            let distancia = dataLancamento - agora;

            let dias = Math.floor(distancia / (1000 * 60 * 60 * 24));
            let horas = Math.floor((distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
            let segundos = Math.floor((distancia % (1000 * 60)) / 1000);

            contadorElemento.innerHTML = dias + "d " + horas + "h " + minutos + "m " + segundos + "s ";

            if (distancia < 0) {
                clearInterval(timer);
                contadorElemento.innerHTML = "LANÇAMENTO OFICIAL EM PORTUGAL!";
            }
        }, 1000);
    }

    // --- CÓDIGO DA PÁGINA: financiamento.php ---
    const btnCalcular = document.getElementById("btn-calcular");
    if (btnCalcular) {
        btnCalcular.addEventListener("click", function () {
            let valor = document.getElementById("valor").value;
            let meses = document.getElementById("meses").value;
            let resultadoDiv = document.getElementById("resultado-financiamento");

            let valorNum = parseFloat(valor);
            let mesesNum = parseInt(meses);

            if (isNaN(valorNum) || isNaN(mesesNum) || valorNum <= 0 || mesesNum <= 0) {
                resultadoDiv.innerHTML = "Por favor, insira valores válidos.";
                resultadoDiv.style.color = "red";
            } else {
                let taxaJuroAnual = 0.05;
                let taxaJuroMensal = taxaJuroAnual / 12;
                let mensalidade = (valorNum * taxaJuroMensal) / (1 - Math.pow(1 + taxaJuroMensal, -mesesNum));

                let mensalidadeFormatada = new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' }).format(mensalidade);

                resultadoDiv.innerHTML = `A sua mensalidade será de: ${mensalidadeFormatada}`;
                resultadoDiv.style.color = "#6a994e";
            }
        });
    }

    // --- CÓDIGO DA PÁGINA: contacto.php (Envio de Email com Fetch API) ---
    const formContacto = document.getElementById("form-contacto");
    if (formContacto) {
        formContacto.addEventListener("submit", function (e) {
            e.preventDefault();

            let nome = document.getElementById("nome").value;
            let email = document.getElementById("email").value;
            let assunto = document.getElementById("assunto").value;
            let mensagem = document.getElementById("mensagem").value;
            let msgFeedback = document.getElementById("msg-feedback");

            let dados = {
                nome: nome,
                email: email,
                assunto: assunto,
                mensagem: mensagem
            };

            msgFeedback.innerHTML = "<div class='alert alert-info'>A enviar mensagem...</div>";

            fetch('API/api_contacto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        msgFeedback.innerHTML = `<div class='alert alert-success'>${data.mensagem}</div>`;
                        formContacto.reset();
                    } else {
                        msgFeedback.innerHTML = `<div class='alert alert-danger'>${data.mensagem}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Erro na comunicação:', error);
                    msgFeedback.innerHTML = "<div class='alert alert-danger'>Ocorreu um erro ao comunicar com o servidor.</div>";
                });
        });
    }

    // --- CÓDIGO EXTRA: Drag and Drop Upload em adicionarViatura.php ---
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('imagem-input');

    if (dropZone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('bg-light', 'border-danger'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('bg-light', 'border-danger'), false);
        });

        dropZone.addEventListener('drop', function (e) {
            let dt = e.dataTransfer;
            let files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                dropZone.innerHTML = `<p class="text-success fw-bold mb-0"><i class="fas fa-check"></i> Ficheiro pronto: ${files[0].name}</p>`;
            }
        }, false);

        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                dropZone.innerHTML = `<p class="text-success fw-bold mb-0"><i class="fas fa-check"></i> Ficheiro pronto: ${fileInput.files[0].name}</p>`;
            }
        });
    }
});