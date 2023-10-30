export default [
    {path: '/dashboard', component: require('./components/Dashboard.vue').default},
    {path: '/profile', component: require('./components/Profile.vue').default},
    {path: '/orders', component: require('./components/orders/Orders.vue').default},
    {path: '/invoice/:orderId', component: require('./components/invoice/Invoice.vue').default, props: true},
    {path: '/invoices/', component: require('./components/invoice/Invoices.vue').default, props: true},
    {path: '/developer', component: require('./components/Developer.vue').default},
    {path: '/users', component: require('./components/Users.vue').default},
    {path: '/products', component: require('./components/product/Products.vue').default},
    {path: '/product/tag', component: require('./components/product/Tag.vue').default},
    {path: '/product/category', component: require('./components/product/Category.vue').default},
    {path: '*', component: require('./components/NotFound.vue').default}
];

