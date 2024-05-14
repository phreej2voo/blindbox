<template>
	<view class="recording-list-item">
		<view class="time">
			中奖时间: {{ item.create_time }}
		</view>
		<view class="box">
			<view class="goods-img">
				<image :src="item.goods_image" mode="aspectFill"></image>
			</view>
			<view class="goods-info">
				<view class="title-name">
					{{ item.goods_name }}
				</view>
				<view class="goods-num">
					房间名: {{ item.roll_name }}
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name:"recording-list",
		data() {
			return {
			};
		},
		props: {
			item: [Object]
		}
	}
</script>

<style scoped lang="scss">
.recording-list-item{
	width: 100%;
	background: rgba(217,217,217,0.05);
	border-radius: 8rpx;
	padding: 40rpx 20rpx;
	margin-bottom: 30rpx;
	color: #FFF;
	font-size: 28rpx;
	.time{
		
	}
	.box{
		width: 100%;
		height: 216rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
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
			height: 158rpx;
			font-size: 28rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #FFFFFF;
			display: flex;
			flex-wrap: wrap;
			align-content: space-around;
			.title-name{
				width: 100%;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
			}
			.goods-num{
				
			}
		}
	}
}
</style>