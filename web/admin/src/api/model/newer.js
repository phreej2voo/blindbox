import config from "@/config";
import http from "@/utils/request";

export default {
    info: {
        url: `${config.API_URL}/marketing.newer/index`,
        name: "新手必买配置",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        },
    },
    saveConfig: {
        url: `${config.API_URL}/marketing.newer/index`,
        name: "保存新手必买配置",
        post: async function (data = {}) {
            return await http.post(this.url, data);
        },
    },
    mustBuyOrderList: {
        url: `${config.API_URL}/marketing.newer/mustOrder`,
        name: "新手必买订单记录",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        },
    },
    present: {
        url: `${config.API_URL}/marketing.newer/present`,
        name: "买一送一配置",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        },
    },
    savePresent: {
        url: `${config.API_URL}/marketing.newer/present`,
        name: "买一送一配置",
        post: async function (data = {}) {
            return await http.post(this.url, data);
        },
    },
    presentOrder: {
        url: `${config.API_URL}/marketing.newer/presentOrder`,
        name: "新手买一送一订单记录",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        },
    }
};