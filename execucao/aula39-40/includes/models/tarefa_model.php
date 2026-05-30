<?php

include_once __DIR__ . "/../conexao.php";


function salvarTarefa($titulo, $descricao, $categoria, $prioridade, $data, $horario)
{

    $query = "INSERT INTO tarefas (titulo, descricao, categoria, prioridade, data, horario) VALUES ('$titulo', '$descricao', '$categoria', '$prioridade', '$data', '$horario')";


    return mysqli_query(criarConexao(), $query);
}


function listarTarefa() {
    //asterisco representa TODAS as colunas da tabela
    $query = "SELECT * FROM tarefas";

    return mysqli_query(criarConexao(), $query);
}


function deletarTarefa($id){
    $query = "DELETE FROM tarefas WHERE id = '$id'";

    mysqli_query(criarConexao(), $query);
}

function atualizarTarefa($id, $titulo, $descricao){
    $query = "UPDATE tarefas SET titulo = '$titulo', descricao = '$descricao' WHERE id = '$id'";

    mysqli_query(criarConexao(), $query);
}