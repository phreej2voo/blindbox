// 云函数
exports.main = async (event) => {

	const res = await uniCloud.
	getPhoneNumber({
		appid: event.queryStringParameters.appid, // 替换成自己开通一键登录的应用的DCloud appid
		provider: 'univerify',
		apiKey: event.queryStringParameters.apiKey, // 在开发者中心开通服务并获取apiKey
		apiSecret: event.queryStringParameters.apiSecret, // 在开发者中心开通服务并获取apiSecret
		access_token: event.queryStringParameters.access_token,
		openid: event.queryStringParameters.openid
		// access_token: event.access_token,
		// openid: event.openid
	})
	// 执行入库等操作，正常情况下不要把完整手机号返回给前端	
	return {
		code: 0,
		data: res,
		message: '获取手机号成功'
	}


	return res
}

