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

    <?php require_once("header.html")?>

    <div class="container">
    <?php
// Example drink objects array
require_once("drink_list.php");

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
 

<script>
document.addEventListener('DOMContentLoaded', () => {
    

    

    const saveCart = (cart) => {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
        sendCartToServer(cart)
   
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
            
            animateButton(btn)
            const card = btn.closest('.product-card');
            const product = {
                id: card.dataset.id,
                name: card.dataset.name,
                price: parseFloat(card.dataset.price),
                
            };
            addToCart(product);
        });
    });

    


     console.log("DOM fully loaded");

    

    const toggleFavorite = (productCard, productId, productName, productPrice, productImg) => {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const favoriteIcon = productCard.querySelector('.favorite-btn i');
        const isFavorited = favorites.some(item => item.id == productId);

        if (isFavorited) {
            favorites = favorites.filter(item => item.id != productId);
            favoriteIcon.classList.remove('fas');
            favoriteIcon.classList.add('far');
        } else {
            favorites.push({ id: productId, name: productName, price: productPrice, img: productImg });
            favoriteIcon.classList.remove('far');
            favoriteIcon.classList.add('fas');
        }

        localStorage.setItem('favorites', JSON.stringify(favorites));
        updateFavoritesBadge();
    };

    const markInitialFavorites = () => {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        document.querySelectorAll('.product-card').forEach(productCard => {
            const productId = productCard.dataset.id;
            const favoriteIcon = productCard.querySelector('.favorite-btn i');
            if (favorites.some(item => item.id == productId)) {
                favoriteIcon.classList.remove('far');
                favoriteIcon.classList.add('fas');
            } else {
                favoriteIcon.classList.remove('fas');
                favoriteIcon.classList.add('far');
            }
        });
    };

    // ✅ Properly hook favorite buttons
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            console.log("Favorite button clicked");

            const productCard = event.target.closest('.product-card');
            const productId = productCard.dataset.id;
            const productName = productCard.dataset.name;
            const productPrice = parseFloat(productCard.dataset.price);
            const productImg = productCard.dataset.img;
            animateButton(button)
            toggleFavorite(productCard, productId, productName, productPrice, productImg);
        });
    });

    // Initial favorite state
    markInitialFavorites();
    updateFavoritesBadge();  
   fetchServerCart().then(serverCart => {
    console.log(serverCart)
    if (serverCart != null) {
        localStorage.setItem(CART_KEY, JSON.stringify(serverCart));
    }
    
    updateCartBadge(); // just in case the server changed it
}).catch(err => {
    console.error('Error fetching cart from server:', err);
});

const animateButton = (btn) => {
    btn.classList.add('jump');
    setTimeout(() => btn.classList.remove('jump'), 300); // Remove after animation finishes
};
});
</script>


</body>
</html>



    </div> 
    <?php require_once("footer.html")?>

     
</body>
</html>