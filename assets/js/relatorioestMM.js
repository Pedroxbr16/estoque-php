// URL para a nova API de estoque mínimo
const apiUrlEstoqueMinimo = 'http://localhost/estoque-php/back/routes/estoqueMinimo.php?action=graficoEstoqueMinimo';
fetch(apiUrlEstoqueMinimo)
    .then(response => response.json())
    .then(data => {
        // Processar os dados para o gráfico
        const labels = data.map(item => item.tipo_material); // Tipos de materiais
        const estoqueAtual = data.map(item => parseInt(item.estoque_atual)); // Estoque Atual
        const estoqueMinimo = data.map(item => parseInt(item.estoque_minimo)); // Estoque Mínimo

        // Renderizar o novo gráfico de barras agrupadas
        const ctx = document.getElementById('graficoEstoqueMinimo').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // Tipos de materiais
                datasets: [
                    {
                        label: 'Estoque Atual',
                        data: estoqueAtual,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Estoque Mínimo',
                        data: estoqueMinimo,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top' // Exibe a legenda acima do gráfico
                    },
                    title: {
                        display: true,
                        text: 'Estoque Atual x Estoque Mínimo'
                    }
                },
                scales: {
                    x: {
                        stacked: false, // Garante barras lado a lado
                        title: {
                            display: true,
                            text: 'Tipos de Materiais'
                        }
                    },
                    y: {
                        stacked: false, // Garante barras independentes no eixo Y
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade'
                        }
                    }
                }
            }
        });
        
        
    })
    .catch(error => {
        console.error('Erro ao carregar o gráfico:', error.message);
    });
