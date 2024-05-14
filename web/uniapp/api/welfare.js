import { request } from '@/utils/request.js';

// 福利房列表
export const rollList = (data) => {
	return request({
		url: '/marketing/roll/list',
		data: data,
		method: 'GET'
	})
}

// 福利房详情
export const rollInfo = (data) => {
	return request({
		url: '/marketing/roll/info',
		data: data,
		method: 'GET'
	})
}

// 加入福利房
export const rollJoin = (data) => {
	return request({
		url: '/marketing/roll/join',
		data: data,
		method: 'POST'
	})
}

// 获取我的奖品
export const rollGoods = (data) => {
	return request({
		url: '/marketing/roll/goods',
		data: data,
		method: 'GET'
	})
}

// 创建房间
export const rollCreate = (data) => {
	return request({
		url: '/marketing/roll/create',
		data: data,
		method: 'POST'
	})
}

// 参与记录
export const rollLog = (data) => {
	return request({
		url: '/marketing/roll/log',
		data: data,
		method: 'GET'
	})
}

// 获取中奖记录
export const rollReward = (data) => {
	return request({
		url: '/marketing/roll/reward',
		data: data,
		method: 'GET'
	})
}
