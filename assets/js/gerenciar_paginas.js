document.addEventListener('DOMContentLoaded', function () {
    // Obtenção dos elementos HTML necessários
    const paginasTableBody = document.getElementById('paginasTableBody');
    const adicionarPaginaBtn = document.getElementById('adicionarPaginaBtn');
    const modalPagina = new bootstrap.Modal(document.getElementById('modalPagina'));
    const paginaForm = document.getElementById('paginaForm');
    const nomePaginaInput = document.getElementById('nomePagina');
    const paginaIdInput = document.getElementById('paginaId');

    // Função para carregar as páginas
    function carregarPaginas() {
        fetch('../back/controller/gerenciar_paginas.php?action=listar')
            .then(response => response.json())
            .then(data => {
                paginasTableBody.innerHTML = '';

                if (data.length === 0) {
                    paginasTableBody.innerHTML = "<tr><td colspan='2' class='text-center'>Nenhuma página encontrada.</td></tr>";
                } else {
                    data.forEach(pagina => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${pagina.nome || 'Nome não definido'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editar-pagina" data-id="${pagina.id}" data-nome="${pagina.nome}">Editar</button>
                                <button class="btn btn-danger btn-sm excluir-pagina" data-id="${pagina.id}">Excluir</button>
                            </td>
                        `;
                        paginasTableBody.appendChild(row);
                    });

                    adicionarEventosAosBotoesPaginas();
                }
            })
            .catch(error => {
                console.error('Erro ao carregar páginas:', error);
                Swal.fire('Erro!', 'Erro ao carregar páginas.', 'error');
            });
    }

    // Função para adicionar os eventos aos botões de editar e excluir para páginas
    function adicionarEventosAosBotoesPaginas() {
        document.querySelectorAll('.editar-pagina').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');
                editarPagina(id, nome);
            });
        });

        document.querySelectorAll('.excluir-pagina').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                excluirPagina(id);
            });
        });
    }

    // Função para abrir o modal de edição/adicionar nova página
    function editarPagina(id = '', nome = '') {
        if (id) {
            paginaIdInput.value = id;
            nomePaginaInput.value = nome;
            document.getElementById('modalPaginaLabel').textContent = 'Editar Página';
        } else {
            paginaIdInput.value = '';
            nomePaginaInput.value = '';
            document.getElementById('modalPaginaLabel').textContent = 'Adicionar Nova Página';
        }
        modalPagina.show();
    }

    // Função para excluir página
    function excluirPagina(id) {
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
                fetch(`../back/controller/gerenciar_paginas.php?action=excluir&id=${id}`, {
                    method: 'DELETE'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Excluído!', 'Página excluída com sucesso.', 'success');
                            carregarPaginas();
                        } else {
                            Swal.fire('Erro!', 'Erro ao excluir página.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao excluir página:', error);
                        Swal.fire('Erro!', 'Erro ao excluir página.', 'error');
                    });
            }
        });
    }

    // Evento para o botão de adicionar página
    adicionarPaginaBtn.addEventListener('click', function () {
        editarPagina();
    });

    // Evento para o formulário de adicionar/editar página
    paginaForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const id = paginaIdInput.value;
        const nome = nomePaginaInput.value;

        const url = id ? '../back/controller/gerenciar_paginas.php?action=editar' : '../back/controller/gerenciar_paginas.php?action=criar';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, pagina: nome })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', id ? 'Página editada com sucesso.' : 'Página adicionada com sucesso.', 'success');
                    carregarPaginas();
                    modalPagina.hide();
                } else {
                    Swal.fire('Erro!', data.error || 'Erro ao salvar página.', 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao salvar página:', error);
                Swal.fire('Erro!', 'Erro ao salvar página.', 'error');
            });
    });

    // Carregar as páginas ao iniciar
    carregarPaginas();
});
