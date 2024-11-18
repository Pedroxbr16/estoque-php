  // URL do endpoint
  const apiUrl = 'http://localhost/estoque-php/back/routes/dadosGraficos.php?action=dadosgraficos';

  // Requisitar os dados
  fetch(apiUrl)
.then(response => {
  if (!response.ok) {
      throw new Error('Erro ao carregar dados do servidor: ' + response.statusText);
  }
  return response.json();
})
.then(data => {
  // Exibe os dados recebidos no console

  // Verifica se os dados são um array
  if (!Array.isArray(data)) {
      throw new Error('Os dados retornados não são um array');
  }

  const labels = data.map(item => item.tipo_material); // Tipos de materiais
  const valores = data.map(item => item.total); // Totais de cada tipo

  // Renderizar o gráfico
  const ctx = document.getElementById('graficoEstoque').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels, // Tipos de materiais
        datasets: [{
            label: 'Quantidade',
            data: valores, // Valores de estoque
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', // Torna as barras horizontais
        responsive: true,
        plugins: {
            legend: {
                display: false, // Oculta a legenda para simplicidade
            },
            title: {
                display: true,
                text: 'Distribuição de Materiais no Estoque'
            }
        }
    }
});

})
.catch(error => {
  console.error('Erro ao carregar o gráfico:', error.message);
  alert('Erro ao carregar o gráfico. Verifique o console para mais detalhes.');
});