<?php
require_once("admin_validation.php");





function generatedrink($drink) {
            $drinkJson = htmlspecialchars(json_encode($drink), ENT_QUOTES, 'UTF-8');
            echo "<div data-drink=\"$drinkJson\"class='drink-item'>";
            echo "<strong>" . htmlspecialchars($drink['nome_bebida']) . "</strong><br>";
            echo "<img  class='drink-image' src=imagems/".htmlspecialchars($drink['caminho_foto'])."><br>";

            echo "Preço: R$ " . number_format($drink['preco'], 2, ',', '.') . "<br>";
            echo "Estoque: " . intval($drink['estoque']);
            
            echo "<button class='edit-btn' data-drink=\"$drinkJson\">Editar</button>";
            echo "<button onclick='deleteDrink(" . intval($drink['id_bebida']) . ")'>Excluir</button>";
            echo "</div>";
            
        }



    






?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<style>
.add-bebida{
    display: hidden

 }
 .add-categoria{
    display: hidden

 }



 .edit-bebida{
    display: hidden

 }
 .edit-categoria{
    display: hidden

 }




    .drink-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-top: 1rem;
}

.drink-item {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 12px;
  background-color: #f8f8f8;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
}



.drink-item h4 {
  margin: 0 0 8px;
}

.drink-item p {
  margin: 4px 0;
}
.drink-image{
    width: 150px;      
     height: 150px; 

}

:root {
  --primary-color: #CC0000;
  --secondary-color: #FFD700;
  --background-color: #FFFFFF;
  --card-background: #FFFFFF;
  --text-color: #5A3F3B;
  --light-text-color: #8B735C;
  --border-color: #A68E7E;
  --shadow-color: rgba(90, 63, 59, 0.15);
  --hover-dark-color: #4A332F;
  --brown-button-bg: #8B735C;
  --brown-button-hover-bg: #5A3F3B;
}

body {
  font-family: Arial, sans-serif;
  background-color: var(--background-color);
  color: var(--text-color);
  margin: 0;
  padding: 2rem;
}

h2, h3 {
  color: var(--primary-color);
  margin-top: 2rem;
}

ul {
  padding-left: 1.5rem;
}

li {
  margin-bottom: 0.5rem;
}

button {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 4px;
  cursor: pointer;
  margin-left: 0.5rem;
  transition: background-color 0.2s ease;
}

button:hover {
  background-color: var(--hover-dark-color);
}

.drink-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
  margin-top: 1rem;
}

.drink-item {
  background-color: var(--card-background);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 16px;
  box-shadow: 0 2px 5px var(--shadow-color);
  text-align: center;
  transition: transform 0.2s ease-in-out;
}

.drink-item:hover {
  transform: translateY(-3px);
}

.drink-image {
  width: 150px;
  height: 150px;
  object-fit: cover;
  margin-bottom: 1rem;
}

#drinkForm {
  margin-top: 2rem;
  padding: 1.5rem;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  background-color: var(--card-background);
  box-shadow: 0 2px 5px var(--shadow-color);
  max-width: 500px;
}

#drinkForm label {
  font-weight: bold;
  display: block;
  margin-top: 1rem;
}

#drinkForm input,
#drinkForm select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid var(--border-color);
  border-radius: 4px;
  margin-top: 0.4rem;
  box-sizing: border-box;
}

#drinkForm button {
  margin-top: 1.5rem;
  background-color: var(--secondary-color);
  color: #333;
}

#drinkForm button:hover {
  background-color: #e6c200;
}

.edit-btn {
  background-color: var(--brown-button-bg);
}

.edit-btn:hover {
  background-color: var(--brown-button-hover-bg);
}
.form-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  min-height: 100vh;
  background-color: var(--background-color);
}
.center-wrapper {
  display: flex;
  justify-content: center; /* Horizontal */
  align-items: center;     /* Vertical */
  height: 100vh;           /* Full viewport height */
}
.page-container {
  padding: 0 20rem; /* or whatever space you want */
  box-sizing: border-box;
}

.admin-layout {
  display: flex;
  gap: 2rem;
  align-items: flex-start;
}

.admin-content {
  flex: 2;
}

.admin-form {
  flex: 1;
  max-width: 500px;
  width: 100%;
}
.button {
    display: inline-block;
    padding: 0.5rem 1rem;
    border: none;
    background-color: var(--primary-color);
    color: white;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    font-weight: 600;
}

.button:hover {
    background-color: var(--brown-button-hover-bg);
}
</style>

</head>
<body>
    <div class="page-container">

   <div class="admin-layout">

    <div class="admin-content">
        <h2>painel admin</h2>
        <a href="index.php" class="button">← Voltar para Início</a>
        <?php


// 1. Get all categories
$categories = [];
$result = mysqli_query($connection, "SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria");
while ($row = mysqli_fetch_assoc($result)) {
    $categories[$row['id_categoria']] = $row['nome_categoria'];
}

// 2. Get all drinks with their category id, sorted by category (NULL last)
$drinks = [];
$sql = "
    SELECT 
        id_bebida, nome_bebida, preco, estoque, fk_categoria_id_categoria,caminho_foto
    FROM 
        bebida
    ORDER BY 
        (fk_categoria_id_categoria IS NULL), fk_categoria_id_categoria, nome_bebida
";
$result = mysqli_query($connection, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $catId = $row['fk_categoria_id_categoria'] ?? 0; // 0 for no category
    $drinks[$catId][] = $row;
}

// 3. Output categories list
echo "<h2>Categorias</h2> <button onclick= 'criatecategory()'>nova categoria</button><ul>";
foreach ($categories as $id => $name) {
   echo "<li>" . htmlspecialchars($name) . " <button onclick='deletecategory(\"" . $id . "\")'>excluir</button> <button class='edit-btn' onclick='editcategory(\"" . $id . "\")'>editar</button>";
}

echo "</ul>";


echo "<h2>Bebidas por Categoria</h2>";

foreach ($categories as $catId => $catName) {
    echo "<h3>" . htmlspecialchars($catName) . "</h3>";
    if (!empty($drinks[$catId])) {
        echo "<div class='drink-grid'>";
        foreach ($drinks[$catId] as $drink) {
            generatedrink($drink);}
        echo "</div>";
    } else {
        echo "<p>Nenhuma bebida nesta categoria.</p>";
    }
}


echo "<h3>Sem Categoria</h3>";
if (!empty($drinks[0])) {
    echo "<div class='drink-grid'>";
    foreach ($drinks[0] as $drink) {
        generatedrink($drink);
    }//this is 161
    echo "</div>";
} else {
    echo "<p>Nenhuma bebida sem categoria.</p>";
}
?>
    </div>

    <div class="admin-form">
        
        <h2>Adicionar Nova Bebida</h2>

<form id="drinkForm" action="add_drink.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_bebida"><br><br>

    <label for="nome_bebida">Nome da Bebida:</label><br>
    <input type="text" name="nome_bebida" required><br><br>

    <label for="preco">Preço (ex: 12.50):</label><br>
    <input type="number" name="preco" step="0.01" min="0" required><br><br>

    <label for="estoque">Estoque:</label><br>
    <input type="number" name="estoque" min="0" required><br><br>

    <label for="foto" accept=".jpg, .jpeg, .png, .gif" >imagem:</label><br>
    <input type="file" name="foto"><br><br>

    <label for="categoria">Categoria:</label><br>
    <select name="categoria">
        <option value="">--Sem Categoria--</option>
        <?php
        // Fetch categories for the dropdown
        $result = mysqli_query($connection, "SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id_categoria'] . "'>" . htmlspecialchars($row['nome_categoria']) . "</option>";
        }
        ?>
    </select><br><br>
   
    <button type="submit">Adicionar Bebida</button>   <button style="background-color: red; color: white; " type="button" id="cancelEditBtn" style="display: none; margin-left: 0.5rem;">Cancelar</button>
</form>
    </div>

</div>

            






<script>


function deleteDrink(id) {
      if (confirm("Tem certeza que deseja excluir esta bebida? Esta ação não pode ser desfeita.")) {
        window.location.href = "excluir_bebida.php?id=" + encodeURIComponent(id) ;
    }
     
    }
function criatecategory(){
      const nome = prompt("Digite o nome da nova categoria:");
    
    if (!nome || nome.trim() === "") {
        alert("Nome inválido.");
        return;
    }

    
    window.location.href = `createcategory.php?name=${nome.trim()}`;
}

function deletecategory(id){
      

    if (confirm("Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.")) {
       window.location.href = `excluir_categoria.php?id=${id}`;
    }
    
}

function editcategory(id) {
    const newName = prompt("Digite o novo nome da categoria:");
    if (newName && newName.trim() !== "") {
        window.location.href = "editar_categoria.php?id=" + encodeURIComponent(id) + "&name=" + encodeURIComponent(newName.trim());
    }
}
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("drinkForm");
    const submitBtn = form.querySelector("button[type='submit']");
    const cancelBtn = document.getElementById("cancelEditBtn");

    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
            const drink = JSON.parse(this.dataset.drink);

            // Fill form with drink data
            form.elements["id_bebida"].value = drink.id_bebida || '';
            form.elements["nome_bebida"].value = drink.nome_bebida || '';
            form.elements["preco"].value = drink.preco || '';
            form.elements["estoque"].value = drink.estoque || '';
            form.elements["categoria"].value = drink.fk_categoria_id_categoria || '';

            // Change button text to "Salvar Edição"
            submitBtn.textContent = "Salvar Edição";

            // Show cancel button
            cancelBtn.style.display = "inline-block";
        });
    });

    // Handle cancel editing
    cancelBtn.addEventListener("click", () => {
        form.reset(); // Clears the form
        form.elements["id_bebida"].value = ''; // Extra safety
        submitBtn.textContent = "Adicionar Bebida";
        cancelBtn.style.display = "none";
    });
});




</script>

