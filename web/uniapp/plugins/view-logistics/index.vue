<template>
	<view class="container-view">
		<view class="blind-box" v-if="isShowList">
			<view class="total">
				共计{{ goodsTotal }}个赏品
			</view>
			<view class="content">
				<view class="list-item" v-for="(item,index) in goodsList" :key="index">
					<view class="goods-pic">
						<image :src="item.goods_image" mode="aspectFill"></image>
					</view>
					<view class="goods-info">
						<view class="goods-name">
							{{ item.tag_name }}赏 {{ item.goods_name }}
						</view>
						<view class="goods-kinds">
							规格：{{ item.num }}个
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="logistics-box">
			<template v-if="show">
				<view class="logistics">
					<logistics :wlInfo="wlInfo"></logistics>
				</view>
			</template>
			<template v-else>
				<empty :contextTag="'暂无相关物流信息'"></empty>
			</template>
		</view>
	</view>
</template>

<script>
	import logistics from '@/components/xinyu-logistics/xinyu-logistics.vue'
	import {
		getShopExpress,
		getExpress
	} from '@/api/warehouse.js';
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
				goodsList: [],
				isShowList: false,
			}
		},
		onLoad(parms) {
			uni.showLoading({
				title: '加载中',
				mask: true
			})
			if(parms.type == 'box'){
				this.isShowList = true;
				this.getList(parms.id);
			}else{
				this.getGoodsList(parms.id);
			}
		},
		computed: {
			goodsTotal(){
				return this.goodsList.reduce((pre,item) => {
					pre += (Number(item.num)? Number(item.num) : 0);
					return pre
				}, 0)
			}
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
						// 数据
						this.goodsList = Object.values(res.data.sendDetail);
						
					} else {
						uni.$u.toast(res.msg)
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			// 盲盒物流
			async getList(val) {
				try {
					let res = await getExpress({
						deliver_no: val
					});
					uni.hideLoading();
					if (res.code == 0) {
						if (res.data == null || res.data == undefined) {
							this.show = true
							uni.$u.toast('暂无物流信息')
							return
						}
						let express = res.data.express
						if (!express) {
							this.show = true
							uni.$u.toast('暂无物流信息')
							return
						}
						if (express.status == 0) {
							this.show = true
							if (express.result.list) {
								for (var i = 0; i < express.result.list.length; i++) {
									express.result.list[i].context = express.result.list[i].status
									express.result.list[i].timeArr = express.result.list[i].time.split(' ')
								}
							}
							this.wlInfo.post_no = express.result.number
							this.wlInfo.post_name = express.result.expName
							this.wlInfo.logo = express.result.logo
							this.wlInfo.exp_phone = express.result.expPhone
							this.wlInfo.delivery_status = express.result.deliverystatus
							this.wlInfo.list = express.result.list
						}
						// 数据
						this.goodsList = Object.values(res.data.sendDetail);
						
					} else {
						uni.$u.toast(res.msg)
					}
				} catch (e) {
					uni.hideLoading();
					//TODO handle the exception
				}
			}
		}
	}
</script>

<style scoped lang="scss">
	@import "./index.scss";
</style>