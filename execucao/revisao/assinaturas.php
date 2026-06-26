<?php

require_once('config.php');
require_once('./include/models/subscription_model.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['adicionar'])){
		adicionarAssinatura(
			nome: $_POST['servico'],
			valor: $_POST['valor']
		);
	}
}