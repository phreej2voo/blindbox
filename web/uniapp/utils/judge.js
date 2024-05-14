// 页面白名单
const whiteListUrl = [
	'pages/index/index', // 首页
	'pages/market/index', // 商城
	// 'plugins/goods-detail/index', // 普通商品详情
	// 'plugins/newer-goods-detail/index' // 新人商品详情
];

const tabBarUrl = [
	'pages/index/index',
	'pages/market/index',
	'pages/warehouse/index',
	'pages/my/index'
]

const apiWhiteList = [
	'/marketing/dailyTask', // 首页-每日任务
	'/marketing/rank/index' // 首页-榜单
];

// 获取当前路由
export const getCurrentRoute = () => {
	const currentRoutes = getCurrentPages();
    const currentRoute = currentRoutes.length ? currentRoutes[currentRoutes.length - 1].route : '';
    return currentRoute;
}
// 获取路由参数
export const optionsParams = (index) => {
	const currentRoutes = getCurrentPages();
	if(index >= currentRoutes.length) {
		return '';
	}
	const currentParam = currentRoutes[index].options;
	// 拼接参数
	let param = '';
	for (let key in currentParam) {
		param += '?' + key + '=' + currentParam[key];
	}
	return param;
}

export const getCurrentLastRoute = () => {
	const currentRoutes = getCurrentPages();
	let currentRoute = '';
	let params = '';
	if(currentRoutes.length && currentRoutes.length > 1) {
		currentRoute = currentRoutes[currentRoutes.length - 2].route;
		params = optionsParams(currentRoutes.length - 2);
	}
    return currentRoute + params;
}

// 判断当前页面是否在白名单内
export const judgeNeedLogin = () => {
	return whiteListUrl.includes(getCurrentRoute());
}

// 判断当前请求api是否需要登录
export const judgeNeedLoginApi = (url) => {
	return apiWhiteList.includes(url);
}

// 微信浏览器不支持唤起支付宝
export const isWeixinBrowser = () => {
    if (/MicroMessenger/.test(window.navigator.userAgent)) {
        // 微信
        return true;
    } else {
        // /AlipayClient/.test(window.navigator.userAgent) 支付宝
        return false;
    }
}
// 重新登陆后跳转页面(当前页面为登陆页面)
export const jumpCurrentRoute = () => {
	if(getCurrentLastRoute()) {
		uni.reLaunch({url: '/' + getCurrentLastRoute()});
	} else {
		uni.reLaunch({
			url: '/pages/index/index'
		});
	}
}