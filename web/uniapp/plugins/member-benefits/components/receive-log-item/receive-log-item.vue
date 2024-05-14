<template>
	<view class="receive-log-item">
		<view class="time">
			{{ item.create_time }}
		</view>
		<view class="receive-info">
			<view class="">
				{{ item.username }}
			</view>
			<view class="">
				<text>{{ item.award_type == 'coupon'? '优惠券' : item.award_type == 'balance'? '余额' : '' }}</text>x{{ item.num }}
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		props: {
			item: [Object]
		},
		methods: {
			
		}
	}
</script>

<style scoped lang="scss">
.receive-log-item{
	width: 100%;
	color: #fff;
	font-size: 28rpx;
	height: 180rpx;
	border: 2rpx solid #84F985;
	margin-top: 30rpx;
	padding: 0 20rpx;
	.time{
		padding: 30rpx 0;
		text-align: right;
		font-size: 24rpx;
	}
	.receive-info{
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		view{
			text{
				color: #84F985;
				margin-right: 8rpx;
			}
		}
	}
}
</style>
