document.addEventListener('DOMContentLoaded', function () {
    carregarVendedores();
    carregarAnosMesesFixos();
});

// Carregar os vendedores disponíveis
function carregarVendedores() {
    fetch('../back/controller/relatoriovend.php?action=listarVendedores')
        .then(response => response.json())
        .then(data => {
            const vendedorSelect = document.getElementById('vendedor');
            vendedorSelect.innerHTML = '<option value="">Selecione um vendedor</option>';
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
}

// Carregar anos e meses fixos para o filtro
function carregarAnosMesesFixos() {
    const anoSelect = document.getElementById('ano');
    const mesSelect = document.getElementById('mes');

    // Limpar opções anteriores
    anoSelect.innerHTML = '<option value="">Selecione o ano</option>';
    mesSelect.innerHTML = '<option value="">Selecione o mês</option>';

    // Adicionar os últimos 5 anos
    const anoAtual = new Date().getFullYear();
    for (let i = 0; i < 5; i++) {
        const ano = anoAtual - i;
        const option = document.createElement('option');
        option.value = ano;
        option.textContent = ano;
        anoSelect.appendChild(option);
    }

    // Adicionar todos os meses
    const meses = [
        { valor: 1, nome: 'Janeiro' },
        { valor: 2, nome: 'Fevereiro' },
        { valor: 3, nome: 'Março' },
        { valor: 4, nome: 'Abril' },
        { valor: 5, nome: 'Maio' },
        { valor: 6, nome: 'Junho' },
        { valor: 7, nome: 'Julho' },
        { valor: 8, nome: 'Agosto' },
        { valor: 9, nome: 'Setembro' },
        { valor: 10, nome: 'Outubro' },
        { valor: 11, nome: 'Novembro' },
        { valor: 12, nome: 'Dezembro' }
    ];

    meses.forEach(mes => {
        const option = document.createElement('option');
        option.value = mes.valor;
        option.textContent = mes.nome;
        mesSelect.appendChild(option);
    });
}

// Buscar e exibir o relatório de vendas no gráfico
document.addEventListener('click', function (event) {
    if (event.target && event.target.id === 'buscarRelatorio') {
        buscarRelatorioVendas();
    }
});

function buscarRelatorioVendas() {
    const vendedorId = document.getElementById('vendedor').value;
    const ano = document.getElementById('ano').value;
    const mes = document.getElementById('mes').value;

    if (!vendedorId || !ano || !mes) {
        return;
    }

    fetch(`../back/controller/relatoriovend.php?action=obterRelatorioVendas&vendedorId=${vendedorId}&ano=${ano}&mes=${mes}`)
        .then(response => response.json())
        .then(data => {
            exibirGraficoVendas(data);
        })
        .catch(error => {
            console.error('Erro ao buscar o relatório de vendas:', error);
        });
}

// Exibir o gráfico de vendas usando Chart.js
function exibirGraficoVendas(vendas) {
    const ctx = document.getElementById('graficoVendas').getContext('2d');
    const labels = vendas.map(venda => venda.data_venda);
    const valores = vendas.map(venda => venda.total_venda);

    if (window.graficoVendas) {
        window.graficoVendas.destroy(); // Destruir gráfico anterior, se existir
    }

    window.graficoVendas = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Vendas em R$',
                data: valores,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Data'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Valor em R$'
                    }
                }
            }
        }
    });
}
