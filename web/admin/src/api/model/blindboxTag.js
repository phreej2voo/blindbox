import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/box.blindboxTag/index`,
		name: "获取盲盒标签列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	select: {
		url: `${config.API_URL}/box.blindboxTag/list`,
		name: "获取盲盒标签下拉列表",
		get: async function () {
			return await http.get(this.url);
		},
	},
	add: {
		url: `${config.API_URL}/box.blindboxTag/add`,
		name: "添加盲盒标签",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	edit: {
		url: `${config.API_URL}/box.blindboxTag/edit`,
		name: "编辑盲盒标签",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	del: {
		url: `${config.API_URL}/box.blindboxTag/del`,
		name: "删除盲盒标签",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
};
