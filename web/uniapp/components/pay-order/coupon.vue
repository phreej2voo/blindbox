<template>
	<view class="can-be-used" :class="defaultTab == 1 ? '' : 'can-not-be-used'">
		<view class="image">
			<image :src="defaultTab == 1 ? available : notAvailable" mode="aspectFill"></image>
		</view>
		<view class="box">
			<view class="left" :class="defaultTab == 1 ? '' : 'unavailable'">
				<view class="discount">
					{{ discount }}
				</view>
				<view class="expired" v-if="defaultTab != 1">
					已过期
				</view>
			</view>
			<view class="center">
				<view class="coupon-info">
					{{ item.coupon_name || '无' }}
				</view>
				<view class="coupon-expiration-date">
					{{ expirationDate }}
				</view>
			</view>
			<view class="rigth" v-if="defaultTab == 1" @click="changeStatus">
				<image :class="item.checked ? '' : 'selected-btn'" :src="item.checked ? selectedImg : ''" mode="aspectFill">
				</image>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	export default {
		data(){
			return {
				notAvailable: baseUrl.imgroot + 'static/images/userCenter/coupon/not-available-coupon.png',
				available: baseUrl.imgroot + 'static/images/userCenter/coupon/use-coupon.png',
				selectedImg: baseUrl.imgroot + 'static/images/userCenter/coupon/selected-btn.png',
			}
		},
		props: {
			defaultTab: {
				type: [String, Number, Boolean]
			},
			item: [Object],
			index: [Number, String],
		},
		computed: {
			discount(){
				let name;
				if(this.item.coupon_info){
					if(this.item.coupon_info.type == 1){
						name = '满减券'
					}else if(this.item.coupon_info.type == 2){
						name = '折扣券'
					}
				}
				return name
			},
			expirationDate(){
				console.log(this.item.end_time)
				let endTime = this.item.end_time.substring(0,10);
				let startTime = this.item.start_time.substring(0,10);
				return `${startTime} 至 ${endTime}`
			}
		},
		methods: {
			changeStatus(){
				this.$emit('selectCoupon', this.index);
			}
		}
	}
</script>

<style scoped lang="scss">
.can-be-used{
	width: 656rpx;
	height: 220rpx;
	display: flex;
	justify-content: space-between;
	align-items: center;
	position: relative;
	margin-top: 30rpx;
	color: #FFF;
	.image{
		width: 100%;
		height: 100%;
		image{
			width: 100%;
			height: 100%;
		}
	}
	.box{
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 9;
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		padding: 0 30rpx;
		.left{
			width: 190rpx;
			padding: 50rpx 20rpx 50rpx 0;
			border-right: 2rpx dashed;
			border-color:  #79FE93;
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			.discount{
				width: 100%;
				font-size: 48rpx;
				font-weight: 600;
				color: #8BFFA2;
				text-align: center;
				font-family: PingFang SC-Semibold, PingFang SC;
			}
		}
		.unavailable{
			padding: 20rpx 20rpx 20rpx 0;
			border-color: #B9B9B9;
			.discount{
				color: #B9B9B9;
				margin-bottom: 15rpx;
			}
			.expired{
				width: 142rpx;
				height: 48rpx;
				background: #4A4A4A;
				border-radius: 42rpx;
				text-align: center;
				line-height: 48rpx;
				font-size: 24rpx;
				color: #FFFFFF;
			}
		}
		.center{
			// width: 320rpx;
			flex-grow: 1;
			padding-left: 20rpx;
			.coupon-info{
				height: 70rpx;
			}
			.coupon-expiration-date{
				font-size: 20rpx;
			}
		}
		.rigth{
			image{
				width: 36rpx;
				height: 36rpx;
				border: 2rpx solid #82FF80;
				border-radius: 36rpx;
			}
			.selected-btn{
				border: 2rpx solid #FFF;
			}
		}
	}
}
.can-not-be-used{
	color: #B9B9B9;
}
</style>