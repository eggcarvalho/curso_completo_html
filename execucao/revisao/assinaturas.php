<?php

require_once('config.php');
require_once('./include/models/subscription_model.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['adicionar'])){
		adicionarAssinatura(
			nome: $_POST['servico'],
			valor: $_POST['valor']
		);
		header("location: index.php");
	}
	if(isset($_POST['atualizar'])){
		atualizarAssinatura(
			id: $_POST['id'],
			nome: $_POST['servico'],
			valor: $_POST['valor']
		);
		
		header("location: index.php");
	}
}

if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] == 'delete'){
	if(isset($_GET['id'])){
		deletarAssinatura(
			id:$_GET['id']
		);
		header("location: index.php");
	}
}