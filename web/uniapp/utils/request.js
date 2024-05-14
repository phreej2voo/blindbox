import baseUrl from './siteInfo.js';
import qs from 'qs';
import login from './login.js';
import { getCurrentRoute } from  './judge.js';

// 页面白名单
const whiteListUrl = [
	'pages/index/index', // 首页
	'pages/market/index', // 商城
	'plugins/yfs-detail/index', // 一番赏
	'plugins/hash-detail/index', // 无限赏
	'plugins/user-agreement/index', // 用户协议
	'plugins/privacy-agreement/index', // 隐私协议
];

export const request = (args) => {
	const header = {
		// 'X-Form-Id-List': JSON.stringify(popAll()),
		// 'X-Form-Id-List': [],

		'X-Requested-With': (args.header && args.header['X-Requested-With']) ? args.header[
			'X-Requested-With'] : 'XMLHttpRequest',
		'content-type': args.header && args.header.contentType ? args.header['content-type'] :
			'application/x-www-form-urlencoded',
	};

	// 当前接口是否是免登 noAuth: true-免登 false-需要登录
	if(!args.noAuth){
		if(!login.checkLogin()) {
			const curRoute = getCurrentRoute();
			// 当前路由不在白名单内
			if(!whiteListUrl.includes(curRoute)) {
				// uni.showToast({
				// 	title: '请先登录',
				// 	icon: 'error',
				// 	mask: false 
				// });
				login.toLogin();
				return Promise.reject({
					msg: `未登录`
				});
			}
		}
	}
	if(login.getToken()) {
		header['authorization'] = 'Bearer ' + login.getToken();
	}
	return new Promise((resolve, reject) => {
		uni.request({
			url: baseUrl.apiroot + args.url,
			method: args.method,
			data: args.method == 'POST' ? qs.stringify(args.data) : args.data,
			// data: args.data,
			header: header,
			success: (res) => {
				const { data } = res;
				// 登录过期自动登录
				if (data.code == 401) {
					login.toLogin();
				}
				resolve(data)
			},
			fail: (error) => {
				reject(error)
			}
		})
	})
}

