import {
	request
} from '@/utils/request.js';
// 合成活动列表
export const get_conflate_list = (data) => {
	return request({
		url: '/marketing/conflate/list',
		data: data,
		method: 'GET'
	})
}
// 合成活动详情
export const get_conflate_detail = (data) => {
	return request({
		url: '/marketing/conflate/detail',
		data: data,
		method: 'GET'
	})
}
// 获取进度详情
export const get_conflate_progressDetail = (data) => {
	return request({
		url: '/marketing/conflate/progressDetail',
		data: data,
		method: 'GET'
	})
}
// 获取我的材料
export const get_conflate_myGoods = (data) => {
	return request({
		url: '/marketing/conflate/myGoods',
		data: data,
		method: 'GET'
	})
}
// 放入碎片
export const get_conflate_putIn = (data) => {
	return request({
		url: '/marketing/conflate/putIn',
		data: data,
		method: 'POST'
	})
}
// 移除碎片
export const remove_conflate = (data) => {
	return request({
		url: '/marketing/conflate/remove',
		data: data,
		method: 'POST'
	})
}
// 去合成
export const do_conflate = (data) => {
	return request({
		url: '/marketing/conflate/do',
		data: data,
		method: 'POST'
	})
}
// 合成记录
export const get_conflate_log = (data) => {
	return request({
		url: '/marketing/conflate/log',
		data: data,
		method: 'GET'
	})
}