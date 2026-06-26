<?php
include_once __DIR__ . "/../../conexao.php";

function adicionarAssinatura($nome, $valor){
	$query = "INSERT INTO assinaturas (servico, valor) VALUES ('$nome', $valor)";
	mysqli_query(criarConexao(), $query);
}