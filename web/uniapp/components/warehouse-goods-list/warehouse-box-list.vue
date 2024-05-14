<template>
	<view class="container-warehouse">
		<view class="top-id">
			<text>ID：{{ defaultSecondTab == 2? item.deliver_no : item.exchange_no }}</text>
			<text class="status" v-if="defaultSecondTab != 3">{{ status }}</text>
		</view>
		<view class="goods-info">
			<view class="goods-pic">
				<image :src="item.pic" mode="aspectFill"></image>
			</view>
			<view class="goods-name">
				<view class="goods-tip">
					{{ item.title }}
				</view>
				<view class="goods-time">
					{{ defaultSecondTab == 2? '提货' : '兑换'}}数量： {{ defaultSecondTab == 2? item.num : item.exchange_num }}
				</view>
				<view class="goods-time">
					{{ defaultSecondTab == 2? '提货' : '兑换'}}时间： {{ item.create_time }}
				</view>
			</view>
		</view>
		<view class="all-btns" v-if="defaultSecondTab != 3">
			<view class="logistics btn" @click="goLogistics(item)">
				查看物流
			</view>
			<view class="receipt btn" v-if="item.status == 2" @click="comfirmReceipt(item)">
				确认收货
			</view>
		</view>
		
		<modal ref="modal"></modal>
	</view>
</template>

<script>
	import {
		confirmBoxDeliver
	} from '@/api/warehouse.js';
	
	export default {
		name:"warehouse-goods-list",
		data() {
			return {
				
			};
		},
		props: {
			item: [Object,String],
			defaultSecondTab: [String, Number]
		},
		computed: {
			status(){
				let itemStatus;
				if(this.item.status == 1){
					itemStatus = '待发货'
				}else if(this.item.status == 2){
					itemStatus = '待收货'
				}else if(this.item.status == 3){
					itemStatus = '已签收'
				}
				return itemStatus
			}
		},
		methods: {
			// 查看物流
			goLogistics(item) {
				uni.navigateTo({
					url: '/plugins/view-logistics/index?type=box&id=' + item.deliver_no
				})
			},
			// 确认收货
			comfirmReceipt(e){
				this.$refs.modal.showModal({
					title: '是否确认收货？',
					confirm: async () => {
						try{
							let res = await confirmBoxDeliver({
								deliver_no: e.deliver_no
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