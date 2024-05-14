import {
	request
} from '@/utils/request.js'
//盲盒订单试算
export const order_trail = (data) => {
	return request({
		url: '/order/trail',
		data: data,
		method: 'POST'
	})
}
//盲盒创建订单
export const create_order = (data) => {
	return request({
		url: '/order/createOrder',
		data: data,
		method: 'POST'
	})
}
//盲盒支付订单
export const pay_order = (data) => {
	return request({
		url: '/order/payOrder',
		data: data,
		method: 'POST'
	})
}


//商品订单试算
export const order_shop_trail = (data) => {
	return request({
		url: '/order/shop/trail',
		data: data,
		method: 'POST'
	})
}
//商品创建订单
export const create_shop_order = (data) => {
	return request({
		url: '/order/shop/createOrder',
		data: data,
		method: 'POST'
	})
}

//商品支付订单
export const pay_shop_order = (data) => {
	return request({
		url: '/order/shop/payOrder',
		data: data,
		method: 'POST'
	})
}

/**
 * 获取重抽结果
 * @param {*} data reward_id
 * @returns 
 */
export const getReward = (data) => {
	return request({
		url: '/order/getReward',
		data: data,
		method: 'GET'
	})
}

/**
 * 获取支付方式
 * @param {*}  
 * @returns 	
 * "key": "alipay_open",
	"value": "1" 1-打开
 */
export const getPayType = () => {
	return request({
		url: '/common/payConfig',
		method: 'GET'
	})
}
