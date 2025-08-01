<?php
session_start();

$cart = $_SESSION['cart'] ?? [];


require_once("drink_list.php")

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tomaê - Seu Carrinho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
      .clicked {
          transform: translateY(-4px);
          transition: transform 0.15s;
      }
      .quantity-controls {
          display: flex;
          align-items: center;
          gap: 8px;
          margin: 5px 0;
      }
      .quantity-controls button {
          padding: 2px 6px;
          font-size: 14px;
          cursor: pointer;
      }
    </style>
</head>
<body>
   <?php require_once("header.html")?>

    <div class="container cart-page">
        <h2 class="section-title">Seu Carrinho</h2>

        <div class="cart-items" id="cart-items-list">
           <?php
$cart = $_SESSION['cart'] ?? [];
$deliveryFee = 5.00;
$subtotal = 0;
?>

<div class="cart-items" id="cart-items-list">
    <?php if (empty($cart)): ?>
        <p id="empty-cart-message" style="text-align: center; color: var(--text-color);">Seu carrinho está vazio.</p>
    <?php else: ?>
        <?php foreach ($cart as $item): 
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
        ?>
            <div class="cart-item" data-id="<?= $item['id'] ?>">
                <img src="<?= $item['img'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="item-info">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="item-price">R$ <?= number_format($itemTotal, 2, ',', '.') ?></p>
                </div>
                <div class="quantity-controls">
                    <button class="quantity-btn decrease-quantity" data-id="<?= $item['id'] ?>">-</button>
                    <span class="quantity-display"><?= $item['quantity'] ?></span>
                    <button class="quantity-btn increase-quantity" data-id="<?= $item['id'] ?>">+</button>
                </div>
                <button class="remove-from-cart-btn" data-id="<?= $item['id'] ?>">Remover</button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="cart-summary">
    <div class="summary-item">
        <span>Subtotal:</span>
        <span id="subtotal-value">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
    </div>
    <div class="summary-item">
        <span>Taxa de Entrega:</span>
        <span id="delivery-fee-value">R$ <?= number_format($deliveryFee, 2, ',', '.') ?></span>
    </div>
    <div class="summary-item total">
        <span>Total:</span>
        <span id="total-value">R$ <?= number_format($subtotal + $deliveryFee, 2, ',', '.') ?></span>
    </div>
    <button class="checkout-btn">Finalizar Pedido</button>
</div>

        
        
        
        
        
        
        
            
    

       

   <?php require_once("footer.html")?>

<script src="datatransferutils.js"></script> 
<script>
document.addEventListener('DOMContentLoaded', () => {
    fetchServerCart().then(() => {
        updateCartBadge();
        renderCartItems();
       
    });

    const removeFromCart = (productId) => {
        let cart = getCart().filter(item => item.id != productId);
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        updateCartBadge();
        renderCartItems();
        sendCartToServer(cart);
    };

    const updateQuantity = (productId, change) => {
        let cart = getCart();
        const productIndex = cart.findIndex(item => item.id == productId);

        if (productIndex > -1) {
            cart[productIndex].quantity += change;
            if (cart[productIndex].quantity <= 0) {
                cart.splice(productIndex, 1);
            }

            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            updateCartBadge();
            renderCartItems();
            sendCartToServer(cart);
        }
    };

    const addToCart = (product) => {
        let cart = getCart();
        const existingProductIndex = cart.findIndex(item => item.id == product.id);

        if (existingProductIndex > -1) {
            cart[existingProductIndex].quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }

        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        updateCartBadge();
        renderCartItems();
        sendCartToServer(cart);
    };

    function renderCartItems() {
    const cart = getCart();
    const container = document.getElementById('cart-items-list');
    const existingItemElems = document.querySelectorAll(".cart-item");
    let displaylist = document.querySelectorAll(".quantity-display")
    console.log("triedupdatingcart")
    // Remove any DOM items that are no longer in the cart
    console.log(existingItemElems)
    existingItemElems.forEach(elem => {
        const id = elem.dataset.id
        console.log("iterated trough them")
        if (!cart.find(item => item.id === id)) {
            elem.remove();
            console.log("triedremoving")
        }
    });

    let subtotal = 0;
    console.log(cart)
   for (let index = 0;index < cart.length ;index++){
    
        

            const item = cart[index]
            // Update quantity
           displaylist[index].textContent = item.quantity

            subtotal += item.price * item.quantity;
        
};

    // Update subtotal & total
    document.getElementById('subtotal-value').textContent = `R$ ${subtotal.toFixed(2)}`;
    
    const deliveryFee = 5.00; // hardcoded for now
    const total = subtotal + deliveryFee;
    document.getElementById('total-value').textContent = `R$ ${total.toFixed(2)}`;

    // Handle empty cart message
    const emptyMessage = document.getElementById('empty-cart-message');
    if (cart.length === 0 && emptyMessage) {
        emptyMessage.style.display = 'block';
    } else if (emptyMessage) {
        emptyMessage.style.display = 'none';
    }
}
const animateButton = (btn) => {
    btn.classList.add('jump');
    setTimeout(() => btn.classList.remove('jump'), 300); // Remove after animation finishes
};
function attachCartEventListeners() {
    // Handle quantity increase
    document.querySelectorAll('.increase-quantity').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.dataset.id);
            updateQuantity(id, 1);
        });
    });

    // Handle quantity decrease
    document.querySelectorAll('.decrease-quantity').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.dataset.id);
            updateQuantity(id, -1);
        });
    });

    // Handle item removal
    document.querySelectorAll('.remove-from-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.dataset.id);
            removeFromCart(id);
        });
    });
}
attachCartEventListeners();
    
});
</script>


   
</body>
</html>