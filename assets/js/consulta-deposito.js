document.addEventListener("DOMContentLoaded", function () {
    const buscarButton = document.getElementById("buscarButton");
    const descricaoInput = document.getElementById("descricao");
    const tipoMaterialSelect = document.getElementById("tipo_material");
    const segmentoSelect = document.getElementById("segmento");
    const tbody = document.getElementById("resultTable");
    const pagination = document.getElementById("pagination");

    let paginaAtual = 1; // Página atual
    const itensPorPagina = 10; // Número de itens por página

    // Carregar opções de filtros (Tipos de Material e Segmentos)
    function carregarOpcoes() {
        fetch('../back/depositoController.php?action=listarTiposMaterial')
            .then(response => response.json())
            .then(data => {
                tipoMaterialSelect.innerHTML = '<option value="" selected>Todos</option>';
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
                segmentoSelect.innerHTML = '<option value="" selected>Todos</option>';
                data.forEach(segmento => {
                    const option = document.createElement("option");
                    option.value = segmento.segmento;
                    option.textContent = segmento.segmento;
                    segmentoSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erro ao carregar segmentos:", error));
    }

    // Carregar tabela e aplicar filtros e paginação
    function carregarTabela(pagina = 1) {
        const filtros = {
            descricao: descricaoInput.value || "",
            tipo_material: tipoMaterialSelect.value || "",
            segmento: segmentoSelect.value || ""
        };

        const url = `../back/depositoController.php?action=listarMateriais&pagina=${pagina}&itensPorPagina=${itensPorPagina}&descricao=${encodeURIComponent(filtros.descricao)}&tipo_material=${encodeURIComponent(filtros.tipo_material)}&segmento=${encodeURIComponent(filtros.segmento)}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = ""; // Limpa as linhas anteriores

                if (data.materiais.length === 0) {
                    tbody.innerHTML = "<tr><td colspan='9' class='text-center'>Nenhum material encontrado.</td></tr>";
                } else {
                    data.materiais.forEach(material => {
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

                    atualizarPaginacao(data.totalPaginas, data.paginaAtual);
                }

                adicionarEventosExcluir(); // Adiciona eventos de exclusão
            })
            .catch(error => console.error("Erro ao carregar a tabela:", error));
    }

    // Atualizar paginação
    function atualizarPaginacao(totalPaginas, paginaAtual) {
        pagination.innerHTML = ""; // Limpa a paginação anterior

        for (let i = 1; i <= totalPaginas; i++) {
            const button = document.createElement("button");
            button.textContent = i;
            button.className = "btn btn-sm " + (i === paginaAtual ? "btn-primary" : "btn-secondary");
            button.addEventListener("click", () => {
                carregarTabela(i);
                paginaAtual = i; // Atualiza a página atual
            });
            pagination.appendChild(button);
        }
    }

    // Adicionar evento para excluir material
    function adicionarEventosExcluir() {
        document.querySelectorAll(".excluir-btn").forEach(button => {
            button.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Você não poderá reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        excluirMaterial(id);
                    }
                });
            });
        });
    }

    // Excluir material
    function excluirMaterial(id) {
        fetch(`../back/depositoController.php?action=excluirMaterial&id=${id}`, {
            method: "DELETE",
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Excluído!',
                        'O material foi excluído com sucesso.',
                        'success'
                    );
                    carregarTabela(paginaAtual); // Atualizar tabela após exclusão
                } else {
                    Swal.fire(
                        'Erro!',
                        'Erro ao excluir material: ' + data.error,
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Erro!',
                    'Erro ao excluir material: ' + error.message,
                    'error'
                );
            });
    }

    // Evento do botão "Buscar"
    buscarButton.addEventListener("click", function () {
        paginaAtual = 1; // Reinicia para a primeira página ao aplicar filtros
        carregarTabela(paginaAtual);
    });

    // Carregar filtros e tabela inicial
    carregarOpcoes();
    carregarTabela();
});
