<?php

require_once 'config.php';
require_once 'models/tarefa_model.php';


if(isset($_GET['action'])){

// SALVA A TAREFA NO BANCO DE DADOS
    if ($_GET['action'] == 'salvar') {

        $status = salvarTarefa($_POST['titulo'], $_POST['descricao'], $_POST['categoria'], $_POST['prioridade'], $_POST['data'], $_POST['horario']);

        if ($status) {
            echo 'Tarefa salva com sucesso!';
        } else {
            echo 'Erro ao salvar a tarefa.';
        }
        die();
    }

    if($_GET['action'] == 'deletar' && isset($_GET['id'])){
        deletarTarefa($_GET['id']);
        header('Location: ../index.php');
    }

    if($_GET['action'] == 'atualizar' && isset($_GET['id'])){
        atualizarTarefa($_GET['id'], $_POST['titulo'], $_POST['descricao']);
        header('Location: ../index.php');
    }

// CASO DÊ PROBLEMA MOSTRA ESSA INFORMAÇÃO
    die('Função inválida!');
}


// CONSULTA E RETORNA TODOS OS DADOS DO BANCO DE DADOS DA TABELA TAREFA

$tarefas = listarTarefa();