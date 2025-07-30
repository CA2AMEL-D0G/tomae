<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomaê - Seu Delivery de Bebidas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <header class="header">
        <img src="tomaê.png" alt="TomaeLogo" class="logo" style="height: 130px; width: auto;" />
        <div class="search-bar">
            <input type="text" id="product-search" placeholder="Buscar bebidas, marcas...">
            <i class="fas fa-search search-icon"></i>
        </div>
        <div class="nav-icons">
            <a href="favoritos.php" class="icon-btn"><i class="fas fa-heart"></i><span class="badge" id="favorites-badge">0</span></a>
            <button class="icon-btn"><i class="fas fa-bell"></i></button>
            <a href="carrinho.php" class="icon-btn"><i class="fas fa-shopping-cart"></i><span class="badge" id="cart-badge">0</span></a>
        </div>
    </header>

    <div class="container">
    <?php
// Example drink objects array
$drinks = [
    ["id" => 1, "name" => "Skol", "price" => 4.5, "category" => "Cerveja", "img" => "images/skol.jpg"],
    ["id" => 2, "name" => "Heineken", "price" => 6.5, "category" => "Cerveja", "img" => "images/heineken.jpg"],
    ["id" => 3, "name" => "Absolut", "price" => 49.0, "category" => "Vodka", "img" => "images/absolut.jpg"],
    ["id" => 4, "name" => "Smirnoff", "price" => 29.0, "category" => "Vodka", "img" => "images/smirnoff.jpg"],
    ["id" => 5, "name" => "Cerveja", "price" => 29.0, "category" => "Vodka", "img" => "images/smirnoff.jpg"],
    // ... add more drinks
];

// Group drinks by category
$groupedDrinks = [];
foreach ($drinks as $drink) {
    $groupedDrinks[$drink['category']][] = $drink;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tomaê - Início</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .category-section {
            margin-bottom: 30px;
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-left: 10px;
        }

        .product-grid {
            display: flex;
            overflow-x: auto;
            padding: 10px;
            gap: 10px;
        }

        .product-card {
            min-width: 160px;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .product-info {
            text-align: center;
            margin-top: 5px;
        }

        .price {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <header>
        <h1>Bem-vindo ao Tomaê</h1>
    </header>

    <main>
        <?php foreach ($groupedDrinks as $category => $drinksList): ?>
            <section class="category-section">
                <h2 class="category-title"><?= htmlspecialchars($category) ?></h2>
                <div class="product-grid">
                    <?php foreach ($drinksList as $drink): ?>
                        <div class="product-card" 
                             data-id="<?= $drink['id'] ?>"
                             data-name="<?= htmlspecialchars($drink['name']) ?>"
                             data-price="<?= $drink['price'] ?>"
                             data-img="<?= htmlspecialchars($drink['img']) ?>">
                             
                            <button class="favorite-btn"><i class="far fa-heart"></i></button>
                            <img src="<?= htmlspecialchars($drink['img']) ?>" alt="<?= htmlspecialchars($drink['name']) ?>">
                            <div class="product-info">
                                <h3><?= htmlspecialchars($drink['name']) ?></h3>
                                <p><?= strpos($drink['name'], 'Cerveja') !== false ? 'Long Neck 330ml' : '' ?></p>
                                <span class="price">R$ <?= number_format($drink['price'], 2, ',', '.') ?></span>
                            </div>
                            <button onclick="" class="add-to-cart-btn">Adicionar</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>



<span id="cart-badge">0</span>
 <script src="datatransferutils.js"></script> 

<script>
document.addEventListener('DOMContentLoaded', () => {
    

    

    const saveCart = (cart) => {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
        sendCartToServer(cart)
   
};

    const updateCartBadge = () => {
        const cart = getCart();
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = totalItems;
    };

    const addToCart = (product) => {
        const cart = getCart();
        const existing = cart.find(item => item.id === product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
      
        saveCart(cart);
        updateCartBadge()
        ;
    };

    // Hook up all store page "Add to Cart" buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.product-card');
            const product = {
                id: card.dataset.id,
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                
            };
            addToCart(product);
        });
    });

    
    
   fetchServerCart().then(serverCart => {
    console.log(serverCart)
    if (serverCart != null) {
        localStorage.setItem(CART_KEY, JSON.stringify(serverCart));
    }
    
    updateCartBadge(); // just in case the server changed it
}).catch(err => {
    console.error('Error fetching cart from server:', err);
});


});
</script>


</body>
</html>



    </div> 
    <nav class="footer-nav">
        <a href="index.php" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Início</span>
        </a>
        <a href="carrinho.php" class="nav-item">
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

     
</body>
</html>