import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/marketing.vipCard/index`,
		name: "会员卡列表",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	add: {
		url: `${config.API_URL}/marketing.vipCard/add`,
		name: "添加会员卡",
		post: async function (data, config = {}) {
			return await http.post(this.url, data, config);
		},
	},
	edit: {
		url: `${config.API_URL}/marketing.vipCard/edit`,
		name: "编辑会员卡",
		post: async function (data, config = {}) {
			return await http.post(this.url, data, config);
		},
	},
	del: {
		url: `${config.API_URL}/marketing.vipCard/del`,
		name: "删除会员卡",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	info: {
		url: `${config.API_URL}/marketing.vipCard/edit`,
		name: "获取会员卡信息",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	buy: {
		url: `${config.API_URL}/marketing.vipCard/buy`,
		name: "获取购买记录",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	log: {
		url: `${config.API_URL}/marketing.vipCard/log`,
		name: "获取领取记录",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	}
};
