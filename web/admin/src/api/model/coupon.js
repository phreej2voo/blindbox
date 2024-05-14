import config from "@/config";
import http from "@/utils/request";

export default {
	list: {
		url: `${config.API_URL}/marketing.coupon/index`,
		name: "优惠券列表",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	select: {
		url: `${config.API_URL}/marketing.coupon/list`,
		name: "获取盲盒分类下拉列表",
		get: async function () {
			return await http.get(this.url);
		},
	},
	add: {
		url: `${config.API_URL}/marketing.coupon/add`,
		name: "添加优惠券",
		post: async function (data, config = {}) {
			return await http.post(this.url, data, config);
		},
	},
	log: {
		url: `${config.API_URL}/marketing.coupon/log`,
		name: "单次领取记录",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	del: {
		url: `${config.API_URL}/marketing.coupon/del`,
		name: "删除优惠券",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	receive: {
		url: `${config.API_URL}/marketing.coupon/receive`,
		name: "领取记录",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	open: {
		url: `${config.API_URL}/marketing.coupon/open`,
		name: "开启关闭优惠券",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	getAll: {
		url: `${config.API_URL}/marketing.coupon/getAllCoupon`,
		name: "获取全部的优惠券",
		get: async function (data, config = {}) {
			return await http.get(this.url, data, config);
		},
	},
	send: {
		url: `${config.API_URL}/marketing.coupon/send`,
		name: "发放优惠券",
		post: async function (data, config = {}) {
			return await http.post(this.url, data, config);
		},
	},
};
