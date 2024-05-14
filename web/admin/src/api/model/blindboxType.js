import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/box.blindboxType/index`,
		name: "获取盲盒分类列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	select: {
		url: `${config.API_URL}/box.blindboxType/list`,
		name: "获取盲盒分类下拉列表",
		get: async function () {
			return await http.get(this.url);
		},
	},
	add: {
		url: `${config.API_URL}/box.blindboxType/add`,
		name: "添加盲盒分类",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	edit: {
		url: `${config.API_URL}/box.blindboxType/edit`,
		name: "编辑盲盒分类",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	del: {
		url: `${config.API_URL}/box.blindboxType/del`,
		name: "删除盲盒分类",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
};
