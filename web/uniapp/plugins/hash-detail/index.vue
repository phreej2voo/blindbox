<template>
	<view class="container">
		<scroll-view class="body" enableFlex scroll-y @scrolltolower="getMoreData" @refresherpulling="refresherpulling">
			<view class="top-area main-center-flex">
				<image :src="picInfo.pic" mode="heightFix"></image>
			</view>
			<view class="list-class">
				<view v-for="(item,index) in goodsList" :key="index" class="goods-item">
					<view class="sp-item">
						<view :style="{'background-image': 'url('+iconObj[item.tab_name]+')'}" class="icon a-icon">{{ item.tab_name }}</view>
						<view class="sp-title">{{'获得概率：' + item.probability + '%'}}</view>
					</view>
					<view class="grid-class">
						<view v-for="(child,jndex) in item.list" :key="jndex" class="item-class" @click="openGoodsDetail(child)">
							<view class="item-top main-center-flex">
								<view class="goods-tag" :class="child.tag_name === 'LAST' ? 'last' : 'probability'">
									{{ child.probability + '%' }}
								</view>
								<view v-if="child.stock <= 0" class="sell-out">售罄</view>
								<u--image :showLoading="true" :src="child.goods_image"
									:width="itemImgSize" :height="itemImgSize" mode="scaleToFill">
								</u--image>
								<view class="tag-title">{{ child.stock + '/' + (child.sales + child.stock) }}</view>
							</view>
							<view class="item-bottom">
								<view>{{ child.goods_name }}</view>
								<view>{{ '官方零售价：' + child.price }}</view>
								<view>{{'赏级：' + child.tag_name}}</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</scroll-view>

		<view class="menu">
			<view :style="{'background-image': 'url('+menuBg+')'}" class="menu-text" @click="openMenu(1)">中奖记录</view>
			<view :style="{'background-image': 'url('+menuBg+')'}"  @click="openMenu(2)">
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
			<!-- <view :style="{'background-image': 'url('+menuBg+')'}" @click="openMenu(3)">
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
			<view class="btn">
				<view class="op-area">
					<image :src="reduceImg" class="op-img" @click="reduce"></image>
					<view class="count-text">{{countNum}}</view>
					<image :src="addImg" class="op-img" @click="add"></image>
				</view>
				<view :style="{'background-image': 'url('+draw+')'}" class="draw-text" @click="drawGoods">
					抽赏
				</view>
			</view>
		</view>

		<pay-order ref="payPopup" :orderVisible="orderVisible" @closeOrder="closePayOrder"></pay-order>
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
					<view :style="{'background-image': 'url('+reward_check+')'}" class="check" @click="toWarehouse">去查看</view>
				</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	import { mapGetters, mapState } from 'vuex';
	import PayOrder from '@/components/pay-order/index';
	import ShippingNotice from '@/components/pay-order/shipping-notice';
	import WinningRecord from '@/components/winning-record/index';
	import GoodsDetail from '@/components/yfs-hash-goods/goods-detail';
	import { get_blind_detail, order_result } from '@/api/goods.js';
	import { Base64 } from '../../js_sdk/js-base64/base64.min';

	export default {
		components: {
			PayOrder,
			ShippingNotice,
			WinningRecord,
			GoodsDetail
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
				sp: this.$imgList.homeImgs.sp,
				iconObj: {
					'A': this.$imgList.homeImgs.a_icon,
					'B': this.$imgList.homeImgs.b_icon,
					'C': this.$imgList.homeImgs.c_icon,
					'SP': this.$imgList.homeImgs.sp
				},
				reduceImg: this.$imgList.homeImgs.reduce,
				addImg: this.$imgList.homeImgs.add,
				draw: this.$imgList.homeImgs.draw,
				reward_detail: this.$imgList.homeImgs.reward_detail,
				reward_close: this.$imgList.homeImgs.reward_close,
				reward_check: this.$imgList.homeImgs.reward_check,
				picInfo: {},
				seriesInfo: {}, // 系列信息
				goodsList: [],
				blindbox_id: '', // 盒子id
				play_id: '', // 1-一番赏 2-无限赏
				countNum: 3,
				isNavWhite: true,
				orderVisible: false,
				recordVisible: false,
				itemImgSize: '62px',
				total: 0,
				order_no: '',
				rewardVisible: false,
				rewardList: [],
				detailShow: false,
				detailInfo: {},
				shippingVisible: false
			};
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght'
			})
		},
		onPageScroll(e) {
			const query = uni.createSelectorQuery().in(this);
			// 需要给黄色区域设置一个id标识，在这里是demo
			query.select('.goods-tip').boundingClientRect(data => {
				this.isNavWhite = data.top <= 88;
			}).exec();
		},
		onLoad(params) {
			console.log('hash-', params);
			this.userInfo = uni.getStorageSync('userInfo');
			if(!params.openReward){
				// 保存回调参数
				uni.setStorageSync('backParams', params);
				this.loadParams(params);
			} else{
				// h5支付宝回调
				const backParam = uni.getStorageSync('backParams');
				this.loadParams(backParam);
				this.closePayOrder({order_no: params.order_no});
			}
		},
		onShow() {},
		onHide() {},
		onUnload() {},
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
				this.getBlindDetail(boxId || 0);
			},
			async getBlindDetail(box_id = 0) {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				const {code, data, msg} = await get_blind_detail({id: this.blindbox_id, box_id});
				uni.hideLoading();
				if (code == 0) {
					const {info = {}, list = [], left_num, total_num, now_box, max_box_no} = data;
					this.picInfo = {...info};
					this.seriesInfo = {left_num, total_num, now_box, max_box_no};
					this.goodsList = [...list];
				} else {
					uni.showToast({
						title: msg,
						icon: 'error',
						mask: false,
						complete: () => {}
					});
				}
			},
			openGoodsDetail(item) {
				this.detailShow = true;
				this.detailInfo = {...item,seriesNmae: this.picInfo.name};
			},
			closeDetail() {
				this.detailShow = false;
			},
			closeRecord() {
				this.recordVisible = false;
			},
			getMoreData() {},
			refresherpulling() {},
			reduce() {
				if(this.countNum <= 1) {
					return false;
				}
				this.countNum--;
			},
			add() {
				this.countNum++;
			},
			drawGoods() {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				const params = {
					blindbox_id: this.blindbox_id, // 系列id
					play_id: this.play_id,
					num: this.countNum,
					box_id: this.seriesInfo.now_box, // 盒子id
					seriesName: this.picInfo.name
				};
				this.orderVisible= true;
				setTimeout(() => {
					this.$nextTick(() => {
						this.$refs.payPopup.initData(params);
						// uni.hideLoading();
					});
				}, 0);
			},
			closePayOrder(param) {
				if(!param) {
					this.orderVisible = false;
					return;
				}
				const {order_no} = param;
				this.order_no = order_no;
				this.orderVisible = false;
				uni.showLoading({
					title: '查询结果',
					mask: true
				});
				order_result({order_no}).then(res => {
					uni.hideLoading();
					const {code, data = {}} = res;
					if (code == 0) {
						const {detail = []} = data;
						for (let i = 0; i < detail.length; i++) {
							detail[i].goods_image = detail[i].goods_image.split(',')[0];
						}
						this.rewardList = detail;
						this.$nextTick(() => {
							this.rewardVisible = true;
						});
					} else {
						this.getOrderRes();
					}
				});
			},
			getOrderRes(type) {
				order_result({
					order_no: this.order_no
				}).then(res => {
					const {code, data = {}} = res;
					if (code == 0) {
						const {detail = []} = data;
						this.rewardList = detail;
						this.$nextTick(() => {
							this.rewardVisible = true;
						});
					} else {
						if (this.total < 20) {
							setTimeout(() => {
								this.total = this.total + 1
								this.getOrderRes()
							}, 800)
						} else {
							uni.navigateTo({
								url: `/plugins/hash-detail/index?id=${this.blindbox_id}`
							})
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
				this.rewardVisible = false;
				this.getBlindDetail();
			},

			openMenu(type) {
				switch(type) {
					case 1:
						this.recordVisible = true;
						setTimeout(() => {
							this.$nextTick(() => {
								const params = {
									blindbox_id: this.blindbox_id,
									box_id: this.seriesInfo.now_box
								};
								this.$refs.recordPopup.initRecordList(params);
							});
						}, 0);
						break;
					case 2:
						this.getBlindDetail();
						break;
					case 3:
						// this.rewardVisible = true;
						break;
				}
			},
			toOrderPage() {
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
.container{
	width: 100vw;
	/* #ifdef MP-WEIXIN || APP-PLUS */
	height: 100vh;
	/* #endif */
	/* #ifdef H5 */
	height: calc(100vh - 60px);
	/* #endif */
	background: #1D1F36;
	/* padding-bottom: env(safe-area-inset-bottom); */
	.u-nav-slot{
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
	}
	.body {
		overflow-y: scroll;
		padding: 30rpx;
		height: calc(100% - 230rpx);
	}
	.top-area{
		position: relative;
		width: 100%;
		height: 300rpx;
		padding: 12rpx;
		border: 8rpx solid;
		/* 8表示内向偏移量，写成和边框设置的宽度一样即可 */
		border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 8;
		clip-path: inset(0 round 14rpx);
		image{
			width: 100%;
			height: 100%;
		}
		.title{
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 120rpx;
			background: rgba(23,23,23,0.61);
			border-radius: 0px 0px 0px 0px;
			opacity: 1;
			padding: 10rpx 20rpx 4rpx 40rpx;
			display: flex;
			flex-direction: column;
		}
		.title_1{
			font-size: 32rpx;
			font-family: PingFang SC-Medium, PingFang SC;
			font-weight: 500;
			color: #FFFFFF;
		}
		.title_2{
			font-family: PingFang SC-Medium, PingFang SC;
			font-weight: 500;
			color: #FFFFFF;
			display: flex;
			align-content: center;
			view:first-child{
				font-size: 32rpx;
			}
			view:last-child{
				font-size: 28rpx;
			}
		}
	}
	.sp-class{
		position: relative;
		width: 100%;
		height: 100rpx;
		margin-top: 30rpx;
		display: flex;
		flex-direction: column;
		align-items: flex-start;
	}
	.list-class{
		margin-top: 20rpx;
		display: flex;
		flex-direction: column;
		.goods-item{
			width: 100%;
			display: flex;
			flex-direction: column;
			margin-bottom: 10rpx;
		}
		.sp-item{
			width: 100%;
			height: 100rpx;
			display: flex;
			align-items: center;
			margin-top: 20rpx;
		}
		.icon{
			width: 92rpx;
			height: 101rpx;
			font-size: 38rpx;
			font-family: BTH;
			font-weight: 400;
			background-size: 100% 100%;
			text-align: center;
			line-height: 101rpx;
		}
		.sp{
			color: #FFEE52;
		}
		.sp-title{
			font-size: 26rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #FFFFFF;
			padding-left: 16rpx;
		}
		.a-icon{
			color: #BDFFFD;
		}
		.grid-class{
			margin-top: 16rpx;
			display: grid;
			justify-content: space-between;
			grid-gap: 30rpx 20rpx;
			grid-template-columns: repeat(4, 152rpx);
			grid-template-rows: auto;
		}
		.item-class{
			width: 152rpx;
			height: 250rpx;
			background: #000000;
			.goods-tag{
				position: absolute;
				width: 90%;
				height: 40rpx;
				top: 0;
				left: 0px;
				-webkit-clip-path: polygon(0 0, 100% 0, 92% 100%, 0 100%);
				clip-path: polygon(0 0, 100% 0, 92% 100%, 0 100%);
				z-index: 99;
				font-size: 24rpx;
				font-family: BTH;
				color: rgba(29, 31, 54, 1);
				line-height: 40rpx;
				padding-left: 8rpx;
				border-top-left-radius: 2rpx;
			}
			.probability{
				background-color: rgba(130, 255, 128, 1);
			}
			.last{
				background-image: linear-gradient(to right, rgba(255, 225, 65,1), rgba(255,241,168,0.42));
			}
			.sell-out{
				position: absolute;
				width: 42rpx;
				height: 92rpx;
				top: 34rpx;
				border: 4rpx solid #82ff80;
				z-index: 99;
				-webkit-transform: rotate(4deg);
				transform: rotate(4deg);
				color: #FFFFFF;
				font-size: 24rpx;
				text-align: center;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			.item-top{
				position: relative;
				width: 100%;
				height: 145rpx;
				padding: 10rpx 10rpx 10rpx 10rpx;
				background: #000000;
				border-radius: 10rpx;
				opacity: 1;
				border: 4rpx solid;
				border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 4;
				clip-path: inset(0 round 8rpx);
				display: flex;
				justify-content: center;
				align-items: center;
				.tag-title{
					position: absolute;
					bottom: 0;
					right: 0;
					width: 60rpx;
					height: 32rpx;
					background: #B26CFF;
					font-size: 22rpx;
					font-family: PingFang SC-Medium, PingFang SC;
					color: #FFFFFF;
					text-align: center;
					line-height: 32rpx;
					border-top-left-radius: 4rpx;
					border-bottom-left-radius: 4rpx;
				}
			}
			.item-bottom{
				width: 100%;
				padding: 4rpx;
				height: calc(100% - 145rpx);
				display: flex;
				flex-direction: column;
				justify-content: space-around;
				view{
					font-family: PingFang SC-Medium, PingFang SC;
					color: #FFFFFF;
				}
				view:first-child{
					overflow: hidden;
					white-space: nowrap;
					text-overflow: ellipsis;
					font-size: 18rpx;
				}
				view:nth-child(2){
					font-size: 16rpx;
				}
				view:last-child{
					font-size: 16rpx;
				}
			}
		}
	}
	.menu{
		position: fixed;
		right: 0;
		top: 100rpx;
		display: flex;
		flex-direction: column;
		.share-btn{
			border: none;
		}
		.img1{
			width: 30rpx;
			height: 36rpx;
		}
		.img2{
			width: 32rpx;
			height: 30rpx;
		}
		.menu-text{
			font-size: 36rpx;
			font-family: BTH;
			font-weight: 400;
			color: #171717;
		}
		.menu-text2{
			padding-left: 10rpx;
			font-size: 36rpx;
			font-family: BTH;
			font-weight: 400;
			color: #171717;
		}
	}
	.menu>view{
		width: 170rpx;
		height: 75rpx;
		background-size: 100% 100%;
		margin-top: 12rpx;
		display: flex;
		justify-content: space-evenly;
		align-items: center;
	}
	.movable-area{
		position: fixed;
		/* #ifdef MP-WEIXIN */
		top: 0;
		bottom: env(safe-area-inset-bottom);
		/* #endif */
		/* #ifdef H5 || APP-PLUS */
		top: 20px;
		bottom: 60px;
		/* #endif */
		left: 0;
		width: 100%;
		/* #ifdef MP-WEIXIN */
		height: calc(100vh - env(safe-area-inset-bottom));
		/* #endif */
		/* #ifdef H5 || APP-PLUS */
		height: calc(100vh - 80px);
		/* #endif */
		pointer-events: none;
		z-index: 99;
		.movable-view{
			width: 122rpx;
			height: 114rpx;
			pointer-events: auto;
			position: relative;
		}
		.shang-dai{
			width: 122rpx;
			height: 114rpx;
			font-family: BTH;
			font-size: 34rpx;
			font-weight: 400;
			color: #171717;
			background-size: 100% 100%;
			text-align: center;
			padding-top: 48rpx;
		}
	}
	.draw-area{
		position: fixed;
		left: 0;
		bottom: 0;
		width: 100%;
		height: 250rpx;
		padding-bottom: env(safe-area-inset-bottom);
		background-color: #1D1F36;
		.title-img{
			width: 100%;
			height: 21rpx;
		}
		.btn{
			width: 100;
			padding-top: 20rpx;
			display: flex;
			justify-content: space-evenly;
			align-items: center;
		}
		.op-area{
			width: 45%;
			display: flex;
			justify-content: space-around;
			align-items: center;
		}
		.count-text{
			font-size: 68rpx;
			font-family: BTH;
			font-weight: 400;
			color: #79FE93;
			min-width: 80rpx;
			text-align: center;
		}
		.op-img{
			width: 62rpx;
			height: 62rpx;
		}
		.draw-text{
			width: 336rpx;
			height: 131rpx;
			background-size: 100% 100%;
			text-align: center;
			line-height: 136rpx;
			font-family: BTH;
			color: #FFFFFF;
			font-size: 48rpx;
		}
	}

	.reward-container{
		width: 100vw;
		height: 100vh;
		padding-top: 10%;
		padding-bottom: env(safe-area-inset-bottom);
		background-color: #1D1F36;
		.inner{
			position: relative;
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		.close{
			position: absolute;
			top: 10rpx;
			right: 30rpx;
			width: 52rpx;
			height: 52rpx;
		}
		.reward-title{
			text-align: center;
			font-size: 54rpx;
			font-family: BTH;
			font-weight: 400;
			background-image: linear-gradient(180deg, #72FF70 0%, #BB89FF 93%);
			background-clip: text;
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}
		.detail-view{
			width: 694rpx;
			height: 852rpx;
			background-size: 100% 100%;
			margin-top: 20rpx;
			padding: 50rpx 20rpx;
		}
		.grid-view{
			width: 100%;
			padding: 20rpx 25rpx;
			display: grid;
			justify-content: space-between;
			grid-gap: 20rpx 10rpx;
			grid-template-columns: repeat(3, 180rpx);
			grid-template-rows: auto;
		}
		.detail-item{
			position: relative;
			width: 180rpx;
			height: 288rpx;
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		.goods-tag{
			position: absolute;
			width: calc(100% - 8rpx);
			height: 40rpx;
			top: 4rpx;
			left: 4rpx;
			background-image: linear-gradient(to right, rgba(73,200,136,0.8), rgba(255,241,168,0.42));
			z-index: 99;
			font-size: 24rpx;
			font-family: BTH;
			color: rgba(29, 31, 54, 1);
			line-height: 40rpx;
			padding-left: 8rpx;
			border-top-left-radius: 2rpx;
		}
		.last{
			background-image: linear-gradient(to right, rgba(73,200,136,0.8), rgba(255,241,168,0.42));
		}
		.detail_1{
			width: 100%;
			height: 180rpx;
			position: relative;
			padding: 10rpx;
			border: 4rpx solid;
			border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 4;
			clip-path: inset(0 round 7rpx);
			image{
				margin-top: 10rpx;
				width: 100%;
				height: 100%;
			}
			.tag-class{
				position: absolute;
				top: 0;
				left: 40rpx;
				width: 90rpx;
				height: 36rpx;
				background: #2F2F2F;
				border-radius: 0px 0px 0px 0px;
				opacity: 1;
				color: #FFFFFF;
				font-size: 22rpx;
				text-align: center;
				line-height: 36rpx;
			}
		}
		.detail_2{
			width: 100%;
			height: calc(100% - 180rpx);
			display: flex;
			flex-direction: column;
			justify-content: center;
			text{
				padding-top: 8rpx;
				color: #FFFFFF;
				font-family: PingFang SC-Regular, PingFang SC;
				font-weight: 400;
			}
			text:first-child{
				font-size: 22rpx;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
			}
			text:nth-child(2){
				font-size: 20rpx;
			}
			text:last-child{
				font-size: 20rpx;
			}
		}
		.check{
			width: 299rpx;
			height: 91rpx;
			background-size: 100% 100%;
			text-align: center;
			line-height: 91rpx;
			color: #FFFFFF;
			font-size: 40rpx;
			font-family: BTH;
			margin-top: -20rpx;
		}
	}
}
</style>