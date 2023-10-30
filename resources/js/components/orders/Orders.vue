<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Latest Orders</h3>

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
                            <div>
                                <select v-model="invoiceFilter" @change="fetchOrders">
                                    <option value="">All Orders</option>
                                    <option value="invoiced">Invoiced</option>
                                    <option value="not_invoiced">Not Invoiced</option>
                                </select>
                            </div>
                            <div class="table">
                                <div class="table-header" :class="{'admin': $gate.isAdmin(), 'user': !$gate.isAdmin()}">
                                    <div v-if="$gate.isAdmin()">User ID</div>
                                    <div>Order ID</div>
                                    <div>Total Amount</div>
                                    <div>Status</div>
                                    <div>View Details</div>
                                    <div>Invoice</div>
                                </div>

                                <div v-for="order in orders" :key="order.id" class="table-row"
                                     :class="{'admin': $gate.isAdmin(), 'user': !$gate.isAdmin()}">
                                    <div v-if="$gate.isAdmin()">{{ order.user_id }}</div>
                                    <div>
                                        <router-link :to="`/invoice/${order.id}`">{{ order.id }}</router-link>
                                    </div>
                                    <div>{{ order.total_amount }}</div>
                                    <div>
                                        <span v-if="!$gate.isAdmin()" class="badge" :class="getColor(order.status)">{{
                                                capitalizeFirstLetter(order.status)
                                            }}</span>
                                        <select v-if="$gate.isAdmin()" v-model="order.status"
                                                @change="prepareStatusChange(order)">
                                            <option value="pending">Pending</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="processing">Processing</option>
                                        </select>
                                        <button
                                            v-if="$gate.isAdmin()"
                                            @click="updateOrderStatus(order)"
                                            class="btn btn-success elevation-1"
                                        >Update Status
                                        </button>
                                    </div>
                                    <div>
                                        <button
                                            @click="toggleDetails(order)"
                                            class="btn btn-info elevation-1"
                                        >
                                            View Details
                                        </button>
                                    </div>
                                    <div>
                                        <button v-if="$gate.isAdmin() && !order.invoice_id"
                                                @click="generateInvoice(order)"
                                                class="btn btn-success elevation-1">
                                            Generate Invoice
                                        </button>
                                        <div v-else-if="isInvoiceGenerated || order.invoice_id ">Invoice Generated</div>
                                    </div>

                                    <div v-if="order.showDetails" class="order-details">
                                        <div class="details-header"
                                             :class="{'admin': $gate.isAdmin(), 'user': !$gate.isAdmin()}">
                                            <div></div>
                                            <div>Title</div>
                                            <div>Quantity</div>
                                            <div>Price</div>
                                        </div>
                                        <div v-for="detail in order.order_details" :key="detail.id" class="detail-row"
                                             :class="{'admin': $gate.isAdmin(), 'user': !$gate.isAdmin()}">
                                            <div><img :src="detail.product.photo.replace(/\s/g, '')"
                                                      :alt="detail.product.name" width="100"></div>
                                            <div>{{ detail.product.name }}</div>
                                            <div>{{ detail.quantity }}</div>
                                            <div>{{ detail.price }}</div>
                                        </div>
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
import Invoice from "../invoice/Invoice";

export default {
    components: {
        Invoice
    },
    data() {
        return {
            orders: [],
            userId: null,
            isCartVisible: false,
            isInvoiceGenerated: false,
            invoiceFilter: '',
            selectedStatus: {},
        };
    },
    methods: {
        toggleDetails(order) {
            this.$set(order, 'showDetails', !order.showDetails);
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        getColor(status) {
            const statusColors = {
                'pending': 'badge-warning',
                'shipped': 'badge-success',
                'delivered': 'badge-danger',
                'cancelled': 'badge-warning',
                'processing': 'badge-info',
            };
            return statusColors[status];
        },
        async fetchOrders() {
            try {
                let url = '/api/orders';
                const response = await axios.get(url, {

                    params: {
                        invoiced: this.invoiceFilter
                    }
                });
                this.orders = response.data;
            } catch (error) {
                console.error('An error occured while fetching the orders:', error);
            }

        },
        prepareStatusChange(order) {
            this.selectedStatus[order.id] = order.status;
        },
        async updateOrderStatus(order) {
            try {
                const newStatus = this.selectedStatus[order.id];
                const response = await axios.put(`/api/orders/${order.id}`, {
                    status: newStatus
                });
                console.log(newStatus);
                if (response.data.status === newStatus) {
                    order.status = newStatus; // Update the local state
                    Swal.fire({
                        icon: 'success',
                        title: 'Status changed',
                        text: `Order Status was updated succesfully to "${order.status}"`,
                        showConfirmButton: false,
                        timer: 2500
                    })
                } else {
                    console.error('The status was not updated on the server.');
                    Swal.fire({
                        icon: 'error',
                        title: 'Ohoh..',
                        text: 'The status was not updated on the server.'
                    })
                }
            } catch (error) {
                console.error('An error occurred while updating the order status:', error)
            }
        },
        async generateInvoice(order) {
            try {
                const response = await axios.post(`/api/orders/${order.id}/invoice`);
                if (response.data.message === 'Invoice created successfully') {
                    this.$set(order, 'invoice_id', response.data.invoice_id); // Update the local state
                    this.isInvoiceGenerated = true;
                    Swal.fire({
                        icon: 'success',
                        title: 'Invoice generated',
                        text: `Invoice is generated succesfully, click on order for a view`,
                    })
                }
            } catch (error) {
                console.error('An error occurred while generating the invoice:', error);
            }
        }
    },
    async mounted() {
        this.fetchOrders();
    },
    created() {
        this.userId = window.user.id;
        this.loadCartItems();
    }
}
</script>

<style scoped>
.table {
    width: 100%;
}

.table-header.admin,
.table-row.admin,
.details-header.admin,
.detail-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
    background-color: #fff;
    width: 100%;
}

.table-header.user,
.table-row.user,
.details-header.user,
.detail-row.user {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
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

.badge {
    font-size: 100% !important;
}
</style>
