<template>
	<view class="container column-start-flex">
		<u--image :src="imgSrc" width="318rpx" height="400rpx"></u--image>
		<view class="text">
			扫码添加客服微信
		</view>
	</view>
</template>

<script>
	import { getKeFuCode } from "@/api/my.js"
	export default {
		data() {
			return {
				imgSrc: 'https://cdn.uviewui.com/uview/album/1.jpg'
			}
		},
		onLoad() {
			getKeFuCode().then(res => {
				if (res.code == 0) {
					this.imgSrc = res.data
				}
			})
		},

	}

</script>

<style scoped>
	.container {
		width: 750rpx;
		height: 100vh;
		padding-top: 140rpx;
		background: #1D1F36;
	}

	.text {
		font-size: 32rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFF;
		margin-top: 18rpx;
	}

</style>

