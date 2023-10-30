<template>
    <div class="invoice-box">
        <div v-if="invoice">
            <div class="invoice-data px-3 py-5">
                <h3>Invoice Details</h3>
                <p>Invoice Number: <b>{{ invoice.invoice.invoice_number }}</b></p>
                <p>Invoice Date: {{ invoice.invoice.invoice_date }}</p>

                <h3>Products List</h3>
                <table>
                    <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="detail in orderDetails" :key="detail.id">
                        <td>{{ detail.product.name }}</td>
                        <td>{{ detail.quantity }}</td>
                        <td>{{ detail.price }}£</td>
                    </tr>
                    </tbody>
                </table>
                <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right;">Total Amount:</td>
                    <td>{{ invoice.invoice.total_amount }}£</td>
                </tr>
                </tfoot>
            </div>

            <button @click="downloadInvoice" class="mx-3">Download Your Invoice in PDF</button>
        </div>
        <div v-else>
            <p>No invoice available for this order</p>
        </div>
    </div>

</template>

<script>
import html2pdf from "html2pdf.js";

export default {
    name: "Invoice",
    props: ['orderId'],
    data() {
        return {
            invoice: null,
            orderDetails: []
        };
    },
    methods: {
      downloadInvoice() {
          let invoiceContent = document.querySelector('.invoice-data');
          html2pdf(invoiceContent);
      }
    },
    mounted() {
        axios.get(`/api/orders/${this.orderId}/invoice`)
            .then(response => {
                this.invoice = response.data; // Directly assign the response data to the invoice
                this.orderDetails = response.data.orderDetails;
            })
            .catch(error => {
                console.error('Error fetching invoice:', error);
            });
    }
}
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 8px 12px;
    border: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

tfoot td {
    font-weight: bold;
}

tfoot {
    float: right;
}

.invoice-box {
    padding: 15px;
}
</style>
