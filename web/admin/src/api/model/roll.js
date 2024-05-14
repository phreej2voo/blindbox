import config from "@/config";
import http from "@/utils/request";

export default {
    list: {
        url: `${config.API_URL}/marketing.roll/index`,
        name: "福利房列表",
        get: async function (data, config = {}) {
            return await http.get(this.url, data, config);
        },
    },
    add: {
        url: `${config.API_URL}/marketing.roll/add`,
        name: "创建福利房",
        post: async function (data, config = {}) {
            return await http.post(this.url, data, config);
        },
    },
    edit: {
        url: `${config.API_URL}/marketing.roll/edit`,
        name: "编辑福利房",
        post: async function (data, config = {}) {
            return await http.post(this.url, data, config);
        },
    },
    del: {
        url: `${config.API_URL}/marketing.roll/del`,
        name: "关闭福利房",
        get: async function (data, config = {}) {
            return await http.get(this.url, data, config);
        },
    },
    reward: {
        url: `${config.API_URL}/marketing.roll/edit`,
        name: "获取奖品",
        get: async function (data, config = {}) {
            return await http.get(this.url, data, config);
        },
    },
    detail: {
        url: `${config.API_URL}/marketing.roll/detail`,
        name: "获取参与详情",
        get: async function (data, config = {}) {
            return await http.get(this.url, data, config);
        },
    }
};
