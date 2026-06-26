<?php
include_once __DIR__ . "/../../conexao.php";

function adicionarAssinatura($nome, $valor){
	$query = "INSERT INTO assinaturas (servico, valor) VALUES ('$nome', $valor)";
	mysqli_query(criarConexao(), $query);
}


function listarAssinatura(){
	
	$query = "SELECT * FROM assinaturas";

	return mysqli_query(criarConexao(), $query);
}


function atualizarAssinatura($id, $nome, $valor){
	$query = "UPDATE assinaturas SET servico='$nome', valor='$valor' WHERE id = '$id'";

	return mysqli_query(criarConexao(), $query);
}


function deletarAssinatura($id){
	$query = "DELETE FROM assinaturas WHERE id = '$id'";

	return mysqli_query(criarConexao(), $query);
}