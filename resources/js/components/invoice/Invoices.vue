<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Invoices</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table">
                                <div class="table-header" :class="{'admin': $gate.isAdmin(), 'user': !$gate.isAdmin()}">
                                    <div v-if="$gate.isAdmin()">User ID</div>
                                    <div>Invoice ID</div>
                                    <div>Order ID</div>

                                </div>
                                <div v-for="invoice in invoices" :key="invoice.id" class="table-row"
                                     :class="{'admin': $gate.isAdmin(), 'user': !$gate.isAdmin()}">
                                    <div v-if="$gate.isAdmin()">{{ invoice.order.user_id }}</div>
                                    <div>
                                        <router-link :to="`/invoice/${invoice.order_id}`">
                                            {{ invoice.invoice_number }}
                                        </router-link>
                                    </div>
                                    <div>
                                        <router-link :to="`/order/${invoice.order_id}`">
                                            {{ invoice.order_id }}
                                        </router-link>
                                    </div>
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
export default {
    data() {
        return {
            invoices: [],
        };
    },
    mounted() {
        this.fetchInvoices();
    },
    methods: {
        async fetchInvoices() {
            try {
                const response = await axios.get('/api/invoices');
                this.invoices = response.data;
            } catch (error) {
                console.error('An error occurred while fetching the invoices:', error);
            }
        },
    },
};
</script>

<style scoped>
.table {
    width: 100%;
}

.table-header.admin,
.table-row.admin {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    background-color: #fff;
}

.table-header.user,
.table-row.user {
    display: grid;
    grid-template-columns: 1fr 1fr;
    background-color: #fff;
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
}
</style>
