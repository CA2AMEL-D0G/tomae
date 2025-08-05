<?php
require_once("admin_validation.php");
require_once("src/database/database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    // Sanitize & validate inputs
    $id_bebida = isset($_POST["id_bebida"]) ? trim($_POST["id_bebida"]) : '';
    $nome = isset($_POST["nome_bebida"]) ? trim($_POST["nome_bebida"]) : '';
    $preco = isset($_POST["preco"]) ? filter_var($_POST["preco"], FILTER_VALIDATE_FLOAT) : false;
    $estoque = isset($_POST["estoque"]) ? filter_var($_POST["estoque"], FILTER_VALIDATE_INT) : false;
    $categoria = ($_POST["categoria"] !== '') ? filter_var($_POST["categoria"], FILTER_VALIDATE_INT) : null;

    if ($nome === '') {
        $errors[] = "Nome da bebida é obrigatório.";
    }

    if ($preco === false || $preco < 0) {
        $errors[] = "Preço inválido.";
    }

    if ($estoque === false || $estoque < 0) {
        $errors[] = "Estoque inválido.";
    }

    if ($categoria === false) {
        $errors[] = "Categoria inválida.";
    }

    // Handle image upload (optional)
    $caminho_foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['foto']['type'], $allowedTypes)) {
            $errors[] = "Tipo de imagem inválido. Apenas JPG, PNG e GIF são permitidos.";
        } else {
            $uploadDir = "imagems/";
            $tmpName = $_FILES['foto']['tmp_name'];
            $filename = basename($_FILES['foto']['name']);
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($tmpName, $destination)) {
                $caminho_foto = $filename;
            } else {
                $errors[] = "Erro ao salvar imagem.";
            }
        }
    }

    // If validation failed, stop
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        echo "<a href='admin_painel.php'>Voltar</a>";
        exit;
    }

    // Proceed with DB insert/update
    if (empty($id_bebida)) {
        $sql = "INSERT INTO bebida (nome_bebida, preco, estoque, fk_categoria_id_categoria, caminho_foto) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "sdiis", $nome, $preco, $estoque, $categoria, $caminho_foto);
    } else {
        // Update existing
        $id_bebida = filter_var($id_bebida, FILTER_VALIDATE_INT);
        if ($id_bebida === false) {
            echo "<p style='color:red;'>ID da bebida inválido.</p><a href='admin_painel.php'>Voltar</a>";
            exit;
        }

        if ($caminho_foto !== null) {
            $sql = "UPDATE bebida SET nome_bebida = ?, preco = ?, estoque = ?, fk_categoria_id_categoria = ?, caminho_foto = ? 
                    WHERE id_bebida = ?";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "sdiisi", $nome, $preco, $estoque, $categoria, $caminho_foto, $id_bebida);
        } else {
            $sql = "UPDATE bebida SET nome_bebida = ?, preco = ?, estoque = ?, fk_categoria_id_categoria = ?
                    WHERE id_bebida = ?";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "sdiii", $nome, $preco, $estoque, $categoria, $id_bebida);
        }
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: admin_painel.php");
    exit;
}
?>
