<template>
	<u-popup :show="shippingVisible" mode="center" :safeAreaInsetBottom="false" @close="closeShipping">
		<view class="shipping-container">
			<view class="inner">
				<image :src="reward_close" class="close" @click="closeShipping"></image>
				<view class="reward-title">发货详情</view>
				<scroll-view :style="{'background-image': 'url('+reward_detail+')'}"
					class="detail-view" enableFlex scroll-y>
					<view class="list">
						<text class="text-class">{{ shippingText }}</text>
					</view>
				</scroll-view>
				<view :style="{'background-image': 'url('+reward_check+')'}" class="check" @click="closeShipping">知道了</view>
			</view>
		</view>
	</u-popup>
</template>
<script>

export default {
	components: {},
	props: {
		shippingVisible: {
			type: Boolean,
			default: false
		},
		shippingType: {
			type: [String, Number],
			default: 1
		}
	},
	data() {
		return {
			reward_close: this.$imgList.homeImgs.reward_close,
			reward_detail: this.$imgList.homeImgs.reward_detail,
			reward_check: this.$imgList.homeImgs.reward_check,
			shippingText: '',
			yfsText: 
			`1.无限赏奖项从高到低依次为SP、A、B、C、D等 款式的其中一种赏品 （概率在商品列表公示）
			2.每次购买可随机获得对应价值的奖项赏品
			3.各类级别赏中赏概率是该类别赏的中赏概率，不代表具体赏品的中奖概率
			4.可选择购买“数量”会依次在赏池中随机获得对应数量的赏品
			5.赏品抽奖存在概率性，请理性消费，产品一经出售，不支持无理由退货，禁止末成年人购买
			6、赏品说明：赏品有万代、品牌等字样为该品牌 产品.其他没有标注均为祖国版
			7.部分赏品为代购商品，代购赏品会在各渠道进行 物流代发。`,
			hashText: 
			`1.无限赏奖项从高到低依次为SP、A、B、C、D等 款式的其中一种赏品 （概率在商品列表公示）
			2.每次购买可随机获得对应价值的奖项赏品
			3.各类级别赏中赏概率是该类别赏的中赏概率，不代表具体赏品的中奖概率
			4.可选择购买“数量”会依次在赏池中随机获得对应数量的赏品
			5.赏品抽奖存在概率性，请理性消费，产品一经出售，不支持无理由退货，禁止末成年人购买
			6、赏品说明：赏品有万代、品牌等字样为该品牌 产品.其他没有标注均为祖国版
			7.部分赏品为代购商品，代购赏品会在各渠道进行 物流代发。`,
		}
	},
	watch: {
		shippingVisible(newVal) {
			if(newVal) {
				if (this.shippingType == 1) {
					this.shippingText = this.yfsText;
				} else {
					this.shippingText = this.hashText;
				}
			}
		}
	},
	computed: {},
	created(){
	},
	mounted(){
	},
	methods:{
		closeShipping() {
			this.$emit('closeShipping');
		}
	}
}
</script>

<style lang="scss" scoped>
	.shipping-container{
		width: 100vw;
		height: 100vh;
		/* #ifdef MP-WEIXIN */
		padding-top: 10%;
		/* #endif */
		/* #ifndef MP-WEIXIN */
		padding-top: 26%;
		/* #endif */
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
			top: -6rpx;
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
			padding: 50rpx 20rpx;
		}
		.list{
			width: 100%;
			padding: 30rpx 40rpx;
		}
		.text-class{
			overflow-wrap: break-word;
			color: #FFFFFF;
			font-size: 28rpx;
			font-family: PingFangHK-Regular;
			line-height: 22px;
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
</style>