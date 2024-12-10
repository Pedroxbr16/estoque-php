document.querySelector("form").addEventListener("submit", function (e) {
    const descricao = document.getElementById("descricao").value;
    if (descricao.trim() === "") {
        e.preventDefault();
        Swal.fire({
            title: "Erro!",
            text: "Descrição do material não pode estar vazia.",
            icon: "error",
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Recupera os valores originais do produto do PHP
    const produtoTipoMaterial = document.getElementById('tipo_material').getAttribute('data-tipo-material');
    const produtoSegmento = document.getElementById('segmento').getAttribute('data-segmento');

    // Carregar opções do banco para Grupo de Mercadoria (Segmento)
    fetch('../back/estoqueController.php?action=listarGruposDeMercadoria')
        .then(response => response.json())
        .then(data => {
            const segmentoSelect = document.getElementById('segmento');
            segmentoSelect.innerHTML = ''; // Limpa as opções atuais

            data.forEach(segmento => {
                const option = document.createElement('option');
                option.value = segmento.segmento;
                option.textContent = segmento.segmento;

                // Marca o valor original como selecionado
                if (segmento.segmento === produtoSegmento) {
                    option.selected = true;
                }

                segmentoSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Erro ao carregar segmentos:', error));

    // Carregar opções do banco para Tipo de Material
    fetch('../back/estoqueController.php?action=listarTiposDeMaterial')
        .then(response => response.json())
        .then(data => {
            const tipoMaterialSelect = document.getElementById('tipo_material');
            tipoMaterialSelect.innerHTML = ''; // Limpa as opções atuais

            data.forEach(tipoMaterial => {
                const option = document.createElement('option');
                option.value = tipoMaterial.tipo_material;
                option.textContent = tipoMaterial.tipo_material;

                // Marca o valor original como selecionado
                if (tipoMaterial.tipo_material === produtoTipoMaterial) {
                    option.selected = true;
                }

                tipoMaterialSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Erro ao carregar tipos de material:', error));
});


