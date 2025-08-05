<?php
session_start();
$acessoConcedido = false;
require_once("src/database/database.php");






if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['user'], $_POST['senha'])) { 
    $usuario = $_POST['user'];
    $senha = $_POST['senha'];
    
    

    

    
}else if (isset($_SESSION["user"]) && isset($_SESSION["senha"])){
    $usuario = $_SESSION['user'];
    $senha = $_SESSION['senha'];

}
    
    if (isset($usuario)&&isset($senha)){
        $user = db_query($connection, "SELECT * FROM admin WHERE login = ?", [$usuario], true);
        if ($user){
        
            if ($user["senha"]==$senha) {
                $acessoConcedido = true;
                $_SESSION["user"]=$usuario;
                $_SESSION["senha"]=$senha;
            


        }}
    }

if(!$acessoConcedido) { 
echo "<h1>acesso negado</h1>";


die();
};