<template>
    <div class="order-box">
        <div>
            <div class="order-data px-3 py-5" v-if="order">
                <h3>Order Details</h3>
                <p>Order ID: <b>{{ order.id }}</b></p>
                <p>Order Status: <b>{{ order.status }}</b></p>

                <h3>Order Items</h3>
                <div class="order-details">
                    <div class="details-header">
                        <div></div>
                        <div>Title</div>
                        <div>Quantity</div>
                        <div>Price</div>
                    </div>
                    <div v-for="detail in order.order_details" :key="detail.id" class="detail-row">
                        <div><img :src="'/' + detail.product.photo.replace(/\s/g, '')"
                                  :alt="detail.product.name" width="100"></div>
                        <div>{{ detail.product.name }}</div>
                        <div>{{ detail.quantity }}</div>
                        <div>{{ detail.price }}£</div>
                    </div>
                </div>
                <p class="pt-2 text-right">Total Amount: {{ order.total_amount }}£</p>
                <div v-if="order.invoice" class="pt-3">
                    <h3>Invoice</h3>
                    <p>Invoice Number:
                        <router-link :to="`/invoice/${order.invoice.order_id}`">
                            {{ order.invoice.invoice_number }}
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "OrderDetails",
    data() {
        return {
            order: null
        };
    },
    methods: {
        getImageUrl(relativePath) {

        }
    },
    async mounted() {
        const orderId = this.$route.params.order_id;
        try {
            const response = await axios.get(`/api/order/${orderId}`);
            this.order = response.data.order;
            console.log('Order Data:', this.order);
        } catch (error) {
            console.error('An error occurred while fetching the order:', error);
        }
    }
};
</script>

<style scoped>
.table {
    width: 100%;
}


.table-header,
.table-row,
.details-header,
.detail-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    background-color: #fff;
    width: 100%;
}

.order-details {
    grid-column: span 6;
}

.table-header div {
    padding: 10px;
    background-color: #eee;
    font-weight: bold;
}

.table-row > div,
.detail-row div {
    padding: 10px;
    border-bottom: 1px solid #ccc;
}

button {
    cursor: pointer;
    border: #38c172;
    color: #fff;
}

</style>
