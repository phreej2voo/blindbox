<template>
	<view class="container">
		<tabs-list :tabList="tabList" :defaultTab="currentTab"
		@changeTab="changeTab"></tabs-list>
		<view class="list-container">
			<template v-if="order_list.length">
				<view class="list-item" v-for="(item,index) in order_list" :key="index">
					<view class="list-head main-space-between">
						<view>
							订单编号：{{item.recharge_no}}
						</view>
						<view :style="{'color': currentTab == 2 ? '#999999' : '#EA6E7A' }">
							{{item.status == 1 ? '待支付' : item.status == 2 ? '支付成功' : item.status == 3 ? '支付失败' : '已过期'}}
						</view>
					</view>
					<view class="list-head main-space-between">
						<view>
							充值时间：{{item.create_time}}
						</view>
						<view>
							充值金额：{{item.amount}}元
						</view>
					</view>
					<view class="list-head main-space-between">
						<view>
							充值方式：{{item.pay_way=='1'?'微信':'支付宝'}}
						</view>
					</view>
					<view class="list-opertaion main-end-flex" v-if="item.status == 1">
						<template v-if="item.status == 1">
							<view class="cancel-order list-button-item main-center-flex"
								@click="cancelOrder(item.recharge_no)">
								取消订单
							</view>
							<view class="pay-order list-button-item main-center-flex" @click="repayment(item.recharge_no)">
								重新支付
							</view>
						</template>
					</view>
				</view>
			</template>
			<template v-else>
				<empty></empty>
			</template>
		</view>
	</view>
</template>

<script>
	import {
		mapGetters,
		mapState
	} from 'vuex';
	import { balanceInfo, cancel, repay } from '@/api/banlance.js'
	export default {
		data() {
			return {
				show: false,
				tabList: [{
						id: 0,
						name: '全部',
						value: '0'
					}, {
						id: 1,
						name: '待支付',
						value: '1'
					},
					{
						id: 2,
						name: '已完成',
						value: '2'
					},
				],
				currentTab: 0,
				order_list: [],
				page: 1,
				type: 0,
				addLoading: true,
			}
		},
		onLoad(parms) {
			this.getBlanceInfo();
		},
		onReachBottom() {
			if(this.addLoading){
				this.page++;
				this.getBlanceInfo();
			}
		},
		methods: {
			async getBlanceInfo(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true,
					})
					let res = await balanceInfo({
						page: this.page,
						limit: 10,
						type: this.currentTab
					});
					uni.hideLoading();
					if (res.code == 0) {
						if(!res.data.rows.length){
							this.addLoading = false;
						};
						if(this.page == 1){
							this.order_list = res.data.rows;
						}else{
							this.order_list =[...this.order_list, ...res.data.rows];
						}
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			repayment(id) {
				let platform = ''
				// #ifdef APP-PLUS
				platform = 'app'
				// #endif
				// #ifdef MP-WEIXIN
				platform = 'miniapp'
				// #endif
				repay({
					recharge_no: id,
					pay_way: '1',
					platform: platform
				}).then(res => {
					// #ifdef MP
					uni.requestPayment({
						provider: 'wxpay', //支付类型-固定值
						timeStamp: res.timeStamp, // 时间戳（单位：秒）
						nonceStr: res.nonceStr, // 随机字符串
						package: res.package, // 固定值
						signType: res.signType, //固定值
						paySign: res.paySign, //签名
						success: function(res) {
							uni.showToast({
								title: '支付成功',
								icon: 'none'
							})
						},
						fail(res) {
							uni.showToast({
								title: '支付失败',
								icon: 'none'
							})
						}
					})
					// #endif
				})
			},
			changeTab(e) {
				this.addLoading = true;
				this.page = 1;
				this.order_list = [];
				this.currentTab = e.id;
				this.getBlanceInfo();
			},
			async cancelOrder(val) {
				try{
					let res = await cancel({
						recharge_no: val
					});
					if (res.code == 0) {
						this.getBlanceInfo()
						uni.showToast({
							icon: 'none',
							title: '操作成功'
						})
					}
				}catch(e){
					//TODO handle the exception
				}
			},
		}
	}

</script>

<style scoped lang="scss">
	.container {
		width: 100%;
		min-height: 100vh;
		background: #1D1F36;
		padding: 0 30rpx;
	}
	
	.list-container {
		width: 100%;
		padding-top: 120rpx;
	}

	.list-item {
		width: 100%;
		background: rgba(217,217,217,0.05);
		border-radius: 8rpx;
		color: #FFF;
		margin-bottom: 30rpx;
	}

	.list-head {
		height: 75rpx;
		width: 100%;
		padding: 25rpx;
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
	}

	.list-body {
		height: 235rpx;
		width: 100%;
		padding: 30rpx;
	}

	.list-body-left {
		height: 100%;
		width: 175rpx;
	}

	.list-body-right {
		height: 100%;
		width: calc(100% - 175rpx);
		padding: 10rpx;
	}


	.goods-price {
		height: 50%;
		width: 100%;
	}

	.goods-name {
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}

	.goods-price {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
	}

	.goods-price>view:first-child {
		font-size: 34rpx;
		font-family: Abel;
		font-weight: 400;
	}

	.goods-price>view:first-child text {
		font-size: 20rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
	}

	.goods-price>view:last-child {
		font-size: 20rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
	}

	.list-foot {
		height: 150rpx;
		width: 100%;
		padding: 30rpx;
	}

	.goods-num {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
	}

	.goods-total-price {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		align-items: baseline;
	}

	.goods-total-price>text:last-child {
		font-size: 40rpx;
		font-family: Abel;
		font-weight: 400;
	}

	.list-opertaion {
		width: 100%;
		padding-right: 20rpx;
	}

	.list-button-item {
		margin: 20rpx 0 20rpx 30rpx;
		width: 154rpx;
		height: 60rpx;
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 500;
	}

	.cancel-order,
	.confirm-receive {
		border: 2rpx solid #F8F8F8;
	}

	.pay-order {
		background: #3F3F42;
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 500;
		color: #FFFFFF;
	}

</style>

