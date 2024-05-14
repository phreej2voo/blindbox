<template>
	<view class="warehouse-list-item">
		<view class="goods-img">
			<image :src="item.pic" mode="aspectFill"></image>
		</view>
		<view class="goods-info">
			<view class="title-name">
				{{ item.name }}
			</view>
			<view class="goods-num">
				共计{{ item.t_total }}个赏品
			</view>
			<view class="update-time">
				最近更新：{{ item.create_time }}
			</view>
		</view>
		<view class="right-pointer">
			<image :src="pointer" mode="aspectFill"></image>
		</view>
	</view>
</template>

<script>
	import baseUrl from '../../utils/siteInfo';
	export default {
		name:"warehouse-list",
		data() {
			return {
				pointer: baseUrl.imgroot + 'static/images/wareHouse/pointer.png',
			};
		},
		props: {
			item: [Object]
		}
	}
</script>

<style scoped lang="scss">
.warehouse-list-item{
	width: 100%;
	height: 216rpx;
	background: rgba(217,217,217,0.05);
	border-radius: 8rpx;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0 20rpx;
	margin-bottom: 20rpx;
	.goods-img{
		width: 158rpx;
		height: 158rpx;
		border-radius: 8rpx;
		background-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1));
		display: flex;
		justify-content: center;
		align-items: center;
		image{
			width: 150rpx;
			height: 150rpx;
			border-radius: 8rpx;
			background-color:#FFF;
		}
	}
	.goods-info{
		width: 400rpx;
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
		.title-name{
			width: 100%;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}
		.goods-num{
			height: 60rpx;
			line-height: 60rpx;
		}
		.update-time{
			font-size: 24rpx;
		}
	}
	.right-pointer{
		width: 50rpx;
		padding-right: 20rpx;
		image{
			width: 20rpx;
			height: 28rpx;
		}
	}
}
</style>