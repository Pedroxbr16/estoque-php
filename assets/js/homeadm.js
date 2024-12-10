document.getElementById('verificarEstoqueBtn').addEventListener('click', function () {
    fetch('http://localhost/estoque-php/back/verificar_estoque.php?action=enviarEmail')

        .then(response => response.json())
        .then(data => {
            if (data.success && data.produtos) {
                // Construir tabela com os produtos críticos
                let tabela = `
                    <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 8px;">Produto</th>
                                <th style="padding: 8px;">Quantidade Atual</th>
                                <th style="padding: 8px;">Estoque Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                data.produtos.forEach(produto => {
                    tabela += `
                        <tr>
                            <td style="padding: 8px;">${produto.descricao}</td>
                            <td style="padding: 8px;">${produto.quantidade}</td>
                            <td style="padding: 8px;">${produto.estoque_minimo}</td>
                        </tr>
                    `;
                });
                tabela += `
                        </tbody>
                    </table>
                `;

                // Mostrar SweetAlert com os produtos e opções
                Swal.fire({
                    title: 'Produtos com Estoque Crítico',
                    html: tabela,
                    icon: 'warning',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: 'Enviar Email',
                    denyButtonText: 'Exportar PDF',
                    cancelButtonText: 'Cancelar',
                    width: '800px'
                }).then(result => {
                    if (result.isConfirmed) {
                        enviarEmail(); // Função para enviar o email
                    } else if (result.isDenied) {
                        exportarPDF(data.produtos); // Função para exportar PDF
                    }
                });
            } else {
                Swal.fire('Informação', data.message, 'info');
            }
        })
        .catch(error => {
            Swal.fire('Erro', 'Erro ao verificar o estoque: ' + error.message, 'error');
        });
});
function exportarPDF(produtos) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Adiciona título
    doc.setFontSize(16);
    doc.text('Produtos com Estoque Crítico', 10, 10);

    // Adiciona tabela
    let y = 20;
    doc.setFontSize(12);
    doc.text('Produto', 10, y);
    doc.text('Quantidade Atual', 80, y);
    doc.text('Estoque Mínimo', 150, y);
    y += 10;

    produtos.forEach(produto => {
        doc.text(produto.descricao, 10, y);
        doc.text(produto.quantidade.toString(), 80, y);
        doc.text(produto.estoque_minimo.toString(), 150, y);
        y += 10;
    });

    // Salvar o PDF
    doc.save('estoque_critico.pdf');
}
function enviarEmail() {
    fetch('http://localhost/estoque-php/back/verificar_estoque.php?action=enviarEmail')

        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Sucesso', data.message, 'success');
            } else {
                Swal.fire('Erro', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Erro', 'Erro ao realizar a requisição: ' + error.message, 'error');
        });
}
