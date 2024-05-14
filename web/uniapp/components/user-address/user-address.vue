<template>
	<view class="user-address" @click="changeAddress">
		<view class="icon">
			<image :src="addressIcon" mode="aspectFill"></image>
		</view>
		<view class="user-info">
			<template v-if="addressList.length">
				<view class="">
					<text>{{ addressList[0].receiver }}</text>
					<text>{{ addressList[0].phone }}</text>
				</view>
				<view class="">
					{{ addressName }}
				</view>
			</template>
			<template v-else>
				<view class="contant">请先添加地址</view>
			</template>
		</view>
		<view class="rigth">
			<image :src="pointer" mode="aspectFill"></image>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default {
		name:"user-address",
		data() {
			return {
				pointer: baseUrl.imgroot + 'static/images/wareHouse/pointer.png',
				saveAddressImg: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
				addressIcon: baseUrl.imgroot + 'static/images/userCenter/coupon/address-icon.png',
			};
		},
		props: {
			addressList: [Array, String, Object],
		},
		computed: {
			addressName(){
				let shipmentAddress = '';
				if(this.addressList.length){
					let newName = this.addressList[0];
					shipmentAddress = newName.province_name + newName.city_name + newName.area_name + newName.address
				}
				return shipmentAddress
			}
		},
		methods: {
			// 切换收件地址
			changeAddress(){
				uni.navigateTo({
					url: '/plugins/address/index?fromType=user'
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
.user-address{
	width: 100%;
	padding: 40rpx 34rpx;
	background: rgba(217,217,217,0.05);
	border-radius: 8rpx;
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-size: 28rpx;
	font-family: PingFang SC-Regular, PingFang SC;
	font-weight: 400;
	color: #FFFFFF;
	.icon{
		width: 35rpx;
		height: 47rpx;
		image{
			width: 100%;
			height: 100%;
		}
	}
	.user-info{
		flex-grow: 0.8;
		view{
			height: 50rpx;
			line-height: 50rpx;
			text{
				margin-right: 30rpx;
			}
		}
	}
	.rigth{
		width: 20rpx;
		height: 28rpx;
		image{
			width: 100%;
			height: 100%;
		}
	}
}
</style>