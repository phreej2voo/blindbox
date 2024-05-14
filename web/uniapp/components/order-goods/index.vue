<template>
	<view class="container-order">
		<view class="record-item" v-for="(item,index) in goods_list" :key="index">
			<view class="record-no">
				<text>订单号：{{ item.order_no||item.deliver_no }}</text>
				<text>{{ type == 1? '待付款': type == 2? '待发货' : '待收货' }}</text>
			</view>
			<template v-if="type == 1">
				<view class="record-kinds-series">
					<view class="record-kinds-image">
						<image :src="item.blindbox.pic" mode="aspectFill"></image>
					</view>
					<view class="record-series-info">
						<view class="text-ellipsis_2">
							{{item.blindbox.name}}
						</view>
					</view>
				</view>
			</template>
			<template v-else-if="type != 1">
				<block v-for="(item_1,index_1) in item.detail" :key="index_1">
					<view class="record-kinds-series" :key="index_1">
						<view class="record-kinds-image">
							<image :src="item_1.rewardSimple.goods_image" mode="aspectFill"></image>
						</view>
						<view class="record-series-info">
							<view class="text-ellipsis_2">
								{{item_1.rewardSimple.goods_name}}
							</view>
						</view>
					</view>
				</block>
			</template>

			<view class="open-again" v-if="type!=2">
				<view class="opera-btn" :class="isClick == 1? 'hoverBtn' : ''" v-if="type==1" @click="cancelOrder(item, 1)">
					取消订单
				</view>
				<view class="opera-btn" :class="isClick == 2? 'hoverBtn' : ''" v-if="type==1" @click="rePay(item,2)">
					去支付
				</view>
				<view class="opera-btn" :class="isClick == 3? 'hoverBtn' : ''" @click="viewLogistics(item, 3)" v-if="type==3">
					查看物流
				</view>
				<view class="opera-btn" :class="isClick == 4? 'hoverBtn' : ''" @click="confirmReceipt(item, 4)" v-if="type==3">
					确认收货
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		confirmBoxDeliver
	} from '@/api/warehouse.js'
	import {
		cancel,
		repay
	} from '@/api/order.js'
	export default {
		props: ['goods_list', 'type'],
		data() {
			return {
				limit: 10,
				page: 1,
				openInfoList: [],
				isClick: '',
			}
		},
		methods: {
			viewLogistics(item, num) {
				this.isClick = num;
				uni.navigateTo({
					url: '/plugins/view-logistics/index?id=' + item.deliver_no
				})
			},
			confirmReceipt(item, num) {
				this.isClick = num;
				// 确认收货
				if (this.type != 3) {
					return
				}
				confirmBoxDeliver({
					deliver_no: item.deliver_no
				}).then(res => {
					if (res.code == 0) {
						uni.$u.toast('收货成功')
						this.$emit('renovate', this.type)
					} else {
						uni.$u.toast(res.msg)
					}
				})
			},
			cancelOrder(item, num) {
				this.isClick = num;
				cancel({
					order_no: item.order_no
				}).then(res => {
					if (res.code == 0) {
						uni.$u.toast('操作成功')
						this.$emit('renovate', this.type)
					} else {
						uni.$u.toast(res.msg)
					}
				})

			},
			rePay(item, num) {
				this.isClick = num;
				repay({
					order_no: item.order_no,
					platform: 'miniapp'
				}).then(res => {
					// #ifdef MP-WEIXIN
					uni.requestPayment({
						provider: 'wxpay', //支付类型-固定值
						timeStamp: res.timeStamp, // 时间戳（单位：秒）
						nonceStr: res.nonceStr, // 随机字符串
						package: res.package, // 固定值
						signType: res.signType, //固定值
						paySign: res.paySign, //签名
						success: function(res_pay) {
							console.log(res_pay, '？？？？？？')
							uni.showToast({ title: '支付成功', icon: 'none' })
							// that.$emit('closeOrder', res.data.order_no, that.payParms)
						},
						fail(res_pay) {
							uni.showToast({
								title: '支付失败',
								icon: 'none'

							})
						}
					})
					// #endif
					// #ifdef APP-PLUS
					let orderInfo = {
						appid: res.appid,
						noncestr: res.noncestr,
						package: res.package, // 参数按照官网的来 写死的
						partnerid: res.partnerid,
						prepayid: res.prepayid,
						timestamp: res.timestamp,
						sign: res.sign,
					};
					uni.requestPayment({
						provider: "wxpay", // 这个参数是写死的
						orderInfo: orderInfo, //微信、支付宝订单数据
						success: function(res) {
							console.log("这里是微信支付成功的回调");
						},
						fail: function(err) {
							console.log(res);
						},
					});
					// #endif
				})
			},
		}
	}

</script>

<style scoped lang="scss">
@import './index.scss';
</style>