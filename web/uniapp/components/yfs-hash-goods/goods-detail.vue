<template>
	<u-popup :show="detailShow" mode="center" :safeAreaInsetBottom="false" @close="close">
		<view class="detail-container main-center-flex">
			<image :src="detail_close" class="close" @click="close"></image>
			<view class="inner main-center-flex">
				<view class="bg" :style="{'background-image': 'url('+detail_bg+')'}">
					<image :src="login_icon" class="icon"></image>
					<scroll-view class="scroll-view" enableFlex scroll-y>
						<view class="content">
							<view class="goods_img">
								<image :src="goodsDetail.goods_image"></image>
							</view>
						</view>
						<view class="text">{{ goodsDetail.goods_name }}</view>
						<!-- <view class="text">{{ goodsDetail.seriesNmae }}</view> -->
						<view class="text">{{ '赏级：' + goodsDetail.tag_name }}</view>
						<view class="text2">{{ '概率：' + goodsDetail.probability + '%' }}</view>
					</scroll-view>
				</view>
			</view>
		</view>
	</u-popup>
</template>

<script>
	export default {
		props: {
			detailShow: {
				type: Boolean,
				default: false
			},
			goodsDetail: {
				type: Object,
				default: () => {
					return {};
				}
			}
		},
		data() {
			return {
				login_icon:  this.$imgList.homeImgs.login_icon,
				detail_close: this.$imgList.boxImgs.detail_close,
				detail_bg: this.$imgList.boxImgs.detail_bg,
			}
		},
		methods: {
			close() {
				this.$emit('close');
			}
		}
	}
</script>

<style lang="scss" scoped>
	.detail-container{
		width: 100vw;
		height: 100vh;
		padding-bottom: env(safe-area-inset-bottom);
		background-color: #1D1F36;
		.close{
			position: absolute;
			top: 178rpx;
			right: 58rpx;
			width: 52rpx;
			height: 52rpx;
		}
		.inner{
			position: relative;
			width: 552rpx;
			height: 766rpx;
			background: linear-gradient(180deg, #82FF80 0%, #B26CFF 100%);
			border-radius: 8rpx;
			padding: 20rpx;
		}
		.bg{
			width: 508rpx;
			height: 724rpx;
			display: flex;
			flex-direction: column;
			background-size: 100% 100%;
		}
		.icon{
			width: 168rpx;
			height: 100rpx;
			margin-top: 20rpx;
			margin-left: 20rpx;
		}
		.scroll-view{
			width: 100%;
			height: calc(100% - 100rpx);
			padding: 10rpx 10rpx;
		}
		.content{
			width: 100%;
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		.goods_img{
			width: 418rpx;
			height: 388rpx;
			margin-top: 20rpx;
			padding: 4rpx;
			border: 8rpx solid;
			// 8表示内向偏移量，写成和边框设置的宽度一样即可
			border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 8;
			clip-path: inset(0 round 14rpx);
			image{
				width: 100%;
				height: 100%;
			}
		}
		.text{
			padding: 10rpx 46rpx 0 46rpx;
			font-size: 28rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 600;
			color: #181818;
		}
		.text2{
			padding: 10rpx 46rpx 0 46rpx;
			font-size: 30rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 600;
			color: #69FF86;
		}
	}
</style>