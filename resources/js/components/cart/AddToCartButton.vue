<template>
    <button @click="addToCart" class="btn btn-success elevation-1">
        Add To Cart
    </button>
</template>

<script>
export default {
    name: "AddToCartButton",
    props: {
        product: {
            type: Object,
            required: true,
        }
    },
    methods: {
        addToCart() {
            this.$emit('add-to-cart', this.product);
        }
    }
}
</script>

<style scoped>
    button {
        cursor: pointer;
        border: #38c172;
    }
</style>
