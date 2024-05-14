<template>
	<view class="container" @touchmove.stop.prevent="moveHandle">
		<view class="head">
			<!-- <image src="../../static/image/goods/reward-bg.png" mode="" class="head-image"></image> -->
			<image :src="GoodsPoolImg.rewardBg" mode="" class="head-image"></image>
			<view class="head-title">
				恭喜获得
			</view>
			<view class="ranks">
				<view v-if="num != 1" class="list" v-for="(item,idx1) in num" :key="idx1">
					<text>{{idx1+1}}</text>
					<image v-if="idx > idx1" :src="goods_list[idx1].images" mode=""></image>
				</view>
			</view>
		</view>

		<view class="result-box">
			<view v-for="(item,index) in boxList" :key="index" class="item-box">
				<uni-badge :text="index+1" type="error" absolute="rightTop" size="small"></uni-badge>
			</view>
		</view>
		<!-- <view class="content" :style="{'background-image': 'url('+goodsBg+')'}">
		</view> -->
		<view class="contents" :style="{'background-image': (isShowPropList ? 'url('+RedrawPopupImg+')' : '')}">
			<view class="scroll-class" :style="{'background-image': 'url('+GoodsPoolImg.poolBack+')'}">
				<view class="scroll-title">商品池</view>
				<view class="cbox1">
					<view class="item_contents">
						<view class="cons_list">
							<scroll-view scroll-x="true" class="scroll_list">
								<view class="lists_cons" :style="{transform: 'translateX('+moveX+'rpx)',transition: miao+'s ease-in-out'}">
									<!-- poolsDetails奖池-非结果 -->
									<block v-for="(item,index) in poolsDetails" :key="index">
										<view class="goods-item"
											:style="{'background-image':item.boxTag.name=='普通款'?'url('+common+')':'url('+rare+')'}">
											<view class="goods-type"
												:style="{'color':item.boxTag.name=='普通款'?colorObj.common_color:item.boxTag.name=='稀有款'?colorObj.rare_color:item.boxTag.name=='传说款'?colorObj.lore_color:colorObj.epic_color}">
												{{item.boxTag.name}}
											</view>
											<view class="goods-img"
												:style="{'background':item.goods_image.indexOf('.png')!=-1?'#fff':''}">
												<u--image :showLoading="true" :src="item.goods_image" width="170rpx" height="170rpx">
												</u--image>
											</view>
											<view class="goods-detail">
												<view class="goods-name">
													{{item.goods_name}}
												</view>
												<view class="goods-price">
													￥{{item.price}}
												</view>
											</view>
										</view>
										<!-- <view :id='item.hash_no' class="detalis" :style="{background:'url('+item.goods_image+')'}">
											<image :src="item.image" mode=""></image>
											<view class="tit">
												{{item.goods_name}}
											</view>
											<view class="price">
												￥{{item.goods_pirce}}
											</view>
										</view> -->
									</block>
								</view>
							</scroll-view>
						</view>
						<!-- <view class="cons_list_mask"></view> -->
					</view>
				</view>
				<view class="scroll-bottom">^</view>
			</view>
			<view v-if="isShowPropList" class="new-redraw-content main-space-around">
				<view class="redraw-text">{{ isFirstProp ? `还有${propList.length}张重抽卡` : '还有 0 张重抽卡'}}</view>
				<view class="btn-class">
					<view v-if="isFirstProp" class="badge">{{ propList.length }}</view>
					<view class="btn" @click="useProp">{{isFirstProp ? '立即使用' : '去获得'}}</view>
				</view>
			</view>
		</view>
		<!-- 下单之后首次显示 -->
		<!-- <view v-if="isFirstProp && !isShowPropList" class="redraw-content main-space-around" :style="{'background-image': 'url('+RedrawImg+')'}">
			<image :src="CouponsImgObj.prop" class="redraw-image"></image>
			<view class="redraw-text">{{ `有${propList.length}张重抽卡` }}</view>
			<view class="btn-class">
				<view v-if="isFirstProp" class="badge">{{ propList.length }}</view>
				<view class="btn" @click="useProp">立即使用</view>
			</view>
		</view> -->

		<view class="di">
			<view class="stoppic">
				<image src="https://img.50api.cn/dingdang/stop.png" mode="widthFix"></image>
				<text>{{daojishi}}</text>
			</view>
		</view>
		<view class="kai" @click="kaiqi">一键开启</view>

		<view v-if="isLotterying" class="footer">
			<view class="receive common" @click="goToWarehouse">
				去仓库查收
			</view>
			<view class="once-more common" @click="onceMore">
				再来一次
			</view>
		</view>

		<!-- 抽奖结果展示 -->
		<u-popup v-model="show" mode="center">
			<view class="result" :style="{'background':'url('+bgurl+')','backgroundSize':'100% 100%'}">
				<u-navbar :custom-back="back" :immersive="true" back-icon-color="#fff" :border-bottom="false"
					:background="{'backgroundColor':''}" title=" "></u-navbar>
				<view class="rbox">
					<view class="tit">
						<image src="https://img.50api.cn/dingdang/cst.webp" mode="widthFix"></image>
					</view>
					<view class="goodsBox">
						<view class="cbox" :class="'c'+num">
							<view class="list" :style="{'background':'url('+item.bg+')'}" v-for="(item,index) in goods_list"
								:key="index">
								<image :src="item.images" mode=""></image>
								<view class="name" v-if="num != 10">
									{{item.goods_name}}
								</view>
								<view class="price">￥{{item.goods_pirce}}</view>
							</view>
						</view>
					</view>
					<view class="bottom" v-if="!isPay">
						<view class="btit" v-if="!isPay">*试玩结果仅供参考</view>
						<view class="btn" @click="jixu" v-if="istwo">
							继续开盒 x10
						</view>
						<view class="btn" @click="$.to('/pages/home/kaixiang?id='+boxId)" v-else>
							来发真的试试

						</view>
					</view>

					<view class="bottom" v-if="isPay">
						<view class="btit" v-if="!isPay">*试玩结果仅供参考</view>
						<view class="btn" @click="jixu" v-if="istwo">
							继续开盒 x10
						</view>
						<view class="btn" @click="shou" v-else>
							全部收下

						</view>
					</view>
				</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import { RedrawImg, RedrawPopupImg, CouponsImgObj, GoodsPoolImg } from '../../utils/objectValue';
	// import horizontalScroll from '../horizontal-scroll/horizontal-scroll.vue';

	export default {
		props: ['goods_list', 'colorObj', 'propList', 'poolList', 'goods_num'],
		components: {
			// horizontalScroll
		},
		data() {
			return {
				total: 0,
				common_color: '',
				rare_color: '',
				lore_color: '',
				epic_color: '',
				common: baseUrl.imgroot + '/front/border.png',
				rare: baseUrl.imgroot + '/front/border-little.png',
				RedrawImg,
				RedrawPopupImg,
				CouponsImgObj,
				GoodsPoolImg,
				indicatorDots: false,
				autoplay: true,
				interval: 2000,
				duration: 500,
				curItemId: '',
				isShowPropList: false, // 是否展示使用重抽卡
				goodsBg: baseUrl.imgroot + '/static/images/kitego/goods-bg.png',
				leftWidth: 0,
				leftWidthPx: '',
				resultList: [],
				swiperInter: null,
				curHashNo: '',
				isLotterying: false, // 是否在开奖中

				winningId: '', //中奖id
				count: 5,
				winningIndex: 0, //中奖索引
				moveX: 0,

				miao:5,
				show: false,
				boxId: '',
				bgurl: 'https://img.50api.cn/dingdang/csbg.webp', //抽奖背景
				boxUrl: 'https://img.50api.cn/dingdang/cs.png', //抽奖商品背景
				muteBgMusic: true,
				num: '', //几抽
				idx: 0, //当前抽的第几发
				jieguo: [], //抽中的全部商品
				yichou: [], //当前已经抽到的商品
				daojishi: 5, //倒计时
				ding: '', //倒计时定时器
				isKai: false, //是否一键开启
				istwo: false, //是否有二抽
				isPay: false, //是否正式抽
				ooid: '',
				twoPay: {}, //支付二十抽全部数据
				endTime: '',
				poolsDetails: [],
			}
		},
		created() {
			const {windowWidth} = uni.getSystemInfoSync();
			console.log('window-Width-', windowWidth);
			this.windowWidth = windowWidth;
		},
		// 不是页面
		onLoad() {
			console.log('load-', this.goods_list);
			// uni.$on('cdata', (data) => {
			// 	console.log("数据接收", data);
			// });
			// this.getData()
			// this.getBox(data.id)
			// setTimeout(() => {
			// 	_this.startCountdown();
			// }, 300)
		},
		watch: {
			// 奖池
			poolList: {
				handler(newVal) {
					console.log('pool-', newVal);
					if(newVal) {
						let fakeArr = [];
						newVal.forEach((item, idx) => {
							fakeArr.push({
								...item,
								id: 10000 + idx,
								isFake: true
							});
						});
						const add = fakeArr.concat();
						fakeArr = fakeArr.concat(newVal);
						if (fakeArr.length < 40) {
							for (let i = 0; i < 40; i++) {
								if (fakeArr.length >= 40) {
									break
								}
								fakeArr.unshift(...add);
							}
						}
						fakeArr = fakeArr.concat(add);
						this.poolsDetails = [...fakeArr];
						console.log('ggg-', this.poolsDetails);
						const _this = this;
						setTimeout(() => {
							_this.startCountdown();
							_this.preMove();
						}, 600);
					}
				},
				immediate: true
			},
		},
		computed: {
			// 显示初次使用重抽卡
			isFirstProp() {
				return this.propList && this.propList.length;
			}
		},
		methods: {
			// 开始抽奖
			startCountdown() {
				console.log("开始抽奖");
				this.winningId = this.poolsDetails[this.idx].goods_id;
				console.log("抽中的id", this.winningId);
				this.daojishi = 5;
				this.preMove();
				this.ding = setInterval(() => {
					this.daojishi -= 1;
					if (this.daojishi <= 0) {
						this.daojishi = 0;
					}
				}, 1000);
				this.end();
			},
			end() {
				this.endTime = setTimeout(() => {
					clearInterval(this.ding)
					this.yichou = this.goods_list[this.idx]
					console.log(this.yichou,"一抽");
					this.idx++;
					if (this.idx >= (this.jieguo.length)) {
						this.show = true
						return
					}
					setTimeout(() => {
						this.miao = 0
						this.moveX = 0
					},900)
					if (!this.isKai) {
						setTimeout(() => {
							this.miao = 5
							this.startCountdown()
						}, 1000)
					}
				}, 5000);
			},
			// 拿到数据之后执行 需要保证中奖数据不在最后5条中
			preMove() {
				for(let i = 0; i < this.goods_list.length; i++) {
					const item = this.goods_list[i];
					for(let j = 0; j < this.poolsDetails.length; j++) {
						const jtem = this.poolsDetails[j];
						if(!jtem.isFake && item.goods_id == jtem.goods_id) {
							this.winningIndex = j;
							break;
						}
					}
				}
				console.log(this.winningIndex, 'this.winningIndex----');
				// this.winningIndex = this.poolsDetails.findIndex(item => item.id == this.winningId)
				const randomNum = Math.floor(Math.random() * (260 - 120 + 1)) + 120; // 随机滚动补差数据确保滚动在中奖条目范围内
				this.moveX = (-254 * this.winningIndex - 1) + randomNum + 50; //282 宽度258+右边距24
			},


			handleScroll() {
				clearInterval(this.swiperInter);
				let num = 0;
				const boxWidth = 185;
				let i = 0;
				const goodsList = this.resultList.slice(this.resultList.length / 2);
				this.startInterval();
			},
			startInterval() {
				const _this = this;
				let num = 0;
				let i = 0;
				const boxWidth = 185;
				const goodsList = this.resultList.slice(this.resultList.length / 2);
				this.swiperInter = setInterval(() => {
					console.log('num-', num, _this.leftWidth);
					_this.leftWidth += _this.windowWidth * 0.4;
					_this.leftWidthPx = _this.leftWidth + 'px';
					// 一轮结束
					if(num >= _this.goods_list.length) {
						if(_this.resultList[num].hash_no === _this.goods_list[num - _this.goods_list.length].hash_no){
							clearInterval(_this.swiperInter);
							_this.swiperInter = null;
							const obj = {..._this.resultList[num], isCur: true};
							// _this.$set(_this.resultList,num,obj);
							_this.resultList.splice(num, 1, obj);
							console.log('asda', num, _this.resultList[num],_this.goods_list[num - _this.goods_list.length].hash_no);
							// _this.curHashNo = _this.resultList[num].curHashNo;
							// setTimeout(() => {
							// 	_this.startInterval();
							// }, 1500);
						}
					} else {
						console.log('111-');
						num++;
					}
					i++;
				}, 400);
			},
			moveHandle() {
				return false;
			},
			goToWarehouse() {
				this.$store
					.commit(
						'param/setType',
						'box'
					)
				this.$store
					.commit(
						'param/setStatus',
						'1'
					)
				uni.switchTab({
					url: '/pages/warehouse/index',
					success: function(res) {
						console.log(res)
						getApp().globalData.paramsData = {
							type: 'box',
							currentIndex: 0
						};
					}
				})
			},

			onceMore() {
				this.$emit('onceMore', '1')
			},
			useProp() {
				if(this.isFirstProp) {
					// 使用后展示重抽卡
					this.isShowPropList = true;
					this.$emit('useProp');
				} else {
					// 去获得
				}
			}
		}
	}

</script>

<style lang="scss" scoped>
	.container {
		width: 100%;
		height: 100vh;
		padding-bottom: calc(220rpx + env(safe-area-inset-bottom));

	}

	.head {
		width: 100%;
		text-align: center;
		/* background: url(https://cdn.kitego.cn/hashmart/goods_detail/reward-bg.png) no-repeat; */
		/* background: url('~@/static/image/goods/reward-bg.png') no-repeat; */
		background-position: center;
		background-size: 564rpx 84rpx;
		/* #ifdef APP-PLUS */
		margin-top: 250rpx;
		/* #endif */
		/* #ifdef MP-WEIXIN */
		margin-top: 200rpx;
		/* #endif */

	}
	.result-box{
		width: 100%;
		padding: 15px 15px;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		.item-box{
			position: relative;
			width: 70px;
			height: 70px;
			margin-top: 10px;
			margin-right: 10px;
			border: 1px solid #F7DAFF;
			border-radius: 6px;
		}
	}

	.head-image {
		/* #ifdef MP-WEIXIN */
		width: 564rpx;
		height: 84rpx;
		position: relative;
		/* #endif */
		/* #ifdef APP-PLUS */
		display: inline-block;
		width: 564rpx;
		height: 42rpx !important;
		position: relative;
		/* #endif */
	}

	.head-title {
		width: 564rpx;
		height: 90rpx;
		text-align: center;
		color: #fff;
		font-size: 90rpx;
		line-height: 90rpx;
		font-family: BTH;
		position: relative;
		/* #ifdef MP-WEIXIN */
		top: -94rpx;
		/* #endif */
		left: 0;
		right: 0;
		margin: auto;
		/* #ifdef APP-PLUS */
		top: -26rpx;
		/* #endif */
	}

	.content {
		z-index: -999;
		background-size: 730rpx 622rpx;
		/* #ifdef MP-WEIXIN */
		margin-top: 54rpx;
		/* #endif */
		/* #ifdef APP-PLUS */
		margin-top: 84rpx;
		/* #endif */
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		height: 622rpx;
		animation: roate 8s infinite linear;
	}

	.contents {
		// background-size: 730rpx 722rpx;
		background-size: 100% 100%;
		// height: 50%;
		background-repeat: no-repeat center;
		// margin-top: 74rpx;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		max-height: 796rpx;
		min-height: 650rpx;
		// overflow-y: auto;
		width: 100%;
		// position: fixed;
		// left: 0;
		
		/* #ifdef APP-PLUS */
		// top: 366rpx;
		/* #endif */

		/* #ifdef MP-WEIXIN */
		// top: 402rpx;
		/* #endif */
	}
	.scroll-class{
		// width: 375px;
		width: 100%;
		height: 327px;
		background-size: 100% 100%;
		.scroll-title{
			font-size: 32px;
			font-family: BTH;
			font-weight: 400;
			color: #FFFFFF;
			text-align: center;
			line-height: 55px;
		}
		.goods-view{
			width: 100%;
			height: 360rpx;
			overflow: hidden;
			display: flex;
			align-items: center;
			margin-top: 26px;
			padding: 0 20px;
		}
		.scroll-bottom{
			text-align: center;
		}
	}
	@keyframes fly {
        0% {
            transform: scale(1);
            top: 100%;
        }
        50% {
            transform: scale(0.5);
            top: calc(50% - 25px);
        }
        100% {
            transform: scale(0.5);
            top: 0;
        }
	}
	@keyframes roate {
		0% {
			transform: rotateZ(0);
			-ms-transform: rotateZ(0);
			-moz-transform: rotateZ(0);
			-webkit-transform: rotateZ(0);
			-o-transform: rotateZ(0);
		}

		100% {
			transform: rotateZ(360deg);
			-ms-transform: rotateZ(360deg);
			-moz-transform: rotateZ(360deg);
			-webkit-transform: rotateZ(360deg);
			-o-transform: rotateZ(360deg);
		}
	}

	.goods {
		width: 240rpx;
		height: 352rpx;
		margin-right: 20px;
		// padding-top: 50px;
		// padding-left: 50px;
	}

	.goods-item {
		background-repeat: no-repeat;
		background-size: 230rpx 342rpx;
		width: 230rpx;
		height: 342rpx;
	}

	.goods-type {
		position: relative;
		top: 16rpx;
		left: 40rpx;
		/* color: #242424; */
		font-size: 30rpx;
		font-family: BTH;
	}

	.goods-img {
		width: 170rpx;
		height: 170rpx;
		position: relative;
		left: 30rpx;
		top: 20rpx;
	}
	.select-img{
		animation: fly 1s linear forwards;
	}

	.goods-detail {
		position: relative;
		top: 56rpx;
		color: #242424;
		font-size: 24rpx;
		left: 32rpx;
		width: 170rpx;
		text-align: center;
	}

	.goods-name {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		text-align: center;
		font-family: Source Han Sans CN;
	}
	.new-image {
		width: 60px;
		height: 63px;
	}
	.new-redraw-content {
		width: 100%;
		height: 66px;
		z-index: 999;
		margin-top: 42px;
		.redraw-text{
			font-size: 25px;
			font-family: BTH;
			color: #FFFFFF;
			line-height: 23px;
		}
		.btn-class{
			position: relative;
			.badge{
				display: inline-block;
				position: absolute;
				top: -5px;
				right: -5px;
				min-width: 20px;
				min-height: 20px;
				background-color: #EA6E7A;
				-webkit-backdrop-filter: blur(0.42539px);
				backdrop-filter: blur(0.42539px);
				border-radius: 50%;
				font-size: 10px;
				font-family: PingFangHK-Semibold, PingFangHK;
				color: #FFFFFF;
				text-align: center;
				line-height: 20px;
			}
		}
		.btn {
			width: 88px;
			height: 29px;
			border: 0.5px solid #FFFFFF;
			font-size: 14px;
			font-family: PingFangHK-Medium, PingFangHK;
			font-weight: 500;
			color: #FFFFFF;
			line-height: 27px;
			border-radius: 5px;
			text-align: center;
		}
	}

	.redraw-content {
		// margin-top: -180px;
		width: 100%;
		height: 66px;
		background-size: 100% 100%;
		z-index: 999;
		margin-top: 42px;
		.redraw-image {
			margin-top: 15px;
			width: 60px;
			height: 63px;
		}
		.redraw-text{
			padding-top: 10px;
			font-size: 25px;
			font-family: BTH;
			color: #FFFFFF;
			line-height: 23px;
		}
		.btn-class{
			padding-top: 10px;
			position: relative;
			// padding-top: 10px;
			.badge{
				display: inline-block;
				position: absolute;
				top: -5px;
				right: -5px;
				min-width: 20px;
				min-height: 20px;
				background-color: #EA6E7A;
				-webkit-backdrop-filter: blur(0.42539px);
				backdrop-filter: blur(0.42539px);
				border-radius: 50%;
				font-size: 10px;
				font-family: PingFangHK-Semibold, PingFangHK;
				color: #FFFFFF;
				text-align: center;
				line-height: 20px;
			}
		}
		.btn {
			width: 73px;
			height: 29px;
			border: 0.5px solid #FFFFFF;
			font-size: 14px;
			font-family: PingFangHK-Medium, PingFangHK;
			font-weight: 500;
			color: #FFFFFF;
			line-height: 27px;
			border-radius: 5px;
			text-align: center;
		}
	}
	.footer {
		display: flex;
		justify-content: space-around;
		height: 100rpx;
		margin-top: 20px;
	}

	.receive {
		border: 2rpx solid #fff;
	}

	.once-more {
		background: linear-gradient(to right, #8F09E6, #B546FF);
	}

	.common {
		width: 316rpx;
		height: 100rpx;
		line-height: 100rpx;
		color: #FBF8FF;
		font-size: 28rpx;
		text-align: center;
	}



	.item_contents {
		width: 100%;
		// background: url('https://img.50api.cn/dingdang/ql.png') no-repeat;
		// background-size: 100% 100%;
		padding-top: 52rpx;
		// height: 662rpx;
		height: 100%;
		box-sizing: border-box;
		position: relative;
		overflow: hidden;
	}

	.cons_list {
		display: inline-block;
		padding-left: 32rpx;
		box-sizing: border-box;
	}

	.scroll_list {
		width: 100%;
		height: 374rpx;

	}

	.lists_cons {
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
		
	}

	.detalis {
		width: 258rpx;
		height: 374rpx;
		margin-right: 24rpx;
		position: relative;
		background-size: 100% 100% !important;
		text-align: center;
	}

	.detalis image {
		width: 150rpx;
		height: 150rpx;
		margin-top: 80rpx;
	}

	.detalis .tit {
		display: -webkit-box;
		text-overflow: ellipsis;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
		margin-top: 10rpx;
		color: #fff;
		font-size: 24rpx;
		padding: 0 16rpx;
	}
	

	.detalis .price {
		color: #fff;
		margin-top: 10rpx;
		font-family: 'myfont';
	}

	/* ::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	} */

	.cons_list_mask {
		width: 100%;
		height: 662rpx;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 99999;
		background-color: rgba(0, 0, 0, 0);
	}
</style>
<style>
	@import url("../../static/css/luckbox.css");
</style>
