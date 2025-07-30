document.addEventListener('DOMContentLoaded', () => {

    // --- Funções do Carrinho ---
    const updateCartBadge = () => {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartBadge = document.getElementById('cart-badge');
        const totalItemsInCart = cart.reduce((sum, item) => sum + item.quantity, 0);
        if (cartBadge) {
            cartBadge.textContent = totalItemsInCart;
        }
    };

    const addToCart = (product) => {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingProductIndex = cart.findIndex(item => item.id == product.id);

        if (existingProductIndex > -1) {
            cart[existingProductIndex].quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartBadge();
        console.log(`Produto "${product.name}" adicionado/quantidade atualizada no carrinho!`);
        renderCartItems(); // Recarrega o carrinho para refletir a nova quantidade
    };
    const animateButton = (btn) => {
    btn.classList.add('jump');
    setTimeout(() => btn.classList.remove('jump'), 300); // Remove after animation finishes
};
    const updateQuantity = (productId, change) => {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const productIndex = cart.findIndex(item => item.id == productId);

        if (productIndex > -1) {
            cart[productIndex].quantity += change;
            if (cart[productIndex].quantity <= 0) {
                cart.splice(productIndex, 1); // Remove o item se a quantidade for 0 ou menos
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartBadge();
            renderCartItems(); // Recarrega o carrinho para refletir a nova quantidade e total
        }
    };

    const removeFromCart = (productId) => {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.id != productId); // Remove TODAS as ocorrências do produto
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartBadge();
        renderCartItems(); // Recarrega a lista do carrinho
    };

    // Função para renderizar o carrinho na página do carrinho.html
    const renderCartItems = () => {
        const cartItemsList = document.getElementById('cart-items-list');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const subtotalValue = document.getElementById('subtotal-value');
        const totalValue = document.getElementById('total-value');
        const deliveryFeeValue = document.getElementById('delivery-fee-value'); // Adicionado
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        if (!cartItemsList) return; // Sai se não estiver na página do carrinho (ex: index.html)

        cartItemsList.innerHTML = ''; // Limpa os itens existentes

        if (cart.length === 0) {
            emptyCartMessage.style.display = 'block';
            subtotalValue.textContent = 'R$ 0,00';
            totalValue.textContent = 'R$ 5,00'; // Manter taxa de entrega mesmo com carrinho vazio
            deliveryFeeValue.textContent = 'R$ 5,00'; // Garante que a taxa de entrega seja exibida
        } else {
            emptyCartMessage.style.display = 'none';
            let subtotal = 0;

            cart.forEach(item => {
                const cartItemDiv = document.createElement('div');
                cartItemDiv.classList.add('cart-item');
                cartItemDiv.setAttribute('data-id', item.id);
                cartItemDiv.innerHTML = `
                    <img src="${item.img}" alt="${item.name}">
                    <div class="item-info">
                        <h3>${item.name}</h3>
                        <p class="item-price">R$ ${(item.price * item.quantity).toFixed(2)}</p>
                    </div>
                    <div class="quantity-controls">
                        <button class="quantity-btn decrease-quantity" data-id="${item.id}">-</button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button class="quantity-btn increase-quantity" data-id="${item.id}">+</button>
                    </div>
                    <button class="remove-from-cart-btn" data-id="${item.id}">Remover</button>
                `;
                cartItemsList.appendChild(cartItemDiv);
                subtotal += (item.price * item.quantity);
            });

            const deliveryFee = 5.00;
            subtotalValue.textContent = `R$ ${subtotal.toFixed(2)}`;
            deliveryFeeValue.textContent = `R$ ${deliveryFee.toFixed(2)}`; // Exibe a taxa de entrega
            totalValue.textContent = `R$ ${(subtotal + deliveryFee).toFixed(2)}`;
        }
    };

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
    // --- Funções de Favoritos (Mantidas como estão, pois não parecem ter problema) ---
    const updateFavoritesBadge = () => {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const favoritesBadge = document.getElementById('favorites-badge');
        if (favoritesBadge) {
            favoritesBadge.textContent = favorites.length;
        }
    };

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
        renderFavoriteItems(); // Atualiza a lista de favoritos na página de favoritos
    };

    const renderFavoriteItems = () => {
        const favoritesList = document.getElementById('favorites-list');
        const emptyFavoritesMessage = document.getElementById('empty-favorites-message');
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

        if (!favoritesList) return;

        favoritesList.innerHTML = '';

        if (favorites.length === 0) {
            emptyFavoritesMessage.style.display = 'block';
        } else {
            emptyFavoritesMessage.style.display = 'none';
            favorites.forEach(item => {
                const productCard = document.createElement('div');
                productCard.classList.add('product-card');
                productCard.setAttribute('data-id', item.id);
                productCard.setAttribute('data-name', item.name);
                productCard.setAttribute('data-price', item.price);
                productCard.setAttribute('data-img', item.img);

                productCard.innerHTML = `
                    <button class="favorite-btn favorited"><i class="fas fa-heart"></i></button>
                    <img src="${item.img}" alt="${item.name}">
                    <div class="product-info">
                        <h3>${item.name}</h3>
                        <p>${item.name.includes('Cerveja') ? 'Long Neck 330ml' : ''}</p> 
                        <span class="price">R$ ${item.price.toFixed(2)}</span>
                    </div>
                    <button class="add-to-cart-btn">Adicionar</button>
                `;
                favoritesList.appendChild(productCard);
            });

            // Re-adiciona listeners para botões de adicionar ao carrinho e favoritar
            favoritesList.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const productCard = event.target.closest('.product-card');
                    const productId = productCard.dataset.id;
                    const productName = productCard.dataset.name;
                    const productPrice = parseFloat(productCard.dataset.price);
                    const productImg = productCard.dataset.img;
                    animateButton(button)
                    addToCart({ id: productId, name: productName, price: productPrice, img: productImg });
                });
            });

            favoritesList.querySelectorAll('.favorite-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const productCard = event.target.closest('.product-card');
                    const productId = productCard.dataset.id;
                    const productName = productCard.dataset.name;
                    const productPrice = parseFloat(productCard.dataset.price);
                    const productImg = productCard.dataset.img;
                    toggleFavorite(productCard, productId, productName, productPrice, productImg);
                });
            });
        }
    };
























    // --- Lógica Principal e Delegação de Eventos ---

    // Inicialização ao carregar a página
    updateCartBadge();
    updateFavoritesBadge();
    renderCartItems(); // Renderiza carrinho se estiver na página correta (carrinho.html)
    renderFavoriteItems(); // Renderiza favoritos se estiver na página correta (favoritos.html)

    // Delegação de eventos para os botões de adicionar ao carrinho e favoritar (para index.html e favoritos.html)
    // Esses listeners ficam no document porque os product-cards são estáticos (ou menos dinâmicos)
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const productCard = event.target.closest('.product-card');
            const productId = productCard.dataset.id;
            const productName = productCard.dataset.name;
            const productPrice = parseFloat(productCard.dataset.price);
            const productImg = productCard.dataset.img;
            addToCart({ id: productId, name: productName, price: productPrice, img: productImg });
        });
    });

    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const productCard = event.target.closest('.product-card');
            const productId = productCard.dataset.id;
            const productName = productCard.dataset.name;
            const productPrice = parseFloat(productCard.dataset.price);
            const productImg = productCard.dataset.img;
            toggleFavorite(productCard, productId, productName, productPrice, productImg);
        });
    });

    // MARCA AQUI A MUDANÇA CRÍTICA PARA O CARRINHO: DELEGAÇÃO DE EVENTOS
    // Adiciona um ÚNICO listener ao elemento pai do carrinho (cart-items-list)
    // Ele vai "ouvir" cliques em seus filhos com as classes específicas
    const cartItemsListElement = document.getElementById('cart-items-list');
    if (cartItemsListElement) { // Garante que estamos na página do carrinho
        cartItemsListElement.addEventListener('click', (event) => {
            const target = event.target;
            const productId = target.dataset.id;

            if (target.classList.contains('increase-quantity')) {
                updateQuantity(productId, 1);
            } else if (target.classList.contains('decrease-quantity')) {
                updateQuantity(productId, -1);
            } else if (target.classList.contains('remove-from-cart-btn')) {
                removeFromCart(productId);
            }
        });
    }

    // Marca os favoritos iniciais ao carregar a página
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
    markInitialFavorites();

    // Lógica da barra de pesquisa
    const productSearchInput = document.getElementById('product-search');
    if (productSearchInput) {
        productSearchInput.addEventListener('input', (event) => {
            const searchTerm = event.target.value.toLowerCase();
            const allProductCards = document.querySelectorAll('.product-card');
            
            allProductCards.forEach(card => {
                const productName = card.dataset.name.toLowerCase();
                if (productName.includes(searchTerm)) {
                    card.style.display = 'block'; // Mostra o card
                    const categorySection = card.closest('.product-grid');
                    if (categorySection) {
                        const categoryTitle = categorySection.previousElementSibling; // h2
                        const categoryDivider = categoryTitle ? categoryTitle.nextElementSibling : null; // hr
                        
                        if (categoryTitle && categoryTitle.classList.contains('category-title')) {
                            categoryTitle.style.display = 'block';
                        }
                        if (categoryDivider && categoryDivider.classList.contains('category-divider')) {
                            categoryDivider.style.display = 'block';
                        }
                    }
                } else {
                    card.style.display = 'none'; // Esconde o card
                }
            });

            // Ocultar categorias vazias após a pesquisa
            document.querySelectorAll('.category-title').forEach(title => {
                const categorySection = title.nextElementSibling; // O product-grid da categoria
                if (categorySection && categorySection.classList.contains('product-grid')) {
                    const visibleProducts = categorySection.querySelectorAll('.product-card[style*="display: block"]');
                    const hrDivider = title.nextElementSibling; // O <hr> logo abaixo do título

                    if (visibleProducts.length === 0) {
                        title.style.display = 'none';
                        if (hrDivider && hrDivider.tagName === 'HR') {
                            hrDivider.style.display = 'none';
                        }
                    } else {
                        title.style.display = 'block';
                        if (hrDivider && hrDivider.tagName === 'HR') {
                            hrDivider.style.display = 'block';
                        }
                    }
                }
            });
        });
    }

}); // Fim do DOMContentLoaded