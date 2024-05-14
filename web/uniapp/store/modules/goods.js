const state = {
	poolsDetails: {}, // 商品池子
    goodsDetails: {}, // 中奖结果
	goodsList: [], // 首页商品列表
	weeklyInfo: {},
	weeklyDetail: {} // 当前每日限定详情
};

const getters = {
	getPoolsDetails(state) {
		return state.poolsDetails
	},
	getGoodsDetails(state) {
		return state.goodsDetails
	},
	getGoodsList(state) {
		return state.goodsList
	},
	getWeeklyInfo(state) {
		return state.weeklyInfo
	},
	getWeeklyDetail(state) {
		return state.weeklyDetail
	}
};

const mutations = {
	setPoolsDetails(state, data) {
		state.poolsDetails = data
	},
	setGoodsDetails(state, data) {
		state.goodsDetails = data
	},
	setGoodsList(state, data) {
		state.goodsList = data
	},
	setWeeklyInfo(state, data) {
		state.weeklyInfo = data
	},
	setWeeklyDetail(state, data) {
		state.weeklyDetail = data
	}
};

const actions = {
	setPoolsDetailsAct(context) {
		context.commit('setPoolsDetails')
	},
	setGoodsDetailsAct(context) {
		context.commit('setGoodsDetails')
	},
	setGoodsListAct(context) {
		context.commit('setGoodsList')
	},
	setWeeklyInfoAct(context) {
		context.commit('setWeeklyInfo')
	},
	setWeeklyDetailAct(context) {
		context.commit('setWeeklyDetail')
	}
};

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
}

