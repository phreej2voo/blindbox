<template>
	<view class="cards-detail-index" v-if="cardsInfo">
		<!-- 返回上一页 -->
		<titleBack :paddingTop="paddingTop" :titleName="titleName"></titleBack>
		<!-- 背景图 -->
		<image :src="cardsDetailBck" mode="widthFix" class="cards-detail-bck"></image>
		<!-- 卡券 -->
		<view class="cards-detail-container">
			<view class="cards-info-box">
				<image :src="vipCardsBox" mode="aspectFill" class="vip-cards-box"></image>
				<image :src="cardsDetailIcon" mode="aspectFill" class="cards-detail-icon"></image>
				<view class="cards-info">
					<view class="cards-name">
						{{ cardsInfo.title }}
					</view>
					<view class="cards-content">
						每日福利大放送
					</view>
					<view class="cards-content">
						更多权益等你来领
					</view>
				</view>
			</view>
			<!-- 权益 -->
			<view class="benefits-title">
				<view class="line"></view>
				<view class="benefits-title-name">会员权益</view>
				<view class="line"></view>
			</view>
			<scroll-view scroll-y="true" class="benefits-list">
				<!-- <view v-for="item in 6" :key="item">权益 {{ item }}</view> -->
				<view>{{ cardsInfo.price }}</view>
			</scroll-view>
		</view>
		<!-- 购买按钮 -->
		<view class="buying-bottom">
			<view class="buying-box">
				<image :src="cardsDetailBottom" mode="widthFix"></image>
				<view class="buying-container">
					<view class="left">
						{{ cardsInfo.type == 2 ? '30' : ' 7'}}天
						<text class="price">￥{{ cardsInfo.price }}</text>
					</view>
					<view class="right" @click="payOrder">
						<image :src="cardsDetailBuying" mode="aspectFill"></image>
						<view class="buying">
							立即购买
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 支付弹窗 -->
		<simplePayOrder ref="simplePayOrder" :payAccoumt="cardsInfo.price" :cardId="cardsInfo.id"></simplePayOrder>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import titleBack from '@/components/title-back/title-back.vue';
	import simplePayOrder from '@/components/simple-pay-order/simple-pay-order.vue';
	export default {
		data() {
			return {
				cardsDetailBck: baseUrl.imgroot + 'static/images/memberBenefits/cards-detail-bck.png',
				vipCardsBox: baseUrl.imgroot + 'static/images/memberBenefits/vip-cards-box.png',
				cardsDetailIcon: baseUrl.imgroot + 'static/images/memberBenefits/cards-detail-icon.png',
				cardsDetailBottom: baseUrl.imgroot + 'static/images/memberBenefits/cards-detail-bottom.png',
				cardsDetailBuying: baseUrl.imgroot + 'static/images/memberBenefits/cards-detail-buying.png',
				paddingTop: 14,
				titleName: '',
				cardsInfo: null,
			}
		},
		components: {
			titleBack,
			simplePayOrder,
		},
		methods: {
			// 调出支付弹窗
			payOrder(){
				this.$refs.simplePayOrder.open();
			}
		},
		onLoad(options) {
			// #ifdef MP
			let menuButtonInfo = uni.getMenuButtonBoundingClientRect();
			this.paddingTop = (menuButtonInfo.top + menuButtonInfo.height / 2) * 2 - 21;
			// #endif
			// #ifdef APP-PLUS
			let menuButtonInfo = uni.getSystemInfoSync();
			this.paddingTop = (menuButtonInfo.safeArea.top) * 2;
			// #endif
			this.cardsInfo = JSON.parse(decodeURIComponent(options.cardsInfo));
			if(this.cardsInfo.type){
				if(this.cardsInfo.type == 1){
					this.titleName = '周卡';
				}else if(this.cardsInfo.type == 2){
					this.titleName = '月卡';
				}
			}
		}
	}
</script>

<style scoped lang="scss">
@import './cards-detail.scss';
</style>
