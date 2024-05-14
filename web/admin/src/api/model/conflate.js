import config from "@/config";
import http from "@/utils/request";

export default {
	// name=&page=1&limit=15&status=&activity_time=
	conflateList: {
		url: `${config.API_URL}/marketing.Conflate/index`,
		name: "获取碎片合成列表",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	conflateAdd: {
		url: `${config.API_URL}/marketing.Conflate/add`,
		name: "添加碎片合成",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	conflateEdit: {
		url: `${config.API_URL}/marketing.Conflate/edit`,
		name: "编辑碎片合成",
		post: async function (data = {}) {
			return await http.post(this.url, data);
		},
	},
	// id
	conflateDel: {
		url: `${config.API_URL}/marketing.Conflate/del`,
		name: "删除合成活动",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	},
	conflateLog: {
		url: `${config.API_URL}/marketing.Conflate/log`,
		name: "合成记录",
		get: async function (data = {}) {
			return await http.get(this.url, data);
		},
	}
};
