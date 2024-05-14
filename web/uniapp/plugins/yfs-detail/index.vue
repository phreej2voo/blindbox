<template>
	<view class="container">
		<scroll-view class="body" enableFlex scroll-y @scrolltolower="getMoreData" @refresherpulling="refresherpulling">
			<view class="top-area main-center-flex">
				<image :src="picInfo.pic" mode="heightFix"></image>
			</view>
			<view class="box-area" :style="{'background-image': 'url('+boxBg+')'}">
				<view class="change-box" @click="changeBox">
					<image :src="change"></image>
					<text>换箱</text>
				</view>
				<view v-if="showSeriesInfo" class="content">
					<view class="main-center-flex first_line">{{ picInfo.name }}</view>
					<view class="second_line">
						<view class="second-view">
							<text class="white-text">剩余</text>
							<text class="green-text">{{seriesInfo.left_num}}</text>
							<text class="white-text">{{'/' + seriesInfo.total_num + '张'}}</text>
						</view>
						<view class="second-view">
							<text class="white-text">第</text>
							<text class="green-text">{{seriesInfo.now_box}}</text>
							<text class="white-text">{{'/' + seriesInfo.max_box_no + '箱'}}</text>
						</view>
					</view>
					<view class="third_line">
						<view v-if="showLast" class="third-view" @click="lastBox">
							<image :src="left"></image>
							<text class="margin1">上一箱</text>
						</view>
						<view v-else class="third-view"></view>
						<view v-if="showNext" class="third-view" @click="nextBox">
							<text>下一箱</text>
							<image class="margin1" :src="right"></image>
						</view>
					</view>
				</view>
			</view>
			<view class="list-class">
				<view v-for="(item,index) in goodsList" :key="index" class="item-class" @click="openGoodsDetail(item)">
					<view class="item-top main-center-flex">
						<view class="goods-tag" :class="item.tag_name === 'LAST' ? 'last' : 'probability'">
							{{ item.tag_name === 'LAST' ? item.tag_name + '赏' : item.probability + '%' }}
						</view>
						<view v-if="item.stock <= 0" class="sell-out">售罄</view>
						<!-- 由于在nvue下，u-image名称被uni-app官方占用，在nvue页面中请使用u--image名称，在vue页面中使用u--image或者u-image均可 -->
						<u--image :showLoading="true" :src="item.goods_image" :width="itemImgSize" :height="itemImgSize"
							mode="scaleToFill">
						</u--image>
						<view class="tag-title">{{ item.stock + '/' + (item.total_stock) }}</view>
					</view>
					<view class="item-bottom">
						<view>{{ item.goods_name }}</view>
						<view>{{ '官方零售价：' + item.price }}</view>
						<view>{{'赏级：' + item.tag_name}}</view>
					</view>
				</view>
			</view>
		</scroll-view>

		<view class="menu">
			<view :style="{'background-image': 'url('+menuBg+')'}" class="menu-text" @click="openMenu(1)">中奖记录</view>
			<view :style="{'background-image': 'url('+menuBg+')'}" @click="openMenu(2)">
				<image :src="refresh" class="img1"></image>
				<text class="menu-text2">刷新</text>
			</view>
			<!-- #ifdef MP-WEIXIN -->
			<view :style="{'background-image': 'url('+menuBg+')'}" @click="openMenu(3)">
				<button plain type="default" class="share-btn" data-name="yfsShare" open-type="share">
					<image :src="share" class="img2"></image>
					<text class="menu-text2">分享</text>
				</button>
			</view>
			<!-- #endif -->
			<!-- #ifdef APP-PLUS || H5 -->
			<!-- <view :style="{'background-image': 'url('+menuBg+')'}" @click="h5CreateCode">
				<image :src="share" class="img2"></image>
				<text class="menu-text2">分享</text>
			</view> -->
			<!-- #endif -->
		</view>
		<movable-area class="movable-area">
			<movable-view class="movable-view" x="650rpx" y="900rpx" direction="all">
				<view :style="{'background-image': 'url('+shangdai+')'}" class="shang-dai" @click="toOrderPage">
					赏袋
				</view>
			</movable-view>
		</movable-area>
		<!-- 底部抽赏按钮 -->
		<view class="draw-area">
			<image :src="titleImg" class="title-img"></image>
			<view class="count">
				<view v-for="(item,index) in btnList" :key="index" :style="{'background-image': 'url('+countBg+')'}"
					class="count-class main-center-flex" @click="drawGoods(1,item)">
					{{ item.label }}
				</view>
			</view>
			<view class="btn-area main-center-flex">
				<view class="btn" :style="{'background-image': 'url('+bthBg+')'}" @click="drawGoods(2)">
					余箱全收
				</view>
			</view>
		</view>

		<pay-order ref="payPopup" :orderVisible="orderVisible" @openShipping="openShipping" @closeOrder="closePayOrder"></pay-order>
		<shipping-notice :shippingVisible="shippingVisible" :shippingType="play_id" 
			@closeShipping="closeShipping">
		</shipping-notice>
		
		<winning-record ref="recordPopup" :recordVisible="recordVisible" :recordType="play_id"
			@closeOrder="closeRecord">
		</winning-record>
		
		<goods-detail :detailShow="detailShow" :goodsDetail="detailInfo" @close="closeDetail"></goods-detail>
		
		<u-popup :show="rewardVisible" mode="center" :safeAreaInsetBottom="false" @close="closeReward">
			<view class="reward-container">
				<view class="inner">
					<image :src="reward_close" class="close" @click="closeReward"></image>
					<view class="reward-title">中奖详情</view>
					<scroll-view v-if="rewardList.length" :style="{'background-image': 'url('+reward_detail+')'}"
						class="detail-view" enableFlex scroll-y>
						<view class="grid-view">
							<view v-for="(item,index) in rewardList" :key="index" class="detail-item">
								<view class="goods-tag last">
									{{ item.tag_name + '赏' }}
								</view>
								<view class="detail_1 main-center-flex">
									<image :src="item.goods_image" width="150rpx" height="150rpx" mode="scaleToFill">
									</image>
								</view>
								<view class="detail_2">
									<text>{{ item.goods_name }}</text>
									<text>{{ '官方零售价：￥' + item.goods_price }}</text>
								</view>
							</view>
						</view>
					</scroll-view>
					<view :style="{'background-image': 'url('+reward_check+')'}" class="check" @click="toWarehouse">去查看
					</view>
				</view>
			</view>
		</u-popup>

		<!-- 分享二维码 -->
		<template v-if="canvasStatus">
			<view class="qr-code" @click="closeCode">
				<view class="container" @click.stop="">
					<view class="img-list">
						<canvas class="canvas" canvas-id="myCanvas"></canvas>
					</view>
					<!-- 保存按钮 分享 -->
					<!-- #ifndef H5 -->
					<view class="save-share">
						<block v-for="(item, index) in shareBtnList" :key="index">
							<view class="btn-item" :key="index">
								<view class="uni-share-button-box" @click="shareClickBtn(item)">
									{{ item.text }}
								</view>
							</view>
						</block>
					</view>
					<!-- #endif -->
				</view>
			</view>
		</template>
		<!-- 开赏动画 -->
		<template v-if="isAnimation">
			<animation :rewardList="reverseRewardList" :probabilitiesList="probabilitiesList" :totleDraws="totleDraws"
			:randomIndexList="randomIndexList" @closeAnimation="closeAnimation"></animation>
		</template>
	</view>
</template>

<script>
	import {
		mapGetters,
		mapState
	} from 'vuex';
	import PayOrder from '@/components/pay-order/index';
	import ShippingNotice from '@/components/pay-order/shipping-notice';
	import WinningRecord from '@/components/winning-record/index';
	import GoodsDetail from '@/components/yfs-hash-goods/goods-detail';
	import {
		get_blind_detail,
		order_result
	} from '@/api/goods.js';
	import {
		Base64
	} from '../../js_sdk/js-base64/base64.min';
	import utils from '@/utils/canvasUtils.js';
	import { drawImg } from '@/utils/appqrCode.js';
	import baseUrl from '../../utils/siteInfo';
	import animation from './components/animation/index.vue';

	export default {
		components: {
			PayOrder,
			ShippingNotice,
			WinningRecord,
			GoodsDetail,
			animation,
		},
		data() {
			return {
				boxBg: this.$imgList.homeImgs.boxBg,
				change: this.$imgList.homeImgs.change,
				left: this.$imgList.homeImgs.left,
				right: this.$imgList.homeImgs.right,
				countBg: this.$imgList.homeImgs.countBg,
				bthBg: this.$imgList.homeImgs.bthBg,
				titleImg: this.$imgList.homeImgs.titleImg,
				refresh: this.$imgList.homeImgs.refresh,
				share: this.$imgList.homeImgs.share,
				menuBg: this.$imgList.homeImgs.menuBg,
				shangdai: this.$imgList.homeImgs.shangdai,
				reward_detail: this.$imgList.homeImgs.reward_detail,
				reward_close: this.$imgList.homeImgs.reward_close,
				reward_check: this.$imgList.homeImgs.reward_check,
				picInfo: {},
				seriesInfo: {}, // 系列信息
				goodsList: [],
				blindbox_id: '', // 盒子id
				play_id: '', // 1-一番赏 2-无限赏
				isNavWhite: true,
				btnList: [{
						label: '抽一发',
						value: 1
					},
					{
						label: '抽三发',
						value: 3
					},
					{
						label: '抽五发',
						value: 5
					},
				],
				orderVisible: false,
				recordVisible: false,
				itemImgSize: '62px',
				total: 0,
				order_no: '',
				rewardVisible: false,
				rewardList: [],
				detailShow: false,
				detailInfo: {},
				socketMsgQueue: [],
				userInfo: '',
				changeBoxId: false,
				lastGooodsList: [],
				canvasStatus: false,
				posterSrc: '',
				shareBtnList: [
					{
						id: 1,
						text: '保存到手機'
					}
				],
				imgList: [
					baseUrl.imgroot + 'static/images/userCenter/invitePerson/invite_bg.png', // 背景
				],
				pageParams: {}, // 页面加载参数
				shippingVisible: false,
				
				probabilitiesList: [], // 中奖名单
				appreciationList: [], // 赏种名单
				isAnimation: false,
				minRandom: 4,
				randomIndexList: [],
				reverseRewardList: [],
				totleDraws: 0,
			};
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght'
			}),
			showLast() {
				return this.seriesInfo.now_box > 1;
			},
			showNext() {
				return this.seriesInfo.now_box < this.seriesInfo.max_box_no;
			},
			showSeriesInfo() {
				return this.seriesInfo && Object.keys(this.seriesInfo).length;
			},
		},
		onPageScroll(e) {
			const query = uni.createSelectorQuery().in(this);
			// 需要给黄色区域设置一个id标识，在这里是demo
			query.select('.goods-tip').boundingClientRect(data => {
				this.isNavWhite = data.top <= 88;
			}).exec();
		},
		onLoad(params) {
			if(params.type){
				let currentTip = '';
				if(params.type == 1){
					currentTip = '一番赏';
				}else if(params.type == 3){
					currentTip = '全局赏';
				}
				uni.setNavigationBarTitle({
					title: currentTip
				});
			}
			this.userInfo = uni.getStorageSync('userInfo');
			if(!params.openReward){
				// 保存回调参数
				uni.setStorageSync('backParams', params);
				this.loadParams(params);
			} else{
				// h5支付宝回调
				const backParam = uni.getStorageSync('backParams');
				const backSeriesInfo  = uni.getStorageSync('seriesInfo');
				if(backSeriesInfo) {
					backParam.boxId = backSeriesInfo.now_box;
				}
				this.loadParams(backParam);
				this.closePayOrder({order_no: params.order_no});
			}
			
		},
		onShow() {
			if (this.changeBoxId) {
				this.getBlindDetail(this.seriesInfo.now_box);
			}
		},
		onHide() {
			this.changeBoxId = false;
		},
		onUnload() {
			this.changeBoxId = false;
			uni.closeSocket();
		},
		methods: {
			loadParams(param) {
				this.pageParams = {...param};
				const {
					agent,
					id,
					type = 1,
					boxId
				} = param;
				this.play_id = type;
				// 分享进入 agent<-->userId
				if (agent) {
					const info = Base64.decode(agent);
					const agent_user_id = Base64.decode(info);
					uni.setStorageSync('agent_user_id', agent_user_id);
					this.blindbox_id = id;
				} else {
					// 列表跳转进入
					this.blindbox_id = id;
				}
				this.getBlindDetailFirst(boxId || 0);
			},
			// WebSocket 连接
			openSockect() {
				uni.connectSocket({
					url: baseUrl.socketUrl,
					complete: (data) => {
						console.log(data);
					}
				});
				uni.onSocketOpen((res) => {
					console.log(res, 'WebSocket连接已打开！');
					this.joinGroup();
					uni.onSocketMessage((res_03) => {
						console.log('收到服务器内容：' + res_03.data);
						let socketInfo = JSON.parse(res_03.data)
						switch (socketInfo.type) {
							case 'pong':
								break;
							case 'left_stock':
								let socketList = socketInfo.data;
								this.processData(socketList);
								break;
							default:
								break;
						}
					});
					setInterval(() => {
						uni.sendSocketMessage({
							data: JSON.stringify({
								cmd: 'ping'
							})
						});
					}, 5000)
				});
			},
			joinGroup() {
				let msg = {
					cmd: 'LOGIN',
					uid: this.userInfo.id,
					group: this.blindbox_id + '_' + this.seriesInfo.now_box,
				};
				uni.sendSocketMessage({
					data: JSON.stringify(msg)
				});
			},
			processData(socketList) {
				let keys = Object.keys(socketList);
				let values = Object.values(socketList);
				this.seriesInfo.left_num = keys.reduce((pre, item, index) => {
					let addNum = values[index];
					this.lastGooodsList.forEach(element => {
						if (item == element.blindbox_goods_id) {
							addNum = 0;
						}
					});
					pre += Number(addNum);
					return pre
				}, 0);
				this.goodsList = this.goodsList.reduce((pre, item) => {
					keys.forEach((el, index) => {
						if (el == item.blindbox_goods_id) {
							item.stock = Number(values[index]);
						}
					});
					pre.push(item);
					return pre;
				}, []);
			},
			async getBlindDetail(box_id = 0) {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				const {
					code,
					data,
					msg
				} = await get_blind_detail({
					id: this.blindbox_id,
					box_id
				});
				uni.hideLoading();
				this.changeBoxId = false;
				if (code == 0) {
					const {
						info = {}, list = [], left_num, total_num, now_box, max_box_no
					} = data;
					this.picInfo = {
						...info
					};
					this.seriesInfo = {
						left_num,
						total_num,
						now_box,
						max_box_no
					};
					this.goodsList = list;
					this.joinGroup();
				} else {
					uni.showToast({
						title: msg, // 必须
						icon: 'error', // 默认success  error fail loading
						mask: false, // 是否显示透明蒙层，防止触摸穿透，默认：false
						complete: () => {}
					});
				}
			},
			// 首次加载
			async getBlindDetailFirst(box_id = 0) {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				const {
					code,
					data,
					msg
				} = await get_blind_detail({
					id: this.blindbox_id,
					box_id
				});
				uni.hideLoading();
				if (code == 0) {
					const {
						info = {}, list = [], left_num, total_num, now_box, max_box_no
					} = data;
					this.picInfo = {
						...info
					};
					this.seriesInfo = {
						left_num,
						total_num,
						now_box,
						max_box_no
					};
					uni.setStorageSync('seriesInfo', this.seriesInfo);
					this.goodsList = list;
					this.lastGooodsList = this.goodsList.filter(item => item.tag_id == 0);
					this.appreciationList = list.reduce((pre, item) => {
						if(item.tag_name != 'LAST'){
							pre.push({
								goods_id: item.goods_id,
								goods_image: item.goods_image
							});
						}
						return pre;
					},[])
					this.openSockect();
				} else {
					uni.showToast({
						title: msg, // 必须
						icon: 'error', // 默认success  error fail loading
						mask: false, // 是否显示透明蒙层，防止触摸穿透，默认：false
						complete: () => {}
					});
				}
			},
			getMoreData() {},
			refresherpulling() {},
			// 抽赏-打开订单支付界面
			drawGoods(type, item) {
				uni.showLoading({
					title: '',
					mask: true
				});
				this.totleDraws = (type === 1 ? item.value : this.seriesInfo.left_num);
				const params = {
					blindbox_id: this.blindbox_id, // 系列id
					play_id: this.play_id,
					num: type === 1 ? item.value : this.seriesInfo.left_num,
					box_id: this.seriesInfo.now_box, // 盒子id
					seriesName: this.picInfo.name
				};
				this.orderVisible = true;
				this.changeBoxId = false;
				setTimeout(() => {
					this.$nextTick(() => {
						this.$refs.payPopup.initData(params);
						uni.hideLoading();
					});
				}, 0);
			},
			closePayOrder(param) {
				if (!param) {
					this.orderVisible = false;
					return;
				}
				const {order_no} = param;
				this.order_no = order_no;
				this.orderVisible = false;
				this.changeBoxId = false;
				uni.showLoading({
					title: '查询结果',
					mask: true
				});
				order_result({
					order_no
				}).then(res => {
					uni.hideLoading();
					const {
						code,
						data = {}
					} = res;
					if (code == 0) {
						const {
							detail = []
						} = data;
						for (let i = 0; i < detail.length; i++) {
							detail[i].goods_image = detail[i].goods_image.split(',')[0];
						}
						this.rewardList = detail;
						if(this.appreciationList.length <= 5){
							this.rewardVisible = true;
						}else{
							this.reverseRewardList = JSON.parse(JSON.stringify(detail)).reverse().filter(item => item.tag_name != 'LAST');
							this.createWinnerList(detail);
						}
					} else {
						this.getOrderRes();
					}
				});
			},
			getOrderRes(type) {
				this.changeBoxId = false;
				order_result({
					order_no: this.order_no
				}).then(res => {
					const {
						code,
						data = {}
					} = res;
					if (code == 0) {
						const {
							detail = []
						} = data;
						this.rewardList = detail;
						if(this.appreciationList.length <= 5){
							this.rewardVisible = true;
						}else{
							this.createWinnerList(detail);
						}
					} else {
						if (this.total < 20) {
							setTimeout(() => {
								this.total = this.total + 1
								this.getOrderRes();
							}, 800)
						} else {
							uni.navigateTo({
								url: `/plugins/yfs-detail/index?id=${this.blindbox_id}`
							})
						}
					}
				})
			},
			// 创建中奖名单
			createWinnerList(detailList){
				let noLastList = detailList.filter(item => item.tag_name != 'LAST');
				// 未中奖名单
				noLastList.forEach((item, index) => {
					let newAppreciationList = JSON.parse(JSON.stringify(this.appreciationList));
					let randomList = newAppreciationList.filter(el => el.goods_id != item.goods_id);
					for(let i = 0; i < randomList.length; i++) {
						let tipIndex = parseInt(Math.random() * (randomList.length - 1));
						let tempValue = randomList[i];
						randomList[i] = randomList[tipIndex];
						randomList[tipIndex] = tempValue;
					}
					this.probabilitiesList.push([...randomList]);
				});
				console.log(this.probabilitiesList, '不包含中奖名单')
				// 随机插入中奖名单
				let winnerList = JSON.parse(JSON.stringify(noLastList));
				let randomIndex;
				let totalIndex = 0;
				this.probabilitiesList.reduce((pre, item, index) => {
					if(index % 5 == 0){
						randomIndex = parseInt(Math.random() * (item.length - this.minRandom + 1) + this.minRandom);
						this.randomIndexList.push(Number(randomIndex) + (this.appreciationList.length) * totalIndex);
						totalIndex++;
					}
					item.splice(randomIndex, 0, winnerList[index]);
					pre.push([...item]);
					return pre
				}, []);
				let needFill = 5 - this.probabilitiesList.length % 5;
				
				if(needFill && parseFloat(this.probabilitiesList.length / 5) > 1){
					const arr = Array(this.appreciationList.length).fill('');
					for(let j = 0; j < needFill; j++){
						this.probabilitiesList.push(arr);
					}
					let newProbabilitiesList = this.probabilitiesList.slice(0,5);
					let kIndex = this.probabilitiesList.length;
					for(let k = 5; k < kIndex; k++){
						let addK = k % 5;
						newProbabilitiesList[addK].push(...this.probabilitiesList[k]);
					}
					this.probabilitiesList = newProbabilitiesList;
				}
				this.isAnimation = true; // 开启开场动画
				console.log(this.probabilitiesList, '包含中奖名单')
			},
			// 关闭开赏动画
			closeAnimation(){
				// 开启结果弹窗
				this.probabilitiesList = []; // 中奖名单
				this.minRandom = 3;
				this.randomIndexList = [];
				this.isAnimation = false;
				this.rewardVisible = true;
				
			},
			changeBox() {
				this.changeBoxId = false;
				uni.navigateTo({
					url: `/plugins/change-box/index?id=${this.blindbox_id}&sort=stock`
				})
			},
			nextBox() {
				this.changeBoxId = false;
				this.seriesInfo.now_box = Number(this.seriesInfo.now_box) + 1
				const nextBoxId = this.seriesInfo.now_box;
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				this.getBlindDetail(nextBoxId);
			},
			lastBox() {
				this.changeBoxId = false;
				this.seriesInfo.now_box = Number(this.seriesInfo.now_box) - 1
				const lastBoxId = this.seriesInfo.now_box;
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				this.getBlindDetail(lastBoxId);
			},
			openGoodsDetail(item) {
				this.changeBoxId = false;
				this.detailShow = true;
				this.detailInfo = {
					...item,
					seriesNmae: this.picInfo.name
				};
			},
			closeDetail() {
				this.changeBoxId = false;
				this.detailShow = false;
			},
			closeRecord() {
				this.changeBoxId = false;
				this.recordVisible = false;
			},
			openMenu(type) {
				switch (type) {
					case 1:
						this.changeBoxId = false;
						this.recordVisible = true;
						const params = {
							blindbox_id: this.blindbox_id,
							box_id: this.seriesInfo.now_box
						};
						this.$refs.recordPopup.initRecordList(params);
						break;
					case 2:
						this.changeBoxId = false;
						this.getBlindDetail(this.seriesInfo.now_box);
						break;
					case 3:
						this.changeBoxId = false;
						break;
				}
			},
			h5CreateCode() {
				const codeImg = this.createQrcode();
				this.generateCanvas(codeImg);
			},
			// app h5生成二维码
			createQrcode() {
				const userInfo = uni.getStorageSync('userInfo');
				const id1 = Base64.encode(JSON.stringify(userInfo.id));
				const id2 = Base64.encode(id1);
				const {
					id,
					type,
					boxId
				} = this.pageParams;
				const shareUrl = window.location.protocol + '//' + window.location.host + 
				`/#/plugins/yfs-detail/index?agent_user_id=${id2}id=${id}type=${type}boxId=${boxId}`;
				const imgData = drawImg(shareUrl, {size: 280});
				return imgData;
			},
			generateCanvas(qrcode) {
				this.canvasStatus = true;
				utils.poster2canvas({imgList: this.imgList, qrcode}, (res) => {
					uni.hideLoading();
					this.posterSrc = res;
				});
			},
			// 关闭二维码
			closeCode(){
				this.canvasStatus = false;
			},
			// 分享+保存到相册
			shareClickBtn(item){
				if(item.id == 1){
					// 保存到相册
					this.savePicture();
				}
			},
			// 保存到本地
			savePicture() {
				uni.downloadFile({
					url: this.posterSrc, // 图片地址
					success: (res) => {
						if (res.statusCode === 200) {
							uni.saveImageToPhotosAlbum({
								filePath: res.tempFilePath,
								success: function() {
									uni.showToast({
										title: "保存成功",
										icon: "none"
									});
								},
								fail: function() {
									uni.showToast({
										title: "保存失敗",
										icon: "none"
									});
								}
							});
						}
					}
				})
			},
			toWarehouse() {
				this.changeBoxId = false;
				uni.switchTab({
					url: '/pages/warehouse/index'
				});
			},
			closeReward() {
				this.changeBoxId = false;
				this.rewardVisible = false;
				this.isAnimation = false; //关闭开赏动画
				this.probabilitiesList = [];
				setTimeout(() => {
					this.getBlindDetail(this.seriesInfo.now_box);
				}, 50);
			},
			toOrderPage() {
				this.changeBoxId = false;
				uni.navigateTo({
					url: '/plugins/order-detail/index?currentTab=1'
				});
			},
			openShipping() {
				this.shippingVisible = true;
			},
			closeShipping() {
				this.shippingVisible = false;
			}
		}
	}
</script>

<style lang="scss" scoped>
	@import "./index.scss";
</style>