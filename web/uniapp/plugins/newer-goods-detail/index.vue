<template>
	<!-- <app-layout v-if="refreshPage"> -->
		<!-- #ifdef MP-WEIXIN -->
		<page-meta :page-style="`overflow: ${buyVisible ? 'hidden':''}`">
		</page-meta>
		<!-- #endif -->
		<view class="container" :style="{'padding-top': phoneHieght}">
			<view class="page-background">
				<!-- #ifdef APP-PLUS || H5 -->
				<img :src="back_1" alt="" class="back1-img">
				<!-- #endif -->
				<!-- #ifdef MP-WEIXIN -->
				<image :src="back_1" mode="" class="back1-img"></image>
				<!-- #endif -->
			</view>

			<u-navbar title="商品详情" :autoBack="true" :bgColor="isNavWhite ? '#fff' : 'rgba(0,0,0,0)'">
			</u-navbar>
			<barrage-bar ref="barrage" class="barrage-class"></barrage-bar>
			<view class="music">
				<!-- #ifdef MP-WEIXIN -->
				<page-head title="audio"></page-head>
				<!-- #endif -->
				<!-- #ifdef MP-WEIXIN -->
				<image v-if="isPlaying" :src="musicBg" mode="" class="music-img" @click="playMusic">
				</image>
				<image v-else :src="stopMusic" mode="" class="stop-music" @click="playMusic">
				</image>
				<!-- #endif -->
				<!-- #ifdef APP-PLUS || H5 -->
				<img v-if="isPlaying" :src="musicBg" alt="" class="music-img" @click="playMusic">
				<img v-else :src="stopMusic" alt="" class="stop-music" @click="playMusic">
				<!-- #endif -->
			</view>
			<!-- 右侧按钮栏 -->
			<view class="right-menu column-center-flex">
				<view class="item-class column-center-flex" @click="fairOpen">
					<!-- #ifdef APP-PLUS || H5 -->
					<img :src="GoodsRightMenu.fair" alt="" class="menu-img">
					<!-- #endif -->
					<!-- #ifdef MP-WEIXIN -->
					<image :src="GoodsRightMenu.fair" mode="" class="menu-img"></image>
					<!-- #endif -->
					<view class="menu-text">公平开盒</view>
				</view>
				<view class="item-class column-center-flex" @click="sharePosters">
					<!-- #ifdef APP-PLUS || H5 -->
					<img :src="GoodsRightMenu.share" alt="" class="menu-img">
					<!-- #endif -->
					<!-- #ifdef MP-WEIXIN -->
					<image :src="GoodsRightMenu.share" mode="" class="menu-img"></image>
					<!-- #endif -->
					<view class="menu-text">分享</view>
				</view>
			</view>
			<scroll-view class="body" enableFlex scroll-y>
				<view class="goods-swiper">
					<view class="goods-background main-center-flex">
						<!-- #ifdef APP-PLUS || H5 -->
						<img src="http://dev-hashmart.mstshop.cn/static/images/back_2.png" alt="" class="back-img">
						<!-- #endif -->
						<!-- #ifdef MP-WEIXIN -->
						<image src="http://dev-hashmart.mstshop.cn/static/images/back_2.png" mode="" class="back-img"></image>
						<!-- #endif -->
					</view>
					<swiper :autoplay="false" @change="e => currentSwiper = e.current" class="goods-swiper">
						<swiper-item v-for="(item,index) in goodsList" :key="index" class="column-center-flex">
							<view class="goods-item-image">
								<u--image :showLoading="false" :src="item.pic" width="500rpx" height="500rpx">
								</u--image>
							</view>
							<view class="goods-info column-center-flex">
								<view class="goods-info-kinds-container main-center-flex">
									<view class="info-kinds-name">
										{{item.boxTag.name}}
									</view>
									<view class="goods-info-kinds main-center-flex">
										<view class="goods-info-kinds_1 main-center-flex">
											<!-- #ifdef APP-PLUS || H5 -->
											<img src="../../static/image/goods/arrow_2.png" alt=""
												class="arrow-img arrow-reversal">
											<!-- #endif -->
											<!-- #ifdef MP-WEIXIN -->
											<image src="../../static/image/goods/arrow_2.png" mode=""
												class="arrow-img arrow-reversal"></image>
											<!-- #endif -->
											<!-- #ifdef APP-PLUS || H5 -->
											<img src="../../static/image/goods/arrow_2.png" alt="" class="arrow-img">
											<!-- #endif -->
											<!-- #ifdef MP-WEIXIN -->
											<image src="../../static/image/goods/arrow_2.png" mode="" class="arrow-img"></image>
											<!-- #endif -->
										</view>
									</view>
								</view>
								<view class="goods-info-text text-ellipsis_2">
									{{item.goods_name}}
								</view>
								<view class="goods-info-price">
									<text>￥</text>
									{{item.price}}
								</view>
							</view>
						</swiper-item>
					</swiper>
				</view>
				<view class="goods-tip column-center-flex">
					<text>开盒必出以下宝贝之一</text>
				</view>
				<view class="goods-rate-container">
					<view class="goods-rate-head main-start-flex">
						<view class="rate-head-title column-center-flex">
							<view>获得</view>
							<view>概率</view>
						</view>
						<view class="rate-item-scroll">
							<view class="rate-item" v-for="(item,index) in probabilityList" :key="index"
								:style="{'background':item.color}">
								<view class="rate-item-head main-center-flex" :style="{'color':item.color}">
									{{item.tag}}
								</view>
								<view class="rate-item-footer main-center-flex">
									{{item.probability}}%
								</view>
							</view>
						</view>
					</view>
					<view class="goods-rate-fotter text-margin text-ellipsis">
						因概率对小数点后三位进行四舍五入处理，故存在总值不为100%的可能
					</view>
				</view>
				<goods-detail :goods-list="goodsList" type="box"></goods-detail>
			</scroll-view>

			<view class="buy-button main-center-flex">
				<view class="buy-back" :style="{'background-image': 'url('+HonorListImg.buyOneBack+')'}">{{ goodsTypeText }}</view>
				<view class="buy-button-detail" @tap="goPayPage">
					<view class="main-center-flex">
						<view>立即开盒</view>
					</view>
					<view class="main-center-flex">
						<view>¥{{price}}</view>
						<view v-if="goodsType == 3" class="free-text">50%概率免单</view>
					</view>
				</view>
			</view>

			<u-popup :show="fairOpening" mode="bottom" @close="fairOpening = !fairOpening">
				<view class="fair">
					<view class="main-space-between">
						<view class="fair-title">
							公平开盒
						</view>
						<u-icon name="close" @click="fairOpening = !fairOpening" color="#333"></u-icon>
					</view>
					<view class="seed">
						<view class="seed-title">
							我的种子
						</view>
						<view class="seed-body">
							<text class="seed-content">{{hash_key}}</text>
							<view class="seed-change" @click="changeSeed">
								更换
							</view>
						</view>
						<view class="seed-title">
							时间种子
						</view>
						<view class="seed-time">
							<view class="seed-date">
								{{currentTime}}
							</view>
							<view class="seed-num">
								{{numberGro}}
							</view>
						</view>
					</view>

					<view class="seed-rule">
						抽盒规则
					</view>
					<view class="rule-text">
						每次开盒将通过哈希计算获得一个数字，该数字所对应的物品即为本次开盒所得物品。
					</view>
					<view class="seed-rule">
						公平算法
					</view>
					<view class="rule-text">
						平台结合用户自行提供的种子和时间种子，用哈希算法得到平台无法篡改的抽盒结果，保证抽盒过程绝对公平。
					</view>
					<view class="seed-rule last-title" @click="openHashInfo">
						点击查看哈希算法详细说明
						<u-line length="336rpx" color="#8D01E6"></u-line>
					</view>
					<view class="know" @click="fairOpening = !fairOpening">
						知道了
					</view>
				</view>
			</u-popup>
			<u-loading-page :loading="loadingPage" loading-text="加载中..."></u-loading-page>

			<u-popup :show="orderVisible" mode="bottom" @close="closeOrderPopup">
				<newer-order-sure ref="order" class="order-view" @closeOrder="closeVisible">
				</newer-order-sure>
			</u-popup>
			<goods-share v-if="blindbox_id" ref="goodsShare" :postersData="postersData" :blindId="blindbox_id"></goods-share>
			<free-play-animation v-if="isShowPlay" :showPlay="showPlay" :numType="numType" @closePlay="closePlay">
			</free-play-animation>
			<modal ref="modal" class="model-bg"></modal>
		</view>
	<!-- </app-layout> -->
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import {mapGetters, mapState} from 'vuex';
	import {
		get_blind_detail,
		order_result,
		getInfo,
		updatehash,
		getMusic
	} from '@/api/goods.js';
	import {getReward} from '@/api/pay.js';
	import {getMyProp, useProp} from '@/api/coupon.js';
	import {mustBuyTrail, presentTrail, freeBuyTrail} from '@/api/newer.js';
	import NewerOrderSure from '@/components/newer-order-sure/index.vue'
	import GoodsDetail from '@/components/goods-detail/index.vue';
	import GoodsShare from '@/components/goods-detail/goods-share.vue';
	import FreePlayAnimation from '@/components/free-play-animation/index.vue';
	import BarrageBar from '@/components/winning-barrage/barrage-bar';
	import { GoodsRightMenu, HonorListImg } from '../../utils/objectValue';

	export default {
		components: {
			NewerOrderSure,
			GoodsDetail,
			GoodsShare,
			FreePlayAnimation,
			BarrageBar
		},
		data() {
			return {
				GoodsRightMenu,
				HonorListImg,
				back_1: baseUrl.imgroot + '/front/back_1.png',
				back_2: baseUrl.imgroot + '/front/back_2.png',
				buy_head_bk_1: baseUrl.imgroot + '/front/buy_head_bk_1.png',
				musicBg: baseUrl.imgroot + '/static/images/goods/music-bg.png',
				stopMusic: baseUrl.imgroot + '/static/images/goods/stop-music.png',
				box_name: null,
				title: 'innerAudioContext',
				hashInfo: false,
				payParms: null,
				hash_name: null,
				hash_pop: false,
				hash_key: null,
				numberGro: null,
				currentTime: '',
				fairOpening: false,
				goods_num: 0,
				price: 0,
				goods_list: [],
				propList: [],
				goodsShow: false,
				order_num: '',
				refreshPage: false,
				commonLeft: 'linear-gradient(99deg, #EB5C4A 0%, #8F09E6 0%, #B546FF 100%)',
				rareLeft: 'linear-gradient(99deg, #FFFFFF 0%, #EB5C4A 0%, #F6CA7C 100%)',
				commonRight: 'linear-gradient(0deg, #FBF7FF 0%, #F8E2FF 100%)',
				rareRight: 'linear-gradient(0deg, #FFFCF7 0%, #F7D8B8 100%)',
				orderVisible: false,
				goodsType: 1, // 1-新人必买 2-买一送一 3-白嫖
				goodsTypeText: '',
				goodsTypeTextObj: {
					1: '新人3抽起购',
					2: '新人买一赠一'
				},
				blindbox_id: '',
				num: 3,
				type: 'box',
				isNavWhite: false,
				swiperList: [],
				currentSwiper: 0,
				goodsList: [],
				probabilityList: [],
				styleList: [],
				buyVisible: false,
				loadingPage: true,
				musicSrc: null,
				isPlaying: true,
				isPlayEnd: false,
				total: 0,
				color: {
					common_color: '',
					rare_color: '',
					lore_color: '',
					epic_color: ''
				},
				rewardId: '',
				current: 0,
				isFree: false, // 是否有面单机会
				isShowFree: false,
				freeList: [], // 免单机会
				freeResList: [], // 免单结果
				postersData: {},
				isShowLottery: false,
				poolList: [],
				trailObj: {
					1: mustBuyTrail,
					2: presentTrail,
					3: freeBuyTrail
				},
				isShowPlay: false,
				showPlay: false, // 显示白嫖动画
				numType: '1',
				_audioContext: null
			}
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght',
			})
		},
		onShow() {
			// const pages = getCurrentPages();
			// const currPage = pages[pages.length - 1]; // 当前页面
			// if(currPage.data != undefined){
			// 	let json = currPage.data.testdata;
			// }
			this.loadingPage = false;
		},
		/* 此页面为新人必买 买一送一 白嫖详情专用 */
		async onLoad(params) {
			const {scene} = params;
			console.log('params-', params);
			// spId=TVRnPQ== & bId=1
			let bId = '';
			// 二维码进入
			if(scene) {
				const info = decodeURIComponent(scene);
				const arr = info.split('&');
				bId = arr[1].substring(4);
				uni.setStorageSync('agent_user_id', arr[0].substring(5));
			} else {
				// 列表跳转进入
				bId = params.id;
			}
			this.blindbox_id = bId;

			const {price, type, numType, limit} = params;
			
			this.goodsType = type;
			this.goodsTypeText = type == 3 ? `限购${limit}次` : this.goodsTypeTextObj[type];
			if(type == 3) {
				this.numType = numType;
				this.isShowPlay = true;
				this.$nextTick(() => {
					this.showPlay = true;
				});
			} else {
				this.price = price;
			}
			this.getBlindDetail(bId);
			this.getMusicInfo(); // 新增
			
			setInterval(() => {
				this.currentTime = this.timeChange(Date.now());
				this.numberGro = this.numGro();
			}, 10);
			this.$nextTick(() => {
				this.$refs.barrage.initBarrage();
			})
		},
		onHide() {
			this.refreshPage = false;
			this.stop();
		},
		onUnload() {
			console.log('onUnload--');
			this._audioContext && this._audioContext.destroy();
			this.$refs.barrage.close();
		},
		destroyed() {
			console.log('destroyed--');
			this.stop();
		},
		onPageScroll(e) {
			let query = uni.createSelectorQuery().in(this);
			// 需要给黄色区域设置一个id标识，在这里是demo
			query.select('.goods-tip').boundingClientRect(data => {
				this.isNavWhite = data.top <= 88;
			}).exec();
		},
		methods: {
			closePlay() {
				setTimeout(() => {
					this.isShowPlay = false;
				}, 300);
			},
			async getBlindDetail(id) {
				uni.showLoading({
					title: '',
					mask: true
				});
				const {code, data} = await get_blind_detail({id});
				uni.hideLoading();
				if (code == 0) {
					this.postersData = {...data};
					const {info = {}, list = [], probability = []} = data;
					list.forEach(item => {
						item.pic = item.goods_image.split(',')[0];
					});
					if(this.goodsType == 3) {
						this.price = info.price;
					}
					this.box_name = info.name;
					this.goodsList = list;
					this.poolList = [...list];
					this.isShowLottery = true;
					this.probabilityList = probability;
					this.processingStyles(probability);
					this.loadingPage = false;
				} else {
					uni.switchTab({
						url: '/pages/index/index'
					});
				}
			},
			processingStyles(styleList) {
				for (var i = 0; i < styleList.length; i++) {
					if (styleList[i].tag == '稀有款') {
						this.color.rare_color = styleList[i].color;
					} else if (styleList[i].tag == '史诗款') {
						this.color.epic_color = styleList[i].color;
					} else if (styleList[i].tag == '传说款') {
						this.color.lore_color = styleList[i].color;
					} else {
						this.color.common_color = styleList[i].color;
					}
				}
			},
			// 获取背景音乐
			async getMusicInfo() {
				const {code, data} = await getMusic();
				if(code == 0) {
					this.musicSrc = data;
					this._audioContext = null;
					this.createAudio();
				}
			},
			playMusic() {
				if (this.isPlaying) {
					this.pause();
					return;
				}
				this.isPlaying = !this.isPlaying;
				this._audioContext.play();
				this.isPlayEnd = false;
			},
			// 公平开盒
			fairOpen() {
				this.fairOpening = true;
				this.getHashKey();
			},
			// 分享
			sharePosters() {
				this.$nextTick(() => {
					this.$refs.goodsShare.open();
				});
			},
			stop() {
				if(this._audioContext) {
					this._audioContext.stop();
				}
				this.isPlaying = false;
			},
			pause() {
				this._audioContext.pause();
				this.isPlaying = false;
			},
			createAudio() {
				const innerAudioContext = (this._audioContext = uni.createInnerAudioContext());
				innerAudioContext.autoplay = true; //自动播放
				innerAudioContext.loop = true; //循环播放
				innerAudioContext.src = this.musicSrc;
				innerAudioContext.onPlay(() => { //可以播放事件
					this.playing = !innerAudioContext.paused; //查看是否可以自动播放
				});
				innerAudioContext.onError((res) => {
					console.log(res.errMsg);
					console.log(res.errCode);
				});
			},
			openHashInfo() {
				uni.navigateTo({
					url: '/plugins/hash-algorithm/index'
				})
			},
			// 先订单试算看是否有购买权限 有--> 跳转到支付页面 无-->提示语
			async goPayPage() {
				uni.showLoading({
					title: '',
					mask: true
				});
				const {code, data, msg = ''} = await this.trailObj[this.goodsType]();
				uni.hideLoading();
				if(code == 0) {
					// 可以购买
					const params = {
						payInfo: {...data},
						param: {
							blindbox_id: this.blindbox_id,
							goodsType: this.goodsType
						}
					};
					this.orderVisible = true
					this.$nextTick(() => {
						this.$refs.order.initData(params);
					});
				} else {
					uni.$u.toast(msg);
				}
				return;
			},
			closeOrderPopup() {
				this.orderVisible = false;
			},
			changeSeed() {
				this.$refs.modal.showModal({
					title: '更换种子',
					isInput: true,
					inputModel: this.hash_name,
					confirm: (e) => {
						this.hash_name = e
						this.sureChange()
					}
				})
			},
			sureChange() {
				updatehash({
					token: this.hash_name
				}).then(res => {
					if (res.code == 0) {
						this.hash_pop = false
						uni.showToast({
							icon: 'none',
							title: res.msg
						})
						this.getHashKey()
					}
				})
			},
			getHashKey() {
				getInfo().then(res => {
					if (res.code == 0) {
						this.hash_key = res.data.hash_key
						this.hash_name = this.hash_key
					}
				})
			},
			numGro() {
				var result = Math.floor(Math.random() * 10000);
				if (result < 10) {
					return "000" + result;
				} else if (result < 100) {
					return "00" + result;
				} else {
					return result;
				}
			},
			timeChange(date) {
				let nowdate = new Date(date);
				let YY = nowdate.getFullYear() + '-';
				let MM = (nowdate.getMonth() + 1 < 10 ? '0' + (nowdate.getMonth() + 1) : nowdate.getMonth() + 1) + '-';
				let DD = (nowdate.getDate() < 10 ? '0' + (nowdate.getDate()) : nowdate.getDate());
				let hh = (nowdate.getHours() < 10 ? '0' + nowdate.getHours() : nowdate.getHours()) + ':';
				let mm = (nowdate.getMinutes() < 10 ? '0' + nowdate.getMinutes() : nowdate.getMinutes()) + ':';
				let ss = (nowdate.getSeconds() < 10 ? '0' + nowdate.getSeconds() : nowdate.getSeconds());
				return YY + MM + DD + " " + hh + mm + ss;
			},
			// 支付订单
			closeVisible({order_no, payParms, num}) {
				console.log('pay-close-', order_no, payParms, num);
				this.orderVisible = false;
				this.order_num = order_no;
				this.payParms = payParms;
				uni.showLoading({
					title: '查询结果',
					mask: true
				});
				order_result({
					order_no: this.order_num
				}).then(res => {
					uni.hideLoading();
					const {code, data = {}} = res;
					if (code == 0) {
						const {id, detail = []} = data;
						for (var i = 0; i < detail.length; i++) {
							detail[i].goods_image = detail[i].goods_image.split(',')[0]
						}
						// 第一次中奖结果id
						this.rewardId = id
						this.goods_list = detail
						this.getPropList();

						this.$store.commit('goods/setPoolsDetails', {poolList: this.poolList, probabilityList: this.probabilityList});
						this.$store.commit('goods/setGoodsDetails', {goods_list: this.goods_list});
						uni.navigateTo({
							url: `/plugins/result-box/index?id=${this.blindbox_id}&order_num=${this.order_num}&num=${num}&isnew=${true}`
						});

						// setTimeout(() => {
						// 	uni.$emit('cdata', {
						// 		id: this.blindbox_id, // 盲盒id
						// 		order_num: this.order_num, // 订单号
						// 		num: num // 抽数
						// 	});
						// }, 600);
						
					} else {
						this.getOrderRes()
					}
				})
			},
			getOrderRes() {
				order_result({
					order_no: this.order_num
				}).then(res => {
					const {code, data = {}} = res;
					if (code == 0) {
						const {id, detail = []} = data;
						this.rewardId = id
						this.goods_list = detail
						setTimeout(() => {
							this.goodsShow = true
							this.getPropList();
						})
					} else {
						if (this.total < 20) {
							setTimeout(() => {
								this.total = this.total + 1
								this.getOrderRes()
							}, 800)
						} else {
							uni.navigateTo({
								url: '/plugins/order-detail/index'
							})
						}
					}
				})
			},
			// 获取重抽卡
			async getPropList() {
				const {code, data = []} = await getMyProp();
				if(code == 0) {
					this.propList = data;
				}
			},
			// 使用重抽卡
			toUseProp() {
				const params = {
					id_code: this.propList[0].id_code,
					type: this.propList[0].type,
					reward_id: this.rewardId
				};
				this.usePropResult(params);
			},
			// 获取使用重抽之后的reward_id
			async usePropResult(params) {
				const {code, data = {}} = await useProp(params);
				if(code == 0) {
					const {record_id} = data;
					this.getRewardResult(record_id);
				}
			},
			// 获取重抽结果
			async getRewardResult(record_id) {
				const {code, data = {}} = await getReward({reward_id: record_id});
				if(code == 0) {
					const {id, detail = []} = data;
					// 保存上次结果id
					this.rewardId = id;
					const arr = [...detail];
					for (let i = 0; i < arr.length; i++) {
						arr[i].goods_image = arr[i].goods_image.split(',')[0];
					}
					this.goods_list = arr;
					// 更新剩余重抽卡
					this.getPropList();
				}
			}
		}
	}

</script>

<style lang="scss" scoped>
	.container {
		width: 750rpx;
		height: 100vh;
		background: linear-gradient(90deg, #F2F0FF, #EDEBFF, #F3F8FF);
		z-index: 1;
		padding-bottom: calc(130rpx + env(safe-area-inset-bottom));
	}
	.body{
		overflow-y: scroll;
		height: 100%;
	}
	.swiper{
		width: 100%;
		pointer-events: none;
		.swiper-item {

		}
		.item-image {
			width: 335rpx;
			height: 335rpx;
		}
	}
	.page-background {
		position: absolute;
		top: 0;
		left: 0;
	}
	.barrage-class{
		width: 100%;
		position: fixed;
		top: 150rpx;
		z-index: 999;
		pointer-events: none;
	}
	::v-deep .u-navbar__content {
		/* background: none !important; */
		/* background: #fff; */
	}

	.goods-swiper {
		width: 100%;
		position: relative;
		margin-top: 15rpx;
		height: 750rpx;
	}

	.goods-swiper {
		width: 100%;
		z-index: 3;
		mix-blend-mode: darken;
	}



	.goods-background {
		width: 100%;
		z-index: 2;
		position: absolute;
	}

	.goods-info {
		width: 100%;
		height: 250rpx;
	}

	.goods-info-kinds-container {
		position: relative;
	}

	.goods-info-kinds {
		width: 375rpx;
		height: 42rpx;
		background: #edc9d7;
		clip-path: polygon(0 0, 100% 0, calc(100% - 40rpx) 100%, 40rpx 100%);
	}

	.goods-info-kinds_1 {
		width: 334rpx;
		height: 42rpx;
		background: #ea6e7a;
		position: relative;
		clip-path: polygon(0 0, 100% 0, calc(100% - 40rpx) 100%, 40rpx 100%);
	}

	.goods-info-kinds_1 image {
		width: 32rpx;
		height: 17rpx;
	}

	.arrow-reversal {
		transform: rotate(180deg);
		margin-right: 180rpx;
	}

	.info-kinds-name {
		font-size: 58rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
		-webkit-text-stroke: 3rpx #E96D79;
		text-stroke: 3rpx #E96D79;
		position: absolute;
		z-index: 1;
		top: -30rpx;
	}

	.goods-info-text {
		width: 100%;
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
		text-align: center;
		padding: 0 40rpx;
		margin-top: 25rpx;
	}

	.goods-info-price {
		font-size: 40rpx;
		font-family: Abel;
		font-weight: 900;
		color: #333333;
		margin-top: 28rpx;
	}

	.goods-info-price text {
		font-size: 26rpx;
		font-family: Source Han Sans CN;
		font-weight: 600;
		color: #333333;
	}

	.goods-tip {
		height: 125rpx;
		width: 100%;
		background: #fff;
	}

	.goods-tip text:first-child {
		font-size: 26rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}

	.goods-tip text:last-child {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #999999;
		margin-top: 20rpx;
	}
	.goods-remain {
		width: 100%;
		height: 70rpx;
		background: #3F3F42;
		padding: 0 30rpx;
		position: relative;
	}

	.goods-remain text {
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
	}

	.goods-remain-detail {
		font-size: 22rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
	}

	::v-deep .goods-remain-detail image {
		margin-left: 15rpx;
	}

	.goods-remain-tip {
		position: absolute;
		font-size: 20rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #DEDBDB;
		left: 130rpx;
	}

	.goods-rate-container {
		width: 690rpx;
		height: 170rpx;
		background: #FFFFFF;
		margin: 10rpx auto 17rpx auto;
		padding: 20rpx;
		padding-bottom: 10rpx;
	}

	.goods-rate-head {
		width: 100%;
		height: 95rpx;
	}

	.rate-head-title {
		font-size: 26rpx;
		font-family: BTH;
		font-weight: 400;
		color: #333333;
		min-width: 10%;
	}

	.rate-item-scroll {
		width: 90%;
		height: 100%;
		padding: 0 15rpx;
		display: flex;
		overflow-x: scroll;
	}

	.rate-item {
		min-width: 126rpx;
		height: 95rpx;
		/* background: #D76474; */
		margin-right: 20rpx;
		padding: 2rpx;
		flex: 1;
	}

	.rate-item-head {
		width: 100%;
		height: 60rpx;
		background: #FFFFFF;
		font-size: 28rpx;
		font-family: BTH;
		font-weight: 400;
		color: #D45567;
	}

	.rate-item-footer {
		width: 100%;
		height: 30rpx;
		font-size: 20rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
	}

	.goods-rate-fotter {
		font-size: 20rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #999999;
		width: 100%;
		height: calc(100% - 95rpx);
	}

	.buy-button {
		position: fixed;
		bottom: 0;
		left: 0;
		padding-bottom: env(safe-area-inset-bottom);
		width: 750rpx;
		height: calc(130rpx + env(safe-area-inset-bottom));
		background: #FFFFFF;
		box-shadow: 1rpx -4rpx 16rpx 0 rgba(30, 30, 30, 0.15);
	}
	.buy-back{
		position: absolute;
		top: 18rpx;
		right: 14rpx;
		width: 182rpx;
		height: 58rpx;
		background-size: 100% 100%;
		text-align: center;
		font-size: 12px;
		color: #ffffff;
		line-height: 49rpx;
	}

	.buy-button-detail {
		width: 690rpx;
		height: 98rpx;
		font-size: 38rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FBF8FF;
		text-shadow: 0 2rpx 0 rgba(28, 28, 27, 0.33);
		background: linear-gradient(99deg, #EB5C4A, #F6CA7C);
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-evenly;
		.free-text{
			margin-left: 10rpx;
			height: 40rpx;
			text-align: center;
			line-height: 40rpx;
			padding: 0 6rpx;
			background-color: #ea7416;
			opacity: 0.9;
			font-size: 11px;
			font-family: Source Han Sans CN;
			color: #FFFFFF;
		}
	}

	.buy-modal-content {
		width: 750rpx;
		background: linear-gradient(0deg, #FFFFFF 0%, #CAD0FF 100%);
	}

	.buy-head {
		width: 100%;
		height: 230rpx;
		position: relative;
	}


	.buy-head-bk-1 {
		width: 750rpx;
		height: 66rpx;
		position: absolute;
		right: 0;
		top: 0;
	}

	.buy-head-bk-2 {
		width: 318rpx;
		height: 329rpx;
		position: absolute;
		right: 0;
		top: -100rpx;
	}

	.buy-head-title {
		font-size: 74rpx;
		font-family: BTH;
		font-weight: 400;
		color: #503B33;
		position: absolute;
		top: 0;
		z-index: 1;
		padding-left: 30rpx;
	}

	.buy-modal-num {
		font-size: 113rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
		background: linear-gradient(0deg, #D83C1B 0%, #F0762F 100%);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
	}

	.buy-head-tip {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #999999;
	}

	.buy-head-title view:first-child {
		height: 88rpx;
		margin-top: 20rpx;
	}

	.buy-body {
		width: 100%;
		padding: 0 30rpx;
	}

	.buy-num-item {
		width: 100%;
		height: 145rpx;
		background: linear-gradient(0deg, #FBF7FF 0%, #F8E2FF 100%);
		margin-bottom: 30rpx;
	}

	.buy-item-left {
		width: 195rpx;
		height: 100%;
		background: linear-gradient(99deg, #EB5C4A 0%, #8F09E6 0%, #B546FF 100%);
		clip-path: polygon(0 0, 100% 0, calc(100% - 20rpx) 100%, 0 100%);
		padding-right: 20rpx;
	}

	.buy-item-left view {
		width: 88rpx;
		font-size: 44rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
	}

	.buy-item-right {
		height: 100%;
		width: calc(100% - 195rpx);
		position: relative;
	}

	.buy-item-right text:first-child {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}

	/* .buy-item-right text:nth-child(2) {
		font-size: 36rpx;
		font-family: Abel;
		font-weight: 400;
		color: #333333;
		line-height: 40rpx;
	}
 */
	/* .buy-item-right text:last-child {
		font-size: 24rpx;
		font-family: Abel;
		font-weight: 400;
		text-decoration: line-through;
		color: #333333;
		opacity: 0.6;
		margin-left: 10rpx;
	} */

	.buy-item-right-top {
		position: absolute;
		top: 0;
		right: 0;
		width: 128rpx;
		height: 36rpx;
		background: #EA6E7A;
		font-size: 22rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
		clip-path: polygon(5rpx 0, 100% 0, 100% 100%, 0 100%, 5rpx 70%);
		padding-left: 5rpx;
	}

	.fair-open {
		width: 66rpx;
		height: 190rpx;
		position: fixed;
		right: 0;
		top: 578rpx;
		z-index: 200;
	}

	.music {
		position: fixed;
		right: 5px;
		top: 250rpx;
		width: 80rpx;
		height: 86rpx;
		z-index: 200;
	}
	.music-img {
		width: 80rpx;
		height: 86rpx;
		animation: rotateVbtn 5s linear infinite 800ms 0 ease;
		animation: rotateVbtn 5s linear infinite;
	}
	.stop-music {
		width: 80rpx;
		height: 86rpx;
	}
	.right-menu{
		width: 100rpx;
		// height: 240rpx;
		position: fixed;
		right: 0;
		top: 350rpx;
		z-index: 200;
		.item-class{
			width: 100%;
			height: 126rpx;
			margin-bottom: 30rpx;
		}
		.menu-img{
			width: 80rpx;
			height: 86rpx;
		}
		.menu-img2{
			width: 87rpx;
			height: 92rpx;
		}
		.menu-text{
			margin-top: -16rpx;
			width: 100%;
			height: 44rpx;
			background-color: #8D01E6;
			border-radius: 44rpx;
			opacity: 0.8;
			font-size: 22rpx;
			font-family: PingFang SC-Medium, PingFang SC;
			color: #FFFFFF;
			font-weight: 400;
			text-align: center;
			line-height: 44rpx;
		}
	}
	.free {
		width: 102rpx;
		height: 116rpx;
		position: fixed;
		right: 0;
		top: 320rpx;
		z-index: 200;
	}

	@-webkit-keyframes rotateVbtn {
		0% {

			transform: rotate(0)
		}

		100% {

			transform: rotate(360deg)
		}
	}

	.goods-item-image {
		width: 500rpx;
		height: 500rpx;
	}

	.box-k {
		position: fixed;
		z-index: 1000;
		height: 20%;
		width: 100%;
		top: 0;
		background: rgba(0, 0, 0, 0.7);
		// background-color: rgba(0, 0, 0, 0.5);
	}

	.order-view {
		width: 100%;
		height: 80vh;
		z-index: 1000;
	}

	.text-margin {
		margin: 10rpx 0 0 10rpx;
	}

	.openBox {
		position: fixed;
		z-index: 500;
		top: 0;
		height: 100%;
		background: rgba(0, 0, 0, 0.7);
	}

	.reward {
		width: 100%;
		height: 100%;
		background-size: 100% 100%;
		position: fixed;
		z-index: 500;
		top: 0;
		left: 0;
		background: rgba(0, 0, 0, 0.7);
	}
	.lottery-view{
		width: 100%;
		height: 100%;
		background-size: 100% 100%;
		position: fixed;
		z-index: 500;
		top: 0;
		left: 0;
		background: rgba(0, 0, 0, 0.7);
	}
	.fair {
		padding: 30rpx;
	}

	.fair-title {
		font-size: 48rpx;
		color: #333;
	}

	.seed {
		background: #FAF9F9;
		margin-top: 40rpx;
		padding: 32rpx;
	}

	.seed-title {
		font-size: 28rpx;
		font-weight: 600;
		height: 40rpx;
		line-height: 40rpx;
		color: #333;
	}

	.seed-body {
		display: flex;
		align-items: center;
		margin: 14rpx 0;
	}

	.seed-content {
		width: 456rpx;
		height: 70rpx;
		line-height: 70rpx;
		padding-left: 24rpx;
		font-size: 28rpx;
		background: #EAEAEA;
		color: #333;
	}

	.seed-change {
		font-size: 28rpx;
		width: 170rpx;
		background: #3F3F42;
		color: #fff;
		text-align: center;
		height: 70rpx;
		line-height: 70rpx;
		/* position: relative;
		left: -22rpx; */
	}

	.seed-time {
		font-size: 28rpx;
		color: #333;
		width: 626rpx;
		border: 2rpx solid #EAEAEA;
		height: 72rpx;
		line-height: 72rpx;
		padding-left: 25rpx;
		margin-top: 12rpx;
		display: flex;
	}

	.seed-num {
		margin-left: 20rpx;
	}

	.seed-rule {

		font-size: 28rpx;
		color: #8D01E6;
		margin-top: 40rpx;
	}

	.last-title {
		width: 336rpx;
		height: 36rpx;
		line-height: 36rpx;
	}

	.rule-text {
		font-size: 24rpx;
		color: #666666;
		margin-top: 28rpx;
	}

	.know {
		width: 690rpx;
		height: 98rpx;
		background: #3F3F42;
		text-align: center;
		line-height: 98rpx;
		font-size: 28rpx;
		color: #fff;
		margin-top: 40rpx;
	}

	.change-seed {
		height: 264rpx;
		width: 500rpx;
		text-align: center;
		padding: 20rpx;
		background: #FAF9F9;
	}

	.change-title {
		font-size: 30rpx;
		color: #333;
		height: 60rpx;
		line-height: 60rpx;
		font-weight: 600;
	}

	.change-sure {
		line-height: 25px;
		width: 300rpx;
		background: #333;
		color: #fff;
		font-size: 28rpx;
		height: 50rpx;
		left: 0;
		right: 0;
		margin: auto;
		margin-top: 38rpx;
	}

	.model-bg {
		z-index: 1000;
		background: rgba(0, 0, 0, 0.7);
	}

	.icon-img {
		width: 318rpx;
		height: 329rpx;
	}

	.head-img {
		width: 750rpx;
		height: 66rpx;
	}

	.fair-img {
		width: 66rpx;
		height: 131rpx;
	}

	.back1-img {
		width: 750rpx;
		height: 503rpx;
	}

	.back-img {
		width: 620rpx;
		height: 620rpx;
	}

	.arrow-img {
		width: 32rpx;
		height: 17rpx;
	}
</style>