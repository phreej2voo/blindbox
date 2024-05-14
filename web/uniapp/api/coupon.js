import { request } from '@/utils/request.js';

/**
 * 获取优惠券列表
 * @param {*} data page=1&limit=20&type=1可用 2-不可用
 * @returns 
 */
export const getCouponList = (data) => {
	return request({
		url: '/marketing/coupon/list',
		data: data,
		method: 'GET'
	})
}

/**
 * 获取我的道具卡
 * @param {*} data 
 * @returns 
 */
export const getMyProp = (data) => {
	return request({
		url: '/marketing/prop/valid',
		data: data,
		method: 'GET'
	})
}

/**
 * 使用道具卡
 * @param {*} data 
 * @returns 
 */
export const useProp = (data) => {
	return request({
		url: '/marketing/prop/useProp',
		data: data,
		method: 'POST'
	})
}

/**
 * 获取重抽结果
 * @param {*} data 
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
 * 获取订单可用优惠券 blindbox_id=3&amount=897
 * @param {*} data 
 * @returns 
 */
export const getValidCoupon = (data) => {
	return request({
		url: '/marketing/coupon/valid',
		data: data,
		method: 'GET'
	})
}

/**
 * 检测免费机会
 * @param {*} data blindbox_id
 * @returns 
 */
export const checkFreeChance = (data) => {
	return request({
		url: '/marketing/chance/check',
		data: data,
		method: 'GET'
	})
}

/**
 * 使用免单
 * @param {*} data id_code
 * @returns 
 */
export const useFreeChance = (data) => {
	return request({
		url: '/marketing/chance/useChance',
		data: data,
		method: 'GET'
	})
}

/**
 * 我的免单记录
 * @param {*} data limit=10&page=1&status=1 status 1-未使用  2-已使用
 * @returns 
 */
export const getMyFreeRecords = (data) => {
	return request({
		url: '/marketing/chance/list',
		data: data,
		method: 'GET'
	})
}