<template>
	<uni-popup ref="popup" type="center" backgroundColor="#1D1F36">
		<view class="pop-content">
			<image :src="publicNews" mode="aspectFill"></image>
			<view class="illustrate">
				<view class="illustrate-title">
					玩法说明
					<uni-icons class="icons" @click="close" type="close" size="38" color="#82FF80"></uni-icons>
				</view>
				<view class="illustrate-text">
					1.无限赏奖项从高到低依次为SP、A、B、C、D等 款式的其中一种赏品 （概率在商品列表公示）；<br/>2.每次购买可随机获得对应价值的奖项赏品；<br/>3.各类级别赏中赏概率是该类别赏的中赏概率，不代表具体赏品的中奖概率；<br/>4.可选择购买“数量”会依次在赏池中随机获得对应数量的赏品；<br/>5.赏品抽奖存在概率性，请理性消费，产品一经出售，不支持无理由退货，禁止末成年人购买。<br/>6、赏品说明：赏品有万代、品牌等字样为该品牌 产品.其他没有标注均为祖国版<br/>7.部分赏品为代购商品，代购赏品会在各渠道进行 物流代发。
				</view>
				<view class="already" @click="close">
					知道了
				</view>
			</view>
		</view>
	</uni-popup>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	export default {
		data(){
			return {
				publicNews: baseUrl.imgroot + 'static/images/welfare/public-news.png',
			}
		},
		methods: {
			open(){
				this.$refs.popup.open()
			},
			close() {
				this.$refs.popup.close()
			}
		}
	}
</script>

<style lang="scss" scoped>
.pop-content{
	width: 633rpx;
	height: 971rpx;
	position: relative;
	image{
		width: 100%;
		height: 100%;
		position: absolute;
	}
	.illustrate{
		width: 100%;
		height: 100%;
		position: relative;
		z-index: 9;
		font-weight: 400;
		color: #FFFFFF;
		padding: 42rpx 42rpx 10rpx 42rpx;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-content: space-between;
		font-family: YouSheBiaoTiHei-Regular, BTH;
		.illustrate-title{
			width: 100%;
			font-size: 64rpx;
			text-align: center;
			position: relative;
			.icons{
				position: absolute;
				right: 0;
			}
		}
		.illustrate-text{
			font-size: 24rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			height: 680rpx;
		}
		.already{
			width: 250rpx;
			text-align: center;
			font-size: 48rpx;
		}
	}
}
</style>