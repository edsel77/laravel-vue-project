/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import { Form, HasError, AlertError } from 'vform';
import Swal from 'sweetalert2';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/en';

window.Form = Form;
window.Swal = Swal;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.use(VueRouter);
Vue.use(ElementUI, { locale });

let routes = [
	{ path: '/home', component: require('./components/Home.vue').default },
	{ path: '/user-management', component: require('./components/UserManagement.vue').default },
	{ path: '/register-user', component: require('./components/RegisterUser.vue').default },
	{ path: '/profile/:user_id', component: require('./components/Profile.vue').default },
	{ path: '/update-user/:user_id', component: require('./components/UserSetting.vue').default },
	{ path: '/account-setting/:user_id', component: require('./components/UserSetting.vue').default },
]

const router = new VueRouter({
	mode: 'history',
	routes,
});

Vue.prototype.$user_roles = [
	{ 'id': 1, 'role': 'Admin' },
	{ 'id': 2, 'role': 'User' },
];

const app = new Vue({
	router,
}).$mount('#app');
