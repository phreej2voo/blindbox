import config from "@/config";
import http from "@/utils/request";

export default {
    set: {
        url: `${config.API_URL}/marketing.Invite/set`,
        name: "设置邀新",
        post: async function (data = {}) {
            return await http.post(this.url, data);
        },
    },
    getSetInfo: {
        url: `${config.API_URL}/marketing.Invite/set`,
        name: "获取邀新信息",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        },
    },
    log: {
        url: `${config.API_URL}/marketing.Invite/getInviteLog`,
        name: "获取邀新信息",
        get: async function (data = {}) {
            return await http.get(this.url, data);
        },
    }
};