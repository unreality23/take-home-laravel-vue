<template>
    <div class="shopping-cart" v-if="isCartVisible">
        <button class="close-btn" @click="toggleCart">Close</button>
        <h2>Shopping Cart</h2>
        <div v-if="cartItems.length > 0">
            <ul>
                <li v-for="item in cartItems" :key="item.id">
                    <div class="item-details">
                        <img :src="item.product.photo.replace(/\s/g, '')" :alt="item.product.name" width="100">

                    </div>
                    <div class="item-info">
                        <p class="item-info-name">{{ item.product.name }}</p>
                        <p>{{ item.product.price }}£</p>
                        <p>Quantity: {{ item.quantity }}</p>
                    </div>
                    <button @click="removeFromCart(item.product.id)" class="remove-button">X</button>
                </li>
            </ul>
            <div>
                <button @click="placeOrder">Place Order</button>
            </div>
        </div>
        <div v-else>
            <p>Your cart is empty.</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cartItems: {
            type: Array,
            required: true
        },
        isCartVisible: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        removeFromCart(index) {
            this.$emit('remove-from-cart', index);
        },
        placeOrder() {
            this.$emit('place-order');
        },
        toggleCart() {
            this.$emit('update:isCartVisible', !this.isCartVisible);
        }

    },
}
</script>

<style scoped>
.shopping-cart {
    position: fixed;
    top: 0;
    right: 0;
    width: 400px;
    background-color: white;
    z-index: 9999;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.close-btn {
    position: absolute;
    right: 10px;
    top: 10px;
    background-color: red;
    border: none;
    font-size: 20px;
    cursor: pointer;
}

@media (max-width: 600px) {
    .shopping-cart {
        width: 100%;
    }
}

h2 {
    margin-bottom: 20px;
    font-size: 24px;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
}

ul > li > div {
    display: flex;
}

.item-details,
.item-info {
    flex: 1;
    font-size: 1rem;
    padding: 5px;
    flex-direction: column;
}

.item-info p {
    margin-bottom: 0px;
}

.item-info-name {
    font-weight: bold;
}

button {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

button:hover {
    background-color: darkred;
}
</style>
