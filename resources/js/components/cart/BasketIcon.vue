<template>
    <div class="basket-item">
        <a href="#" id="toggle-cart-button" @click="toggleCart"><i class="fa fa-shopping-basket"></i></a>
    </div>
</template>

<script>
export default {
    name: "BasketIcon",
    methods: {
        toggleCart() {
            this.$emit('toggle-cart');
        }
    }
}
</script>

<style scoped>
.basket-item {
    display: flex;
    flex-flow: row-reverse;
}

a#toggle-cart-button {
    color: #000;
    font-size: 1rem;
    margin-right: 10px;
    margin-bottom: 10px;
    transition: all 0.2s ease-in;
}

a#toggle-cart-button:hover {
    color: #7f7575;
}
</style>
