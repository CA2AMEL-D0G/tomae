<?php
require_once("src/database/database.php");

// Fetch categories: [id => name]
$categories = [];
$catResult = mysqli_query($connection, "SELECT id_categoria, nome_categoria FROM categoria");
while ($row = mysqli_fetch_assoc($catResult)) {
    $categories[$row["id_categoria"]] = $row["nome_categoria"];
}

// Fetch drinks
$drinks = [];
$drinkResult = mysqli_query($connection, "SELECT id_bebida, nome_bebida, preco, estoque, metadados, fk_categoria_id_categoria, caminho_foto FROM bebida");

while ($row = mysqli_fetch_assoc($drinkResult)) {
    $drinks[] = [
        "id" => (int)$row["id_bebida"],
        "name" => $row["nome_bebida"],
        "price" => (int)round($row['preco'] * 100),
        "stock" => (int)$row["estoque"],
        "category" => $row["fk_categoria_id_categoria"], // category ID
        "metadata" => $row["metadados"], // assuming it's a JSON string or similar
        "img" => "imagems/" . $row["caminho_foto"]
    ];
}

?>
