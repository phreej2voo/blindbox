<template>
	<view class="shipment-goods">
		<view class="goods-list">
			<view class="goods-item" v-for="(item, index) in goodsList" :key="index">
				<view class="goods-pic">
					<image :src="item.goods_image" mode="aspectFill"></image>
				</view>
				<view class="goods-info">
					<view class="goods-name">
						{{ item.goods_name }}
					</view>
					<view class="specification">
						规格： {{ item.tag_name }}赏
					</view>
					<view class="decompose" v-if="false">
						可分解121212哈希币
					</view>
					<view class="goods-num">
						<text>
							共计{{ item.t_total }}个赏品
						</text>
						<image :src="arrowright" mode="heightFix"></image>
					</view>
				</view>
			</view>
		</view>
		<!-- 运费 -->
		<view class="freight">
			<text>运费</text>
			<text>￥{{ trialCalculation }}</text>
		</view>
		<!-- 合计 -->
		<view class="total">
			<text>共{{ totalGoodsNum }}件 | 合计</text>
			<text class="bigger">￥{{ trialCalculation }}</text>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default {
		data(){
			return {
				arrowright: baseUrl.imgroot + 'static/images/wareHouse/arrowright.png',
			}
		},
		props: {
			goodsList: [Array,String],
			trialCalculation: [Number, String]
		},
		computed: {
			totalGoodsNum(){
				return this.goodsList.reduce((pre,item) => {
					pre += (Number(item.t_total)? Number(item.t_total) : 0);
					return pre
				}, 0)
			}
		}
	}
</script>

<style scoped lang="scss">
@import "./index.scss";
</style>