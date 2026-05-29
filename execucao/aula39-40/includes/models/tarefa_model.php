<?php

include_once __DIR__ . "/../conexao.php";


function salvarTarefa($titulo, $descricao, $categoria, $prioridade, $data, $horario)
{

    $query = "INSERT INTO tarefas (titulo, descricao, categoria, prioridade, data, horario) VALUES ('$titulo', '$descricao', '$categoria', '$prioridade', '$data', '$horario')";


    return mysqli_query(criarConexao(), $query);;
}
