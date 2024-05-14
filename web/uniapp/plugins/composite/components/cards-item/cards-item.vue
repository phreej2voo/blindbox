<template>
	<view class="item-box">
		<image :src="cardsBox" mode="aspectFill" class="border-img"></image>
		<view class="cards-box-content">
			<view class="activity-time" :style="{'background': `url(${timeBtn})`}">
				活动时间 {{ item.start_time }} 至 {{ item.end_time }}
			</view>
			<view class="cards-content">
				<!-- 合成商品的信息 -->
				<view class="cards-info">
					<view class="cards-img">
						<image :src="item.image" mode="aspectFill" ></image>
					</view>
					<view class="cards-name-price">
						<view class="crads-name">
							{{ item.name }}
						</view>
						<view class="cards-num">
							剩余<text class="green">{{ item.stock }}个</text>/<text class="purple"> {{ item.sales }}个</text>已合成
						</view>
						<view class="cards-price-btn">
							<view class="price">
								￥<text class="bigger">{{ item.price }}</text>
							</view>
							<view class="btn" @click="goSynthesis(item)">
								<image :src="compositeBtn" mode="aspectFill" class="btn-img"></image>
								<text>去合成</text>
							</view>
						</view>
					</view>
				</view>
				<!-- 进度 -->
				<view class="progress-box">
					<text>进度</text>
					<view class="progress-outBox">
						<view class="innerBox" :style="{'width': item.progress * 100 + '%'}"></view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default {
		data() {
			return {
				cardsBox: baseUrl.imgroot + 'static/images/composite/cards_box.png',
				timeBtn: baseUrl.imgroot + 'static/images/composite/time_btn.png',
				compositeBtn: baseUrl.imgroot + 'static/images/composite/composite_btn.png',
			}
		},
		props: {
			item: [String, Object]
		},
		methods: {
			// 去合成
			goSynthesis(el){
				uni.navigateTo({
					url: '/plugins/composite/advance/advance?id=' + el.id
				});
			}
		}
	}
</script>

<style scoped lang="scss">
@import "./cards-item.scss"
</style>
