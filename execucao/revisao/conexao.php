<?php



function criarConexao(){
	return mysqli_connect(
		hostname: DB_HOST,
		database: DB_NAME,
		username: DB_USER,
		password: DB_PASS,
		port: DB_PORT,
	);
}