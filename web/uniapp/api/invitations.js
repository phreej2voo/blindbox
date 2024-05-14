import { request } from '@/utils/request.js';

/**
 * 查看用户等级
 * @param {*} data level
 * @returns 
 */
export const getInvitationsInfo = (data) => {
	return request({
		url: '/marketing/invite/info',
		data: data,
		method: 'GET'
	})
}

/**
 * 我的奖励
 * @param {*} data 
 * @returns 
 */
export const getMyReward = (data) => {
	return request({
		url: '/marketing/invite/reward?page=1&limit=10',
		data: data,
		method: 'GET'
	})
}

/**
 * 我的朋友
 * @param {*} data 
 * @returns 
 */
export const getMyFriends = (data) => {
	return request({
		url: '/marketing/invite/friend?page=1&limit=10',
		data: data,
		method: 'GET'
	})
}

/**
 * 我的二维码
 * @param {*} data 
 * @returns 
 */
export const getQrcode = (data) => {
	return request({
		url: '/marketing/invite/qrcode',
		data: data,
		method: 'POST'
	})
}