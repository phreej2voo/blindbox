<template>
	<view class="wrap reward" :style="{'background-image': 'url('+GoodsPoolImg.lotteryBack+')'}">

		<view class="luckbox">
			<view class="head">
				<!-- <image src="../../static/image/goods/reward-bg.png" mode="" class="head-image"></image> -->
				<image :src="GoodsPoolImg.rewardBg" mode="" class="head-image"></image>
				<view class="head-title">
					恭喜获得
				</view>
			</view>
			<view class="ranks">
				<view v-if="num != 1" class="list" v-for="(item,idx1) in num" :key="idx1">
					<text>{{idx1+1}}</text>
					<image v-if="idx > idx1" :src="jieguo[idx1].images" mode=""></image>
				</view>
			</view>
			<view class="cbox1">
				<view class="item_contents">
					<view class="cons_list">
						<scroll-view scroll-x="true" class="scroll_list">
							<view class="lists_cons" :style="{transform: 'translateX('+moveX+'rpx)',transition: miao+'s ease-in-out'}">
								<block v-for="(item,index) in goodsDetails" :key="index">
									<view :id="item.goods_id" class="detalis" :style="{background:'url('+item.bg+')'}">
										<image :src="item.image" mode=""></image>
										<view class="tit">
											{{item.goods_name}}
										</view>

										<view class="price">
											￥{{item.goods_pirce}}
										</view>
									</view>
								</block>
							</view>
						</scroll-view>
					</view>
					<!-- <view class="cons_list_mask"></view> -->
				</view>
			</view>
			<view class="count-class">
				<view class="count-view">
					<!-- <image src="https://img.50api.cn/dingdang/stop.png" mode="widthFix"></image> -->
					<text>{{daojishi}}</text>
				</view>
			</view>

			<view class="kai" @click="kaiqi">一键开启</view>
		</view>

		<uni-popup ref="popup" type="center" class="popup-class">
			<view class="result" :style="{'background':'url('+bgurl+')','backgroundSize':'100% 100%'}">
				<!-- <u-navbar :custom-back="back" :immersive="true" back-icon-color="#fff" :border-bottom="false"
					:background="{'backgroundColor':''}" title=" "></u-navbar> -->
				<view class="rbox">
					<view class="tit">
						<image src="https://img.50api.cn/dingdang/cst.webp" mode="widthFix"></image>
					</view>
					<view class="goodsBox">
						<view class="cbox" :class="'c'+num">
							<view v-for="(item,index) in jieguo" :key="index"
								class="list" :style="{'background':'url('+item.bg+')'}">
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
		</uni-popup>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import { RedrawImg, RedrawPopupImg, CouponsImgObj, GoodsPoolImg } from '../../utils/objectValue';
	import {
		get_blind_detail,
		order_result,
		getInfo,
		updatehash,
		getMusic,
		goodsInfo
	} from '@/api/goods.js';

	export default {
		// name: "horizontalScroll",
		data() {
			return {
				GoodsPoolImg,
				goodsDetails: [],
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
				isShow: false,
				ding: null, //倒计时定时器
				isKai: false, //是否一键开启
				istwo: false, //是否有二抽
				isPay: false, //是否正式抽
				ooid: '',
				twoPay: {}, //支付二十抽全部数据
				endTime:'',
				countDown: 5,
			}
		},
		watch: {
			goodsDetails: {
				handler(newValue) {
					console.log('nnn-', newValue);
					if(newValue.length) {
						// this.preMove()
					}
				},
				immediate: true
			}
		},
		computed: {
		},
		onLoad(option) {
			const data1 = this.$store.getters['goods/getPoolsDetails'];
			const data2 = this.$store.getters['goods/getGoodsDetails'];
			console.log('option---', option);
			console.log('option---', data1, data2);
			this.boxId = option.id
			if (option.num == 20) {
				this.num = 10
				this.istwo = true
			} else {
				this.num = option.num
			}
			this.isPay = false
			// const _this = this;
			// this.ding = setInterval(() => {
			// 	console.log('sss', _this.countDown);
			// 	_this.countDown = _this.countDown - 1;
			// 	if (_this.countDown <= 0) {
			// 		_this.countDown = 0;
			// 		clearInterval(this.ding);
			// 		this.ding = null
			// 	}
			// }, 250);
			this.getData(option)
			this.getBox(option.id)
			// #ifdef H5
			if(option.ooid){
				this.boxId = option.boxid
				if (option.num == 20) {
					this.num = 10
					this.istwo = true
				} else {
					this.num = option.num
				}
				this.ooid = option.ooid
				this.isPay = true
				this.getData1(option.num)
				this.getBox(option.boxid)
			}
			// #endif
			uni.loadFontFace({
				family: 'myfont',
				source: 'url("https://img.50api.cn/dingdang/YouSheBiaoTiHei.ttf")',
			})

			/*
			uni.$on('cdata', (data) => {
				console.log("数据接收", data);
				this.boxId = data.id
				if (data.num == 20) {
					this.num = 10
					this.istwo = true
				} else {
					this.num = data.num
				}
				this.isPay = false

				this.getData(data)
				this.getBox(data.id)
			})
			uni.$on('paydata', (data) => {
				console.log(data, '数据接收2');
				this.boxId = data.id
				if (data.num == 20) {
					this.num = 10
					this.istwo = true
				} else {
					this.num = data.num
				}
				this.ooid = data.ooid
				this.isPay = true
				this.getData1(data.num)
				this.getBox(data.id)
			})
			uni.$on('kaidata', (data) => {
				console.log(data, "数据接收3");
				this.boxId = data.boxfl_id
				this.num = 1
				this.isPay = true
				this.getData2(data.id)
				this.getBox(data.boxfl_id)
			})
			 */
		},
		onUnload() {
			// uni.$off('cdata')
			// uni.$off('paydata')
			// uni.$off('kaidata')
		},
		methods: {
			// 事件触发，每秒一次
			change(timestamp) {
				console.log('timestamp---', timestamp);
			},
			// ref形式获取内部的值
			getSeconds() {
				console.log('seconds-', this.$refs.uCountDown.seconds);
			},
			back(){
				// history.back()
				uni.switchTab({
					url:'/pages/tabBar/home'
				})
			},
			shou(){
				uni.switchTab({
					url:'/pages/tabBar/cangku'
				})
			},
			// 奖池
			async getBox(id) {
				const {code, data = {}} = await get_blind_detail({id});
				if (code == 0) {
					const {list = []} = data;
					let arr = [...list]
					let jia = []
					arr.forEach((item, index) => {
						jia.push({
							...item,
							goods_id: 10000 + index
						})
					})
					let add = jia.concat()
					jia = jia.concat(arr)
					if (jia.length < 40) {
						for (var i = 0; i < 40; i++) {
							if (jia.length >= 40) {
								break
							}
							jia.unshift(...add)
						}
					}
					jia = jia.concat(add)
					jia.forEach(item => {
						if (item.tag == 'legend') {
							item.bg = 'https://img.50api.cn/dingdang/csgoodsbg.png'
						} else if (item.tag == 'supreme') {
							item.bg = 'https://img.50api.cn/dingdang/ssgoodsbg.png'
						} else if (item.tag == 'rare') {
							item.bg = 'https://img.50api.cn/dingdang/xygoodsbg.png'
						} else {
							item.bg = 'https://img.50api.cn/dingdang/gjgoodsbg.png'
						}
					})
					console.log("处理数据", jia);
					this.goodsDetails = jia;
				}
				// get_blind_detail({id}).then(res => {
				// 	const {code, data = {}} = res
				// 	if (code == 0) {
				// 		const {list = []} = data;
				// 		let arr = [...list]
				// 		let jia = []
				// 		arr.forEach((item, index) => {
				// 			jia.push({
				// 				...item,
				// 				goods_id: 10000 + index
				// 			})
				// 		})
				// 		let add = jia.concat()
				// 		jia = jia.concat(arr)
				// 		if (jia.length < 40) {
				// 			for (var i = 0; i < 40; i++) {
				// 				if (jia.length >= 40) {
				// 					break
				// 				}
				// 				jia.unshift(...add)
				// 			}
				// 		}
				// 		jia = jia.concat(add)
				// 		jia.forEach(item => {
				// 			if (item.tag == 'legend') {
				// 				item.bg = 'https://img.50api.cn/dingdang/csgoodsbg.png'
				// 			} else if (item.tag == 'supreme') {
				// 				item.bg = 'https://img.50api.cn/dingdang/ssgoodsbg.png'
				// 			} else if (item.tag == 'rare') {
				// 				item.bg = 'https://img.50api.cn/dingdang/xygoodsbg.png'
				// 			} else {
				// 				item.bg = 'https://img.50api.cn/dingdang/gjgoodsbg.png'
				// 			}
				// 		})
				// 		console.log("处理数据", jia);
				// 		this.goodsDetails = jia;
				// 	}
				// }).catch(err => {
					
				// });
			},
			// 获取抽奖结果
			getData(data) {
				const {order_num} = data;
				order_result({order_no: order_num}).then(res => {
					const {code, data} = res;
					if (code == 0) {
						const {id, detail = []} = data;
						this.changeBg(detail)
					}
				}).catch(err => {
					
				});
			},
			// 抽中商品换背景
			changeBg(data) {
				// data.forEach((item, index) => {
				// 	if (item.tag == 'legend') {
				// 		item.bg = 'https://img.50api.cn/dingdang/cs.png'
				// 	} else if (item.tag == 'supreme') {
				// 		item.bg = 'https://img.50api.cn/dingdang/ss.png'
				// 	} else if (item.tag == 'rare') {
				// 		item.bg = 'https://img.50api.cn/dingdang/xy.png'
				// 	} else {
				// 		item.bg = 'https://img.50api.cn/dingdang/gj.png'
				// 	}
				// })
				this.jieguo = [...data]
				console.log(this.jieguo,"结果");
				this.state();
			},
			// 开始抽奖
			state() {
				console.log("开始抽奖", this.idx);
				// 默认第一条中奖
				this.winningId = this.jieguo[this.idx].goods_id
				console.log("抽中的id", this.winningId);
				this.daojishi = 5;
				this.preMove()
				// 每隔一秒 计数减一
				this.ding = setInterval(() => {
					this.daojishi--;
					console.log('sss', this.daojishi);
					if (this.daojishi <= 0) {
						this.daojishi = 0;
						clearInterval(this.ding);
						this.ding = null
					}
				}, 1000)
				this.end()
			},
			end() {
				this.endTime = setTimeout(() => {
					clearInterval(this.ding)
					this.ding = null
					this.yichou = this.jieguo[this.idx]
					this.idx++;
					console.log("一抽", this.yichou, this.idx, this.jieguo.length);
					if (this.idx >= this.jieguo.length) {
						console.log('show-popup');
						this.show = true;
						this.$refs.popup.open('center');
						return false;
					} else {
						setTimeout(() => {
							console.log('iii1');
							this.miao = 0
							this.moveX = 0
						},900)
						if (!this.isKai) {
							console.log('set2');
							setTimeout(() => {
								this.miao = 5
								this.state()
							}, 1000)
						}
					}
				}, 5000)
			},
			getData1(num) {
				console.log(num, "几抽");
				this.$http({
					url: "index.php/api/index/getOne",
					header: {
						// "Content-Type": "application/json",
						"token": uni.getStorageSync("user").token
					},
					data: {
						ooid: this.ooid
					}
				}).then(res => {
					console.log(res, "抽中的商品");
					if (res.data.code == 1) {
						if (num == 20) {
							var data = res.data.data.data
							var one = data.slice(0, 10)
							var two = data.slice(10, 20)
							this.twoPay = {
								one: one,
								two: two
							}
							console.log(this.twoPay, '分开的数据');
							this.changeBg(this.twoPay.one)
						} else {
							this.changeBg(res.data.data.data)
						}
					}
				}).catch(err => {});
			},
			getData2(id) {
				this.$http({
					url: "index.php/api/index/getOnea",
					header: {
						// "Content-Type": "application/json",
						"token": uni.getStorageSync("user").token
					},
					data: {
						id: id
					}
				}).then(res => {
					console.log(res, "盲盒");
					if (res.data.code == 1) {
						this.changeBg(res.data.data.data)

					} else {
						this.$tip.tip(res.data.msg)
					}
				}).catch(err => {});
			},
			
			// 一键开启
			kaiqi() {
				this.isKai = true
				this.daojishi = 0
				this.$forceUpdate()
				this.show = true
			},
			
			// 二十发二次开盒
			jixu() {
				this.miao = 0
				this.moveX = 0
				clearTimeout(this.endTime)
				this.num = 10
				this.istwo = false
				this.idx = 0
				this.jieguo = []
				this.yichou = []
				this.daojishi = 5
				this.isKai = false
				clearInterval(this.ding)
				setTimeout(() => {
					this.miao = 5
					if (this.isPay) {
						this.jieguo = this.twoPay.two
						this.changeBg(this.twoPay.two)
					} else {
						this.getData()
					}
					// this.getBox(this.boxId)
					this.show = false
				},300)
			},

			// 拿到数据之后执行 需要保证中奖数据不在最后5条中
			preMove() {
				this.winningIndex = this.goodsDetails.findIndex(item => item.goods_id == this.winningId)
				const randomNum = Math.floor(Math.random() * (260 - 120 + 1)) + 120; // 随机滚动补差数据确保滚动在中奖条目范围内
				console.log(this.winningIndex, 'this.winningIndex----')
				this.moveX = (-282 * this.winningIndex - 1) + randomNum + 50 //282 宽度258+右边距24
			},
		}
	}
</script>

<style lang="scss" scoped>
	@import url("../../static/css/luckbox.css");

	.reward {
		width: 100%;
		height: 100%;
		background-size: 100% 100%;
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
	.count-class{
		width: 100%;
		height: 50px;
		display: flex;
		justify-content: center;
		align-items: center;
		.count-view{
			width: 140px;
			height: 100%;
			text-align: center;
			font-size: 20px;
			color: #ffffff;
			background: linear-gradient(260deg, rgba(162,39,244,0.31) 0%, #BC58FF 100%);
			border-radius: 10px;
			opacity: 1;
			border: 1px solid #FFFFFF;
			display: flex;
			justify-content: center;
			align-items: center;
		}
	}
	.item_contents {
		width: 100%;
		// background: url('https://img.50api.cn/dingdang/ql.png') no-repeat;
		background-size: 100% 100%;
		padding-top: 52rpx;
		height: 662rpx;
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
	.popup-class{
		width: 100%;
		height: 350px;
	}
</style>
