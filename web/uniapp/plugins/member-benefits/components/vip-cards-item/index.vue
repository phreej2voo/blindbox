<template>
	<view class="vip-cards-item">
		<image :src="vipCardsBox" mode="aspectFill" class="vip-cards-box"></image>
		<view class="vip-cards-container">
			<view class="cards-img">
				<image :src="cardsTypeBck" mode="aspectFill"></image>
				<text>{{ item.type == 1? '周卡' : '月卡'}}</text>
			</view>
			<view class="cards-info">
				<view class="cards-name">
					{{ item.title }}
				</view>
				<view class="cards-price-num">
					<view class="cards-price">
						￥{{ item.price }}
					</view>
					<view class="cards-num" v-if="item.stock != -1">
						库存<text class="green">{{ item.stock }}</text>张
					</view>
				</view>
			</view>
		</view>
		<!-- 购买 -->
		<view class="buying-btn">
			<view class="btn-box" @click="goDetail(item)">
				<image :src="vipCardsBtn" mode="aspectFill" class="vip-cards-btn"></image>
				<text>购买</text>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default{
		data(){
			return {
				vipCardsBox: baseUrl.imgroot + 'static/images/memberBenefits/vip-cards-box.png',
				vipCardsBtn: baseUrl.imgroot + 'static/images/memberBenefits/vip-cards-btn.png',
				cardsTypeBck: baseUrl.imgroot + 'static/images/memberBenefits/cards-type-bck.png',
			}
		},
		props: {
			currentTab: [String, Number],
			item: [Object]
		},
		methods: {
			goDetail(item){
				let cardsInfo = {
					description: item.description,
					title: item.title,
					price: item.price,
					type: item.type,
					id: item.id
				};
				uni.navigateTo({
					url: '/plugins/member-benefits/cards-detail/cards-detail?cardsInfo='+ encodeURIComponent(JSON.stringify(cardsInfo))
				})
			}
		}
	}
</script>

<style scoped lang="scss">
@import './index.scss';
</style>