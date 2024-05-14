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
			<view v-if="isFree" class="item-class column-center-flex" @click="useFreeOrder">
				<!-- #ifdef APP-PLUS || H5 -->
				<img :src="GoodsRightMenu.free" alt="" class="menu-img2">
				<!-- #endif -->
				<!-- #ifdef MP-WEIXIN -->
				<image :src="GoodsRightMenu.free" mode="" class="menu-img2"></image>
				<!-- #endif -->
				<view class="menu-text">免单</view>
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
							<!-- showLoading-是否显示加载中的图标或者自定义的slot 默认true -->
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

										<!-- #ifdef APP-PLUS -->
										<img src="../../static/image/goods/arrow_2.png" alt=""
											class="arrow-img arrow-reversal">
										<!-- #endif -->
										<!-- #ifdef MP-WEIXIN -->
										<image src="../../static/image/goods/arrow_2.png" mode=""
											class="arrow-img arrow-reversal"></image>
										<!-- #endif -->
										<!-- #ifdef APP-PLUS -->
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
			<view v-if="showGuarantee" class="min-guarantee" @click="openGuarantRules">
				<view class="min-guarantee_text1">保底</view>
				<view class="min-guarantee_text2">{{ guarantee.title }}</view>
				<view class="min-guarantee_text3">已抽 {{guarantee.buy_num + '/' + guarantee.trigger_num}}</view>
				<image :src="MinGuarantee.icon" mode="widthFix"></image>
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
			<view class="buy-button-detail main-center-flex" @tap="buyBox">
				买1抽 ¥{{money}}
			</view>
		</view>

		<u-popup :show="buyVisible" mode="bottom" @close="buyVisible = !buyVisible">
			<view class="buy-modal-content">
				<view class="buy-head">
					<view class="buy-head-title">
						<view>
							公平开盒
						</view>
						<view style="font-size: 47rpx;">
							精选商品速速来抽
						</view>
						<view class="buy-head-tip">
							满意即可发货，不满意可取消
						</view>
					</view>
					<view class="buy-head-bk-1">
						<image src="../../static/image/goods/head-bg.png" mode="" class="head-img"></image>
					</view>
					<view class="buy-head-bk-2">
						<image :src="buy_head_bk_1" mode="" class="icon-img"></image>
					</view>
				</view>
				<view class="buy-body">
					<view class="buy-num-item main-start-flex" v-for="(item,index) in isolate" :key="index"
						@click="goPayPage(item.num)">
						<view class="buy-item-left column-center-flex"
							:style="{'background':index==(isolate.length-1)?rareLeft:commonLeft}">
							<view>
								{{item.name}}
							</view>
						</view>
						<view class="buy-item-right main-center-flex"
							:style="{'background':index==(isolate.length-1)?rareRight:commonRight}">
							<view class="main-center-align-end">
								<text>￥</text>
								<text>{{money*item.num}}</text>
							</view>
						</view>
					</view>
				</view>
			</view>
		</u-popup>
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
		<u-popup v-if="showGuarantee" :show="guarantOpening" mode="bottom" @close="closeGuarant">
			<view class="guarant-popup">
				<view class="main-space-between">
					<view class="fair-title">
						规则说明
					</view>
					<u-icon name="close" @click="closeGuarant" color="#333"></u-icon>
				</view>
				<view class="guarant-rules">
					<view class="rules-title">
						<text class="num-class">{{ guarantee.trigger_num  }}</text>发必出稀有款或以上
					</view>
					<view class="rules-text">
						该盒子每抽<text>{{ guarantee.trigger_num }}</text>次，必出
						<view class="epic">{{ guarantee.tag_name }}</view>
						或以上的物品。触发保底时，会将普通款物品升级为隐藏款，隐藏款物品的等级均为稀有或以上。
					</view>
				</view>
				<view class="guarant-rules">
					<view class="rules-title">隐藏款物品</view>
					<view class="rules-text">
						触发升级的隐藏款物品来源于盲盒商品池外，不影响开盒概率，物品等级最低为稀有款
					</view>
				</view>
				<view class="know" @click="closeGuarant">
					知道了
				</view>
			</view>
		</u-popup>
		<u-loading-page :loading="loadingPage" loading-text="加载中..."></u-loading-page>

		<u-popup :show="orderVisible" mode="bottom" @close="closeOrderPopup">
			<order-sure ref="order" class="order-view" :blindbox_id="blindbox_id" :box_name="box_name" :num="goods_num" :type="type" 
				@closeOrder="closeVisible" @useCouponCode="useCouponCode">
			</order-sure>
		</u-popup>
		<openBox v-if="show" class="openBox" ref="openBoxs"></openBox>
		<!-- <rewardRes v-if="goodsShow" class="reward" :style="{'background-image': 'url('+GoodsPoolImg.lotteryBack+')'}"
			:goods_list="goods_list" :poolList="poolList" :goods_num="goods_num" :propList="propList" :colorObj="color" 
			@onceMore="onceAgain" @useProp="toUseProp">
		</rewardRes> -->
		<!-- 免单结果 -->
		<free-reward-res v-if="isShowFree" class="reward" :goods_list="freeResList" @onceMore="onceAgainFree">
		</free-reward-res>
		<goods-share v-if="blindbox_id" ref="goodsShare" :postersData="postersData" :blindId="blindbox_id"></goods-share>
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
		getMusic,
		goodsInfo
	} from '@/api/goods.js';
	import {
		create_order,
		pay_order,
		order_trail,
		getReward
	} from '@/api/pay.js';
	import { getMyProp, useProp, checkFreeChance, useFreeChance} from '@/api/coupon.js';
	import orderSure from '@/components/order-sure/index.vue';
	import GoodsDetail from '@/components/goods-detail/index.vue';
	import GoodsShare from '@/components/goods-detail/goods-share.vue';
	import OpenBox from '@/components/open-box/index.vue';
	import rewardRes from '@/components/reward-res/index.vue';
	import FreeRewardRes from '@/components/reward-res/free-index.vue';
	import BarrageBar from '@/components/winning-barrage/barrage-bar';
	import { GoodsRightMenu, PoolsImgChinese, PoolsResultFont, MinGuarantee, GoodsShareBack, GitLog } from '@/utils/objectValue';
	export default {
		components: {
			GoodsDetail,
			orderSure,
			OpenBox,
			rewardRes,
			FreeRewardRes,
			GoodsShare,
			BarrageBar
		},
		data() {
			return {
				GoodsRightMenu,
				PoolsImgChinese,
				PoolsResultFont,
				GitLog,
				MinGuarantee,
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
				money: 0,
				goods_list: [],
				propList: [],
				goodsShow: false,
				order_num: '',
				show: false,
				refreshPage: false,
				commonLeft: 'linear-gradient(99deg, #EB5C4A 0%, #8F09E6 0%, #B546FF 100%)',
				rareLeft: 'linear-gradient(99deg, #FFFFFF 0%, #EB5C4A 0%, #F6CA7C 100%)',
				commonRight: 'linear-gradient(0deg, #FBF7FF 0%, #F8E2FF 100%)',
				rareRight: 'linear-gradient(0deg, #FFFCF7 0%, #F7D8B8 100%)',
				orderVisible: false,
				isolate: [{
						name: '一发入魂',
						price: "195",
						cost_price: '300',
						save_money: '105',
						num: 1
					},
					{
						name: '欧气三连',
						price: "570",
						cost_price: '900',
						save_money: '325',
						num: 3
					},
					{
						name: '霸气五连',
						price: "1000",
						cost_price: '1500',
						save_money: '500',
						num: 5
					},
					{
						name: '壕气十连',
						price: "1800",
						cost_price: '3000',
						save_money: '1200',
						num: 10
					}
				],
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
				couponCode: '',
				curItemId: '',
				current: 0,
				isFree: false, // 是否有面单机会
				isShowFree: false,
				freeList: [], // 免单机会
				freeResList: [], // 免单结果
				postersData: {},
				rewardList: [],
				isShowLottery: false,
				poolList: [],
				guarantOpening: false,
				guarantee: {},
				guarantRes: [],
				textShadow: {
					'普通款': '0px 0px 5px #1D5DED',
					'传说款': '0px 0px 5px #841DC1',
					'史诗款': '0px 0px 5px #7A26F0',
					'稀有款': '0px 0px 5px #004785',
				},
				// -webkit-text-stroke
				stroke: {
					1: '1.2px #1D5DED',
					2: '1.2px #841DC1',
					3: '1.2px #7A26F0',
					4: '1.2px #004785',
				},
				_audioContext: null
			}
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght',
			}),
			showGuarantee() {
				return this.guarantee && Object.keys(this.guarantee).length;
			}
		},
		onShow() {
			const pages = getCurrentPages();
			const currPage = pages[pages.length - 1]; // 当前页面
			if(currPage.data != undefined){
				let json = currPage.data.testdata;
				console.log('json-', json);
			}
		},
		async onLoad(parms) {
			// '/plugins/goods-detail/index?id=' + item.id + '&money=' + item.price + '&src=' + this.musicSrc + '&goods_name=' + item.name
			const {scene} = parms;
			console.log('parms-', parms);
			// spId=TVRnPQ== & bId=1
			let bId = ''; // 盲盒id
			// 二维码进入
			if(scene) {
				const info = decodeURIComponent(scene);
				const arr = info.split('&');
				bId = arr[1].substring(4);
				uni.setStorageSync('agent_user_id', arr[0].substring(5));
			} else {
				// 列表跳转进入
				bId = parms.id;
			}
			this.blindbox_id = bId;
			this.getBlindDetail(bId);
			this.getMusicInfo(); // 新增
			
			setInterval(() => {
				this.currentTime = this.timeChange(Date.now())
				this.numberGro = this.numGro()
			}, 10)
			this.checkIsFree();
			this.$nextTick(() => {
				this.$refs.barrage.initBarrage();
			})
		},
		onHide() {
			this.refreshPage = false
			this.stop()
		},
		onUnload() {
			this._audioContext && this._audioContext.destroy();
			this.$refs.barrage.close();
		},
		onPageScroll(e) {
			let query = uni.createSelectorQuery().in(this);
			//需要给黄色区域设置一个id标识，在这里是demo
			query.select('.goods-tip').boundingClientRect(data => {
				this.isNavWhite = data.top <= 88

			}).exec();
		},
		destroyed() {
			this.stop()
		},
		methods: {
			// 获取背景音乐
			async getMusicInfo() {
				const {code, data} = await getMusic();
				if(code == 0) {
					this.musicSrc = data;
					this._audioContext = null;
					this.createAudio();
				}
			},
			sharePosters() {
				const posterObj = {
					back: GoodsShareBack, // 背景
					postersData: this.postersData // 海报数据
				};
				this.$nextTick(() => {
					this.$refs.goodsShare.open(posterObj);
				});
			},
			openGuarantRules() {
				this.guarantOpening = true;
			},
			closeGuarant() {
				this.guarantOpening = false;
			},
			// 检测是否可以免单
			async checkIsFree(onecMore) {
				const {code, data = []} = await checkFreeChance({blindbox_id: this.blindbox_id});
				if(code == 0 && data.length) {
					// 保存免单机会
					this.freeList = data;
					this.isFree = true;
					if(onecMore) {
						this.useFreeOrder();
					}
				} else {
					this.isFree = false;
				}
			},
			// 使用免单
			async useFreeOrder() {
				this.show = true;
				const {code, data = {}} = await useFreeChance({id_code: this.freeList[0].id_code})
				if(code == 0) {
					const {record_id} = data;
					this.getFreeResult(record_id);
				}
			},
			// 获取免单结果
			async getFreeResult(record_id) {
				const {code, data = {}} = await getReward({reward_id: record_id});
				this.show = false;
				this.isShowFree = true;
				if(code == 0) {
					const {id, detail = []} = data;
					const arr = [...detail];
					for (let i = 0; i < arr.length; i++) {
						arr[i].goods_image = arr[i].goods_image.split(',')[0];
					}
					this.freeResList = arr;
				}
			},
			// 再来一次免单
			async onceAgainFree() {
				this.isShowFree = false;
				this.show = true;
				this.checkIsFree(true);
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
			shareGoods() {},
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
			fairOpen() {
				this.fairOpening = true
				this.getHashKey()
			},
			onceAgain(val) {
				this.orderVisible = false
				this.show = false
				this.goodsShow = false
				if (val == 1) {
					this.closeVisible('', this.payParms, val)

				}
				let data = this.payParms
				data.use_integral = 2
				if (val != 1) {
					uni.showLoading({
						title: '',
						mask: true
					});
				}
				create_order({
					...data,
					pay_way: '4',
					coupon_code: this.couponCode || ''
				}).then(res => {
					if (res.code == 0) {
						let platform = ''
						// #ifdef APP-PLUS
						platform = 'app'
						// #endif
						// #ifdef MP-WEIXIN
						platform = 'miniapp'
						// #endif
						pay_order({
							order_no: res.data.order_no,
							platform
						}).then(res_order => {
							this.order_num = res.data
								.order_no
							if (val != 1) {
								this.closeVisible(res.data.order_no, this.payParms, val)
								uni.hideLoading();
							}

						}).catch(err_order => {
							uni.$u.toast(err_order.msg)
						})
					} else {
						uni.$u.toast(res.msg)
					}
				}).catch(err => {
					uni.$u.toast(err.msg)
				})
			},
			buyBox() {
				if (this.goodsList.length) {
					this.buyVisible = !this.buyVisible
				} else {
					uni.showToast({
						icon: 'none',
						title: '此盲盒内没有商品，请换个盲盒购买'
					})
				}

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
			getOrderRes(type) {
				order_result({
					order_no: this.order_num
				}).then(res => {
					const {code, data = {}} = res;
					if (code == 0) {
						const {id, detail = []} = data;
						this.rewardId = id
						this.goods_list = detail.filter(item => {
							return item.source !== 2;
						});
						setTimeout(() => {
							this.show = false
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
			// 支付订单 val---order_no  params---payParms
			closeVisible(val, params, type) {
				if (type == 1) {
					this.order_num = null
				}
				this.payParms = params
				// this.show = true
				this.orderVisible = false
				this.order_num = val
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
						this.rewardId = id;
						// detail中包含 中奖结果---source=1 赠品---source=2
						this.goods_list = [...detail];
						this.getPropList();

						console.log('ppp-', this.payParms);
						// 设置支付参数
						this.$goods.setPayParams(this.payParms);
						this.$store.commit('goods/setPoolsDetails', {poolList: this.poolList, probabilityList: this.probabilityList});
						// 筛选出赠品
						const guarantRes = detail.filter(item => {
							return item.source == 2;
						});
						if(guarantRes.length) {
							this.$store.commit('goods/setGoodsDetails', {goods_list: this.goods_list, guarantRes});
						} else {
							this.$store.commit('goods/setGoodsDetails', {goods_list: this.goods_list});
						}
						// &order_num=${this.order_num}
						uni.navigateTo({
							url: `/plugins/result-box/index?id=${this.blindbox_id}&num=${this.goods_num}`
						});

						// setTimeout(() => {
						// 	uni.$emit('cdata', {
						// 		id: this.blindbox_id, // 盲盒id
						// 		order_num: this.order_num, // 订单号
						// 		num: this.goods_num // 抽数
						// 	});
						// }, 600);
						
					} else {
						this.getOrderRes(type)
					}
				})
				if (type != 1) {
					setTimeout(() => {
						uni.hideLoading();
						this.show = false
						this.goodsShow = true
					}, 2000)
				}
			},
			useCouponCode(value) {
				this.couponCode = value || '';
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
			},
			async getBlindDetail(id) {
				uni.showLoading({
					title: '',
					mask: true
				});
				const {code, data} = await get_blind_detail({id});
				uni.hideLoading();
				if (code == 0) {
					const {guarantee = null, info = {}, list = [], probability = []} = data;
					console.log('data-', data);
					this.guarantee = guarantee;
					list.forEach(item => {
						item.pic = item.goods_image.split(',')[0]
					});
					this.box_name = info.name;
					this.money = info.price;
					this.postersData = {...data};
					this.goodsList = list;
					this.rewardList = [...list];
					this.poolList = [...list];
					this.isShowLottery = true;
					this.probabilityList = probability;
					this.processingStyles(probability);
					this.loadingPage = false;
				} else {
					uni.switchTab({
						url: '/pages/index/index'
					})
				}
			},
			processingStyles(styleList) {
				for (var i = 0; i < styleList.length; i++) {
					if (styleList[i].tag == '稀有款') {
						this.color.rare_color = styleList[i].color
					} else if (styleList[i].tag == '史诗款') {
						this.color.epic_color = styleList[i].color
					} else if (styleList[i].tag == '传说款') {
						this.color.lore_color = styleList[i].color
					} else {
						this.color.common_color = styleList[i].color
					}
				}
			},
			goPayPage(val) {
				this.goods_num = val
				let param = {
					blindbox_id: this.blindbox_id,
					num: val,
					type: this.type
				}
				this.buyVisible = false
				this.orderVisible = true
				this.$nextTick(() => {
					this.$refs.order.initData(param)
				})
				// setTimeout(() => {
				// 	uni.$emit('cdata', {
				// 		id: this.blindbox_id,
				// 		num: val
				// 	});
				// }, 600);
			},
			closeOrderPopup() {
				this.orderVisible = false;
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
	.min-guarantee{
		width: 100%;
		height: 84rpx;
		display: flex;
		justify-content: space-around;
		align-items: center;
		background-color: #3F3E43;
		view{
			color: #FFFFFF;
		}
		.min-guarantee_text1{
			width: 100rpx;
			padding-left: 30rpx;
			font-size: 16px;
			font-weight: 600;
		}
		.min-guarantee_text2{
			width: 60%;
			padding-left: 60rpx;
			font-size: 12px;
			opacity: 0.8;
		}
		.min-guarantee_text3{
			font-size: 13px;
		}
		image{
			width: 12px;
			// height: 12px;
		}
	}
	.guarant_box{
		width: 90vw;
		max-height: 60vh;
		padding: 10rpx 20rpx;
		background-size: 100% 100%;
		-webkit-filter: drop-shadow(0px 0px 70px #e779fc);
		filter: drop-shadow(0px 0px 70px #e779fc);

		.inner_box{
			width: 100%;
			height: 100%;
			// border: 1px solid #f7cfff;
			// box-shadow: 0px 0px 70px 0px #e779fc;
			// background-color: rgba(124, 78, 255, 0.37);
			// -webkit-clip-path: polygon(23% 1%, 1% 17%, 0% 99%, 100% 100%, 100% 17%, 76% 0%);
			// clip-path: polygon(23% 1%, 1% 17%, 0% 99%, 100% 100%, 100% 17%, 76% 0%);
			// border-radius: 50rpx;
			// background: 
			// radial-gradient(circle at top left, transparent 50px, rgba(124, 78, 255, 0.37) 0) top left, 
			// radial-gradient(circle at top right, transparent 50px, rgba(124, 78, 255, 0.37) 0) top right, 
			// radial-gradient(circle at bottom right, transparent 0px, rgba(124, 78, 255, 0.37) 0) bottom right, 
			// radial-gradient(circle at bottom left, transparent 0px, rgba(124, 78, 255, 0.37) 0) bottom left;
			// background-size: 50% 50%;
			// background-repeat: no-repeat;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
		.top-area{
			width: 100%;
			height: 80rpx;
		}
		.star-class {
			width: 17rpx;
			height: 18rpx;
			background-color: rgba(255, 255, 255, 0.7);
			clip-path: polygon(48% 0, 58% 30%, 98% 35%, 58% 45%, 49% 77%, 38% 44%, 2% 35%, 38% 30%);
		}
		.guarant-title{
			width: 220rpx;
			text-align: center;
			font-family: BTH;
			font-size: 26px;
			color: #ffffff;
		}
		.res-area{
			width: 100%;
			height: calc(100% - 200rpx);
			margin-top: 40rpx;
			overflow-y: auto;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-wrap: wrap;
			// height: calc(100% - 80rpx);
		}
		.bottom-area{
			height: 80rpx;
			// padding: 20rpx 0;
			text-align: center;
			line-height: 60rpx;
			font-size: 16px;
			color: #ffffff;
			view{
				padding: 0 4rpx;
				font-size: 26px;
				font-family: BTH;
			}
		}

		.normal {
			margin-bottom: 20rpx;
			margin-right: 20rpx;
			width: 238rpx;
			height: 330rpx;
			text-align: center;
			background-size: 100% 100% !important;
			.goods-img{
				width: 100%;
				padding: 20rpx;
				height: 160rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: transparent;
				image{
					width: 140rpx;
					height: 140rpx;
				}
			}
			.goods-detail{
				width: 100%;
				height: calc(100% - 70rpx - 160rpx);
				color: #242424;
				font-size: 24rpx;
				text-align: center;
				display: flex;
				flex-direction: column;
				justify-content: center;
				padding: 0 24rpx;
			}
		}
		.normal2 {
			margin-bottom: 20rpx;
			margin-right: 20rpx;
			width: 214rpx;
			height: 297rpx;
			text-align: center;
			background-size: 100% 100% !important;
			.goods-img{
				width: 100%;
				padding: 20rpx;
				height: 130rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: transparent;
				image{
					width: 110rpx;
					height: 110rpx;
				}
			}
			.goods-detail{
				width: 100%;
				height: calc(100% - 70rpx - 130rpx);
				color: #242424;
				font-size: 24rpx;
				text-align: center;
				display: flex;
				flex-direction: column;
				justify-content: center;
				padding: 0 24rpx;
			}
		}
		.goods-type{
			width: 100%;
			height: 70rpx;
			padding: 2 20rpx 10rpx 8rpx;
			display: flex;
			justify-content: space-between;
		}
		.guarant-log{
			width: 59rpx;
			height: 59rpx;
			background-size: 100% 100%;
			text-align: center;
			font-size: 11px;
			font-family: PingFang SC-Semibold, PingFang SC;
			color: #FFFFFF;
			// background:: linear-gradient(-45deg, transparent 50%, rgba(132, 50, 246, 0.5) 50%, rgba(132, 50, 246, 0.5) 100%);
			// background: linear-gradient(to top left, transparent 0%, #fff 49.9%, #8432F6 50%, #8432F6 100%);
		}
		.goods-title{
			width: 131rpx;
			height: 45rpx;
			padding-top: 10rpx;
			background-size: 100% 100%;
			text{
				font-size: 46rpx;
				font-family: BTH;
				text-align: right;
				font-weight: 400;
				color: #FFFFFF;
				line-height: 48rpx;
			}
		}
		.goods-name{
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			text-align: center;
			font-family: Source Han Sans CN;
			font-size: 12px;
			font-family: PingFang SC-Regular, PingFang SC;
			color: #FFFFFF;
		}
		.goods-price{
			color: #FFFFFF;
			margin-top: 10rpx;
			font-size: 20px;
			font-family: BTH;
			font-weight: 400;
		}
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

	.buy-button-detail {
		width: 690rpx;
		height: 98rpx;
		font-size: 38rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FBF8FF;
		text-shadow: 0 2rpx 0 rgba(28, 28, 27, 0.33);
		background: linear-gradient(99deg, #EB5C4A, #F6CA7C);
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
	.free-play{
		position: fixed;
		left: 0px;
		top: 250rpx;
		width: 100rpx;
		z-index: 200;
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
			height: 63px;
			margin-bottom: 15px;
		}
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
		margin-top: -8px;
		width: 100%;
		height: 22px;
		background-color: #8D01E6;
		border-radius: 22px;
		opacity: 0.8;
		font-size: 11px;
		font-family: PingFang SC-Medium, PingFang SC;
		color: #FFFFFF;
		font-weight: 400;
		text-align: center;
		line-height: 22px;
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
	.guarant-popup{
		padding: 30rpx;
		.guarant-rules{
			margin-top: 40rpx;
		}
		.rules-title{
			font-size: 28rpx;
			color: #8D01E6;
		}
		.epic{
			display: inline-block;
			color: #FFFFFF;
			background-color: #dd648f;
			-webkit-transform: skewX(-45deg);
			transform: skewX(-15deg);
			font-size: 24rpx;
			margin: 0 5rpx;
			padding: 4rpx;
		}
		.num-class{
			font-size: 30rpx;
		}
		.rules-text{
			font-size: 24rpx;
			color: #666666;
			margin-top: 28rpx;
		}
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

