<template>
	<view class="z-bullet-test">
		<block v-if="domWidth == 0">
			<view class="z-container" :style="{'background-image': 'url('+BarrageImg.bg+')'}">
				<view v-for="(item,index) in 10" :key="index" class="z-single">
					<!-- {{index}} -->
				</view>
			</view>
		</block>
		<block v-else>
			<view class="out-bg" :style="{'background-image': 'url('+BarrageImg.bg+')'}">
				<view class="z-list"
					:style="{'--domWidth': `-${domWidth}px`,
					'animation-play-state': isAnimate,
					'animation-duration': duration+'s',
					'animation-iteration-count': 1}">

					<!-- style="--domWidth: -{{domWidth}}px;
					animation-play-state: {{isAnimate}};
					animation-duration: {{duration}}s;
					animation-iteration-count: 1;" -->

					<!-- :style="{'--domWidth': `-${domWidth}px`,
					'animation-play-state': isAnimate,
					'animation-duration': duration+'s',
					'animation-iteration-count': 1}" -->
					<!-- style="--domWidth--:-{{domWidth}}px; @keyframes rowScrollTest {0% { transform: translateX(1); } 100% { transform: translateX(-433.85px); }}" -->
					<view class="z-container">
						<view v-for="(item,index) in bulletList" :key="index" class="z-single">
							{{item.user_name}}--{{item.goods_name}}
							<!-- <text>{{item.user_name}}</text>
							<text>{{item.goods_name}}</text> -->
						</view>
					</view>
				</view>
			</view>
		</block>
	</view>
</template>

<script>
	import { getAwardList } from '@/api/home.js';
	import { BarrageImg } from '@/utils/objectValue';

	export default {
		props: {
			// 初始弹幕列表
			// bulletList: {
			// 	type: Array,
			// 	default: () => {
			// 		return [];
			// 	}
			// },
			showBarrage: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				BarrageImg,
				bulletList: [],
				startAnimate: true,
				duration: 15,
				domWidth: 0,
				delayTime: 0, // 延迟动画时间
				animateObj: {
					true: 'running',
					false: 'paused'
				},
				cssStyle: {
					
				}
			};
		},
		computed: {
			isAnimate() {
				return this.animateObj[this.startAnimate];
			}
		},
		watch: {},
		created() {},
		mounted() {
			console.log('addad');
			this.getAwardList();
		},
		methods: {
			async getAwardList() {
				const {code, data = []} = await getAwardList();
				this.bulletList = [];
				if(code == 0) {
					this.bulletList = [...data];
					this.$nextTick(() => {
						setTimeout(() => {
							this.handleData();
						}, 300);
					});
				}
			},
			initPlay() {
				this.bulletList = [];
				this.getAwardList();
			},
			handleData() {
				const query = uni.createSelectorQuery().in(this);
				query.selectAll('.z-container').boundingClientRect(data => {
					console.log('rect-' + JSON.stringify(data));
					this.domWidth = data[0].width;
					this.duration = 5;
					// this.setKeyframe();

					setTimeout(() => {
						this.$emit('over');
					}, this.duration * 1000);
				}).exec();
			},
			setKeyframe() {
				const query = uni.createSelectorQuery().in(this);
				const doms = query.selectAll('.z-list');
				console.log('doms-', doms);
				const sheet = doms.styleSheets[0];
				const rules = sheet.cssRules || sheet.rules;
				for (let i = 0; i < rules.length; i++) {
					const rule = rules[i];
					if (rule.name === 'rowScrollTest') {
						const keyframes = rule.cssRules || rule.rules;
						for (let j = 0; j < keyframes.length; j++) {
							const keyframe = keyframes[j];
							if (keyframe.keyText === '0%') {
								keyframe.style.transform = `translateX(1)`;
							} else if (keyframe.keyText === '100%') {
								keyframe.style.transform = `translateX(-${this.domWidth}px)`;
							}
						}
					}
				}
			},
			calc(res){
				const vt = res[0].width / this.duration;
				const setDuration = (index) => {
					return (res[index].width / vt).toFixed(2);
				};
				const newList = this.bulletList.map((item, index) => {
					item.throttleLeft = res[index].width;
					item.duration = setDuration(index);
					return item;
				});
				this.setData({ isInit: true, handleBulletList: newList });
				console.log("newList", newList);
			}
		}
	}
</script>

<style lang="scss" scoped>
.z-bullet-test {
	width: 690rpx;
	height: 88rpx;
	display: flex;
	align-items: center;
	margin: 0 auto;
	margin-top: 40rpx;
	overflow: hidden;
	.out-bg{
		width: 100%;
		height: 135rpx;
		background-size: 100% 100%;
	}
	.z-list {
		height: 100%;
		height: 100%;
		display: flex;
		flex-wrap: nowrap;
		animation: rowScrollTest 2s linear infinite;
	}
	.z-container {
		height: 100%;
		display: flex;
		flex-wrap: nowrap;
		background-size: 100% 100%;
	}
	.z-single {
		height: 100%;
		white-space: nowrap;
		display: flex;
		align-items: center;
		padding: 10rpx 40rpx;
		margin-right: 20rpx;
		font-size: 28rpx;
		font-family: PingFang SC-Medium, PingFang SC;
		color: #FFFFFF;
		text{
			font-size: 28rpx;
			font-family: PingFang SC-Medium, PingFang SC;
			font-weight: 500;
			color: #FFFFFF;
		}
	}
	@keyframes rowScrollTest {
		0% {
			/* 为0时，ios会闪动 */
			transform: translateX(1);
		}

		100% {
			/* transform: translateX(-433.85px); // 单个数组所渲染的dom长度  */
			transform: translateX(var(--domWidth));
		}
	}
}

</style>