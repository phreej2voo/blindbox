import {
	request
} from '@/utils/request.js';
// �ϳɻ�б�
export const get_conflate_list = (data) => {
	return request({
		url: '/marketing/conflate/list',
		data: data,
		method: 'GET'
	})
}
// �ϳɻ����
export const get_conflate_detail = (data) => {
	return request({
		url: '/marketing/conflate/detail',
		data: data,
		method: 'GET'
	})
}
// ��ȡ��������
export const get_conflate_progressDetail = (data) => {
	return request({
		url: '/marketing/conflate/progressDetail',
		data: data,
		method: 'GET'
	})
}
// ��ȡ�ҵĲ���
export const get_conflate_myGoods = (data) => {
	return request({
		url: '/marketing/conflate/myGoods',
		data: data,
		method: 'GET'
	})
}
// ������Ƭ
export const get_conflate_putIn = (data) => {
	return request({
		url: '/marketing/conflate/putIn',
		data: data,
		method: 'POST'
	})
}
// �Ƴ���Ƭ
export const remove_conflate = (data) => {
	return request({
		url: '/marketing/conflate/remove',
		data: data,
		method: 'POST'
	})
}
// ȥ�ϳ�
export const do_conflate = (data) => {
	return request({
		url: '/marketing/conflate/do',
		data: data,
		method: 'POST'
	})
}
// �ϳɼ�¼
export const get_conflate_log = (data) => {
	return request({
		url: '/marketing/conflate/log',
		data: data,
		method: 'GET'
	})
}