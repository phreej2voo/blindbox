
import { request } from '@/utils/request.js';

/**
 * 榜单
 * @param {*} data type 1-今日 2-本周
 * @returns 
 */
export const getRankData = (data) => {
	return request({
		url: '/marketing/rank/index',
		data: data,
		method: 'GET'
	})
}

/**
 * 新人必买订单试算
 * @param {*}
 * @returns 
 */
export const mustBuyTrail = () => {
	return request({
		url: '/marketing/newer/mustBuyTrail',
		method: 'GET'
	})
}

/**
 * 新人必买下单
 * @param {*} data pay_way
 * @returns 
 */
export const createMustOrder = (data) => {
	return request({
		url: '/marketing/newer/createMustOrder',
		data: data,
		method: 'POST'
	})
}

/**
 * 买一送一订单试算
 * @param {*}
 * @returns 
 */
export const presentTrail = () => {
	return request({
		url: '/marketing/newer/presentTrail',
		method: 'GET'
	})
}

/**
 * 买一送一下单
 * @param {*} data pay_way
 * @returns 
 */
export const createPresentOrder = (data) => {
	return request({
		url: '/marketing/newer/createPresentOrder',
		data: data,
		method: 'POST'
	})
}

/**
 * 白嫖订单试算
 * @param {*}
 * @returns 
 */
export const freeBuyTrail = () => {
	return request({
		url: '/marketing/lucky/trail',
		method: 'GET'
	})
}

/**
 * 白嫖创建订单
 * @param {*} data pay_way
 * @returns 
 */
export const createFreePlayOrder = (data) => {
	return request({
		url: '/marketing/lucky/createOrder',
		data: data,
		method: 'POST'
	})
}

/**
 * 每日限定订单试算 id
 * @param {*}
 * @returns 
 */
export const weekTrail = (data) => {
	return request({
		url: '/marketing/week/trail',
		data: data,
		method: 'POST'
	})
}

/**
 * 每日限定创建订单
 * @param {*} data id pay_way
 * @returns 
 */
export const createWeekOrder = (data) => {
	return request({
		url: '/marketing/week/createOrder',
		data: data,
		method: 'POST'
	})
}