<?php
require_once("src/database/database.php");

if (isset($_GET["name"])){
    $nome = $_GET["name"];
    mysqli_query($connection,"INSERT INTO categoria(nome_categoria)
    values ('$nome') ");




}
header("Location: admin_painel.php");