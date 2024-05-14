import {
	request
} from '@/utils/request.js'
//可购会员卡列表
export const vipCardList = (data) => {
	return request({
		url: '/marketing/vipCard/list',
		data: data,
		method: 'GET'
	})
}
// 创建订单
export const createOrder = (data) => {
	return request({
		url: '/marketing/vipCard/createOrder',
		data: data,
		method: 'POST'
	})
}
// 权益领取记录
export const vipCardLog = (data) => {
	return request({
		url: '/marketing/vipCard/log',
		data: data,
		method: 'GET'
	})
}
// 待领取权限
export const vipCardEquity = (data) => {
	return request({
		url: '/marketing/vipCard/equity',
		data: data,
		method: 'GET'
	})
}
// 领取权益
export const vipCardReceive = (data) => {
	return request({
		url: '/marketing/vipCard/receive',
		data: data,
		method: 'POST'
	})
}