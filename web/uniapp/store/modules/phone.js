const state = {
	phoneNavHeight: '',
	platform: '' // 所属平台
};

const getters = {
	phoneHieght(state) {
		return parseInt(state.phoneNavHeight.split('px')[0]) + 44 + 'px'
	},
	topHeight(state) {
		return parseInt(state.phoneNavHeight.split('px')[0]) + 'px'
	},
	uniPlatform() {
		return state.platform;
	}
};
const mutations = {
	SET_PHONE_NAME_MUT(state) {
		state.phoneNavHeight = uni.$u.addUnit(uni.$u.sys().statusBarHeight, 'px')
	},
	SET_PLATFORM(state) {
		uni.getSystemInfoSync({
			success: function (res) {
				state.platform = res.uniPlatform;
			}
		});
	}
};

const actions = {
	SET_PHONE_NAME_ACT(context) {
		context.commit('SET_PHONE_NAME_MUT')
	},
	SET_PLATFORM_ACT(context) {
		context.commit('SET_PLATFORM')
	}
};

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
};
