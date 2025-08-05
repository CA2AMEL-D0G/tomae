<?php
require_once("admin_validation.php");
require_once("src/database/database.php");

if (isset($_GET["id"], $_GET["name"])) {
    $id = intval($_GET["id"]);
    $newName = trim($_GET["name"]);

    if ($newName !== "") {
        $query = "UPDATE categoria SET nome_categoria = ? WHERE id_categoria = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "si", $newName, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

header("Location: admin_painel.php");
exit;