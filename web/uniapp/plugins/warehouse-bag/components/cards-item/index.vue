<template>
	<view class="card-item" :class="item.checked? 'selected-item' : ''" @click="selectedItem">
		<view class="crads-img">
			<image :src="item.goods_image" mode="aspectFill"></image>
		</view>
		<view class="cards-info">
			<view class="cards-kinds">
				<view class="checked-btn">
					<text v-if="item.checked"></text>
				</view>
				<view class="name">{{ item.tag_name }}赏 X {{ item.t_total }}  {{ item.goods_name }}</view>
			</view>
			<view class="cards-value">
				<!-- <view class="decompose">
					{{ item.goods_name }}
				</view> -->
				<view class="decompose">
					分解{{ item.recovery_price }}哈希币
				</view>
				<view class="slected-num">
					选中{{ item.checked? item.t_total : '0'}}个
				</view>
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
			item: [Object],
			index: [Number,String]
		},
		methods: {
			selectedItem(){
				this.$emit('checkedItem', this.item, this.index)
			}
		}
	}
</script>

<style scoped lang="scss">
@import './index.scss';
</style>