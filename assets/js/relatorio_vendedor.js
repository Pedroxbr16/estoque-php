document.addEventListener("DOMContentLoaded", function () {
    // Função para buscar resumo de vendas do backend
    function buscarResumoVendas() {
        const tipoRelatorio = document.getElementById("tipo-relatorio").value;

        fetch(`../back/controller/relatorio_vendedor.php?action=resumoVendas&tipo=${tipoRelatorio}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Erro ao buscar dados do servidor");
                }
                return response.json();
            })
            .then((data) => {
                console.log("Dados recebidos do backend:", data); // Debug - Verificar o que está sendo recebido

                let totalSemana = 0;
                let totalMes = 0;
                let totalAnual = 0;
                let vendasDiarias = [];
                let vendasMensais = [];

                if (tipoRelatorio === "semanal") {
                    totalSemana = data.total_semana ? parseFloat(data.total_semana) : 0;
                    vendasDiarias = data.vendas_diarias || new Array(7).fill(0);
                    const vendasSemanaElem = document.getElementById("vendas-semana");
                    if (vendasSemanaElem) {
                        vendasSemanaElem.innerText = `R$ ${totalSemana.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }
                    document.getElementById("resumo-semana").style.display = 'block';
                    document.getElementById("resumo-mes").style.display = 'none';
                    document.getElementById("resumo-anual").style.display = 'none';
                } else if (tipoRelatorio === "mensal") {
                    totalMes = data.total_mes ? parseFloat(data.total_mes) : 0;
                    vendasMensais = data.vendas_semanais || new Array(4).fill(0);
                    const vendasMesElem = document.getElementById("vendas-mes");
                    if (vendasMesElem) {
                        vendasMesElem.innerText = `R$ ${totalMes.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }
                    document.getElementById("resumo-semana").style.display = 'none';
                    document.getElementById("resumo-mes").style.display = 'block';
                    document.getElementById("resumo-anual").style.display = 'none';
                } else if (tipoRelatorio === "anual") {
                    totalAnual = data.vendas_mensais ? data.vendas_mensais.reduce((acc, val) => acc + parseFloat(val), 0) : 0;
                    vendasMensais = data.vendas_mensais || new Array(12).fill(0);
                    const vendasAnualElem = document.getElementById("vendas-anual");
                    if (vendasAnualElem) {
                        vendasAnualElem.innerText = `R$ ${totalAnual.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }
                    document.getElementById("resumo-semana").style.display = 'none';
                    document.getElementById("resumo-mes").style.display = 'none';
                    document.getElementById("resumo-anual").style.display = 'block';
                }

                // Atualizar o gráfico de progresso
                atualizarGrafico(tipoRelatorio, vendasDiarias, vendasMensais);
            })
            .catch((error) => {
                console.error("Erro ao buscar o resumo de vendas:", error);
            });
    }

    // Função para atualizar o gráfico de progresso
    function atualizarGrafico(tipoRelatorio, vendasDiarias, vendasMensais) {
        const ctx = document.getElementById("graficoMeta").getContext("2d");

        // Remover gráfico existente (se necessário)
        if (window.graficoMeta && typeof window.graficoMeta.destroy === "function") {
            window.graficoMeta.destroy();
        }

        let labels = [];
        let datasetProgresso = [];

        if (tipoRelatorio === "semanal") {
            labels = ["Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"];
            datasetProgresso = vendasDiarias;
        } else if (tipoRelatorio === "mensal") {
            labels = ["Semana 1", "Semana 2", "Semana 3", "Semana 4"];
            datasetProgresso = vendasMensais;
        } else if (tipoRelatorio === "anual") {
            labels = [
                "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho",
                "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
            ];
            datasetProgresso = vendasMensais;
        }

        console.log("Labels:", labels); // Debug - Verificar labels
        console.log("Dataset de progresso:", datasetProgresso); // Debug - Verificar dados do gráfico

        // Criar o gráfico
        window.graficoMeta = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Progresso de Vendas",
                        data: datasetProgresso,
                        backgroundColor: "rgba(75, 192, 192, 0.3)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top",
                        labels: {
                            color: "#333",
                            font: {
                                size: 14,
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(200, 200, 200, 0.2)",
                        },
                    },
                },
            },
        });
    }

    // Buscar resumo ao carregar a página
    buscarResumoVendas();

    // Adicionar evento ao filtro de relatório
    document.getElementById("tipo-relatorio").addEventListener("change", buscarResumoVendas);
});
