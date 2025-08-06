<?php
require_once("admin_validation.php"); // Assuming you want to restrict this
require_once("src/database/database.php");

if (!isset($_GET["id"])) {
    die("ID da bebida não fornecido.");
}

$id = $_GET["id"];

// Validate that it's an integer
if (!ctype_digit($id)) {
    die("ID inválido.");
}

// Prepare and execute deletion
$sql = "DELETE FROM bebida WHERE id_bebida = ?";
$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die("Erro na preparação da query: " . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Optional: redirect back
header("Location: admin_painel.php");
exit;
?>