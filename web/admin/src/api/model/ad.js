import config from "@/config";
import http from "@/utils/request";

export default {
    list: {
        url: `${config.API_URL}/marketing.Advertisement/index`,
        name: "获取广告列表",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        }
    },
    add: {
        url: `${config.API_URL}/marketing.Advertisement/add`,
        name: "添加广告",
        post: async function (data = {}) {
            return await http.post(this.url, data);
        }
    },
    edit: {
        url: `${config.API_URL}/marketing.Advertisement/edit`,
        name: "编辑广告",
        post: async function (data = {}) {
            return await http.post(this.url, data);
        }
    },
    del: {
        url: `${config.API_URL}/marketing.Advertisement/del`,
        name: "删除广告",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        }
    },
};