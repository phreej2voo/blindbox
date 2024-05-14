import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/user.userLevel/index`,
		name: "获取会员等级列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	edit: {
		url: `${config.API_URL}/user.userLevel/edit`,
		name: "编辑会员等级",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	add: {
		url: `${config.API_URL}/user.userLevel/add`,
		name: "添加会员等级",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	del: {
		url: `${config.API_URL}/user.userLevel/del`,
		name: "删除会员等级",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
};
