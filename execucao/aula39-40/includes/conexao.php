<?php


function criarConexao()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    return mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
}
