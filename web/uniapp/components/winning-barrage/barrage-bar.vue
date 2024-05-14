<template>
	<view class="barrage-container">
		<view v-if="isShow" class="content">
			<view class="out-bg" :style="{'background-image': 'url('+BarrageImg.bg+')'}">
				<view v-if="curIndex!==-1 && curIndex<bulletList.length" 
					class="text-class">{{ bulletList[curIndex].user_name + '---' + bulletList[curIndex].goods_name}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { getAwardList } from '@/api/home.js';
	import { BarrageImg } from '@/utils/objectValue';

	export default {
		props: {
			direction: {
				type: String,
				default: 'row'
			}
		},
		data() {
			return {
				BarrageImg,
				bulletList: [],
				isShow: false,
				curIndex: -1,
				timer: null
			}
		},
		computed: {},
		created() {},
		mounted() {},
		destroyed() {},
		methods: {
			async initBarrage() {
				const {code, data = []} = await getAwardList();
				this.bulletList = [];
				this.curIndex = -1;
				if(code == 0) {
					this.bulletList = [...data];
					this.open();
				}
			},
			open() {
				this.curIndex++;
				this.timer = setInterval(() => {
					// console.log('curIndex-', this.curIndex);
					if(this.curIndex < this.bulletList.length) {
						this.isShow = true;
						this.$nextTick(() => {
							setTimeout(() => {
								this.isShow = false;
								this.curIndex++;
							}, 2000);
						});
					} else {
						// console.log('stop-', this.curIndex);
						this.close();
						setTimeout(() => {
							this.curIndex = -1;
							this.open();
						}, 3000);
					}
				}, 6000);
			},
			close() {
				console.log('close-');
				clearInterval(this.timer);
				this.timer = null;
				this.isShow = false;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.barrage-container{
		width: 690rpx;
		height: 140rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		// margin: 0 auto;
		overflow: hidden;
		position: fixed;
		top: 250rpx;
		z-index: 999;
		.content{
			width: 100%;
			height: 100%;
		}
		.out-bg{
			width: 100%;
			height: 135rpx;
			background-size: 100% 100%;
			display: flex;
			justify-content: center;
			// align-items: center;
			// padding-top: 25rpx;
		}
		.text-class{
			font-size: 28rpx;
			font-family: PingFang SC-Medium, PingFang SC;
			color: #FFFFFF;
			display: flex;
			align-items: center;
			padding: 0 20rpx 8rpx 0;
		}
	}
</style>