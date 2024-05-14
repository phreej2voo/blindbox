<template>
	<view class="tabs-list" :style="{'justify-content': justifyContent, 'top': topFixed}">
		<block v-for="(item, index) in tabList" :key="index">
			<view class="tab-item" :style="{'width': boxWidth + 'rpx', 'height': boxHeight + 'rpx'}"
			:key="index" @click="clickTab(item)"
			:class="defaultTab == item.id? 'selected-item' : ''">
				<text :style="{'width': textWidth + 'rpx', 'height': textHeight + 'rpx', 'line-height': textHeight + 'rpx'}">
					{{ item.name }}
				</text>
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		name:"tabs-list",
		data() {
			return {
			};
		},
		props: {
			tabList: Array,
			defaultTab: {
				type: [Number, String],
				default: 0
			},
			boxWidth: {
				type: [Number, String],
				default: 176
			},
			boxHeight: {
				type: [Number, String],
				default: 72
			},
			textWidth: {
				type: [Number, String],
				default: 168
			},
			textHeight: {
				type: [Number, String],
				default: 64
			},
			justifyContent: {
				type: String,
				default: 'space-between'
			},
			topFixed: {
				type: [Number, String],
				// #ifndef H5
				default: '0rpx',
				// #endif
				// #ifdef H5
				default:'44px'
				// #endif
			},
		},
		methods: {
			clickTab(e){
				this.$emit('changeTab', e)
			}
		}
	}
</script>

<style scoped lang="scss">
.tabs-list{
	width: 100%;
	height: 120rpx;
	background-color: #1D1F36;
	z-index: 9;
	position: fixed;
	// #ifdef H5
	top: 44px;
	// #endif
	// #ifndef H5
	top: 0;
	// #endif
	left: 0;
	padding: 0 30rpx;
	display: flex;
	align-items: center;
	.tab-item{
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 46rpx;
		font-size: 32rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FFF;
		background-color: #FFF;
		text{
			text-align: center;
			line-height: 58rpx;
			border-radius: 46rpx;
			background-color: #1F1F1F;
		}
	}
	.selected-item{
		background-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1));
		color: #75E276;
	}
}
</style>