import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/store.Store/index`,
		name: "获取门店配置列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	add: {
		url: `${config.API_URL}/store.Store/add`,
		name: "添加门店",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	edit: {
		url: `${config.API_URL}/store.Store/edit`,
		name: "编辑门店",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	del: {
		url: `${config.API_URL}/store.Store/del`,
		name: "删除门店",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
};
