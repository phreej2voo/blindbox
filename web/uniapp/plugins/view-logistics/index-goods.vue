<template>
	<view class="container-view logistics-goods">
		<template v-if="show">
			<view class="logistics" style="height: 100vh;">
				<logistics :wlInfo="wlInfo"></logistics>
			</view>
		</template>
		<template v-else>
			<empty :contextTag="'暂无相关物流信息'"></empty>
		</template>
	</view>
</template>

<script>
	import logistics from '@/components/xinyu-logistics/xinyu-logistics.vue'
	import {
		getShopExpress,
		getExpress
	} from '@/api/warehouse.js'
	export default {
		components: {
			logistics,
		},
		data() {
			return {
				show: false,
				wlInfo: {
					delivery_status: 1, //快递状态 1已签收 2配送中
					post_name: '', //快递名称
					logo: '', //快递logo
					exp_phone: '', //快递电话
					post_no: '', //快递单号
					addr: '', //收货地址
					//物流信息
					list: []
				},
			}
		},
		onLoad(parms) {
			uni.showLoading({
				title: '加载中',
				mask: true
			})
			this.getGoodsList(parms.id);
		},
		methods: {
			// 商品物流
			async getGoodsList(val){
				try{
					let res = await getShopExpress({
						order_id: val
					});
					uni.hideLoading();
					if (res.code == 0) {
						if (res.data == null || res.data == undefined) {
							this.show = true
							uni.$u.toast('暂无物流信息')
							return
						}
						let info = res.data;
						if (!info.result) {
							this.show = true
							uni.$u.toast('暂无物流信息')
							return
						}
						if (info.status == 0) {
							this.show = true
							if (info.result.list) {
								for (var i = 0; i < info.result.list.length; i++) {
									info.result.list[i].context = info.result.list[i].status
									info.result.list[i].timeArr = info.result.list[i].time.split(' ')
								}
							}
							this.wlInfo.post_no = info.result.number
							this.wlInfo.post_name = info.result.expName
							this.wlInfo.logo = info.result.logo
							this.wlInfo.exp_phone = info.result.expPhone
							this.wlInfo.delivery_status = info.result.deliverystatus
							this.wlInfo.list = info.result.list
						}
					} else {
						uni.$u.toast(res.msg)
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
		}
	}
</script>

<style scoped lang="scss">
	@import "./index.scss";
</style>