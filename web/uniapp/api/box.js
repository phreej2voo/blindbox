import { request } from '@/utils/request.js';

// 换箱
export const blindboxes = (data) => {
	return request({
		url: '/goods/blindbox/boxes',
		data: data,
		method: 'POST'
	})
}

// 中奖记录 blindbox_id=1&box_id=1&page=1&limit=5
export const rewardRecords = (data) => {
	return request({
		url: '/goods/blindbox/reward',
		data: data,
		method: 'GET'
	})
}
