import {
	request
} from '@/utils/request.js';
//ä���б�
export const get_bag_box_list = (data) => {
	return request({
		url: '/order/getBagBoxList',
		data: data,
		method: 'GET'
	})
}
//��Ʒ�б�
export const get_bag_goods_list = (data) => {
	return request({
		url: '/order/getBagGoodsList',
		data: data,
		method: 'GET'
	})
}
//ä�жһ�
export const bagBoxExchange = (data) => {
	return request({
		url: '/order/bagBoxExchange',
		data: data,
		method: 'POST'
	})
}

//ä����������ʷ�
export const bagBoxTrail = (data) => {
	return request({
		url: '/order/bagBoxTrail',
		data: data,
		method: 'POST'
	})
}
//ä�������������
export const bagBoxDeliver = (data) => {
	return request({
		url: '/order/bagBoxDeliver',
		data: data,
		method: 'POST'
	})
}
//ä�н�Ʒȷ���ջ�
export const confirmBoxDeliver = (data) => {
	return request({
		url: '/order/confirmBoxDeliver',
		data: data,
		method: 'POST'
	})
}
//�����̳���Ʒȷ���ջ�
export const bagGoodsConfirm = (data) => {
	return request({
		url: '/order/bagGoodsConfirm',
		data: data,
		method: 'POST'
	})
}
//ä���������
export const getExpress = (data) => {
	return request({
		url: '/order/express',
		data: data,
		method: 'GET'
	})
}
//��Ʒ�������
export const getShopExpress = (data) => {
	return request({
		url: '/order/shop/express',
		data: data,
		method: 'GET'
	})
}
// δ����ä������
export const getBagBoxDetail = (data) => {
	return request({
		url: '/order/getBagBoxDetail',
		data: data,
		method: 'GET'
	})
}

