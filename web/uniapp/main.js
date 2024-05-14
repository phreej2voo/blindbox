import Vue from 'vue'
import App from './App'
import uView from '@/uni_modules/uview-ui'
import store from './store/index.js';
import user from './utils/user.js';
import login from './utils/login.js';
import share from './utils/share.js';
import font from './utils/font';
import goods from './utils/goodsParams';
import imgList from './utils/imgList';
import appLayout from '@/components/app-layout/app-layout.vue';
import modal from '@/components/modal/index.vue';
import empty from '@/components/empty/empty.vue';
import tabsList from '@/components/tabs-list/tabs-list.vue';
import comfirmMsg from '@/components/popup-comfirm-msg/index.vue';

Vue.component('app-layout', appLayout);
Vue.component('modal', modal);
Vue.component('comfirmMsg', comfirmMsg);
Vue.component('empty', empty);
Vue.component('tabsList', tabsList);
Vue.config.productionTip = false
Vue.use(uView);
App.mpType = 'app'
Vue.mixin(share);
Vue.mixin(font);
Vue.use({
	install(Vue, options) {
		// Vue.prototype.$sysAppid = sysAppid; //appid
		Vue.prototype.$store = store;
		Vue.prototype.$user = user;
		Vue.prototype.$login = login;
		Vue.prototype.$goods = goods;
		Vue.prototype.$imgList = imgList;
		// Vue.prototype.$jwx = jwx;
		//Vue.prototype.$mallConfig = mallConfig; // 商城配置
	},
});


const app = new Vue({
	...App
})
app.$mount()

