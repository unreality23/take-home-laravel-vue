export default {
    methods: {
        async placeOrder() {
            try {
                const total_amount = this.cartItems.reduce((total, item) => {
                    return total + (item.product.price * item.quantity);
                }, 0);

                const response = await axios.post('/api/orders', {
                    total_amount,
                    items: this.cartItems.map(item => ({
                        product_id: item.product.id,
                        quantity: item.quantity,
                        price: parseFloat(item.product.price)
                    }))
                }, {
                    withCredentials: true
                });

                if (response.status === 200) {
                    this.cartItems = [];
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Order placed succesfully'
                    })

                }

            } catch (error) {
                console.error('An error occured while placing the order: ', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ohoh..',
                    text: 'An error occured while placing the order'
                })
            }

        },
        async addToCart(product) {
            await axios.post(`/api/cart/${this.userId}/add`, {product_id: product.id, quantity: 1});
            this.loadCartItems();
            this.isCartVisible = true;
        },
        async removeFromCart(itemId) {
            try {
                await axios.delete(`/api/cart/${this.userId}/remove/${itemId}`);
                this.loadCartItems();
            } catch (error) {
                console.error("An error occurred while removing the item from the cart:", error);
            }

        },
        async loadCartItems() {
            if (this.userId) {
                const response = await axios.get(`/api/cart/${this.userId}/items`);
                this.cartItems = response.data;
            }

        },
        async toggleCart() {
            this.isCartVisible = !this.isCartVisible;
        }
    }
};
