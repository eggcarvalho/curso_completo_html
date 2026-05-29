<?php

require_once 'config.php';
require_once 'models/tarefa_model.php';


if ($_GET['action'] == 'salvar') {

    $status = salvarTarefa($_POST['titulo'], $_POST['descricao'], $_POST['categoria'], $_POST['prioridade'], $_POST['data'], $_POST['horario']);

    if ($status) {
        echo 'Tarefa salva com sucesso!';
    } else {
        echo 'Erro ao salvar a tarefa.';
    }
    die();
}



die('Função inválida!');
