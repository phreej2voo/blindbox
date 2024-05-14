<template>
	<view class="container-dialog">
		<view class="title">
			分解哈希币
			<image :src="closeImg" mode="widthFix" @click="close"></image>
		</view>
		<view class="goods-info">
			<view class="">
				共计{{ goodsTotal }}个赏品
			</view>
			<view class="dec">
				可分解{{ decomposeTotal }}哈希币
			</view>
		</view>
		<view class="rules">
			分解说明 <br />1、不喜欢的商品可分解平台哈希币（哈希币是作为平台分解商品的媒介） <br />2、哈希币一旦分解，不可转让，不可提现 <br />3、哈希币的所属解释权归平台所有，有疑问请联系客服 <br />4、哈希币的分解数量根据商品市场成本实时变化
		</view>
		<view class="confirm">
			<view class="" @click="confirmDecompose">
				<image :src="confirmSelectedImg" mode="aspectFill"></image>
				<text>确认分解</text>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	export default {
		name:"decompose-dialog",
		data() {
			return {
				closeImg: baseUrl.imgroot + 'static/images/wareHouse/close.png',
				confirmSelectedImg: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
				heightPx: 0
			};
		},
		methods: {
			close(){
				this.$emit('closeDialog')
			},
			confirmDecompose(){
				this.$emit('comfirmDecompose');
			}
		},
		props: {
			goodsTotal: [Number],
			decomposeTotal: [Number]
		},
	}
</script>

<style scoped lang="scss">
.container-dialog{
	width: 100%;
	height: 967rpx;
	overflow-y: scroll;
	border-radius: 48rpx 48rpx 0 0;
	background: #1D1F36;
	font-size: 34rpx;
	font-family: PingFang SC-Medium, PingFang SC;
	font-weight: 500;
	color: #FFFFFF;
	position: relative;
	padding: 40rpx;
	.title{
		width: 100%;
		position: relative;
		text-align: center;
		image{
			width: 52rpx;
			height: 53rpx;
			position: absolute;
			top: 0;
			right: 0;
		}
	}
	.goods-info{
		font-size: 32rpx;
		margin: 50rpx auto;
		view{
			height: 60rpx;
			line-height: 60rpx;
		}
		.dec{
			font-size: 28rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #75E276;
		}
	}
	.rules{
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		padding-bottom: 220rpx;
	}
	.confirm{
		position: fixed;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 200rpx;
		background: #1D1F36;
		view{
			width: 605rpx;
			height: 106rpx;
			position: relative;
			margin: 0 auto;
			margin-top: 30rpx;
			image{
				width: 100%;
				height: 100%;
			}
			text{
				display: inline-block;
				position: absolute;
				width: 100%;
				height: 100rpx;
				color: #FFF;
				text-align: center;
				line-height: 100rpx;
				font-size: 40rpx;
				font-family: BTH;
				font-weight: 400;
				z-index: 99;
				top: 0;
				left: 0;
			}
		}
	}
}
</style>