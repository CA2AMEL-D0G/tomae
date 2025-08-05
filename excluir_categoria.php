<?php
require_once("admin_validation.php");
require_once("src/database/database.php");

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // make sure it's an integer

    $query = "DELETE FROM categoria WHERE id_categoria = ? LIMIT 1";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: admin_painel.php");
