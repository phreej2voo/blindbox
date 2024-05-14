import { request } from '@/utils/request.js';

/**
 * 查看用户等级
 * @param {*} data level
 * @returns 
 */
export const getUserLevel = (data) => {
	return request({
		url: '/user/level',
		data: data,
		method: 'GET'
	})
}

/**
 * 是否开启用户等级
 * @param {*} data level
 * @returns 
 */
export const getLevelStatus = (data) => {
	return request({
		url: '/user/levelStatus',
		data: data,
		method: 'GET'
	})
}

/**
 * 我的权益数量
 * @param {*} data level
 * @returns 
 */
export const getMyEquity = (data) => {
	return request({
		url: '/user/myEquity',
		data: data,
		method: 'GET'
	})
}