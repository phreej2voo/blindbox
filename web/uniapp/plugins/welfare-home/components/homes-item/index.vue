<template>
	<view class="homes-item">
		<view class="homes-info">
			<text class="label">房间名:</text>
			<text>{{ item.title }}</text>
			<text class="status" :style="{'color': item.status  == 1? '#75E276' : item.status == 2? '#A6A6A6' : '#FFF'}">
				{{ item.status  == 1? '进行中' : item.status  == 2? '已结束' : '未通过'}}
			</text>
		</view>
		<view class="homes-info">
			<text class="label">参与时间:</text>
			<text>{{ item.create_time }}</text>
		</view>
		<!-- 进入房间 -->
		<view class="jump-bth" v-if="item.status == 1" @click="goDetail(item)">
			<image :src="jumpBtn" mode=""></image>
			<text>进入房间</text>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default {
		data(){
			return {
				status: 1,
				jumpBtn: baseUrl.imgroot + 'static/images/animation/jump-btn.png',
			}
		},
		props: {
			item: [String, Object]
		},
		methods: {
			// 进入房间
			goDetail(item){
				let params = {
					id: item.roll_id
				};
				uni.navigateTo({
					url: '/plugins/welfare-home/home-detail/home-detail?params=' + encodeURIComponent(JSON.stringify(params))
				})
			},
		}
	}
</script>

<style scoped lang="scss">
.homes-item{
	width: 100%;
	// height: 296rpx;
	height: 246rpx;
	background: #1D1F36;
	border-radius: 8rpx;
	border: 2rpx solid #5ADB6F;
	padding: 36rpx 36rpx;
	font-size: 28rpx;
	font-family: PingFang SC-Regular, PingFang SC;
	font-weight: 400;
	color: #FFFFFF;
	position: relative;
	margin-top: 30rpx;
	display: flex;
	flex-wrap: wrap;
	align-content: space-between;
	.homes-info{
		width: 100%;
		position: relative;
		padding: 10rpx 0;
		.label{
			display: inline-block;
			width: 150rpx;
		}
		.status{
			position: absolute;
			right: 0;
			top: 0	;
		}
	}
	.jump-bth{
		position: absolute;
		right: 10rpx;
		bottom: 86rpx;
		width: 187rpx;
		height: 71rpx;
		image, text{
			width: 100%;
			height: 100%;
		}
		image{
			position: absolute;
		}
		text{
			display: inline-block;
			text-align: center;
			line-height: 71rpx;
			position: relative;
			z-index: 5;
		}
	}
}
</style>