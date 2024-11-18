document.getElementById('deposito').addEventListener('input', function () {
    const query = this.value;
    const sugestoesList = document.getElementById('sugestoesDeposito');

    // Limpa sugestões anteriores
    sugestoesList.innerHTML = '';

    if (query.length >= 2) {
        fetch(`../back/depositoController.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item', 'list-group-item-action');
                    li.textContent = item.deposito;

                    li.addEventListener('click', function () {
                        document.getElementById('deposito').value = this.textContent;
                        sugestoesList.innerHTML = '';
                    });

                    sugestoesList.appendChild(li);
                });
            })
            .catch(error => console.error('Erro ao buscar depósitos:', error));
    }
});


// Função para preencher o campo "Tipo de Material"
fetch('http://localhost/estoque-php/back/routes/getTiposMaterial.php')
    .then(response => response.json())
    .then(data => {
        const tipoMaterialSelect = document.getElementById('tipo_material');
        tipoMaterialSelect.innerHTML = '<option value="" selected>Selecione...</option>'; // Reseta o campo

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.tipo_material;
            option.textContent = item.tipo_material;
            tipoMaterialSelect.appendChild(option);
        });
    })
    .catch(error => console.error('Erro ao carregar os tipos de material:', error));

// Função para preencher o campo "Grupo de Mercadorias"
fetch('http://localhost/estoque-php/back/routes/getGruposMercadorias.php')
    .then(response => response.json())
    .then(data => {
        const segmentoSelect = document.getElementById('segmento');
        segmentoSelect.innerHTML = '<option value="" selected>Selecione...</option>'; // Reseta o campo

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.segmento;
            option.textContent = item.segmento;
            segmentoSelect.appendChild(option);
        });
    })
    .catch(error => console.error('Erro ao carregar os grupos de mercadorias:', error));


    document.getElementById('descricao').addEventListener('input', function () {
        const query = this.value;
        const sugestoesList = document.getElementById('sugestoesDescricao');
    
        // Limpa sugestões anteriores
        sugestoesList.innerHTML = '';
    
        if (query.length >= 2) { // Busca após 2 caracteres
            fetch('http://localhost/estoque-php/back/routes/searchDescricao.php?descricao=${query}')
                .then(response => response.json())
                .then(data => {
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item', 'list-group-item-action');
                        li.textContent = item.descricao;
    
                        li.addEventListener('click', function () {
                            document.getElementById('descricao').value = this.textContent;
                            sugestoesList.innerHTML = ''; // Limpa as sugestões após selecionar
                        });
    
                        sugestoesList.appendChild(li);
                    });
                })
                .catch(error => console.error('Erro ao buscar descrições:', error));
        }
    });
    

