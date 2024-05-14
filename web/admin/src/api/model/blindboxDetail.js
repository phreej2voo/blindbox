import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/box.blindboxGoods/index`,
		name: "获取盲盒商品列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	add: {
		url: `${config.API_URL}/box.blindboxGoods/add`,
		name: "添加盲盒商品",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	box: {
		url: `${config.API_URL}/box.blindboxGoods/box`,
		name: "生成箱子",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	boxesList: {
		url: `${config.API_URL}/box.BlindboxBoxes/index`,
		name: "箱子列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	incBoxes: {
		url: `${config.API_URL}/box.BlindboxBoxes/incBoxes`,
		name: "补箱",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	}
};
