<template>
	<view class="animation-container">
		<image :src="bckImg" mode="aspectFill" class="bck-img"></image>
		<!-- 音乐播放器 -->
		<view class="music-box">
			<image :src="isPlay? stopMusic : musicBtn" mode="aspectFill" @click="isPlay? closeAudioPlay() : audioPlay()"></image>
		</view>
		<view class="animation-content">
			<!-- 五连绝世 -->
			<view class="title">
				<image :src="thunderLeft" mode="" class="thunder"></image>
				<view class="placeholder">
					<text class="text">{{ totleDraws == 1? '一发入魂' :  totleDraws == 3? '三连绝胜' :  totleDraws == 5? '五连绝世' : '大获全胜' }}</text>
				</view>
				<image :src="thunderRight" mode="" class="thunder"></image>
			</view>
			<!-- 动画盒子 -->
			<view class="animation-result">
				<view class="animation-outBox">
					<image :src="animationBox" mode="aspectFill" class="animation-box"></image>
					<view class="innner-box" :animation="animationData1">
						<view class="liner-box" v-for="(item,index) in probabilitiesList" :key="index" :style="{'margin-bottom': (index != (probabilitiesList.length - 1))? '30rpx' : '0rpx'}">
							<view class="item-box" v-for="(el,i) in item" :key="i">
								<image :src="itemBoxBck" mode="aspectFill"></image>
								<view class="text">
									<image :src="el.goods_image" mode="aspectFill"></image>
									<image :animation="index == isIndex && i == resultIndex? animationData2 : ''" :src="el.goods_image" mode="aspectFill" class="result-goods"
									:style="{'opacity': index != isIndex || i != resultIndex? 0 : 1}"></image>
								</view>
							</view>
						</view>
					</view>
					<!-- 中间线 -->
					<view class="center-line">
						<view class="diamonds"></view>
						<view class="white-line"></view>
						<view class="diamonds"></view>
					</view>
				</view>
				<!-- 标题 -->
				<view class="current-result">
					<view>本次获得商品</view>
					<view class="current-num">
						<text class="green">{{ rewardList.length - lenghtFix2}}</text>
						<text>/{{ rewardList.length }}</text>
					</view>
				</view>
				<!-- scroll -->
				<view class="scroll-box">
					<view class="scroll_tab" :animation="animationData3" :style="{'margin-left': scrollTotalLeft}">
						<view class="tab_item" v-for='(item, index) in rewardList' :key="index" :style="{'transform': index == lenghtFix2 && isBigger? 'scale(1.2,1.2)' : 'scale(1,1)'}">
							<image :src="rarity" mode="aspectFill"></image>
							<view class="level">{{ item.tag_name }}赏</view>
							<view class="item-box">
								<view class="img">
									<image :src="item.goods_image" mode="aspectFill"></image>
								</view>
								<view class="goods-name">
									{{ item.goods_name }}
								</view>
								<view class="goods-price">
									参考价￥{{ item.goods_price }}
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="jump-btn" @click="close">
				<image :src="jumpBtn" mode="aspectFill"></image>
				<text>跳过</text>
			</view>
			<!-- #ifndef H5 -->
			<view class="" style="height: 80rpx;"></view>
			<!-- #endif -->
			<!-- #ifdef H5 -->
			<view class="" style="height: 200rpx;"></view>
			<!-- #endif -->
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default {
		data(){
			return {
				bckImg: baseUrl.imgroot + 'static/images/animation/bck-img.png',
				thunderLeft: baseUrl.imgroot + 'static/images/composite/thunder_left.png',
				thunderRight: baseUrl.imgroot + 'static/images/composite/thunder_right.png',
				animationBox: baseUrl.imgroot + 'static/images/animation/animation-box.png',
				itemBoxBck: baseUrl.imgroot + 'static/images/animation/item-box-bck.png', 	
				jumpBtn: baseUrl.imgroot + 'static/images/animation/jump-btn.png',
				musicBtn: baseUrl.imgroot + 'static/images/animation/music-btn.png',
				stopMusic: baseUrl.imgroot + 'static/images/animation/stop-music.png',
				rarity: baseUrl.imgroot + 'static/images/animation/rarity.png', // 稀有
				legend: baseUrl.imgroot + 'static/images/animation/legend.png', // 传说
				epic: baseUrl.imgroot + 'static/images/animation/epic.png', // 史诗
				common: baseUrl.imgroot + 'static/images/animation/common.png', // 普通
				animationData1:null, // 老虎机动画
				animationData2:null, // 奖品下移动画
				animationData3:null, // scroll添加奖品动画
				animation: null,
				animation2: null,
				animation3: null,
				resultIndex: '', // 中奖列数
				totalNum: 0,
				isIndex: -1,
				interIndex: null,
				isBigger: false,
				lenghtFix2: 0,
				lenghtFix: 1,
				animationNum: 0,
				scrollTotalLeft: 0,
				glideDistance: 0,
				resultLength: null,
				runtime: 800,
				scrollWidth: 0,
				innerAudioContext: '',
				isPlay: true,
			}
		},
		props: {
			probabilitiesList: [Array],
			randomIndexList: [Array],
			rewardList: [Array],
			totleDraws: [Number,String]
		},
		watch: {
			rewardList: {
				handler(newVal, oldVal){
					// #ifdef H5
					this.scrollTotalLeft = -newVal.length * 90 + 'px';
					// #endif
					// #ifndef H5
					this.scrollTotalLeft = -newVal.length * 180 + 'rpx';
					// #endif
					this.lenghtFix2 = newVal.length;
					this.animationNum = Math.ceil(this.lenghtFix2 / 5);
				},
				deep: true,
				immediate: true,
			},
			probabilitiesList: {
				handler(newVal, oldVal){
					this.audioPlay('open');
					if(newVal.length){
						setTimeout(() => {
							this.openAnimation1()
						}, 800)
					}
				},
				deep: true, // 是否深度监听
				immediate: true, // 是否在组件创建时立即执行回调函数
			},
		},
		methods: {
			// 音乐播放器开启+关闭
			audioPlay(){
				this.innerAudioContext = uni.createInnerAudioContext();
				this.innerAudioContext.autoplay = true;
				this.innerAudioContext.loop = true;
				this.innerAudioContext.src = 'https://bjetxgzv.cdn.bspapp.com/VKCEYUGU-hello-uniapp/2cc220e0-c27a-11ea-9dfb-6da8e309e0d8.mp3';
				this.isPlay = true;
				this.innerAudioContext.onPlay(() => {
				  console.log('开始播放');
				});
			},
			closeAudioPlay(){
				this.isPlay = false;
				this.innerAudioContext.pause(() => {
				  console.log('播放停止');
				});
			},
			// 开启抽奖动画
			openAnimation1(){
				switch(this.totleDraws){
					case 1:
						this.glideDistance = 614;
						break;
					case 3:
						this.glideDistance = 742;
						break;
					case 5:
						this.glideDistance = 870;
						break;
					default: 
						this.glideDistance = 870;
						break;
				};
				this.resultIndex = this.randomIndexList[this.totalNum];
				let resultList = [];
				this.probabilitiesList.forEach(item => {
					item.forEach((el, index) => {
						if(index == this.resultIndex && el != ''){
							resultList.push(el)
						}
					});
				})
				this.resultLength = resultList.filter(item => item != '').length;
				if(this.totalNum){
					this.runtime = 600 * (this.resultIndex - this.randomIndexList[this.totalNum - 1]);
				}else{
					this.runtime = 600 * this.resultIndex;
				}
				this.animation = uni.createAnimation({
				  duration: this.runtime,
				  timingFunction: 'ease',
				});
				this.scrollWidth = 150 * this.resultIndex + 50 - 350;
				this.animation.translateX(-this.scrollWidth + 'rpx').step()
				// 将动画效果赋值
				this.animationData1 = this.animation.export();
				setTimeout(() => {
					this.totalNum++;
					this.getTransform();
				}, this.runtime);
			},
			// 平移动画
			getTransform2(){
				this.animation2 = uni.createAnimation({
					duration: 500,
					timingFunction: 'linear',
				});
				let scrollXY;
				// #ifdef H5
				scrollXY = parseInt((this.glideDistance - this.isIndex * 128) / 2) + 'px';
				// #endif
				// #ifndef H5
				scrollXY = this.glideDistance - this.isIndex * 128 + 'rpx';
				// #endif
				this.animation2.translate3d('-302rpx', scrollXY, 0).step()
				// 将动画效果赋值
				this.animationData2 = this.animation2.export();
			}, 
			getTransform(){
				this.interIndex = setInterval(() => {
					this.isIndex++;
					if(this.isIndex >= this.resultLength){
						clearInterval(this.interIndex);
						this.isIndex = -1;
						if(this.totalNum < this.animationNum){
							setTimeout(() => {
								this.openAnimation1();
							}, 50)
							// this.bindReact(1);
						}else{
							clearInterval(this.interIndex);
							setTimeout(() => {
								this.close();
							}, 2000)
						}
						this.isBigger = false;
					}else{
						this.lenghtFix2--
						this.isBigger = true;
						this.scroll();
						this.getTransform2();
					}
				}, 1000)
			},
			// 重置
			bindReact(type){
				this.animation = uni.createAnimation({
				  duration: 0
				});
				this.animation2 = uni.createAnimation({
				  duration: 0
				})
				this.animationData1 = this.animation.translateX(0).step().export();
				this.animationData2 = this.animation2.translateY(0).step().export();
				if(type == 1){
					setTimeout(() => {
						this.openAnimation1();
					}, 50)
				}else{
					clearInterval(this.interIndex);
				}
			},
			// end
			// scroll滚动动画
			scroll(){
				this.animation3 = uni.createAnimation({
					duration: 600,
					timingFunction: 'linear',
				});
				// #ifdef H5
				let scrollLeft = this.lenghtFix * 90 + 'px';
				// #endif
				// #ifndef H5
				let scrollLeft = this.lenghtFix * 180 + 'rpx';
				// #endif
				this.animation3.translateX(scrollLeft).step()
				// 将动画效果赋值
				this.animationData3 = this.animation3.export();
				this.lenghtFix++;
			},
			// 关闭动画
			close(){
				this.closeAudioPlay();
				this.$emit('closeAnimation')
			},
		},
	}
</script>

<style scoped lang="scss">
@import './index.scss';
</style>