const CART_KEY = 'cart';
const getCart = () => {
    const cart = JSON.parse(localStorage.getItem(CART_KEY));
    return Array.isArray(cart) ? cart : [];
};
const fetchServerCart = () => {
    return fetch('save_cart.php', { method: 'GET' })
        .then(response => response.json())
        .then(serverCart => {
            if (serverCart && serverCart.length > 0) {
                // Server has cart data, save it to localStorage and update badge
                localStorage.setItem(CART_KEY, JSON.stringify(serverCart));
                
                return serverCart;
            } else {
                // Server empty, send client cart to server
                const clientCart = getCart();
                if (clientCart.length > 0) {
                    return fetch('save_cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ cart: clientCart })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Server cart initialized:', data);
                        return clientCart;
                    });
                }
                return clientCart; // empty cart
            }
        });
};

const sendCartToServer = (cart) => {

 fetch('save_cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ cart }) 
    })
    .then(response => response.json())
    .then(data => console.log('Server response:', data));


}
