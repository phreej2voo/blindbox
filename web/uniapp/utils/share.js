// 不支持API调用，只能用户主动点击触发分享。
// 可使用自定义按钮方式 <button open-type="share"> 或监听系统右上角的分享按钮 onShareAppMessage 进行自定义分享内容
import { Base64 } from '../js_sdk/js-base64/base64.min';

export default {
	data() {
		return {
			// 默认的全局分享内容
			shareConfig: {
				title: '全局分享的标题',
				path: '/pages/index/index',    // 全局分享的路径，比如 首页
				imageUrl: ''    // 全局分享的图片(可本地可网络)
			}
		}
	},
	onLoad: function() {
		// #ifdef MP-WEIXIN
		wx.showShareMenu({
			withShareTicket: true,
			menus: ["shareAppMessage" ,"shareTimeline"]
		})
		// #endif
	},
	// 分享给朋友
	onShareAppMessage(res) {
		let title = '一番赏';
		// button-页面内分享按钮(一番赏详情分享)
		if (res.from === 'button'){
			if(res.target.dataset.name === 'yfsShare') {
				console.log('route-', this.$scope); // $page.fullPath 带参全路径
				const data = uni.getStorageSync('userInfo');
				const id1 = Base64.encode(JSON.stringify(data.id));
				const id2 = Base64.encode(id1);
				// 这块需要传参，不然链接地址进去获取不到数据
				const path = `/` + this.$scope.route + `?agent=${id2}&id=${this.$scope.options.id}&type=${this.$scope.options.type}`;
				console.log('path-', path);
				return {
					title: title,
					path
				};
			}
		}
		// menu-右上角分享按钮
		if (res.from === 'menu') {
			return {
				title: title,
				path: '/pages/index/index',
				// imageUrl: imageUrl
			};
		}
	},
	// 分享到朋友圈
	onShareTimeline(res) {
		return {
			title: '哈希玛特',
			path: '/pages/index/index',
			imageUrl: ''
		};
	},
	methods: {}
}

