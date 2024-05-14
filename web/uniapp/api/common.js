import {
	request
} from '@/utils/request.js';

// 支付方式开关
export const payConfig = (data) => {
	return request({
		url: '/common/payConfig',
		data: data,
		method: 'GET'
	})
}