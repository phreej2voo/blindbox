<template>
	<u-popup :show="orderVisible" mode="bottom" :safeAreaInsetBottom="false" @close="closeOrderPopup">
		<view class="pay-container">
			<view class="head-title">
				<image :src="titleImg" class="title-img"></image>
				<view class="order-title">支付确认</view>
			</view>
			<scroll-view v-if="showPayInfo" class="scroll-view" enableFlex scroll-y>
				<view class="pay-info">
					<view class="pay-info-goods main-start-flex">
						<view class="border-img">
							<image :src="payInfo.blindbox_img" class="goods-img">
							</image>
						</view>
						<view class="pay-info-detail_1 main-space-between">
							<view>
								<view class="goods-title">{{ payParms.seriesName || '' }}</view>
								<view class="num-class goods-text">{{ 'X ' + payInfo.num + '抽' }}</view>
							</view>
							<view class="goods-text">
								￥{{ payInfo.price }}
							</view>
						</view>
					</view>
					<view class="first-info pay-info-item main-space-between" @click="getCoupons">
						<text>可使用优惠券</text>
						<view class="coupon-view">
							<text class="coupon-text" :class="couponCode ? 'sel-coupon' : ''">{{ couponName}}</text>
							<image :src="arrow_right" class="select-img"></image>
						</view>
					</view>
					<view class="pay-info-item main-space-between">
						<text>哈希币抵扣</text>
						<view class="pay-info-item-price" @click="openHashPopup">
							-￥{{currentType == '1' ? hashDeduction.canDeductionAmount : '0'}}
							<image :src="arrow_right" class="select-img" style="margin-left: 20rpx;"></image>
						</view>
					</view>
					<view class="pay-info-item main-space-between">
						<text>合计</text>
						<view class="pay-info-item-price">
							<text>￥</text>
							{{payInfo.total_price}}
						</view>
					</view>
					<view class="line-class">
						<u-line color="#FFFFFF" dashed></u-line>
					</view>
				</view>
				<view class="pay-type">
					<view class="pay-type-head main-start-flex">
						请选择支付方式
					</view>
					<radio-group @change="payTypeChange">
						<label class="pay-type-item main-space-between" v-for="(item, index) in payList" :key="index">
							<view v-if="item.status == 1" class="main-start-flex">
								<image :src="item.pic" mode="" class="icon-img"></image>
								<view class="pay-type-name">
									{{item.name}}
									<text v-if="item.pay_way=='4'" class="ban-integ">{{userInfo.balance || ''}}</text>
									<text v-if="item.pay_way=='3'" class="ban-integ">{{userInfo.integral || ''}}</text>
								</view>
							</view>
							<radio :value="item.pay_way" :checked="item.pay_way === currentPayType" color="#82FF80"
								style="transform:scale(0.8)" />
						</label>
					</radio-group>
				</view>
			</scroll-view>

			<view class="sure-pay">
				<view class="sure-area main-center-flex">
					<view :style="{'background-image': 'url('+confirmSelectedImg+')'}"
						class="sure-btn main-center-flex" @click="confirmPay">
						确认购买
					</view>
				</view>
				<view class="agreement">
					<view v-if="!isAdult" @click="changeSelect" class="no-checked"></view>
					<image v-else :src="sel" @click="changeSelect" class="ok-class"></image>
					<text class="normal left-padding" @click="changeSelect">
						我已满18岁，已阅读并同意 
					</text>
					<text class="rule" @click="goRule(1)">《用户协议》</text>
					<text class="normal">和</text>
					<text class="rule" @click="goRule(2)">《发货须知》</text>
				</view>
			</view>

			<u-popup :show="hashShow" mode="bottom" :safeAreaInsetBottom="false" @close="closeHash">
				<view class="hash-container">
					<view class="head-pop">
						<view class="head-title">
							哈希币 <text class="title_num">(剩余{{userInfo.integral}})</text>
						</view>
						<view class="close-x" @click="closeHash">
							<text>&#10005;</text>
						</view>
					</view>
					<view class="chose_type">
						<radio-group @change="useType">
							<label class="hash-type-item main-space-between" v-for="(item, index) in ifUseList"
								:key="index">
								<view class="main-start-flex">
									<view class="hash-type-name">
										{{item.name}}
									</view>
								</view>
								<radio :value="item.pay_type" :checked="item.pay_type == currentType" color="#82FF80"
									style="transform:scale(0.8)" />
							</label>
						</radio-group>
					</view>
					<view class="footer-sure main-center-flex">
						<view class="hash-btn" :style="{'background-image': 'url('+hashOkBtn+')'}"
							@click="typeSure">
							确定
						</view>
					</view>
				</view>
			</u-popup>

			<u-popup :show="couponVisible" mode="bottom" :safeAreaInsetBottom="false" @close="closeCoupon">
				<view class="coupon-container">
					<view class="title-head">
						<text>选择优惠券</text>
						<view class="close-x" @click="closeCoupon">
							<text>&#10005;</text>
						</view>
					</view>
					<scroll-view class="coupon-scroll" enableFlex scroll-y>
						<view class="no-use main-center-flex">
							<view class="no-use-content">
								<view class="no-text">不使用优惠券</view>
								<view v-if="!noUse" @click="changeNoUseStatus" class="no-use-view"></view>
								<image v-else :src="selectedImg" @click="changeNoUseStatus"></image>
							</view>
						</view>
						<view v-if="couponList.length" class="coupon-content">
							<block v-for="(item, index) in couponList" :key="index">
								<couponsList :key="index" :item="item" :defaultTab="1" @selectCoupon="selectCoupon" :index="index">
								</couponsList>
							</block>
						</view>
						<view v-else class="default">
							<empty></empty>
						</view>
					</scroll-view>
				</view>
			</u-popup>

			<modal ref="modal"></modal>
		</view>
	</u-popup>
</template>

<script>
	import { isWeixinBrowser } from  '../../utils/judge';
	import { get_user_info } from '@/api/my.js';
	import { getValidCoupon } from '@/api/coupon.js';
	import {
		order_trail,
		create_order,
		pay_order,
		order_shop_trail,
		create_shop_order,
		pay_shop_order,
		getPayType
	} from '@/api/pay.js';
	import couponsList from './coupon.vue';

	export default {
		components: {
			couponsList
		},
		props: {
			orderVisible: {
				type: Boolean,
				default: false
			},
		},
		data() {
			return {
				selectedImg: this.$imgList.homeImgs.selectedImg,
				titleImg: this.$imgList.homeImgs.titleImg,
				confirmSelectedImg: this.$imgList.homeImgs.confirmSelectedImg,
				sel: this.$imgList.homeImgs.sel,
				arrow_right: this.$imgList.homeImgs.arrow_right,
				hashOkBtn: this.$imgList.homeImgs.hash_decompose,
				userInfo: {},
				payInfo: {},
				currentPayType: '4',
				currentType: '2', // 1:使用哈希币 2:不使用
				tempCurrentType: '2',
				payParms: {}, // 支付参数
				couponList: [],
				isAdult: false,
				payList: [],
				couponVisible: false,
				noUse: true, // 不使用优惠券
				couponCode: '',
				couponName: '请选择',
				originTrailPrice: 0, // 初始试算金额
				hashShow: false,
				hashDeduction: {},
				ifUseList: [
					{
						name: '使用哈希币',
						pay_type: '1'
					},
					{
						name: '不使用哈希币',
						pay_type: '2'
					}
				],
				payPic: {
					alipay_open: require('../../static/image/pay/ali_png.png'),
					wechat_pay_open: require('../../static/image/pay/wx_pay.png'),
					balance_pay: require('../../static/image/icon/banlance_pay.png')
				},
				payName: {
					alipay_open: '支付宝',
					wechat_pay_open: '微信',
					balance_pay: '账户余额'
				},
				payWay: {
					alipay_open: '2',  // 2-支付宝
					wechat_pay_open: '1', // 1-微信
					balance_pay: '4' // 4-账户余额
				}
			};
		},
		computed: {
			showPayInfo() {
				return this.payInfo && Object.keys(this.payInfo).length;
			},
		},
		created() {},
		methods: {
			getPayList() {
				// {
				// 	"key": "alipay_open",
				// 	"value": "1"
				// }
				getPayType().then(res => {
					const {code, data} = res;
					if(code == 0) {
						let arr = [];
						// #ifdef MP-WEIXIN
						for(let i = 0; i < data.length; i++) {
							const item = data[i];
							if(item.key !== 'alipay_open') {
								arr.push({
									key: item.key,
									status: item.value, // 是否开启 1-开启 <-->value
									pic: this.payPic[item.key],
									name: this.payName[item.key],
									pay_way: this.payWay[item.key]
								});
							}
						}
						// #endif

						// #ifdef H5
						if(!isWeixinBrowser()) {
							arr = data.map(item => {
								return {
									key: item.key,
									status: item.value, // 是否开启 <-->value
									pic: this.payPic[item.key],
									name: this.payName[item.key],
									pay_way: this.payWay[item.key]
								};
							});
						} else {
							for(let i = 0; i < data.length; i++) {
								const item = data[i];
								if(item.key !== 'alipay_open') {
									arr.push({
										key: item.key,
										status: item.value, // 是否开启 <-->value
										pic: this.payPic[item.key],
										name: this.payName[item.key],
										pay_way: this.payWay[item.key]
									});
								}
							}
						}
						// #endif

						// #ifdef APP-PLUS
						arr = data.map(item => {
							return {
								key: item.key,
								status: item.value, // 是否开启 <-->value
								pic: this.payPic[item.key],
								name: this.payName[item.key],
								pay_way: this.payWay[item.key]
							};
						});
						// #endif
						
						arr.push({
							key: '账户余额',
							status: '1',
							pic: require('../../static/image/icon/banlance_pay.png'),
							name: '账户余额',
							pay_way: '4'
						});
						this.payList = arr;
					}
				}).catch(err => {});
			},
			closeOrderPopup() {
				this.$emit('closeOrder');
				this.couponCode = '';
				this.couponName = '请选择';
				this.couponList = [];
			},
			payTypeChange(e) {
				this.currentPayType = e.detail.value
			},
			useType(e) {
				this.tempCurrentType = e.detail.value
			},
			typeSure() {
				this.payParms.use_integral = this.currentType;
				this.currentType = this.tempCurrentType;
				this.trailOrder();
				this.hashShow = false;
			},
			getCoupons() {
				this.couponVisible = true;
				this.getUseCoupons();
			},
			// 选择优惠券
			selectCoupon(selIndex) {
				this.couponList.forEach((item, index) => {
					if(index == selIndex){
						item.checked = !item.checked;
					} else {
						item.checked = false;
					}
				});
				const obj = this.couponList[selIndex];
				// 选择优惠券
				if(obj.checked) {
					this.couponCode = obj.id_code;
					this.couponName = obj.coupon_name;
				} else {
					// 取消优惠券
					this.couponCode = '';
					this.couponName = '不使用优惠券';
				}
				this.noUse = false;
				this.closeCoupon();
				this.trailOrder();
				this.$forceUpdate();
			},
			changeSelect() {
				this.isAdult = !this.isAdult;
			},
			closeCoupon() {
				this.couponVisible = false;
			},
			openHashPopup() {
				this.hashShow = true;
				this.currentType = 1;
				this.trailOrder()
			},
			closeHash() {
				this.hashShow = false;
			},
			async initData(params) {
				this.isAdult = false;
				const {blindbox_id, play_id, num, box_id, seriesName} = params;
				this.payParms = {
					blindbox_id: blindbox_id,
					box_id,
					num,
					play_id: play_id, // 1-一番赏 2-无限赏
					type: 'box',
					seriesName
				};
				this.getUserInfo();
				this.getPayList();
				await this.trailOrder(true);
				this.getUseCoupons(true);
			},
			// 订单试算
			async trailOrder(isFirst = false) {
				// uni.showLoading({
				// 	title: '加载中',
				// 	mask: true
				// })
				const params = {
					blindbox_id: this.payParms.blindbox_id,
					num: this.payParms.num,
					coupon_code: this.couponCode,
					use_integral: this.currentType
				};
				const {code, data, msg} = await order_trail(params);
				uni.hideLoading();
				if (code == 0) {
					this.payInfo = {...data};
					// 初始进入不使用优惠券amount保存下来
					if(isFirst) {
						this.originTrailPrice = data.total_price;
					}
					this.ifUseList[0].name = '使用' + data.canUseIntegral + '哈希币抵扣￥' + data.canDeductionAmount;
					this.hashDeduction = {
						canDeductionAmount: data.canDeductionAmount,
						canUseIntegral: data.canUseIntegral
					};
				} else {
					// uni.$u.toast(msg);
					uni.showToast({
						title: msg,
						icon: 'error',
						mask: false // 是否显示透明蒙层，防止触摸穿透，默认：false
					})
				}
			},
			// 获取当前订单可用优惠券
			getUseCoupons(isInit) {
				if(!isInit) {
					uni.showLoading({
						title: '加载中',
						mask: true
					});
				}
				const params = {
					blindbox_id: this.payParms.blindbox_id,
					// 不使用哈希币下 使用初始试算金额 否则 使用试算金额
					amount: this.currentType == '2' ? this.originTrailPrice : this.payInfo.total_price
				};
				getValidCoupon(params).then(res => {
					uni.hideLoading()
					const {code, data = []} = res;
					if(code == 0) {
						this.couponList = data;
						if(this.couponCode) {
							for(let i = 0; i < this.couponList.length; i++) {
								if(this.couponCode == this.couponList[i].id_code) {
									this.couponList[i].checked = true;
									break;
								}
							}
						}
					}
				}).catch(err => {
					uni.hideLoading()
					uni.showToast({
						title: err.msg,
						icon: 'error',
						mask: false // 是否显示透明蒙层，防止触摸穿透，默认：false
					})
				});
			},
			changeNoUseStatus() {
				this.noUse = !this.noUse;
				this.couponList.forEach((item, index) => {
					item.checked = false;
				});
				if(this.noUse) {
					this.couponName = '不使用优惠券';
					this.couponCode = '';
				} else {
					this.couponName = '请选择';
					this.couponCode = '';
				}
				this.closeCoupon();
				this.trailOrder();
			},
			// 获取账户信息(余额)
			getUserInfo() {
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
				// 余额支付
				if (this.currentPayType == '4') {
					const reaminPrice = this.payParms.type == 'box' ? this.userInfo.balance : this.userInfo.integral;
					if(this.payInfo.total_price > reaminPrice) {
						uni.$u.toast('余额不足');
						return;
					}
				}
				// 确认支付弹框
				this.$refs.modal.showModal({
					title: '支付确认',
					content: `总价为${this.payInfo.total_price}`,
					confirm: () => {
						this.createOrder();
						this.couponCode = '';
						this.couponName = '不使用优惠券';
					}
				})
			},
			// 创建订单-获取订单号
			async createOrder(){
				uni.showLoading({
					title: '支付中',
					mask: true
				});
				const params = {
					blindbox_id: this.payParms.blindbox_id,
					num: this.payParms.num,
					pay_way: this.currentPayType,
					use_integral: this.currentType,
					coupon_code: this.couponCode || '',
					play_id: this.payParms.play_id,
					box_id: this.payParms.box_id
				};
				const createOrderMethod = this.payParms.type == 'box' ? create_order : create_shop_order;
				const {code, data, msg} = await createOrderMethod(params);
				uni.hideLoading();
				if (code == 0) {
					const {order_no = ''} = data;
					this.payRequest(order_no);
				} else {
					uni.showToast({
						title: msg,
						icon: 'error',
						mask: false
					});
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
				const payOrderMethod = this.payParms.type == 'box' ? pay_order : pay_shop_order
				const {code, data, msg = ''} = await payOrderMethod({
					order_no,
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
			},
			goRule(type) {
				if(type == 1) {
					// 用户协议
					uni.navigateTo({
						url: '/plugins/privacy-agreement/index'
					});
				} else {
					this.$emit('openShipping');
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
.pay-container{
	width: 100vw;
	height: 80vh;
	background-color: #1D1F36;
	.head-title{
		width: 100%;
		.order-title{
			text-align: center;
			margin-top: 20rpx;
			color: #FFFFFF;
			font-size: 32rpx;
			height: 32rpx;
			line-height: 32rpx;
		}
	}
	.title-img{
		width: 100%;
		height: 21rpx;
	}
	.scroll-view{
		/* #ifdef MP-WEIXIN */
		height: calc(100% - 280rpx - env(safe-area-inset-bottom));
		/* #endif */
		/* #ifdef APP-PLUS || H5 */
		height: calc(100% - 300rpx - env(safe-area-inset-bottom));
		/* #endif */
		padding: 30rpx;
	}
	.pay-info{
		padding: 30rpx;
		.pay-info-goods{
			width: 100%;
			height: 156rpx;
			.border-img{
				width: 156rpx;
				height: 156rpx;
				padding: 6rpx;
				border: 4rpx solid;
				/* 8表示内向偏移量，写成和边框设置的宽度一样即可 */
				border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 4;
				clip-path: inset(0 round 7rpx);
			}
			.goods-img {
				width: 100%;
				height: 100%;
			}
			.pay-info-detail_1{
				width: calc(100% - 156rpx);
				padding-left: 22rpx;
			}
			.pay-info-detail_1>view:first-child {
				max-width: 70%;
				flex-direction: column;
				align-items: flex-start;
				padding-left: 16rpx;
			}
			.pay-info-detail_1>view:last-child {
				max-width: 30%;
			}
			.num-class{
				padding-top: 12rpx;
			}
			.goods-title{
				color: #FFFFFF;
			}
			.goods-text{
				font-size: 30rpx;
				font-family: PingFang SC-Regular, PingFang SC;
				font-weight: 400;
				color: #A8A8A8;
			}
		}
		.first-info{
			margin-top: 24rpx;
		}
		.line-class{
			margin-top: 24rpx;
		}
		.pay-info-item{
			width: 100%;
			height: 80rpx;
		}
		.pay-info-item>text:first-child {
			font-size: 28rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #FFFFFF;
		}
		.pay-info-item-price {
			font-size: 34rpx;
			font-family: Abel;
			font-weight: 400;
			color: #82FF80;
		}
		.coupon-view{
			display: flex;
			align-items: center;
		}
		.coupon-text{
			font-size: 12px;
			font-family: Abel-Regular, Abel;
			font-weight: 400;
			color: #FFFFFF;
			padding-right: 20rpx;
			opacity: 0.9;
		}
		.sel-coupon{
			color: #82FF80;
		}
		.select-img{
			width: 16rpx;
			height: 26rpx;
		}
	}
	.pay-type {
		width: 100%;
		padding: 30rpx 30rpx 10rpx 30rpx;
		.pay-type-head {
			width: 100%;
			font-size: 30rpx;
			font-family: Source Han Sans CN;
			font-weight: 500;
			color: #FFFFFF;
		}

		.pay-type-name {
			font-size: 28rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #FFFFFF;
			margin-left: 10rpx;
			line-height: 8rpx;
			display: flex;
		}
		.icon-img {
			width: 32rpx;
			height: 32rpx;
		}
		.ban-integ {
			margin-left: 10rpx;
			font-size: 24rpx;
			color: #999;
		}
		.pay-type-item {
			width: 100%;
			height: 80rpx;
		}

		.pay-info-item>text:first-child {
			font-size: 28rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #FFFFFF;
		}

		.pay-info-item>text:last-child {
			font-size: 28rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #FFFFFF;
		}
	}
	.sure-pay{
		position: fixed;
		bottom: 0;
		left: 0;
		width: 100%;
		/* #ifdef MP-WEIXIN */
		height: calc(180rpx + env(safe-area-inset-bottom));
		padding-bottom: env(safe-area-inset-bottom);
		/* #endif */
		/* #ifdef H5 || APP-PLUS */
		height: calc(200rpx + env(safe-area-inset-bottom));
		padding-bottom: 30rpx;
		/* #endif */
		.sure-area{
			font-family: BTH;
			color: #FFFFFF;
		}
		.sure-btn{
			width: 605rpx;
			height: 106rpx;
			background-size: 100% 100%;
			font-size: 46rpx;
		}
		.agreement{
			width: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
			margin-top: 20rpx;
			text{
				font-size: 24rpx;
				font-family: PingFang SC-Regular, PingFang SC;
			}
		}
		.no-checked{
			width: 36rpx;
			height: 36rpx;
			border: 2rpx solid #BDBDBD;
			border-radius: 18rpx;
		}
		.ok-class{
			width: 36rpx;
			height: 36rpx;
		}
		.normal{
			color: #FFFFFF;
		}
		.left-padding{
			padding-left: 10rpx;
		}
		.rule{
			color: #82FF80;
		}
	}

	.coupon-container{
		width: 100vw;
		height: 70vh;
		background-color: #1D1F36;
		/* #ifdef APP-PLUS || H5 */
		padding-bottom: 15px;
		/* #endif */
		.title-head {
			position: relative;
			width: 100%;
			height: 98rpx;
			text-align: center;
		}
		.title-head > text {
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 98rpx;
		}
		.close-x{
			position: absolute;
			color: #82FF80;
			right: 34rpx;
			top: 36rpx;
			opacity: 1;
			width: 36rpx;
			height: 36rpx;
			border-radius: 18rpx;
			border: 2rpx solid #82FF80;
			text-align: center;
			line-height: 24rpx;
			text{
				font-size: 22rpx;
			}
		}
		.coupon-scroll{
			width: 100%;
			height: calc(100% - 100rpx);
			padding-bottom: env(safe-area-inset-bottom);
		}
		.no-use{
			width: 100%;
			height: 84rpx;
			.no-use-content{
				width: 656rpx;
				height: 100%;
				padding: 0 15rpx;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.no-text {
				font-size: 28rpx;
				font-family: PingFang SC-Regular, PingFang SC;
				font-weight: 400;
				color: #FFFFFF;
			}
			.no-use-view{
				width: 36rpx;
				height: 36rpx;
				border-radius: 18rpx;
				border: 2rpx solid #82FF80;
				background-color: #FFFFFF;
			}
			image{
				width: 36rpx;
				height: 36rpx;
			}
		}
		.coupon-content{
			width: 100%;
			height: calc(100% - 84rpx);
			margin-top: 20rpx;
			display: flex;
			justify-content: center;
			flex-wrap: wrap;
		}
		.default {
			width: 100%;
		}
	}

	.hash-container{
		width: 100vw;
		background-color: #1D1F36;
		padding-bottom: env(safe-area-inset-bottom);
		.head-pop {
			position: relative;
			height: 90rpx;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}
		.head-title {
			font-size: 36rpx;
			line-height: 90rpx;
			color: #FFFFFF;
			text-align: center;
		}
		.title_num {
			font-size: 28rpx;
		}
		.close-x{
			position: absolute;
			color: #82FF80;
			right: 34rpx;
			top: 36rpx;
			opacity: 1;
			width: 36rpx;
			height: 36rpx;
			border-radius: 18rpx;
			border: 2rpx solid #82FF80;
			text-align: center;
			line-height: 24rpx;
			text{
				font-size: 22rpx;
			}
		}
		.chose_type {
			padding: 20rpx 50rpx;
		}
		.hash-type-item{
			width: 100%;
			height: 80rpx;
		}
		.hash-type-name {
			font-size: 28rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #FFFFFF;
			margin-left: 10rpx;
			line-height: 8rpx;
			display: flex;
		}
		.footer-sure {
			width: 100%;
			height: 130rpx;
			.hash-btn{
				width: 626rpx;
				height: 117rpx;
				background-size: 100% 100%;
				color: #FFFFFF;
				font-family: BTH;
				font-size: 48rpx;
				text-align: center;
				line-height: 130rpx;
			}
		}
	}
}
</style>