<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomaê - Seu Carrinho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <header class="header">
        <img src="tomaê.png" alt="TomaeLogo" class="logo" style="height: 130px; width: auto;"  />
        <div class="search-bar">
            <input type="text" placeholder="Buscar bebidas, marcas...">
            <i class="fas fa-search search-icon"></i>
        </div>
        <div class="nav-icons">
            <a href="favoritos.php" class="icon-btn"><i class="fas fa-heart"></i><span class="badge" id="favorites-badge">0</span></a>
            <button class="icon-btn"><i class="fas fa-bell"></i></button>
            <a href="carrinho.php" class="icon-btn"><i class="fas fa-shopping-cart"></i><span class="badge" id="cart-badge">0</span></a>
        </div>
    </header>













    <div class="container cart-page">
        <h2 class="section-title">Seu Carrinho</h2>

        <div class="cart-items" id="cart-items-list">
            <p id="empty-cart-message" style="text-align: center; color: var(--text-color);">Seu carrinho está vazio.</p>
        </div>

        <div class="cart-summary">
            <div class="summary-item">
                <span>Subtotal:</span>
                <span id="subtotal-value">R$ 0,00</span>
            </div>
            <div class="summary-item">
                <span>Taxa de Entrega:</span>
                <span id="delivery-fee-value">R$ 5,00</span>
            </div>
            <div class="summary-item total">
                <span>Total:</span>
                <span id="total-value">R$ 5,00</span>
            </div>
            <button class="checkout-btn">Finalizar Pedido</button>
        </div>
    </div>

    <nav class="footer-nav">
        <a href="index.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Início</span>
        </a>
        <a href="carrinho.php" class="nav-item active">
            <i class="fas fa-shopping-cart"></i>
            <span>Carrinho</span>
        </a>
        <a href="favoritos.php" class="nav-item">
            <i class="fas fa-heart"></i>
            <span>Favoritos</span>
        </a>
        <a href="admin_login.php" class="nav-item">
            <i class="fas fa-user"></i>
            <span>Admin</span>
        </a>
    </nav>
<script src="script.js"></script>
     
</body>
</html>


