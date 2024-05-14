<template>
	<view class="container-shipment">
		<!-- 地址 -->
		<userAddress :addressList="addressList"></userAddress>
		<!-- 发货商品 -->
		<shipmentGoods :goodsList="goodsList" :trialCalculation="trialCalculation"></shipmentGoods>
		<!-- 支付方式 -->
		<template v-if="trialCalculation">
			<view class="pay-methods">
				<view class="pay-type-head">
					支付方式
				</view>
				<radio-group @change="payTypeChange">
					<label class="pay-type-item" v-for="(item, index) in payList" :key="index">
						<view class="main-start-flex">
							<image :src="item.pic" mode="" class="icon-img"></image>
							<view class="pay-type-name">
								{{item.name}}
								<text v-if="item.pay_way=='4'" class="ban-integ">{{userInfo.balance? userInfo.balance : 0}}</text>
								<text v-if="item.pay_way=='3'" class="ban-integ">{{userInfo.integral? userInfo.integral : 0}}</text>
							</view>
						</view>
						<radio :value="item.pay_way" :checked="item.pay_way == currentPayType" color="#82FF80"
							style="transform:scale(0.8)" />
					</label>
				</radio-group>
			</view>
		</template>
		<!-- 发货规则 -->
		<view class="rules">
			若完成交易代表您已同意以下约定：<br />1、港澳台地区及部分偏远地区会无法配送。 <br />2、受疫情等因素影响，多地物流公司接单和派送受影响，物流时效性有所延长，还请谅解，如有疑问请随时联系在线客服咨询。<br />3、由于显示器，拍照和做图的过程中，产品可能发生颜色偏差，具体请以实物为准！
		</view>
		<!-- 确认按钮 -->
		<view class="confirm-btn">
			<view class="" @click="confirmShipments">
				<image :src="confirmSelectedImg" mode="aspectFill"></image>
				<text>{{ true? '确认发货' : '确认'}}</text>
			</view>
		</view>
		<!-- 确认弹窗 -->
		<modal ref="modal"></modal>
	</view>
</template>

<script>
	import userAddress from '@/components/user-address/user-address.vue';
	import shipmentGoods from '../components/shipment-goods/index.vue';
	import baseUrl from '@/utils/siteInfo.js';
	import {
		address_list
	} from '@/api/address.js';
	import {
		bagBoxTrail,
		bagBoxDeliver
	} from '@/api/warehouse.js';
	import { get_user_info } from '@/api/my.js';
	import { payConfig } from '@/api/common.js';
	
	export default {
		data() {
			return {
				confirmSelectedImg: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
				addressList: [],
				changeAddressList: [],
				goodsList: [],
				boxTrail: {
					address_id: '',
					goods_id: [],
					blindbox_id: '',
				}, // 盲盒提货试算 + 提货
				trialCalculation: 0,
				currentPayType: '4',
				userInfo: {},
				payList: [{
					pic: require('../../../static/image/pay/wx_pay.png'),
					name: '微信',
					value: 'wx_pay',
					pay_way: '1',
					key: 'wechat_pay_open'
				},
				{
					pic: require('../../../static/image/icon/banlance_pay.png'),
					name: '账户余额',
					value: 'balance_pay',
					pay_way: '4',
					key: 'balance_pay'
				}
				// #ifndef MP
				,
				{
					pic: require('../../../static/image/pay/ali_png.png'),
					name: '支付宝',
					value: 'ali_pay',
					pay_way: '2',
					key: 'alipay_open'
				}
				// #endif
				]
			}
		},
		components: {
			userAddress,
			shipmentGoods,
		},
		methods: {
			// 获取账户余额
			async getUserInfo(){
				try{
					const {code, data, msg = ''} = await get_user_info();;
					if (code == 0) {
						this.userInfo = data;
					} else {
						uni.$u.toast(msg);
					}
				}catch(e){
					//TODO handle the exception
				}
			},
			// 确认发货
			confirmShipments(){
				if(!this.addressList.length){
					uni.showToast({
						title: '请选择收件地址',
						icon: 'none',
						mask: true
					});
					return
				}
				this.$refs.modal.showModal({
					title: '是否确认发货？',
					confirm: () => {
						this.postBagBoxDeliver();
					}
				})
			},
			// 选择支付方式
			payTypeChange(e) {
				this.currentPayType = e.detail.value;
			},
			goResult() {
				let pages = getCurrentPages();
				pages.forEach(item => {
					if(item.route == 'pages/warehouse/index'){
						item.$vm.defaultSecondTab = 2;
					}
				});
				uni.switchTab({
					url: '/pages/warehouse/index',
				})
			},
			// 盲盒提货
			async postBagBoxDeliver(){
				try{
					uni.showLoading({
						title: '提货中',
						mask: true
					});
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
					this.boxTrail.platform = platform;
					this.boxTrail.pay_way = this.currentPayType;
					let res = await bagBoxDeliver(this.boxTrail);
					let data = res.data
					let order_no = res.msg
					uni.hideLoading();
					
					if (res.code == 201) {
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
								uni.showToast({
									title: '支付成功',
									icon: 'none'
								});
								
								setTimeout(() => {
									that.goResult()
								}, 500)
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
					
					if (res.code == 0) {
						setTimeout(() => {
								this.goResult()
						}, 500)
					} else {
						uni.showToast({
							title: res.msg,
							icon: 'none'
						})
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			goPay(data, order_no) {
				const that = this;
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
										uni.showToast({
											title: '支付成功',
											icon: 'none'
										});
										
										setTimeout(() => {
											that.goResult()
										}, 500)
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
										uni.showToast({
											title: '支付成功',
											icon: 'none'
										});
										
										setTimeout(() => {
											that.goResult()
										}, 500)
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
			// 支付方式开关
			async getPayConfig(){
				try{
					let res = await payConfig();
					if(res.code == 0){
						this.payList = this.payList.reduce((pre,item) => {
							res.data.forEach(el => {
								if(el.key == item.key && el.value == 1){
									pre.push(item)
								}
							});
							if(item.key == 'balance_pay'){
								pre.push(item)
							}
							return pre
						}, [])
					}
				}catch(e){
					//TODO handle the exception
				}
			},
			// 获取默认地址
			async getAddress(){
				try{
					let res = await address_list();
					if (res.code == 0) {
						this.addressList = res.data.filter(item => item.default_flag == 1);
						this.getBagBoxTrail();
					}else{
						uni.hideLoading();
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			// 盲盒提货试算
			async getBagBoxTrail(){
				try{
					this.boxTrail.address_id = this.addressList[0].id;
					let res = await bagBoxTrail(this.boxTrail);
					uni.hideLoading();
					if(res.code == 0){
						this.trialCalculation = res.data;
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			}
		},
		onLoad(options) {
			uni.showLoading({
				title: '加载中',
				mask: true
			});
			let params = JSON.parse(decodeURIComponent(options.params));
			this.goodsList = params.strArr;
			this.boxTrail.goods_id = this.goodsList.reduce((pre,item) => {
				pre.push(item.goods_id);
				return pre
			}, []).join(',');
			this.boxTrail.blindbox_id = params.id;
			this.getPayConfig();
			this.getAddress();
		},
		onShow() {
			// 获取账户余额
			this.getUserInfo();
			if(this.changeAddressList.length){
				this.addressList = [...this.changeAddressList];
				this.getBagBoxTrail();
			}
		}
	}
</script>

<style scoped lang="scss">
@import "./shipments.scss";
</style>
