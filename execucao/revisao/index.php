<?php 
require_once "config.php";
require_once "./include/models/subscription_model.php";
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meus Gastos Mensais</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; text-align: center; }
        
        /* Estilos da Tabela e Botões Base */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .btn-excluir { color: red; text-decoration: none; font-weight: bold; }
        .btn-excluir:hover { text-decoration: underline; }
        .btn-editar { color: #007bff; cursor: pointer; text-decoration: none; font-weight: bold; margin-right: 15px; border: none; background: none; padding: 0; font-size: 1em; }
        .btn-editar:hover { text-decoration: underline; }
        .total { text-align: right; font-size: 1.2em; margin-top: 20px; font-weight: bold; color: #d9534f; }
        
        /* Botão Principal */
        .btn-novo { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; display: block; width: 100%; margin-bottom: 20px; font-size: 1.1em; }
        .btn-novo:hover { background: #218838; }

        /* ========================================= */
        /* Estilos do Modal (Fundo Escuro e Janela)  */
        /* ========================================= */
        .modal-overlay {
            display: none; /* Escondido por padrão */
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            align-items: center; justify-content: center;
            z-index: 1000;
        }
        .modal-overlay.ativo {
            display: flex; /* Mostra o modal quando tem a classe 'ativo' */
        }
        .modal-content {
            background: white; padding: 25px; border-radius: 8px; 
            width: 90%; max-width: 400px; position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .btn-fechar {
            position: absolute; top: 10px; right: 15px;
            font-size: 1.5em; cursor: pointer; color: #888;
            border: none; background: none; font-weight: bold;
        }
        .btn-fechar:hover { color: #333; }
        
        /* Formulário dentro do Modal */
        form { display: flex; flex-direction: column; gap: 15px; margin-top: 15px; }
        input { padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 1em; }
        .btn-submit { padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1em; }
        .btn-submit:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <h1>💸 Gestor de Assinaturas</h1>
    
    <!-- Botão que abre o modal para Adicionar -->
    <button class="btn-novo" onclick="abrirModalAdicionar()">+ Nova Assinatura</button>

    <h2>Minhas Assinaturas Ativas</h2>
    
    <table>
        <thead>
            <tr>
                <th>Serviço</th>
                <th>Valor Mensal</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- ========================================================= -->
            <!-- DICA PARA O PHP: Na hora de gerar os botões de editar com o while, -->
            <!-- você pode injetar as variáveis do banco direto na função JS, ex: -->
            <!-- onclick="abrirModalEditar(<?php echo $linha['id']; ?>, '<?php echo $linha['servico']; ?>', <?php echo $linha['valor']; ?>)" -->
            <!-- ========================================================= -->
            <?php 
                $assinaturas = listarAssinatura();

                $total = 0.00;

                while($assinatura = mysqli_fetch_assoc($assinaturas)){
                    $total += $assinatura['valor'];
                ?>
                <tr>
                    <td><?= $assinatura['servico'] ?></td>
                    <td>R$ <?= $assinatura['valor'] ?></td>
                    <td>
                        <!-- Dados estáticos passados para a função JavaScript -->
                        <button class="btn-editar" onclick="abrirModalEditar('<?= $assinatura['id'] ?>', '<?= $assinatura['servico'] ?>', '<?= $assinatura['valor'] ?>')">Editar</button>
                        <a class="btn-excluir" href="./assinaturas.php?action=delete&id=<?= $assinatura['id'] ?>">Cancelar</a>
                    </td>
                </tr>   
                <?php 
            }
            ?>         
        </tbody>
    </table>

    <div class="total">
        Custo Total Mensal: R$ <?= $total ?>
    </div>
</div>

<!-- ========================================================= -->
<!-- ESTRUTURA DO MODAL (Oculto por padrão)                    -->
<!-- ========================================================= -->
<div class="modal-overlay" id="meuModal">
    <div class="modal-content">
        <button class="btn-fechar" onclick="fecharModal()">&times;</button>
        <h2 id="modalTitulo" style="margin-top:0;">Nova Assinatura</h2>
        
        <!-- O mesmo formulário servirá para Inserir e Atualizar -->
        <form action="assinaturas.php" method="POST">
            <!-- ID Oculto (Fica vazio ao criar, e preenchido ao editar) -->
            <input type="hidden" name="id" id="inputId"> 
            
            <input type="text" name="servico" id="inputServico" placeholder="Ex: Netflix, Spotify..." required>
            <input type="number" step="0.01" name="valor" id="inputValor" placeholder="Valor (Ex: 39.90)" required>
            
            <button type="submit" name="salvar" id="btnSubmit" class="btn-submit">Adicionar</button>
        </form>
    </div>
</div>

<!-- ========================================================= -->
<!-- JAVASCRIPT: Lógica de abrir, preencher e fechar o modal   -->
<!-- ========================================================= -->
<script>
    // Selecionando os elementos do Modal
    const modal = document.getElementById('meuModal');
    const tituloModal = document.getElementById('modalTitulo');
    const inputId = document.getElementById('inputId');
    const inputServico = document.getElementById('inputServico');
    const inputValor = document.getElementById('inputValor');
    const btnSubmit = document.getElementById('btnSubmit');

    // Função para abrir o modal VAZIO (Modo Create)
    function abrirModalAdicionar() {
        tituloModal.innerText = "Nova Assinatura";
        
        // Limpa os campos
        inputId.value = "";
        inputServico.value = "";
        inputValor.value = "";
        
        // Configura o botão
        btnSubmit.innerText = "Adicionar";
        btnSubmit.name = "adicionar"; // O name muda para o PHP saber que é um INSERT
        
        // Exibe o modal adicionando a classe 'ativo'
        modal.classList.add('ativo');
    }

    // Função para abrir o modal PREENCHIDO (Modo Update)
    function abrirModalEditar(id, servico, valor) {
        tituloModal.innerText = "✏️ Editar Assinatura";
        
        // Preenche os campos com os dados que vieram do botão
        inputId.value = id;
        inputServico.value = servico;
        inputValor.value = valor;
        
        // Configura o botão
        btnSubmit.innerText = "Salvar Alterações";
        btnSubmit.name = "atualizar"; // O name muda para o PHP saber que é um UPDATE
        
        // Exibe o modal
        modal.classList.add('ativo');
    }

    // Função para fechar o modal
    function fecharModal() {
        modal.classList.remove('ativo');
    }

    // Fecha o modal se o utilizador clicar fora da janela branca
    window.onclick = function(event) {
        if (event.target === modal) {
            fecharModal();
        }
    }
</script>

</body>
</html>