<?php 
require_once("drink_list.php");
session_start();

$cart = $_SESSION["cart"] ?? [];
$email = $_POST["email"] ?? "";
$address = $_POST["endereco"] ?? "";

function findDrinkById($drinks, $id) {
    foreach ($drinks as $drink) {
        if ((string)$drink["id"] === (string)$id) {
            return $drink;
        }
    }
    return null;
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Compra Realizada - Tomaê</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 600px;
            margin: 40px auto;
            font-family: Arial, sans-serif;
        }

        .order-summary {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
        }

        .order-summary h2 {
            margin-bottom: 15px;
        }

        .item {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
        }

        .item-details {
            flex-grow: 1;
        }

        .total {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 15px;
            text-align: right;
        }

        .customer-info {
            margin-bottom: 20px;
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

/* General body styling */
body {
    margin: 0;
    padding: 0;
    background-color: var(--background-color);
    font-family: 'Poppins', sans-serif;
    color: var(--text-color);
}

/* Header */
header {
    background-color: var(--primary-color);
    color: white;
    padding: 1rem;
    text-align: center;
}

/* Card container */
.item {
    background-color: var(--card-background);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: 0 4px 8px var(--shadow-color);
    padding: 1rem;
    margin: 1rem;
    transition: transform 0.2s ease-in-out;
}

.item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px var(--shadow-color);
}

/* Product image */
.item img {
    width: 80px;
    height: 80px;
    border-radius: 6px;
    margin-bottom: 0.5rem;
}

/* Product name */
.item h3 {
    font-size: 1.1rem;
    margin: 0.5rem 0;
    color: var(--text-color);
}

/* Price */
.price {
    font-weight: bold;
    color: var(--primary-color);
}

/* Secondary text (like metadata or category) */
.meta {
    font-size: 0.9rem;
    color: var(--light-text-color);
}

/* Button styling */
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

/* Highlighted badge (like for price or offer) */
.badge {
    background-color: var(--secondary-color);
    color: var(--text-color);
    padding: 0.3rem 0.6rem;
    font-size: 0.8rem;
    border-radius: 4px;
    display: inline-block;
    margin-left: 0.5rem;
}

/* Favorite icon hover */
.favorite-btn i:hover {
    color: var(--hover-dark-color);
}

/* Utility classes */
.text-light {
    color: var(--light-text-color);
}

.text-primary {
    color: var(--primary-color);
}

.bg-primary {
    background-color: var(--primary-color);
    color: white;
}
.item-details{
    color: var(--primary-color)
    
}

    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="button">← Voltar para Início</a>
    <div class="order-summary">
        <h2>Compra realizada com sucesso!</h2>

        <div class="customer-info">
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Endereço:</strong> <?= htmlspecialchars($address) ?></p>
        </div>

        <h3>Resumo do Pedido:</h3>

        <?php foreach ($cart as $item): 
    $drink = findDrinkById($drinks, $item["id"]);
    if (!$drink) continue;

    $itemTotal = $drink["price"] * $item["quantity"] * 0.01;
    $total += $itemTotal;

    // Reduce stock in DB
    $bebida_id = (int) $drink["id"];
    $quantidade_comprada = (int) $item["quantity"];

    $update_sql = "UPDATE bebida SET estoque = GREATEST(estoque - ?, 0) WHERE id_bebida = ?";
    $stmt = mysqli_prepare($connection, $update_sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantidade_comprada, $bebida_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
?>
    <div class="item">
        <img src="<?= htmlspecialchars($drink["img"]) ?>" alt="<?= htmlspecialchars($drink["name"]) ?>">
        <div class="item-details">
            <strong><?= htmlspecialchars($drink["name"]) ?></strong><br>
            Quantidade: <?= $item["quantity"] ?><br>
            Preço Unitário: R$ <?= number_format($drink["price"] * 0.01, 2, ',', '.') ?>
        </div>
        <div class="item-total">
            R$ <?= number_format($itemTotal, 2, ',', '.') ?>
        </div>
    </div>
<?php endforeach;
$_SESSION["cart"] = [];

?>

        <div class="total">
            Total: R$ <?= number_format($total, 2, ',', '.') ?>
        </div>
    </div>
</div>

</body>
<script>
  
  localStorage.removeItem('cart');
</script>
</html>
