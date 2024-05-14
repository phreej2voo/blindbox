<template>
	<view class="title-bck" :style="{'padding-top': paddingTop + 'rpx'}">
		<view class="title-box">
			<view class="pointer-icon" @click="goBack">
				<image src="../../static/image/icon/back.png" mode="widthFix"></image>
			</view>
			<view class="title-name">
				{{ titleName }}
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name:"title-back",
		data() {
			return {
			};
		},
		props: {
			titleName: {
				type: String,
				default: ''
			},
			paddingTop: {
				type: Number,
				default: 14
			},
		},
		methods: {
			goBack(){
				uni.navigateBack()
			}
		}
	}
</script>

<style scoped lang="scss">
.title-bck{
	width: 100%;
	position: fixed;
	z-index: 19;
	left: 0;
	.title-box{
		font-size: 32rpx;
		font-family: PingFang SC-Semibold, PingFang SC;
		color: #FFFFFF;
		position: relative;
		display: flex;
		justify-content: center;
		align-items: flex-end;
		padding-bottom: 40rpx;
		.pointer-icon{
			width: 60rpx;
			height: 42rpx;
			position: absolute;
			z-index: 9;
			left: 30rpx;
			bottom: 40rpx;
			display: flex;
			align-items: center;
			image{
				width: 32rpx;
			}
		}
		.title-name{
			height: 42rpx;
		}
	}
}
</style>