<template>
	<view class="materials-item">
		<image :src="materialsBox" mode="aspectFill" class="item-bck"></image>
		<view class="item-box">
			<view class="materials-goods">
				<image :src="item.goods_image" mode="aspectFill"></image>
			</view>
			<view class="materials-name">
				{{ item.goods_name }}
			</view>
			<view class="materials-price">
				<text class="text">{{ item.conflate_val }}</text>
				<text>合成值</text>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	export default {
		data(){
			return {
				materialsBox: baseUrl.imgroot + 'static/images/composite/Group004.png',
			}
		},
		props: {
			item: [String, Object]
		}
	}
</script>

<style scoped lang="scss">
	@import "./materials-item.scss";
</style>