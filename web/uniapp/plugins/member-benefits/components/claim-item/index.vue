<template>
	<view class="claim-item">
		<view class="benefits-info">
			<view class="name">
				{{ item.name }}
			</view>
			<view class="time">
				类型：{{ item.type == 'coupon'? '优惠券' : item.type == 'balance'? '余额' : '无' }}
			</view>
		</view>
		<view class="btn-box" @click="receive">
			<image :src="item.status == 1? claimBtn : useBtn" mode="aspectFill" class="btn-img"></image>
			<text>{{ item.status == 1? '领取' : '去使用' }}</text>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import {
		vipCardReceive,
	} from '@/api/memberBenefits.js';
	export default {
		data(){
			return {
				claimBtn: baseUrl.imgroot + 'static/images/memberBenefits/claim-btn.png',
				useBtn: baseUrl.imgroot + 'static/images/memberBenefits/use-btn.png',
			}
		},
		props: {
			item: [Object],
			index: [String, Number]
		},
		methods: {
			async receive(){
				try{
					console.log('lingqu')
					uni.showLoading({
						title: '领取中',
						mask: true,
					})
					let res = await vipCardReceive({
						index: this.index
					});
					uni.hideLoading();
					if(res.code == 0){
						this.$emit('getVipCardEquity')
					}
					setTimeout(() => {
						uni.showToast({
							title: res.msg,
							icon: 'none',
							mask: true
						})
					}, 50)
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			}
		}
	}
</script>

<style scoped lang="scss">
.claim-item{
	width: 650rpx;
	height: 242rpx;
	background: #1D1F36;
	border: 2rpx solid #84F985;
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-size: 28rpx;
	font-family: PingFang SC-Regular, PingFang SC;
	font-weight: 400;
	color: #FFFFFF;
	padding: 0 36rpx 0 60rpx;
	margin-top: 30rpx;
	.benefits-info{
		.time{margin-top: 22rpx;
			font-size: 20rpx;
		}
	}
	.btn-box{
		position: relative;
		width: 197rpx;
		height: 88rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		image{
			width: 100%;
			height: 100%;
		}
		text{
			position: absolute;
		}
	}
}
</style>