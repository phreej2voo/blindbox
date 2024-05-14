import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/order.storeOrder/index`,
		name: "线下预约订单列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	edit: {
		url: `${config.API_URL}/order.storeOrder/edit`,
		name: "编辑线下预约订单",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	check: {
		url: `${config.API_URL}/order.storeOrder/check`,
		name: "核销线下预约订单",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
};
