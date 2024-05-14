<template>
	<view class="empty">
		<view class="image">
			<image :src="isShowImg? backImage : ''" mode="aspectFill"></image>
		</view>
		<view class="text">{{ contextTag }}</view>
	</view>
</template>

<script>
	import baseUrl from '../../utils/siteInfo';
	export default {
		name:"empty",
		data() {
			return {
				backImage: baseUrl.imgroot + 'static/images/userCenter/coupon/empty.png',
			};
		},
		props: {
			contextTag: {
				type: [String],
				default: '暂无数据~'
			},
			isShowImg: {
				type: [Boolean],
				default: true,
			}
		}
	}
</script>

<style scoped lang="scss">
.empty{
	width: 100%;
	height: 100%;
	.image{
		width: 370rpx;
		height: 370rpx;
		margin: 0 auto;
		image{
			width: 100%;
			height: 100%;
		}
	}
	.text{
		width: 100%;
		text-align: center;
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #B9B9B9;
	}
}
</style>