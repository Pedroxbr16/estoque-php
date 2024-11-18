// Configurar o evento de clique para expandir o gráfico
document.getElementById('graficoEstoqueMinimo', 'graficoEstoque').addEventListener('click', () => {
    const modal = document.getElementById('modalGrafico');
    modal.style.display = 'block';

    // Renderizar o gráfico expandido (pode ser uma cópia do gráfico existente ou outro tipo)
    const ctxExpandido = document.getElementById('graficoExpandido').getContext('2d');
    new Chart(ctxExpandido, {
        type: 'bar',
        data: {
            labels: labels, // Reutilize os dados do gráfico original
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
                title: {
                    display: true,
                    text: 'Estoque Atual x Estoque Mínimo (Expandido)'
                }
            },
            scales: {
                x: {
                    stacked: false,
                    title: {
                        display: true,
                        text: 'Tipos de Materiais'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantidade'
                    }
                }
            }
        }
    });
});

// Fechar o modal
document.querySelector('.close').addEventListener('click', () => {
    document.getElementById('modalGrafico').style.display = 'none';
});

// Configurar o filtro
document.getElementById('filtroCategoria').addEventListener('change', (event) => {
    const categoria = event.target.value;

    // Filtrar os dados
    let labelsFiltrados = labels;
    let estoqueAtualFiltrado = estoqueAtual;
    let estoqueMinimoFiltrado = estoqueMinimo;

    if (categoria !== 'todos') {
        const indice = labels.indexOf(categoria);
        labelsFiltrados = [labels[indice]];
        estoqueAtualFiltrado = [estoqueAtual[indice]];
        estoqueMinimoFiltrado = [estoqueMinimo[indice]];
    }

    // Atualizar o gráfico expandido
    const ctxExpandido = document.getElementById('graficoExpandido').getContext('2d');
    new Chart(ctxExpandido, {
        type: 'bar',
        data: {
            labels: labelsFiltrados,
            datasets: [
                {
                    label: 'Estoque Atual',
                    data: estoqueAtualFiltrado,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Estoque Mínimo',
                    data: estoqueMinimoFiltrado,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Estoque Atual x Estoque Mínimo (${categoria})`
                }
            },
            scales: {
                x: {
                    stacked: false,
                    title: {
                        display: true,
                        text: 'Tipos de Materiais'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantidade'
                    }
                }
            }
        }
    });
});
