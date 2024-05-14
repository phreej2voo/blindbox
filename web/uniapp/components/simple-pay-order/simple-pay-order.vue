<template>
	<uni-popup ref="popupOrder" type="bottom">
		<view class="simple-order-container">
			<view class="head">
				<image :src="titleImg" mode="widthFix"></image>
				<view class="text">
					支付确认
				</view>
			</view>
			<view class="amount-total">
				支付金额<text class="smaller">￥</text><text>{{ payAccoumt }}</text>
			</view>
			<!-- 支付方式 -->
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
			<!-- 确认按钮 -->
			<view class="confirm-btn" @click="confirmPay">
				<image :src="confirmSelectedImg" mode="aspectFill"></image>
				<text>确认支付</text>
			</view>
		</view>
	</uni-popup>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import { get_user_info } from '@/api/my.js';
	import { payConfig } from '@/api/common.js';
	import { createOrder } from '@/api/memberBenefits.js';
	export default {
		name:"simple-pay-order",
		data() {
			return {
				userInfo: {},
				titleImg: this.$imgList.homeImgs.titleImg,
				currentPayType: '4',
				payList: [{
					pic: require('../../static/image/pay/wx_pay.png'),
					name: '微信',
					value: 'wx_pay',
					pay_way: '1',
					key: 'wechat_pay_open'
				},
				{
					pic: require('../../static/image/icon/banlance_pay.png'),
					name: '账户余额',
					value: 'balance_pay',
					pay_way: '4',
					key: 'balance_pay'
				}
				// #ifndef MP
				,
				{
					pic: require('../../static/image/pay/ali_png.png'),
					name: '支付宝',
					value: 'ali_pay',
					pay_way: '2',
					key: 'alipay_open'
				}
				// #endif
				],
				confirmSelectedImg: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
			};
		},
		props: {
			payAccoumt: {
				type: [Number, String],
				default: 0
			},
			cardId: [String, Number]
		},
		methods: {
			open() {
				this.$refs.popupOrder.open()
			},
			close() {
				this.$refs.popupOrder.close()
			},
			// 切换支付方式
			payTypeChange(e) {
				this.currentPayType = e.detail.value
			},
			// 确认支付
			confirmPay(){
				console.log(this.payAccoumt, this.userInfo.balance, Number(this.payAccoumt) > Number(this.userInfo.balance))
				if(this.currentPayType == 4 && Number(this.payAccoumt) > Number(this.userInfo.balance)){
					uni.$u.toast('余额不足');
					return;
				}else{
					this.createPayOrder()
				}
			},
			// 创建订单
			async createPayOrder(){
				try{
					uni.showLoading({
						title: '支付中',
						mask: true,
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
					let data = {
						card_id: this.cardId,
						pay_way: this.currentPayType,
						platform: platform
					};
					let res = await createOrder(data);
					uni.hideLoading();
					if(res.code == 0){
						this.close();
						uni.redirectTo({
							url: '/plugins/member-benefits/claim-benefits/claim-benefits'
						});
					}
					setTimeout(() => {
						uni.showToast({
							title: res.msg,
							icon: 'none'
						});
					}, 50)
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
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
		},
		mounted(){
			this.getUserInfo();
			this.getPayConfig();
		}
	}
</script>

<style scoped lang="scss">
@import './simple-pay-order.scss';
</style>