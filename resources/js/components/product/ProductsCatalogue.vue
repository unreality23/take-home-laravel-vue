<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <BasketIcon @toggle-cart="toggleCart"/>
                </div>
                <div>
                    <ShoppingCart :cartItems="cartItems"
                                  @remove-from-cart="removeFromCart"
                                  @place-order="placeOrder"
                                  @toggle-cart="toggleCart"
                                  :isCartVisible.sync="isCartVisible"
                    />
                </div>

                <div class="col-12 px-5">
                    <div>
                        <div class="pb-5 ">
                            <h3 class="">Products Catalogue</h3>
                        </div>
                        <div class="product-flex">
                            <div v-for="product in products.data" :key="product.id" class="product-box text-left w-100">
                                <div class="item-image">
                                    <img :src="product.photo.replace(/\s/g, '')" :alt="product.name" class="w-100">
                                </div>
                                <div class="pt-1"><b>{{ product.name }}</b></div>
                                <div class="py-1">{{ product.description | truncate(60, '...') }}</div>
                                <div>Price: <b>{{ product.price }}£</b></div>
                                <div class="pt-1">
                                    <AddToCartButton :product="product" @add-to-cart="addToCart"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import VueTagsInput from '@johmun/vue-tags-input';
import ShoppingCart from "../cart/ShoppingCart";
import AddToCartButton from "../cart/AddToCartButton";
import ShoppingCartMixin from "../cart/ShoppingCartMixin";
import BasketIcon from "../cart/BasketIcon";

export default {
    mixins: [ShoppingCartMixin],
    components: {
        VueTagsInput,
        ShoppingCart,
        AddToCartButton,
        BasketIcon
    },
    data() {
        return {
            products: {},
            cartItems: [],
            userId: null,
            isCartVisible: false,
        }
    },
    methods: {

        getResults(page = 1) {

            this.$Progress.start();

            axios.get('api/product?page=' + page).then(({data}) => (this.products = data.data));

            this.$Progress.finish();
        },
        loadProducts() {
            // if(this.$gate.isAdmin()){
            axios.get("api/product").then(({data}) => (this.products = data.data));
            // }
        },

    },
    created() {
        this.loadProducts();
        this.userId = window.user.id;
        this.loadCartItems();
    },
    filters: {
        truncate: function (text, length, suffix) {
            return text.substring(0, length) + suffix;
        },
    },
}
</script>

<style scoped>
.product-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    align-items: stretch;
}

.product-box {
    display: flex;
    flex-direction: column;
    flex: 1 1 calc(25% - 30px); /* 4 items in a row */
    max-width: calc(25% - 30px);
}

/* For mobile screens */
@media (max-width: 768px) {
    .product-box {
        flex: 1 1 calc(50% - 30px); /* 2 items in a row */
        max-width: calc(50% - 30px);
    }
}
</style>
