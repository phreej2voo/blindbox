import { request } from '@/utils/request.js';

/**
 * 获取每日任务
 * @param {*} data 
 * @returns 
 * status 1:未解锁 2:去领取 3:去使用 4:已使用 5:已过期
 */
export const dailyTask = (data) => {
	return request({
		url: '/marketing/dailyTask',
		data: data,
		method: 'GET'
	})
}

/**
 * 奖品详情
 * @param {*} data 
 * @returns 
 *  amount: "0.00"
    discount: "0.90"
    name: "9折券"
    type: 2 // 1-满减(amount) 2-折扣(discount)
 */
export const rewardDetail = (data) => {
	return request({
		url: '/marketing/reward/detail',
		data: data,
		method: 'POST'
	})
}

/**
 * 领取奖品
 * @param {*} data groupId-任务分组的索引index  rewardId-分组下奖品的index
 * @returns 
 */
export const receiveReward = (data) => {
	return request({
		url: '/marketing/reward/receive',
		data: data,
		method: 'POST'
	})
}