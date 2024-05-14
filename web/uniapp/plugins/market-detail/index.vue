<template>
	<page-meta :page-style="`overflow: ${buyVisible ? 'hidden':''}`">
	</page-meta>
	<view class="container" v-if="marketDetail">
		<scroll-view class="body" enableFlex scroll-y>
			<view class="swiper-container main-center-flex">
				<u-swiper :list="swiperList" :autoplay="true" radius="0" indicatorStyle="right: 30rpx;bottom: 23rpx"
					height="550rpx" @change="e => currentSwiper = e.current">
					<view slot="indicator" class="indicator-dot-container">
						{{currentSwiper + 1}}/{{swiperList.length}}
					</view>
				</u-swiper>
			</view>
			<view class="market-info">
				<view class="market-price">
					<text>{{'￥' + marketDetail.price}}</text>
					<view class="hash-text">
						<text>{{marketDetail.integral_price}}</text>
						<text>哈希币</text>
					</view>
				</view>
				<view class="market-name">
					{{marketDetail.name}}
				</view>
			</view>
			<view class="market-detail">
				<view class="market-detail-title main-start-flex">
					商品详情
				</view>
				<view class="rich-text">
					<rich-text :nodes="htmlNodes"></rich-text>
				</view>
			</view>
		</scroll-view>
		<view class="market-footer market-change">
			<view class="market-footer-button main-center-flex"
				:style="{'background-image': 'url('+hash_decompose+')'}"
				@click="buyVisible = !buyVisible">
				哈希币购买
			</view>
		</view>
		<u-popup :show="buyVisible" bgColor="#1D1F36" mode="bottom" closeable @close="buyVisible = !buyVisible">
			<view class="buy-container">
				<view class="buy-info main-start-flex">
					<view class="buy-goods-image">
						<u--image v-if="marketRule.length == 0" :showLoading="true" :src="swiperList[0]" width="160rpx" height="160rpx"
							mode="scaleToFill">
						</u--image>
						<u--image v-else :showLoading="true" :src="choseImg" width="160rpx" height="160rpx" mode="scaleToFill">
						</u--image>
					</view>
					<view class="buy-goods-detail">
						<view class="text-ellipsis_2">
							{{marketDetail.name}}
						</view>
						<view class="">
							￥{{ruleExtend.length==0?marketDetail.price:moreDetail.price}}
						</view>
						<view class="">
							{{ruleExtend.length==0?marketDetail.integral_price:moreDetail.integral_price}}
							哈希币
						</view>
					</view>
				</view>
				<view class="goods-specifications">
					<view class="rule-title" v-if="marketRule.length>0">规格</view>
					<view class="rule-container" v-for="(item,index) in marketRule" :key="index">
						<view class="rule-title">{{item.title}}</view>
						<view class="specifications-contianer">
							<view v-for="(item_1,index_1) in item.item" :key="index_1"
								class="specifications-item main-center-flex"
								:class="[selectRuleList.indexOf(item_1) != -1 ? 'specifications-selected' : 'specifications-unselect']"
								@click="selectRule(item_1,index)">
								{{item_1}}
							</view>
						</view>
					</view>
				</view>
				<view class="buy-num main-space-between">
					<view class="rule-title">购买数量</view>
					<view class="">
						<u-number-box v-model="buyNum" bgColor="#82FF80"></u-number-box>
					</view>
				</view>

				<view class="confirm-buy main-center-flex" @click="goPayPage(1)"
				:style="{'background-image': 'url('+hash_decompose+')'}" v-if="marketRule.length">
					{{clickStatus?'确定':'暂无商品'}}
				</view>
				<view class="confirm-buy main-center-flex" @click="goPayPage(2)" :style="{'background-image': 'url('+hash_decompose+')'}" v-else>
					确定
				</view>
			</view>
		</u-popup>
		<u-loading-page :loading="loadingPage" loading-text="加载中..."></u-loading-page>
	</view>
</template>

<script>
	import {
		get_market_detail
	} from '@/api/market.js'
	export default {
		data() {
			return {
				hash_decompose: this.$imgList.homeImgs.hash_decompose,
				htmlNodes: '',
				clickStatus: false,
				swiperList: [],
				choseImg: null,
				currentSwiper: 0,
				loadingPage: true,
				marketDetail: null,
				moreDetail: null,
				buyVisible: false,
				currentSpecifications: 0,
				marketRule: [],
				buyNum: 1,
				selectRuleList: [],
				ruleExtend: []
			}
		},
		computed: {
			buyDetail() {
				if (this.marketDetail && this.marketDetail.ruleExtend && this.marketDetail.ruleExtend.length > 0) {
					let data = this.marketDetail.ruleExtend.filter(item => {
						return this.selectRuleList.every(item_1 => {
							return item.sku.indexOf(item_1) != -1
						})
					})
					return data[0]
				} else {
					return null
				}

			},

		},
		watch: {
			buyVisible(newVal) {
				if (!newVal) {
					this.buyNum = 1
					this.selectRuleList = this.marketRule.map(item => {
						return item.item[0]
					})
				}
			}
		},
		onLoad(parms) {
			this.getMarketDetail(parms.id)
		},
		methods: {
			getMarketDetail(id) {
				get_market_detail({
					id
				}).then(res => {
					if (res.code == '0') {
						this.marketDetail = res.data
						this.htmlNodes = res.data.content
						this.swiperList = res.data.image.split(',')
						this.marketRule = res.data.rule.rule
						this.ruleExtend = res.data.ruleExtend
						this.selectRuleList = this.marketRule.map(item => {
							return item.item[0]
						})
						this.mateRule(this.marketRule)
					}
					this.loadingPage = false
				}).catch(err => {
					this.loadingPage = false
				})
			},
			mateRule(param) {
				let newArr = []
				for (var i = 0; i < param.length; i++) {
					newArr.push(param[i].item[0])
				}
				let sku = newArr.join('※')
				// let sku = param.length > 1 ? param[0].item[0] + '※' + param[1].item[0] : param[0].item[0]
				let arr = this.ruleExtend.filter(item => {
					return item.sku == sku
				})
				if (arr.length != 0) {
					this.clickStatus = true
					this.moreDetail = arr[0]
					this.choseImg = arr[0].image
				} else {
					this.clickStatus = false
				}
			},
			selectRule(ruleItem, index) {
				if (this.selectRuleList[index] == ruleItem) return
				this.$set(this.selectRuleList, index, ruleItem)
				let sku = this.selectRuleList.join('※')
				let arr = this.ruleExtend.filter(item => {
					return item.sku == sku
				})
				if (arr.length != 0) {
					this.clickStatus = true
					this.moreDetail = arr[0]
					this.choseImg = arr[0].image
				} else {
					this.clickStatus = false
				}

			},
			goPayPage(type) {
				let sku = this.selectRuleList.join('※')
				if (!this.clickStatus && type == 1) {
					return
				}
				if (!this.clickStatus && this.ruleExtend.length > 0) {
					return
				}
				if (this.clickStatus && this.ruleExtend.length > 0) {
					uni.navigateTo({
						url: '/pages/pay/index?goods_id=' + this.marketDetail.id + '&num=' + this.buyNum +
							'&sku=' + sku +
							'&type=goods'
					})
					this.buyVisible = false
				} else {
					uni.navigateTo({
						url: '/pages/pay/index?goods_id=' + this.marketDetail.id + '&num=' + this.buyNum +
							'&sku=' + sku +
							'&type=goods'
					})
					this.buyVisible = false
				}
			}
		}
	}

</script>

<style lang="scss" scoped>
	.container {
		width: 750rpx;
		/* #ifdef MP-WEIXIN || APP-PLUS */
		height: 100vh;
		/* #endif */
		/* #ifdef H5 */
		height: calc(100vh - 44px);
		/* #endif */
		padding: 30rpx;
		background: #1D1F36;
	}
	.body {
		width: 100%;
		/* #ifdef MP-WEIXIN */
		height: calc(100% - 130rpx - env(safe-area-inset-bottom));
		/* #endif */
		/* #ifdef APP-PLUS || H5 */
		height: calc(100% - 160rpx - env(safe-area-inset-bottom));
		/* #endif */
	}
	.swiper-container{
		width: 100%;
		height: 572rpx;
		border: 8rpx solid;
		border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 8;
		clip-path: inset(0 round 14rpx);
	}
	::v-deep .u-swiper {
		width: 100%;
	}

	.indicator-dot-container {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 78rpx;
		height: 43rpx;
		/* background: rgba(171, 171, 171, 0.6); */
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
	}

	.market-info {
		width: 100%;
		background: linear-gradient(180deg, rgba(127,251,129,0.27) 0%, rgba(126,244,129,0.05) 100%);
		margin-top: 30rpx;
		padding: 30rpx;
	}
	.hash-text{
		display: flex;
		align-items: center;
		margin-top: 26rpx;
		text{
			font-size: 32rpx;
			font-family: Source Han Sans CN;
		}
		text:first-child{
			color: #82FF80;
		}
		text:last-child{
			padding-left: 20rpx;
			color: #FFFFFF;
		}
	}
	.market-price {
		width: 100%;
	}

	.market-price>text:first-child {
		font-size: 36rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
	}

	.market-name {
		font-size: 30rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
		margin-top: 26rpx;
	}

	.market-detail {
		width: 100%;
		margin-top: 30rpx;
		padding-bottom: 30rpx;
	}

	.market-detail-title {
		width: 100%;
		height: 100rpx;
		font-size: 30rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
		padding-left: 30rpx;
	}
	.rich-text{
		color: #FFFFFF;
		padding: 0 30rpx;
	}
	.market-footer {
		width: 750rpx;
		box-shadow: 1rpx -4rpx 16rpx 0 rgba(30, 30, 30, 0.15);
		/* #ifdef MP-WEIXIN */
		height: calc(130rpx + env(safe-area-inset-bottom));
		padding: 0 30rpx env(safe-area-inset-bottom) 30rpx;
		/* #endif */
		/* #ifdef APP-PLUS || H5 */
		height: calc(170rpx + env(safe-area-inset-bottom));
		padding: 0 30rpx 30rpx 30rpx;
		/* #endif */
		position: fixed;
		bottom: 0;
		left: 0;
		background: #1D1F36;
	}

	.market-footer-button {
		width: 690rpx;
		height: 130rpx;
		background-size: 100% 100%;
		font-size: 48rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FBF8FF;
	}

	.market-footer>view:last-child {
		/* background: linear-gradient(99deg, #8F09E6, #B546FF); */
	}

	/* 	.market-footer>view:last-child {
		background: #3F3F42;
	} */

	.market-change {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.market-detail-image {
		width: 750rpx;
	}

	.buy-container {
		width: 100%;
		padding: 30rpx;
	}

	.buy-info {
		width: 100%;
	}

	.buy-goods-image {
		width: 160rpx;
		height: 160rpx;
	}

	.buy-goods-detail {
		width: calc(100% - 160rpx);
		height: 160rpx;
		padding: 0 100rpx 0 30rpx;
	}

	.buy-goods-detail>view:first-child {
		width: 100%;
		height: 50%;
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 500;
		color: #FFFFFF;
	}
	.buy-goods-detail>view:nth-child(2){
		color: #FFFFFF;
	}
	.buy-goods-detail>view:last-child {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #82FF80;
		margin-top: 20rpx;
	}

	.goods-specifications {
		width: 100%;
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #666666;
		margin-top: 100rpx;
	}

	.specifications-contianer {
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		margin-top: 30rpx;
	}

	.specifications-item {
		padding: 0 30rpx;
		height: 60rpx;
		margin-right: 30rpx;
		font-size: 25rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		margin-bottom: 30rpx;
	}
	.rule-title{
		color: #FFFFFF;
	}
	.specifications-selected {
		border: 1rpx solid #88dd87;
		color: #7ce77a;
		/* background: rgba(133, 173, 221, 0.2); */
	}

	.specifications-unselect {
		border: 1rpx solid #FFFFFF;
		color: #FFFFFF;
	}

	.buy-num {
		width: 100%;
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFFFFF;
		margin-top: 50rpx;
	}

	.confirm-buy {
		width: 690rpx;
		height: 114rpx;
		background-size: 100% 100%;
		/* background: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)); */
		font-size: 42rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FBF8FF;
		margin: 80rpx 0 0 0;
		padding-top: 6rpx;
	}

	.rule-container {
		margin-top: 30rpx;
	}

</style>

