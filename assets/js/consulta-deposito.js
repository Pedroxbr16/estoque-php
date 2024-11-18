
document.addEventListener("DOMContentLoaded", function () {
    const buscarButton = document.getElementById("buscarButton");
    const descricaoInput = document.getElementById("descricao");
    const tipoMaterialSelect = document.getElementById("tipo_material");
    const segmentoSelect = document.getElementById("segmento");
    const tbody = document.querySelector("table tbody");


    // Carregar opções de filtros
    function carregarOpcoes() {
        fetch('../back/depositoController.php?action=listarTiposMaterial')
            .then(response => response.json())
            .then(data => {
                tipoMaterialSelect.innerHTML = '<option selected value="">Todos</option>';
                data.forEach(tipo => {
                    const option = document.createElement("option");
                    option.value = tipo.tipo_material;
                    option.textContent = tipo.tipo_material;
                    tipoMaterialSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erro ao carregar tipos de material:", error));

        fetch('../back/depositoController.php?action=listarSegmentos')
            .then(response => response.json())
            .then(data => {
                segmentoSelect.innerHTML = '<option selected value="">Todos</option>';
                data.forEach(segmento => {
                    const option = document.createElement("option");
                    option.value = segmento.segmento;
                    option.textContent = segmento.segmento;
                    segmentoSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erro ao carregar segmentos:", error));
    }

    // Atualizar tabela com os dados filtrados
    function buscarMateriais() {
        const descricao = descricaoInput.value;
        const tipoMaterial = tipoMaterialSelect.value;
        const segmento = segmentoSelect.value;

        // Monta a URL com os parâmetros de filtro
        const url = `../back/depositoController.php?action=listarMateriais&descricao=${encodeURIComponent(descricao)}&tipo_material=${encodeURIComponent(tipoMaterial)}&segmento=${encodeURIComponent(segmento)}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = ""; // Limpa as linhas anteriores

                if (data.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='9' class='text-center'>Nenhum material encontrado.</td></tr>";
                } else {
                    data.forEach(material => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${material.descricao}</td>
                            <td>${material.unidade_medida}</td>
                            <td>${material.quantidade}</td>
                            <td>${material.deposito}</td>
                            <td>${material.estoque_minimo}</td>
                            <td>${material.estoque_seguranca}</td>
                            <td>${material.tipo_material}</td>
                            <td>${material.segmento}</td>
                            <td>
                                <a href="editar_produto.php?id=${material.id}" class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm excluir-btn" data-id="${material.id}">Excluir</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            })
            .catch(error => console.error("Erro ao buscar materiais:", error));
    }

    // Adicionar evento ao botão buscar
    buscarButton.addEventListener("click", buscarMateriais);

    // Carregar opções ao iniciar
    carregarOpcoes();

    // Monta a URL com os parâmetros de filtro
    const url = `../back/depositoController.php?action=listarMateriais&descricao=${encodeURIComponent(descricao)}&tipo_material=${encodeURIComponent(tipoMaterial)}&segmento=${encodeURIComponent(segmento)}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("table tbody");
            tbody.innerHTML = ""; // Limpa as linhas anteriores

            data.forEach(material => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${material.descricao}</td>
                    <td>${material.unidade_medida}</td>
                    <td>${material.quantidade}</td>
                    <td>${material.deposito}</td>
                    <td>${material.estoque_minimo}</td>
                    <td>${material.estoque_seguranca}</td>
                    <td>${material.tipo_material}</td>
                    <td>${material.segmento}</td>
                `;

                tbody.appendChild(row);
            });
        })
        .catch(error => console.error("Erro ao buscar os dados:", error));
});
document.addEventListener("DOMContentLoaded", function() {
    // Função para adicionar os eventos de clique nos botões Excluir
    function adicionarEventosExcluir() {
        const excluirBotoes = document.querySelectorAll(".excluir-btn");

        excluirBotoes.forEach(botao => {
            botao.addEventListener("click", function() {
                const produtoId = this.getAttribute("data-id");

                if (confirm("Tem certeza que deseja excluir este item?")) {
                    // Faz a requisição para o backend
                    fetch(`../back/depositoController.php?action=excluirMaterial&id=${produtoId}`, {
                            method: "DELETE"
                        })
                        .then(response => {
                            if (response.ok) {
                                // Remove a linha da tabela
                                this.closest("tr").remove();
                                alert("Produto excluído com sucesso!");
                            } else {
                                alert("Erro ao excluir o produto. Tente novamente.");
                            }
                        })
                        .catch(error => console.error("Erro ao excluir o produto:", error));
                }
            });
        });
    }

    // Recarrega os dados na tabela
    function carregarTabela() {
        fetch('../back/depositoController.php?action=listarMateriais')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector("table tbody");
                tbody.innerHTML = ""; // Limpa as linhas anteriores

                data.forEach(material => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                <td>${material.descricao}</td>
                <td>${material.unidade_medida}</td>
                <td>${material.quantidade}</td>
                <td>${material.deposito}</td>
                <td>${material.estoque_minimo}</td>
                <td>${material.estoque_seguranca}</td>
                <td>${material.tipo_material}</td>
                <td>${material.segmento}</td>
                <td>
                    <a href="editar_produto.php?id=${material.id}" class="btn btn-primary btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm excluir-btn" data-id="${material.id}">Excluir</button>
                </td>
            `;

                    tbody.appendChild(row);
                });

                // Adiciona os eventos aos botões após carregar a tabela
                adicionarEventosExcluir();
            })
            .catch(error => console.error("Erro ao buscar os dados:", error));
    }

    // Carrega os dados ao iniciar
    carregarTabela();

    // Adiciona evento ao botão de busca (se necessário)
    document.getElementById("buscarButton").addEventListener("click", function() {
        carregarTabela();
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // Função para carregar as opções
    function carregarOpcoes() {
        // Carregar tipos de materiais
        fetch('../back/depositoController.php?action=listarTiposMaterial')
            .then(response => response.json())
            .then(data => {
                const tiposMaterialSelect = document.getElementById("tipo_material");
                tiposMaterialSelect.innerHTML = '<option selected>Todos</option>';

                data.forEach(tipo => {
                    const option = document.createElement("option");
                    option.value = tipo.tipo_material;
                    option.textContent = tipo.tipo_material;
                    tiposMaterialSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erro ao carregar tipos de material:", error));

        // Carregar segmentos
        fetch('../back/depositoController.php?action=listarSegmentos')
            .then(response => response.json())
            .then(data => {
                const segmentoSelect = document.getElementById("segmento");
                segmentoSelect.innerHTML = '<option selected>Todos</option>';

                data.forEach(segmento => {
                    const option = document.createElement("option");
                    option.value = segmento.segmento;
                    option.textContent = segmento.segmento;
                    segmentoSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erro ao carregar segmentos:", error));
    }

    // Carregar as opções ao iniciar
    carregarOpcoes();
});


