import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import phone from './modules/phone.js';
import user from './modules/user.js';
import param from './modules/param.js';
import goods from './modules/goods.js';
const store = new Vuex.Store({
	modules: {
		phone,
		user,
		param,
		goods
	}
});

export default store;

