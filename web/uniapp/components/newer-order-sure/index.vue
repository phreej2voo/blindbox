<template>
	<!-- <u-popup :show="orderVisible" mode="bottom" @close="closeOrderPopup"> -->
	<view class="container">
		<view class="head-title">
			订单确认
		</view>
		<scroll-view v-if="showPayInfo" class="scroll-view" enableFlex scroll-y>
			<view class="pay-info">
				<view class="pay-info_1 main-start-flex">
					<image :src="payInfo.info.pic" class="goods-img"></image>
					<view class="pay-info-detail_1">
						<view class="main-space-between">
							<view class="pay-info-name text-ellipsis">
								<view>{{payInfo.info.name}}</view>
								<view v-if="payParms.goodsType == 2" :style="{'background-image': 'url('+HonorListImg.newerBack+')'}"
									class="main-center-flex newer-back">
									新人买一送一
								</view>
							</view>
							<view class="pay-info-price">
								<text>￥</text>
								{{payInfo.info.price}}
							</view>
						</view>
					</view>
				</view>
				<view class="pay-info-item main-space-between">
					<text>数量</text>
					<text>x{{payInfo.num}}</text>
				</view>
				<u-line v-if="payParms.goodsType == 1" color="rgba(0,0,0,0.1)"></u-line>
				<view v-if="payParms.goodsType == 1" class="new-class main-space-between">
					<text class="new-first">新用户首单优惠</text>
					<view class="new-calc">
						<view>{{ '首单' + payInfo.firstAmount + '元' }}</view>
						<view>{{ '-￥' + String(payInfo.firstOrderAmount).substring(1) }}</view>
					</view>
				</view>
				<u-line color="rgba(0,0,0,0.1)"></u-line>
				<view class="pay-info-item main-space-between">
					<text>合计</text>
					<view class="pay-info-item-price">
						<text>￥</text>
						{{payInfo.totalPrice}}
					</view>
				</view>
			</view>
			<view class="pay-type">
				<view class="pay-type-head main-start-flex">
					支付方式
				</view>
				<radio-group @change="payTypeChange">
					<label class="pay-type-item main-space-between" v-for="(item, index) in payList" :key="index">
						<view class="main-start-flex">
							<image :src="item.pic" mode="" class="icon-img"></image>
							<view class="pay-type-name">
								{{item.name}}
								<text v-if="item.pay_way=='4'" class="ban-integ">{{userInfo.balance}}</text>
								<text v-if="item.pay_way=='3'" class="ban-integ">{{userInfo.integral}}</text>
							</view>
						</view>
						<radio :value="item.pay_way" :checked="item.pay_way === currentPayType" color="#eb5c4a"
							style="transform:scale(0.8)" />
					</label>
				</radio-group>
			</view>
			<view class="pay-remark">
				若完成交易代表您已同意以下约定：<br />
				1、本平台禁止未成年消费<br />
				2、由于盲盒商品特殊属性，打开后不支持退款<br />
				3、港澳台地区及部分偏远地区会无法配送<br />
			</view>
			<!-- <view class="pay-confirms"></view> -->
		</scroll-view>
		<view class="pay-confirm column-align-end-flex">
			<view class="pay-adult main-start-flex">
				<image v-if="isAdult" src="../../static/image/icon/chose.png" mode="" class="icon-img"
					@click="isAdult = !isAdult">
				</image>
				<view v-else class="icon-img chose-icon" @click="isAdult = !isAdult">
				</view>
				<view>
					<text @click="isAdult = !isAdult">
						我已满18岁，已阅读并同意 
					</text>
					<text @click="goRule">《用户协议》</text>
				</view>
			</view>
			<view class="pay-detail main-start-flex">
				<view class="pay-detail-left">
					<view class="">
						合计:￥<text>{{payInfo.totalPrice}}</text>
					</view>
					<view class="main-end-flex">
						共{{payInfo.num}}件
					</view>
				</view>
				<view class="pay-button main-center-flex" @click="confirmPay">
					确认支付
				</view>
			</view>
		</view>
		<modal ref="modal"></modal>
	</view>
	<!-- </u-popup> -->
</template>

<script>
	import {pay_order} from '@/api/pay.js';
	import { get_user_info } from '@/api/my.js';
	import {createMustOrder, createPresentOrder, createFreePlayOrder} from '@/api/newer.js';
	import { HonorListImg } from '../../utils/objectValue';
	import { isWeixinBrowser } from  '../../utils/judge';

	export default {
		data() {
			return {
				HonorListImg,
				currentPayType: '4',
				currentType: '1',
				refreshPage: false,
				payInfo: {},
				payParms: {},
				isAdult: false,
				userInfo: {},
				createOrderObj: {
					1: createMustOrder,
					2: createPresentOrder,
					3: createFreePlayOrder
				}
			}
		},
		onShow() {
			console.log('pay-show-');
		},
		onLoad() {
			console.log('dsadad-');
		},
		onHide() {
			this.refreshPage = false;
		},
		computed: {
			showPayInfo() {
				return this.payInfo && Object.keys(this.payInfo).length;
			},
			payList() {
				let data = [];

				// #ifdef MP-WEIXIN
				data = [
					{
						pic: require('../../static/image/pay/wx_pay.png'),
						name: '微信',
						value: 'wx_pay',
						pay_way: '1'
					},
					{
						pic: require('../../static/image/icon/banlance_pay.png'),
						name: '账户余额',
						value: 'balance_pay',
						pay_way: '4'
					}
				];
				// #endif

				// #ifdef H5
				if(!isWeixinBrowser()) {
					data = [
						{
							pic: require('../../static/image/pay/ali_png.png'),
							name: '支付宝',
							value: 'ali_pay',
							pay_way: '2'
						},
						{
							pic: require('../../static/image/pay/wx_pay.png'),
							name: '微信',
							value: 'wx_pay',
							pay_way: '1'
						},
						{
							pic: require('../../static/image/icon/banlance_pay.png'),
							name: '账户余额',
							value: 'balance_pay',
							pay_way: '4'
						}
					];
				} else {
					data = [
						{
							pic: require('../../static/image/pay/wx_pay.png'),
							name: '微信',
							value: 'wx_pay',
							pay_way: '1'
						},
						{
							pic: require('../../static/image/icon/banlance_pay.png'),
							name: '账户余额',
							value: 'balance_pay',
							pay_way: '4'
						}
					];
				}
				// #endif

				// #ifdef APP-PLUS
				data = [
					{
						pic: require('../../static/image/pay/ali_png.png'),
						name: '支付宝',
						value: 'ali_pay',
						pay_way: '2'
					},
					{
						pic: require('../../static/image/pay/wx_pay.png'),
						name: '微信',
						value: 'wx_pay',
						pay_way: '1'
					},
					{
						pic: require('../../static/image/icon/banlance_pay.png'),
						name: '账户余额',
						value: 'balance_pay',
						pay_way: '4'
					}
				]
				// #endif
				return data;
			}
		},
		methods: {
			payTypeChange(e) {
				this.currentPayType = e.detail.value;
			},
			goRule() {
				uni.navigateTo({
					url: '/plugins/user-agreement/index'
				});
			},
			// 初始化-获取订单试算结果
			initData(params) {
				const {payInfo, param} = params;
				const {blindbox_id, goodsType} = param;
				this.payInfo = {...payInfo};
				this.payParms = {
					blindbox_id: blindbox_id,
					goodsType: goodsType,
					type: 'box'
				};
				// 获取账户余额
				get_user_info().then(res => {
					const {code, data, msg = ''} = res;
					if (code == 0) {
						this.userInfo = data;
					} else {
						uni.$u.toast(msg);
					}
				}).catch(err => {
					uni.$u.toast(err.msg);
				})
			},
			confirmPay() {
				if (!this.isAdult) {
					return uni.$u.toast('请同意用户协议');
				}
				// 确认支付弹框
				this.$refs.modal.showModal({
					title: '支付确认',
					content: `总价为${this.payInfo.totalPrice}`,
					confirm: () => {
						this.createOrder();
					}
				})
			},
			// 创建订单-获取订单号
			async createOrder(){
				uni.showLoading({
					title: '支付中',
					mask: true
				});
				const params = {pay_way: this.currentPayType};
				const {code, data} = await this.createOrderObj[this.payParms.goodsType](params);
				if (code == 0) {
					const {order_no = ''} = data;
					this.payRequest(order_no);
				}
			},
			// 支付
			async payRequest(order_no) {
				if(!order_no) {
					return;
				}
				// 平台
				let platform = '';
				// #ifdef APP-PLUS
				platform = 'app';
				// #endif
				// #ifdef H5
				platform = 'h5';
				// #endif
				// #ifdef MP-WEIXIN
				platform = 'miniapp';
				// #endif
				const {code, data, msg = ''} = await pay_order({
					order_no: order_no,
					platform
				});
				uni.hideLoading();
				if ((this.currentPayType == 1 || this.currentPayType == 2) && code == 201) {
					const that = this;
					// #ifdef MP-WEIXIN
					uni.requestPayment({
						provider: 'wxpay', // 支付类型-固定值
						timeStamp: data.timeStamp, // 时间戳（单位：秒）
						nonceStr: data.nonceStr, // 随机字符串
						package: data.package, // 固定值
						signType: data.signType, // 固定值
						paySign: data.paySign, // 签名
						success: function(res_pay) {
							console.log('？？？？？？', res_pay);
							uni.showToast({
								title: '支付成功',
								icon: 'none'
							});
							that.$emit('closeOrder', {
								order_no,
								num: that.payInfo.num,
								payParms: that.payParms
							});
						},
						fail(res_pay) {
							uni.showToast({
								title: '支付失败',
								icon: 'none'
							});
						}
					})
					// #endif

					// #ifdef APP-PLUS || H5
					that.goPay(data, order_no);
					// #endif
					return;
				}

				if (code == 0) {
					this.$emit('closeOrder', {
						order_no,
						num: this.payInfo.num,
						payParms: this.payParms
					});
				} else {
					uni.$u.toast(msg);
				}
			},
			goPay(data, order_no) {
				debugger
				const that = this;
				console.log('支付参数-', data, this.currentPayType);

				// 1-微信 2-支付宝
				if (this.currentPayType == '2') {
					// #ifdef H5
					document.querySelector('body').innerHTML = data;
					document.forms[0].submit();
					// #endif

					// #ifdef APP-PLUS
					uni.getProvider({
						service: 'payment',
						success: function (payProvider) {
							console.log('payProvider-', payProvider);
							if (~payProvider.provider.indexOf('alipay')) {
								uni.requestPayment({
									provider: 'alipay',
									orderInfo: data,
									success: function (result) {
										console.log('alipay-pay-', result);
										uni.showToast({
											title: '支付成功',
											icon: 'none'
										});
										that.$emit('closeOrder', {
											order_no,
											num: that.payInfo.num,
											payParms: that.payParms
										});
									},
									fail: function (err) {
										uni.showToast({
											title: '支付失败',
											icon: 'none'
										});
									}
								});
							}
						}
					});
					// #endif
				} else {
					// #ifdef H5
					window.location.href = data;
					// #endif

					// #ifdef APP-PLUS
					uni.getProvider({
						service: 'payment',
						success: function (payProvider) {
							console.log('payProvider-', payProvider);
							if (~payProvider.provider.indexOf('wxpay')) {
								uni.requestPayment({
									provider: 'wxpay',
									orderInfo: {
										appid: data.appId,
										partnerid: data.partnerid,
										prepayid: data.prepayid,
										package: 'Sign=WXPay',
										noncestr: data.nonceStr,
										timestamp: data.timeStamp,
										sign: data.sign
									}, 
									success: function (result) {
										console.log('wechat-pay-', result);
										uni.showToast({
											title: '支付成功',
											icon: 'none'
										});
										that.$emit('closeOrder', {
											order_no,
											num: that.payInfo.num,
											payParms: that.payParms
										});
									},
									fail: function (err) {
										uni.showToast({
											title: '支付失败',
											icon: 'none'
										});
									}
								});
							}
						}
					});
					// #endif
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		width: 750rpx;
		height: 100%;
		padding: 36rpx 30rpx 30rpx;
		padding-bottom: calc(190rpx + env(safe-area-inset-bottom));

		background: linear-gradient(90deg, #F2F0FF, #EDEBFF, #F3F8FF);
		z-index: 1000;
	}
	.scroll-view{
		// #ifdef MP-WEIXIN
		height: calc(100% - 120rpx);
		// #endif
		// #ifdef APP-PLUS || H5
		height: calc(100% - 95px);
		// #endif
	}
	.head-title {
		text-align: center;
		margin-bottom: 20rpx;
	}

	.pay-head {
		width: 100%;
		height: 80rpx;
		line-height: 80rpx;
		background: #D88D54;
		text-align: center;
		padding: 0 30rpx;
		font-size: 40rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
		margin-top: 30rpx;
	}

	.pay-info {
		background: #FFFFFF;
		padding: 30rpx;
		padding-bottom: 10rpx;
	}

	.pay-info_1 {
		width: 100%;
		height: 156rpx;
	}

	.pay-info-detail_1 {
		width: calc(100% - 156rpx);
		padding-left: 22rpx;
	}

	.pay-info-detail_1>view:first-child {
		width: 100%;
	}

	.pay-info-name {
		width: 70%;
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		view:first-child{
			font-size: 28rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #333333;
		}
		.newer-back{
			width: 138rpx;
			height: 36rpx;
			padding: 2px;
			background-size: 100% 100%;
			background-color: #e44f4f;
			color: #ffffff;
			font-size: 21rpx;
			border-radius: 2px;
			margin-top: 10rpx;
		}
	}

	.pay-info-price {
		font-size: 34rpx;
		font-family: Abel;
		font-weight: 400;
		color: #333333;
	}

	.pay-info-price text {
		font-size: 20rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}

	.pay-info-tip {
		width: max-content;
		padding: 0 10rpx;
		height: 32rpx;
		background: #D76474;
		font-size: 26rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
		margin-top: 12rpx;
	}

	.pay-info-item,
	.pay-type-item {
		width: 100%;
		height: 80rpx;
	}

	.pay-info-item>text:first-child {
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}

	.pay-info-item>text:last-child {
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}
	.new-class{
		width: 100%;
		height: 80rpx;
	}
	.new-first{
		color: #e44f4f;
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
	}
	.new-calc{
		display: flex;
		align-items: center;
		max-width: calc(100% - 220rpx);
		view{
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
			font-family: Source Han Sans CN;
		}
	}
	.new-calc > view:first-child{
		background-color: #F8E8D6;
		font-size: 22rpx;
		font-weight: 400;
		color: #dcae78;
		padding: 6rpx;
		border-radius: 8rpx;
	}
	.new-calc > view:last-child{
		padding-left: 6rpx;
		font-size: 28rpx;
		font-weight: 400;
		color: #e44f4f;
	}
	.coupon-view{
		display: flex;
		align-items: center;
	}
	.coupon-text{
		font-size: 12px;
		font-family: Abel-Regular, Abel;
		font-weight: 400;
		color: #EA5947;
		padding-right: 10px;
		opacity: 0.9;
	}
	.select-img{
		width: 9px;
		height: 13px;
	}
	.pay-info-item-price {
		font-size: 34rpx;
		font-family: Abel;
		font-weight: 400;
		color: #333333;
	}

	.pay-info-item-price text {
		font-size: 20rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}

	.pay-reduce {
		color: #E60012 !important;
	}

	.pay-icon {
		color: lightgray !important;
	}

	.pay-type {
		width: 100%;
		background: #FFFFFF;
		padding: 30rpx 30rpx 10rpx 30rpx;
		margin-top: 30rpx;
	}

	.pay-type-head {
		width: 100%;
		font-size: 30rpx;
		font-family: Source Han Sans CN;
		font-weight: 500;
		color: #333333;
	}

	.pay-type-name {
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
		margin-left: 10rpx;
		line-height: 8rpx;
		display: flex;
	}

	.ban-integ {
		margin-left: 10rpx;
		font-size: 24rpx;
		color: #999;
	}

	.pay-remark {
		margin-top: 30rpx;
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #999999;
	}

	.pay-confirms {
		width: 100%;
		height: calc(190rpx + env(safe-area-inset-bottom));
		padding-bottom: env(safe-area-inset-bottom);
	}

	.pay-confirm {
		width: 100%;
		height: calc(190rpx + env(safe-area-inset-bottom));
		padding: 20rpx;
		padding-bottom: env(safe-area-inset-bottom);
		background: #FFFFFF;
		box-shadow: 1rpx -4rpx 16rpx 0 rgba(30, 30, 30, 0.15);
		position: fixed;
		bottom: 0;
		left: 0;
	}

	.pay-adult {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #999999;
	}

	.pay-adult text {
		color: #333333;
	}

	.pay-detail {
		margin-top: 34rpx;
	}

	.pay-detail-left {
		height: 80rpx;
	}

	.pay-detail-left>view:first-child {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
	}

	.pay-detail-left>view:first-child text {
		font-size: 42rpx;
		font-family: Abel;
		font-weight: 400;
		color: #333333;
	}

	.pay-detail-left>view:last-child {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #999999;
	}

	.pay-button {
		width: 210rpx;
		height: 80rpx;
		background: #3F3F42;
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
		margin-left: 28rpx;
		margin-top: 10rpx;
	}

	.pop-main {
		padding: 20rpx;
	}

	.head-pop {
		height: 90rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.head-title {
		font-size: 36rpx;
		line-height: 90rpx;
	}

	.title_num {
		font-size: 28rpx;
	}

	.chose_type {
		padding: 20rpx;
		background: linear-gradient(90deg, #F2F0FF, #EDEBFF, #F3F8FF);
	}

	.footer-sure {
		width: 680rpx;
		line-height: 68rpx;
		font-size: 28rpx;
		text-align: center;
		color: #fff;
		background: #333;
		left: 0;
		right: 0;
		margin: auto;
		margin: 30rpx 0;
	}

	.icon-img {
		width: 32rpx;
		height: 32rpx;
	}
	.chose-icon {
		border-radius: 50%;
		border: 2rpx solid #999;
	}

	.goods-img {
		width: 156rpx;
		height: 156rpx;
	}
</style>