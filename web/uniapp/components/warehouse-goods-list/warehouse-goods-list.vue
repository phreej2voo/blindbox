<template>
	<view class="container-warehouse">
		<view class="top-id">
			<text>ID：{{ item.order_no }}</text>
			<text class="status">{{ status }}</text>
		</view>
		<view class="goods-info">
			<view class="goods-pic">
				<image :src="item.goods_image" mode="aspectFill"></image>
			</view>
			<view class="goods-name">
				<view class="goods-tip">
					{{ item.goods_name }}
				</view>
				<view class="goods-time">
					购买数量： {{ item.num? item.num : 1 }}
				</view>
				<view class="goods-time">
					购买时间： {{ item.create_time }}
				</view>
			</view>
		</view>
		<view class="all-btns">
			<view class="logistics btn" @click="goLogistics(item)"
			v-if="item.status == 3 || item.status == 4 || item.status == 5">
				查看物流
			</view>
			<view class="receipt btn" v-if="item.status == 3" @click="comfirmReceipt(item)">
				确认收货
			</view>
		</view>
		
		<modal ref="modal"></modal>
	</view>
</template>

<script>
	import {
		bagGoodsConfirm
	} from '@/api/warehouse.js';
	
	export default {
		name:"warehouse-goods-list",
		data() {
			return {
				
			};
		},
		props: {
			item: [Object,String]
		},
		computed: {
			status(){
				let itemStatus;
				// 1:待支付 2:待发货 3:待收货 4:部分发货 5:已完成 6:已取消 7:已关闭 8:库存不足'
				if(this.item.status == 1){
					itemStatus = '待支付'
				}else if(this.item.status == 2){
					itemStatus = '待发货'
				}else if(this.item.status == 3){
					itemStatus = '待收货'
				}else if(this.item.status == 4){
					itemStatus = '部分发货'
				}else if(this.item.status == 5){
					itemStatus = '已完成'
				}else if(this.item.status == 6){
					itemStatus = '已取消'
				}else if(this.item.status == 7){
					itemStatus = '已关闭'
				}else if(this.item.status == 8){
					itemStatus = '库存不足'
				}
				return itemStatus
			}
		},
		methods: {
			// 查看物流
			goLogistics(item) {
				uni.navigateTo({
					url: '/plugins/view-logistics/index-goods?type=goods&id=' + item.order_id
				})
			},
			// 确认收货
			comfirmReceipt(e){
				this.$refs.modal.showModal({
					title: '是否确认收货？',
					confirm: async () => {
						try{
							let res = await bagGoodsConfirm({
								order_id: e.order_id
							});
							uni.showToast({
								title: res.msg,
								icon: 'none'
							})
							this.$emit('getList', 1)
						}catch(e){
							//TODO handle the exception
						}
					}
				})
			}
		}
	}
</script>

<style scoped lang="scss">
@import "./warehouse-goods-list.scss";
</style>