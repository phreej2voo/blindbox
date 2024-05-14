<template>
	<view class="invite-list">
		<view class="avater" v-if="item.avatar">
			<image :src="item.avatar" mode="aspectFill"></image>
		</view>
		<view class="user-info">
			<view class="title">
				{{ item.nickname? item.nickname : '好友***注册成功' }}
			</view>
			<view class="time">
				{{ item.create_time? item.create_time : '' }}
			</view>
		</view>
		<view class="receive" :style="{'width': currentIndex == 2? '262rpx': '202rpx'}">
			<template v-if="currentIndex == 2">
				<block v-for="(element, i) in item.reward_content" :key="i">
					<text :key="i" style="font-size: 24rpx; color: #82FF80;">
						{{ `${element.name} ${element.type === 'balance' ? element.value : (element.num === 1 ? '' : '* ' + element.num)}` }}
					</text>
				</block>
			</template>
			<template v-else>
				<text>新用户注册</text>
			</template>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default {
		data(){
			return {
				receiveImg: baseUrl.imgroot + 'static/images/userCenter/invitePerson/receive-btn.png',
			}
		},
		props: {
			currentIndex: {
				type: [String, Number]
			},
			item: {
				type: [Object, String]
			}
		}
	}
</script>

<style scoped lang="scss">
.invite-list{
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	color: #FFF;
	font-size: 28rpx;
	font-family: PingFang SC-Regular, PingFang SC;
	font-weight: 400;
	margin-bottom: 30rpx;
	.avater{
		width: 86rpx;
		height: 86rpx;
		background: linear-gradient(180deg, #82FF80 0%, #B26CFF 100%);
		border-radius: 50rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		image{
			width: 77rpx;
			height: 77rpx;
			background: #B3B3B3;
			box-shadow: inset 10rpx 0rpx 0rpx 0rpx rgba(35,35,35,0.25);
			border-radius: 50rpx;
			border: 2rpx solid #FFFFFF;
		}
	}
	.user-info{
		width: 300rpx;
		view{
			width: 100%;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}
		.time{
			font-size: 24rpx;
		}
	}
	.receive{
		// width: 202rpx;
		min-height: 76rpx;
		position: relative;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
		image{
			width: 100%;
			height: 100%;
		}
		text{
			width: 100%;
			padding: 5rpx 0;
			white-space: nowrap;
			font-size: 32rpx;
			overflow-x: scroll;
		}
	}
}
</style>