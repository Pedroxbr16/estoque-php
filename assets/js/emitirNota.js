let produtos = []; // Array para armazenar os produtos adicionados

function adicionarProduto() {
    const produtoSelect = document.getElementById("produto");
    const quantidade = parseInt(document.getElementById("quantidade").value);
    const produtoId = produtoSelect.value;

    // Realizar uma verificação de estoque usando uma requisição AJAX
    fetch(`../back/controller/estoqueController.php?action=verificarEstoque&produto_id=${produtoId}&quantidade=${quantidade}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Se houver estoque suficiente, adicionar o produto
                const produtoSelecionado = produtoSelect.options[produtoSelect.selectedIndex];
                const nome = produtoSelecionado.text.split(" - ")[0];
                const preco = parseFloat(produtoSelecionado.getAttribute("data-preco"));

                if (isNaN(preco)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Preço não encontrado para o produto selecionado.'
                    });
                    return;
                }

                // Verifica se o produto já existe no array
                const produtoExistente = produtos.find(produto => produto.produto_id === produtoId);
                if (produtoExistente) {
                    produtoExistente.quantidade += quantidade;
                    produtoExistente.subtotal = produtoExistente.quantidade * produtoExistente.preco;
                } else {
                    const subtotal = preco * quantidade;
                    produtos.push({
                        produto_id: produtoId,
                        nome,
                        quantidade,
                        preco,
                        subtotal
                    });
                }

                atualizarTabela();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Estoque Insuficiente',
                    text: 'Não há quantidade suficiente em estoque para este produto.'
                });
            }
        })
        .catch(error => {
            console.error('Erro ao verificar o estoque:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao verificar o estoque. Tente novamente.'
            });
        });
}

function atualizarTabela() {
    const tabela = document.getElementById("tabela-itens");
    tabela.innerHTML = "";
    let total = 0;

    produtos.forEach((produto) => {
        total += produto.subtotal;
        tabela.innerHTML += `
            <tr>
                <td>${produto.nome}</td>
                <td>${produto.quantidade}</td>
                <td>R$ ${produto.preco.toFixed(2)}</td>
                <td>R$ ${produto.subtotal.toFixed(2)}</td>
            </tr>
        `;
    });

    document.getElementById("total").textContent = "R$ " + total.toFixed(2);
    document.getElementById("itens").value = JSON.stringify(produtos);
}

function resetarNota() {
    produtos = [];
    atualizarTabela();
}

function prepareFormData() {
    document.getElementById("itens").value = JSON.stringify(produtos);
}

function gerarPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(16);
    doc.text("Nota Fiscal", 105, 10, { align: "center" });
    doc.setFontSize(12);
    doc.text("Produto", 10, 30);
    doc.text("Quantidade", 80, 30);
    doc.text("Preço Unitário", 120, 30);
    doc.text("Subtotal", 170, 30);

    let posY = 40;
    let total = 0;
    produtos.forEach((produto) => {
        doc.text(produto.nome, 10, posY);
        doc.text(String(produto.quantidade), 80, posY);
        doc.text("R$ " + produto.preco.toFixed(2), 120, posY);
        doc.text("R$ " + produto.subtotal.toFixed(2), 170, posY);
        total += produto.subtotal;
        posY += 10;
    });

    doc.setFontSize(12);
    doc.text("Total: R$ " + total.toFixed(2), 170, posY + 10);

    doc.save("nota_fiscal.pdf");
}
