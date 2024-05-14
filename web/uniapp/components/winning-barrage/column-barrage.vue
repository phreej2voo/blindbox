<template>
	<view>
		<view v-if="!isInit" class="y-bullet-container">
			<view class="y-bullet-list-container">
				<view v-for="(item, index) in bulletList" :key="index" class="y-bullet-single">
					<image :src="item.avatar" class="y-bullet-single-avatar"></image>
					<view class="y-bullet-single-info">
						<view class="y-bullet-single-info__job">
							{{item.job}}
						</view>
						<view class="y-bullet-single-info__desc">
							{{item.desc}}
						</view>
					</view>
				</view>
			</view>
		</view>
		<view v-else class="y-bullet-container">
			<!-- :style="{'marqueeWidth': `${throttle}px`, 'animation-play-state': startAnimate ? 'running' : 'paused',
				'animation-duration': `${duration}s`,animation: 'colScroll 8s linear infinite'}" -->
			<view class="y-bullet-list"
				
				style="--marqueeWidth--:-{{throttle}}px; animation-play-state: {{startAnimate ? 'running' : 'paused'}}; animation-duration: {{duration}}s"
				>
				<!-- style="@keyframes colScroll {0% { transform: translateY(1); } 100% { transform: translateY({{throttle}}); }}" -->
				<view class="y-bullet-list-container">
					<view v-for="(item, index) in bulletList" :key="index" class="y-bullet-single">
						<image :src="item.avatar" class="y-bullet-single-avatar"></image>
						<view class="y-bullet-single-info">
							<view class="y-bullet-single-info__job">
								{{item.job}}
							</view>
							<view class="y-bullet-single-info__desc">
								{{item.desc}}
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>

export default {
	props: {
		// 初始弹幕列表
		bulletList: {
			type: Array,
			default: () => {
				return [];
			}
		}
	},
	data() {
		return {
			isInit: false,
			throttle: 0,
			startAnimate: true,
			duration: 5,
			marqueeWidth: 0,
			delayTime: 0 // 延迟动画时间
		};
	},
	computed: {},
	watch: {
		bulletList(newVal) {
			console.log('newVal-', newVal);
			if(newVal && newVal.length) {
				this.$nextTick(() => {
					this.handleData();
				})
			}
		}
	},
	mounted() {},
	methods: {
		handleData() {
			const query =  uni.createSelectorQuery().in(this);
			query.selectAll('.y-bullet-list-container').boundingClientRect(data => {
				console.log('得到布局位置信息-' + JSON.stringify(data));
				this.isInit = true;
				this.throttle = data[0].height;
				// this.throttle = 20;
			}).exec();
			// uni.createSelectorQuery().selectAll(".y-bullet-list-container").boundingClientRect((res) => {
			// 	console.log('res---', res);
			// }).exec();
		}
	}
}
</script>

<style lang="scss" scoped>
.y-bullet-list {
	display: flex;
	flex-direction: column;
	animation: colScroll 8s linear infinite;
	box-sizing: border-box;
}

@keyframes colScroll {
  0% {
	/* 为0时，ios会闪动 */
	transform: translateY(1);
  }

  100% {
	// 定义 2D 转换，沿着 Y 轴移动元素
    transform: translateY(var(--marqueeWidth--));
  }
}

.y-bullet-list-container {
	display: flex;
	flex-direction: column;
}

.y-bullet-single {
	width: 100%;
	height: 70rpx;
	margin-bottom: 28rpx;
	display: flex;
	align-items: center;
}

.y-bullet-single-avatar {
	width: 60rpx;
	height: 60rpx;
	background-color: seagreen;
	border-radius: 50%;
	margin-right: 12rpx;
}

.y-bullet-single-info {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	height: 100%;
}

.y-bullet-single-info__job {
	color: #262d3d;
	font-size: 24rpx;
	line-height: 36rpx;
}
.y-bullet-single-info__desc {
	font-size: 22rpx;
	line-height: 32rpx;
	color: #a1a3b3;
}

</style>