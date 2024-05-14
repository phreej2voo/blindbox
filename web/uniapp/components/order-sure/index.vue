<template>
	<view class="container">
		<view class="head-title">
			订单确认
		</view>
		<scroll-view v-if="showPayInfo" class="scroll-view" enableFlex scroll-y>
			<view class="pay-info">
				<view class="pay-info_1 main-start-flex">
					<image :src="type == 'box' ? payInfo.blindbox_img : payInfo.goods_image" mode="" class="goods-img">
					</image>
					<view class="pay-info-detail_1">
						<view class="main-space-between">
							<view class="pay-info-name text-ellipsis">
								{{box_name}}
							</view>
							<view class="pay-info-price">
								<text>￥</text>
								{{payInfo.price}}
							</view>
						</view>
						<view class="pay-info-tip main-center-flex">
							买{{num}}抽
						</view>
					</view>
				</view>
				<view class="pay-info-item main-space-between">
					<text>数量</text>
					<text>x{{num}}</text>
				</view>
				<view class="pay-info-item main-space-between" @click="getUseCoupons">
					<text>优惠券</text>
					<view class="coupon-view">
						<text class="coupon-text">{{ couponValue || '请选择'}}</text>
						<image :src="SelectImg" mode="" class="select-img"></image>
					</view>
				</view>
				<u-line color="rgba(0,0,0,0.1)"></u-line>
				<view class="" v-if="userInfo.integral!=0">
					<view class="pay-info-item main-space-between">
						<text>哈希币抵扣</text>
						<text class="pay-reduce"
							@click="show=true">-￥{{currentType=='1'?hashDeduction.canDeductionAmount:'0'}} </text>

					</view>
					<u-line color="rgba(0,0,0,0.1)"></u-line>
				</view>
				<view class="pay-info-item main-space-between">
					<text>合计</text>
					<view class="pay-info-item-price">
						<text>￥</text>
						{{payInfo.total_price}}
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
		</scroll-view>
		<!-- <view class="pay-confirms"></view> -->
		<view class="pay-confirm column-align-end-flex">
			<view class="pay-adult main-start-flex">
				<image v-if="isAdult" src="../../static/image/icon/chose.png" mode="" class="icon-img"
					@click="changeSelect">
				</image>
				<view v-else class="icon-img chose-icon" @click="changeSelect">

				</view>
				<view class="consumer-pro">
					<text @click="changeSelect">
						我已满18岁，已阅读并同意 
					</text>
					<text @click="goRule">《用户协议》</text>
				</view>
			</view>
			<view class="pay-detail main-start-flex">
				<view class="pay-detail-left">
					<view class="">
						合计:￥<text>{{payInfo.total_price}}</text>
					</view>
					<view class="main-end-flex">
						共{{num}}件
					</view>
				</view>
				<view class="pay-button main-center-flex" @click="pay">
					确认支付
				</view>
			</view>
		</view>

		<modal ref="modal"></modal>

		<u-popup :show="show" mode="bottom" @close="show = !show">
			<view class="pop-main">
				<view class="head-pop">
					<view class="span">

					</view>
					<view class="head-title">
						哈希币 <text class="title_num">(剩余{{userInfo.integral}})</text>
					</view>
					<view class="" @click="show = !show">
						<u-icon name="close"></u-icon>
					</view>
				</view>
				<view class="chose_type">
					<radio-group @change="useType">
						<label class="pay-type-item main-space-between" v-for="(item, index) in ifUseList"
							:key="index">
							<view class="main-start-flex">
								<view class="pay-type-name">
									{{item.name}}
								</view>
							</view>
							<radio :value="item.pay_type" :checked="item.pay_type == currentType" color="#eb5c4a"
								style="transform:scale(0.8)" />
						</label>
					</radio-group>
				</view>
				<view class="footer-sure" @click="typeSure">
					确定
				</view>
			</view>
		</u-popup>

		<uni-popup ref="coupons" type="bottom" background-color="transparent" class="coupon-popup"
			:safe-area="false" @change="changeState">
			<view class="coupon-container">
				<view class="title-head">
					<text>选择优惠券</text>
					<view @click="closeCoupons">&#10005;</view>
				</view>
				<view class="coupon-content">
					<view class="no-use">
						<view class="no-use-view">
							<view class="no-text">不使用优惠券</view>
							<radio-group @change="noUseCoupon" class="no-checkbox">
								<radio value="no" :checked="isNoUse" />
							</radio-group>
						</view>
					</view>
					<view class="can-use">
						<coupons-item :couponList="couponList" :isNoUse="isNoUse" @selCoupon="selCoupon"></coupons-item>
					</view>
				</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	import CouponsItem from '@/components/coupons/coupons-item';
	import {
		order_trail,
		create_order,
		pay_order,
		order_shop_trail,
		create_shop_order,
		pay_shop_order
	} from '@/api/pay.js';
	import { get_user_info } from '@/api/my.js';
	import { getValidCoupon } from '@/api/coupon.js';
	import { SelectImg } from '../../utils/objectValue';
	import { isWeixinBrowser } from  '../../utils/judge';

	export default {
		props: ['blindbox_id', 'num', 'type', 'box_name'],
		components: {
			CouponsItem
		},
		data() {
			return {
				SelectImg,
				goodsInfo: null,
				currentPayType: '4',
				currentType: '1',
				show: false,
				hashDeduction: null,
				refreshPage: false,
				payInfo: {},
				payParms: {},
				isAdult: false,
				userInfo: {},
				couponList: [],
				isNoUse: false,
				couponValue: '',
				couponCode: ''
			}
		},
		onShow() {
			this.refreshPage = true
			this.payParms = {
				blindbox_id: this.blindbox_id,
				num: this.num,
				type: this.type
			}
			if (this.type == 'box') {
				this.getBoxPayInfo(this.payParms)
			} else {
				this.getGoodsPayInfo(this.payParms)
			}
		},
		onHide() {
			this.refreshPage = false
		},
		computed: {
			ifUseList() {
				let data = [{
						name: '使用哈希币',
						pay_type: '1'
					},
					{
						name: '不使用哈希币',
						pay_type: '2'
					}
				]
				if (this.payParms && this.payParms.type == 'box') {
					return data
				} else {
					return data.filter(item => {
						return item.pay_type == 1
					})
				}
			},
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
				if (this.payParms && this.payParms.type == 'box') {
					return data;
				} else {
					return data.filter(item => {
						return item.pay_way == 3;
					});
				}
			}
		},
		// onLoad() {
		// 	this.payParms = {
		// 		blindbox_id: this.blindbox_id,
		// 		num: this.num,
		// 		type: this.type
		// 	}
		// 	if (this.type == 'box') {
		// 		console.log(this.payParms, '传参')
		// 		this.getBoxPayInfo(this.payParms)
		// 	} else {
		// 		this.getGoodsPayInfo(this.payParms)
		// 	}
		// },
		methods: {
			goRule() {
				uni.navigateTo({
					url: '/plugins/user-agreement/index'
				})
			},
			initData(param) {
				console.log('init-', param);
				this.isAdult = false;
				this.goodsInfo = param
				get_user_info().then(res => {
					if (res.code == 0) {
						this.userInfo = res.data
					}
				})
				this.payParms = {
					blindbox_id: param.blindbox_id,
					num: param.num,
					type: param.type,
					use_integral: 1
				}
				if (this.type == 'box') {
					this.getBoxPayInfo(this.payParms)
				} else {
					this.getGoodsPayInfo(this.payParms)
				}
			},
			// 订单试算
			getBoxPayInfo({
				blindbox_id,
				num,
				use_integral
			}, id_code) {
				uni.showLoading({
					title: '加载中',
					mask: true
				})
				const params = {
					blindbox_id,
					num,
					use_integral
				};
				// 试算一次 保存当前使用的优惠券id_code
				this.couponCode = id_code || '';
				params.coupon_code = id_code || '';
				order_trail(params).then(res => {
					if (res.code == 0) {
						this.payInfo = res.data
						this.ifUseList[0].name = '使用' + res.data.canUseIntegral + '哈希币抵扣￥' + res.data
							.canDeductionAmount
						this.hashDeduction = {
							canDeductionAmount: res.data.canDeductionAmount,
							canUseIntegral: res.data.canUseIntegral,
						}
						uni.hideLoading()
					} else {
						uni.$u.toast(res.msg)
					}
				}).catch(err => {
					uni.hideLoading()
				})
			},
			getGoodsPayInfo({
				goods_id,
				sku,
				num
			}) {
				uni.showLoading({
					title: '加载中',
					mask: true
				})
				order_shop_trail({
					goods_id,
					sku,
					num
				}).then(res => {
					if (res.code == 0) {
						this.payInfo = res.data

						uni.hideLoading()
					} else {
						uni.$u.toast(res.msg)
					}
				}).catch(err => {
					uni.hideLoading()
				})
			},
			changeSelect () {
				this.isAdult = !this.isAdult;
			},
			payTypeChange(e) {
				this.currentPayType = e.detail.value
			},
			useType(e) {
				this.currentType = e.detail.value
			},
			typeSure() {
				this.payParms.use_integral = this.currentType
				this.getBoxPayInfo(this.payParms)

				this.show = false
			},
			changeState(e) {
				const {show = false} = e;
			},
			closeCoupons() {
				this.$refs.coupons.close();
			},
			// 不使用优惠券
			noUseCoupon(e) {
				this.isNoUse = true;
				this.couponValue = '不使用优惠券';
				this.closeCoupons();
				this.getBoxPayInfo(this.payParms);
			},
			// 选择优惠券
			selCoupon(obj) {
				const {value, item} = obj;
				this.isNoUse = false;
				if(item.coupon_info.type == 1) {
					this.couponValue = '- ￥ ' + item.coupon_info.amount;
				} else {
					this.couponValue = item.coupon_name;
				}
				// setTimeout(() => {
					this.closeCoupons();
					this.getBoxPayInfo(this.payParms, value);
				// }, 1000);
			},
			// 获取可用优惠券
			getUseCoupons() {
				this.$refs.coupons.open();
				const params = {
					blindbox_id: this.blindbox_id,
					amount: this.payInfo.total_price
				};
				getValidCoupon(params).then(res => {
					const {code, data = []} = res;
					if(code == 0) {
						this.couponList = data;
					}
				}).catch(err => {
					uni.$u.toast(err.msg || '获取优惠券失败');
					// uni.showToast({
					// 	title: err.msg || '获取优惠券失败',
					// 	icon: 'none'
					// });
				});
			},
			pay() {
				if(!this.isAdult) {
					return uni.$u.toast('请同意用户协议')
				}
				get_user_info().then(res => {
					if (res.code == 0) {
						let isTrue = true;

						// 余额支付
						if (this.currentPayType == '4') {
							const reaminPrice = this.payParms.type == 'box' ? res.data.balance : res.data.integral
							if(this.payInfo.total_price > reaminPrice) {
								isTrue = false
								uni.$u.toast('余额不足')
							}
						}
						if(isTrue) {
							this.payRequest();
						}
					}
				}).catch(err => {})
			},
			payRequest() {
				const that = this
				that.$refs.modal.showModal({
					title: '支付确认',
					content: `总价为${that.payInfo.total_price}`,
					confirm: () => {
						let data = {
							blindbox_id: that.payParms.blindbox_id,
							num: that.num,
							pay_way: that.currentPayType,
							use_integral: that.currentType
						}
						uni.showLoading({
							title: '支付中',
							mask: true
						})
						const params = {...data};
						if(that.payParms.type == 'box') {
							params.coupon_code = this.couponCode || '';
						}
						this.createOrder(params);
					}
				})
			},
			createOrder(params) {
				let createOrderMethod = this.payParms.type == 'box' ? create_order : create_shop_order;
				const that = this;
				createOrderMethod(params).then(res => {
					if (res.code == 0) {
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
						let payOrderMethod = this.payParms.type == 'box' ? pay_order : pay_shop_order
						payOrderMethod({
							order_no: res.data.order_no,
							platform
						}).then(res_order => {
							uni.hideLoading()
							// 支付宝 or 微信
							if ((this.currentPayType == 1 || this.currentPayType == 2) && res_order.code == 201) {
								// #ifdef MP-WEIXIN
								uni.requestPayment({
									provider: 'wxpay', //支付类型-固定值
									timeStamp: res_order
										.data
										.timeStamp, // 时间戳（单位：秒）
									nonceStr: res_order
										.data
										.nonceStr, // 随机字符串
									package: res_order.data
										.package, // 固定值
									signType: res_order
										.data
										.signType, // 固定值
									paySign: res_order.data
										.paySign, // 签名
									success: function(res_pay) {
										console.log(res_pay, '？？？？？？')
										uni.showToast({
											title: '支付成功',
											icon: 'none'
										})
										that.$emit('closeOrder', res.data.order_no, that.payParms)
										that.$emit('useCouponCode', that.couponCode)
									},
									fail(res_pay) {
										uni.showToast({
											title: '支付失败',
											icon: 'none'
										})
									}
								})
								// #endif
								// #ifdef APP-PLUS || H5
								that.goPay(res_order, res.data.order_no)
								// #endif
								return
							}

							if (res_order.code == 0) {
								that.$emit('closeOrder', res.data.order_no, that.payParms)
								that.$emit('useCouponCode', that.couponCode)
							} else {
								uni.$u.toast(res_order.msg)
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
			// h5 app 平台
			goPay(payRes, order_no) {
				const that = this;
				console.log('支付参数-', payRes, this.currentPayType);
				const { data } = payRes;

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
										console.log('alipay-pay-', result)
										uni.showToast({
											title: '支付成功',
											icon: 'none'
										})
										that.$emit('closeOrder', order_no, that.payParms)
										that.$emit('useCouponCode', that.couponCode)
									},
									fail: function (err) {
										uni.showToast({
											title: '支付失败',
											icon: 'none'
										})
									}
								});
							}
						}
					});
					// #endif
				} else {
					// #ifdef H5
					window.location.href = data
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
										console.log('wechat-pay-', result)
										uni.showToast({
											title: '支付成功',
											icon: 'none'
										})
										that.$emit('closeOrder', order_no, that.payParms)
										that.$emit('useCouponCode', that.couponCode)
										// uni.redirectTo({
										// 	url: '/pages/money/result?order_no=' + res.msg
										// })
									},
									fail: function (err) {
										uni.showToast({
											title: '支付失败',
											icon: 'none'
										})
										// uni.redirectTo({
										// 	url: '/pages/money/result?order_no=' + res.msg
										// })
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
		padding: 30rpx;
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
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #333333;
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
	.consumer-pro{
		
	}
	.chose-icon {
		border-radius: 50%;
		border: 2rpx solid #999;
	}

	.goods-img {
		width: 156rpx;
		height: 156rpx;
	}

	.coupon-popup {
		width: 100%;
		.coupon-container {
			width: 100%;
			// min-height: 330px;
			// max-height: 500px;
			height: 360px;
			// padding-bottom: calc(190rpx + env(safe-area-inset-bottom));
		}
		.title-head {
			position: relative;
			width: 100%;
			height: 49px;
			background-color: #ffffff;
			text-align: center;
		}
		.title-head > text {
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #333333;
			line-height: 49px;
		}
		.title-head > view {
			position: absolute;
			right: 8px;
			top: 14px;
			opacity: 1;
		}
		.coupon-content{
			width: 100%;
			height: calc(100% - 49px);
			background: linear-gradient(93deg, #F1EFFF 0%, #D7D3F4 100%);
		}
		.no-use{
			width: 100%;
			padding: 15px;
		}
		.no-use-view {
			width: 100%;
			height: 42px;
			background: #FFFFFF;
			opacity: 1;
			display: flex;
			align-items: center;
			border-radius: 4px;
		}
		.no-text {
			width: calc(100% - 30px);
			height: 100%;
			padding-left: 15px;
			line-height: 42px;
			font-size: 14px;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #54575F;
		}
		.no-checkbox {
			width: 30px;
			height: 100%;
			display: flex;
			align-items: center;
		}
		.can-use {
			padding: 15px;
			height: calc(100% - 80px);
			overflow-y: auto;
		}
	}
</style>

