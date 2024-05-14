import {request} from '@/utils/request.js';

// 获取转卖列表
export const get_home_list = (data) => {
	return request({
		url: '/home/index',
		data: data,
		method: 'GET'
	})
}

// 获取首页广告
export const getHomeAdvertisement = () => {
	return request({
		url: '/home/ad',
		method: 'GET'
	})
}

// 今日活动信息
export const getWeeklyInfo = () => {
	return request({
		url: '/marketing/week/activity',
		method: 'GET'
	})
}

// 中奖弹幕
export const getAwardList = () => {
	return request({
		url: '/home/award',
		method: 'GET'
	})
}