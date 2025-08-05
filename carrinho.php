<?php
session_start();

$cart = $_SESSION['cart'] ?? [];


require_once("drink_list.php");

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
.checkout-form {
  background-color: var(--card-background);
  padding: 2rem;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  box-shadow: 0 4px 8px var(--shadow-color);
  max-width: 400px;
  margin: 2rem auto;
  font-family: sans-serif;
  color: var(--text-color);
}

.checkout-form label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--text-color);
  font-weight: bold;
}

.checkout-form input[type="text"] {
  width: 100%;
  padding: 0.75rem;
  margin-bottom: 1.5rem;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  background-color: var(--background-color);
  color: var(--text-color);
  box-sizing: border-box;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.checkout-form input[type="text"]:focus {
  border-color: var(--primary-color);
  outline: none;
  box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.2); /* primary-color glow */
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
        <?php foreach ($cart as $cartitem): 
            foreach ($drinks as $obj) :
               
                if ($obj['id'] == $cartitem['id']) :
                    $drink = $obj;
                    
                    $drinkJson = htmlspecialchars(json_encode($drink), ENT_QUOTES, 'UTF-8');

            
            
            $drinkTotal = $drink['price'] * $cartitem['quantity']*0.01;
            $subtotal += $drinkTotal;
        ?>
            <div data-price="<?= $drink['price'] ?>" class="cart-item" data-id="<?= $drink['id'] ?>">
                <?php if (file_exists($drink['img'])):
                     ?>
                    <img src="<?= htmlspecialchars($drink['img']) ?>" alt="<?= htmlspecialchars($drink['name']) ?>">
                
                <?php endif; ?>
                
                <div class="cart-card"  id="cart-items-list" data-id="<?= $drink['id'] ?>"
                                data-name="<?= htmlspecialchars($drink['name']) ?>"
                                data-price="<?= $drink['price'] ?>"
                                data-img="<?= htmlspecialchars($drink['img']) ?>">
                    <h3><?= htmlspecialchars($drink['name']) ?></h3>
                    <p class="item-price">R$ <?= number_format($drink['price']*0.01, 2, ',', '.') ?></p>
                </div>
                <div class="quantity-controls">
                    <button class="quantity-btn decrease-quantity" data-id="<?= $cartitem['id'] ?>">-</button>
                    <span class="quantity-display"><?= $cartitem['quantity'] ?></span>
                    <button class="quantity-btn increase-quantity" data-id="<?= $cartitem['id'] ?>">+</button>
                </div>
                <button class="remove-from-cart-btn" data-id="<?= $cartitem['id'] ?>">Remover</button>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
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
<div class="checkout-form">
  <form action="finalizar_compra.php" method="POST">
    <label for="endereco">Endereço</label>
    <input type="text" id="endereco" name="endereco" required>

    <label for="email">E-mail</label>
    <input type="text" id="email" name="email" required>

    <button type="submit" class="checkout-btn">Finalizar Pedido</button>
  </form>
</div>

</div>

        
        
        
        
        
        
        
            
    

       

   <?php require_once("footer.html")?>

<script src="data_transfer_utils.js"></script> 
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

                itemPriceList= {}

                existingItemElems.forEach(elem => {
                    const id = elem.dataset.id
                    
                    if (!cart.find(item => item.id === id)) {
                        elem.remove();
                        
                    }
                    else{
                        
                        itemPriceList[id]=elem.dataset.price
                    }
                });

                let subtotal = 0;
                console.log(cart)
            for (let index = 0;index < cart.length ;index++){
                        const itemInfo = cart[index]
                        console.log(itemInfo)
                        
                        
                        
                        // Update quantity
                        console.log()
                    displaylist[index].textContent = itemInfo.quantity
                    console.log(itemPriceList)
                        subtotal += itemPriceList[itemInfo["id"]] * itemInfo.quantity*0.01;
                    
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