<template>
	<view class="recording-item">
		<view class="recording-status">
			<text class="status">{{ item.status == 1? '进行中' : item.status == 2? '已合成' : ''}}</text>
		</view>
		<view class="recording-info">
			<view class="recording-img">
				<image :src="item.conflate.image" mode="aspectFill"></image>
			</view>
			<view class="recording-name-num">
				<view class="recording-name">
					{{ item.conflate.goods_name }}
				</view>
				<view class="recording-time">
					合成时间：{{ item.create_time }}
				</view>
			</view>
		</view>
		<!-- 消耗的材料 -->
		<view class="material-list">
			<view class="material-item" v-for="(el, i) in item.detail" :key="i">
				<image :src="el.goods_image" mode="aspectFill"></image>
				<view class="material-item-name">{{ el.goods_name }}</view>
				<view class="material-item-num">x{{ el.num }}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data(){
			return {
				
			}
		},
		props: {
			item: [String, Object]
		}
	}
</script>

<style scoped lang="scss">
@import "./recording-item.scss";
</style>