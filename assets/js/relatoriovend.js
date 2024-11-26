

document.addEventListener('DOMContentLoaded', function () {
    // Carregar os vendedores
    fetch('../back/controller/relatoriovend.php?action=listarVendedores')
        .then(response => response.json())
        .then(data => {
            const vendedorSelect = document.getElementById('vendedor');
            data.forEach(vendedor => {
                const option = document.createElement('option');
                option.value = vendedor.id_usuario;
                option.textContent = vendedor.nome;
                vendedorSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar os vendedores:', error);
        });
});

// Buscar Relatório de Vendas do Vendedor Selecionado
function buscarRelatorioVendedor() {
    const vendedorId = document.getElementById('vendedor').value;
    if (!vendedorId) {
        document.getElementById('relatorio-vendas').style.display = 'none';
        return;
    }

    fetch(`../back/controller/relatoriovend.php?action=obterRelatorioVendasPorVendedor&vendedorId=${vendedorId}`)
        .then(response => response.json())
        .then(data => {
            const tabelaVendas = document.getElementById('tabela-vendas');
            tabelaVendas.innerHTML = '';

            data.forEach(venda => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${venda.data_venda}</td>
                    <td>${venda.quantidade_total}</td>
                    <td>R$ ${parseFloat(venda.total_venda).toFixed(2)}</td>
                `;
                tabelaVendas.appendChild(row);
            });

            document.getElementById('relatorio-vendas').style.display = 'block';
        })
        .catch(error => {
            console.error('Erro ao buscar o relatório de vendas:', error);
        });
}
