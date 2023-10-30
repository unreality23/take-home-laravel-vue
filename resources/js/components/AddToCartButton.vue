<template>
    <button @click="addToCart">
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

</style>
