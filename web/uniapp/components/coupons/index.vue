<template>
	<view>
		<view class="list-item" v-for="(item, index) in couponsList" :key="index">
			<view class="left-area">
				<!-- 优惠券 1-满减 2-折扣-->
				<view v-if="item.type === 'coupon'" class="left-bottom-area">
					<text v-if="item.info.type == 1" class="num_1">¥</text>
					<text class="num_2">{{ item.info.type == 1 ? Math.floor(item.info.amount) : (item.info.discount) * 10 + '折' }}</text>
				</view>
				<!-- 重抽卡 -->
				<view v-else class="left-bottom-area">
					<text class="num_2">{{ '重抽卡' }}</text>
				</view>
			</view>
			<!-- <view class="middle-area"></view> -->
			<view class="right-area">
				<view class="right-left">
					<view  class="coupon-name text-ellipsis">
						{{ item.info.name + (item.num === 1 ? '' :  ' * ' + item.num)}}
					</view>
					<!-- <view class="coupon-range">
						{{ item.start_time.substr(0,10) + ' - ' + item.end_time.substr(0,10) }}
					</view> -->
				</view>
				<view class="right-right main-center-flex">
					<view class="use-btn" @click="toUse(item)">
						去使用
					</view>
				</view>
			</view>
	  </view>
	</view>
</template>

<script>
	export default {
		props:{
			couponsList: {
				type: Array,
				default: () => {
					return [];
				}
			}
		},
		data() {
			return {

			};
		},
		methods: {
			toUse(item) {
				uni.switchTab({
					url: '/pages/index/index'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
.list-item{
	width: 100%;
    height: 100px;
	margin-top: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #ffffff;
	background-size: cover;
	background-repeat: no-repeat center;
	border-radius: 5px;
	// background-image: url(http://dev-hashmart.mstshop.cn/static/images/coupons-back.png);

	.left-area{
		width: 32%;
		height: 100%;
		padding: 13px 0;
		display: flex;
		flex-direction: column;
		// align-items: center;
		// justify-content: center;
		background: radial-gradient(circle at top right, #F0EFFF 10px, transparent 0) top right,
					radial-gradient(circle at bottom right, #F0EFFF 10px, transparent 0) bottom right;
		background-size: 50% 50%;
		background-repeat: no-repeat;
	}
	.left-top-area {
		width: 45px;
		height: 25px;
		text-align: center;
		overflow-wrap: break-word;
		color: white;
		background-color: #EB872E;
		font-size: 12px;
		font-family: PingFangHK-Regular;
		white-space: nowrap;
		line-height: 25px;
		border-top-left-radius: 5px;
		border-bottom-right-radius: 5px;
	}
	.left-bottom-area{
		height: 100%;
		margin: 2px 0;
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		border-right: 1px dashed #9D4E2B;
	}
	.num_1 {
		overflow-wrap: break-word;
		color: #9A4824;
		font-size: 18px;
		font-family: PingFangHK-Semibold;
		white-space: nowrap;
	}
	.num_2 {
		overflow-wrap: break-word;
		color: #9A4824;
		font-size: 20px;
		font-weight: 600;
		font-family: PingFangHK-Semibold;
		white-space: nowrap;
	}
	.middle-area {
		width: 2px;
		height: 95%;
	}
	.right-area{
		width: 68%;
		height: 100%;
		display: flex;
		justify-content: space-around;
		align-items: center;
		flex-direction: row;
		background: radial-gradient(circle at top left, #F0EFFF 10px, transparent 0) top left,
					radial-gradient(circle at bottom left, #F0EFFF 10px, transparent 0) bottom left;
	}
	.right-left{
		width: calc(100% - 100px);
		height: 100%;
		padding: 0 5rpx 0 25rpx;
		display: flex;
		align-items: center;
	}
	.right-right {
		width: 100px;
	}
	.use-btn {
		width: 68px;
		height: 27px;
		background: #FFFFFF;
		border: 1px solid #D5B3A4;
		// backdrop-filter: blur(0.66467px);
		border-radius: 5px;
		font-size: 14px;
		font-family: PingFangHK-Regular, PingFangHK;
		font-weight: 400;
		color: #994622;
		line-height: 26px;
		text-align: center;
	}
	.coupon-name {
		font-size: 16px;
		font-family: PingFangHK-Medium, PingFangHK;
		font-weight: 600;
		color: #000000;
		padding-left: 10px;
	}
	.right-top-area {
		
	}
	.right-bottom-area {
		overflow-wrap: break-word;
		color: rgba(179, 120, 120, 1);
		font-size: 14px;
		font-family: PingFangHK-Regular;
		font-weight: NaN;
		text-align: left;
		white-space: nowrap;
		line-height: 20px;
		margin: 4px 29px 0 8px;
	}
	.text_1 {
		overflow-wrap: break-word;
		color: rgba(70, 40, 40, 1);
		font-size: 14px;
		font-family: PingFangHK-Medium;
		font-weight: 500;
		white-space: nowrap;
		line-height: 20px;
	}

	.text_2 {
		overflow-wrap: break-word;
		color: rgba(179, 120, 120, 1);
		font-size: 14px;
		font-family: PingFangHK-Regular;
		font-weight: NaN;
		white-space: nowrap;
		line-height: 20px;
		margin: 4px 29px 0 8px;
	}
}
</style>