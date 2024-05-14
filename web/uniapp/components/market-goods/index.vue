<template>
	<view class="list-class" :class="listData.length <= 1 ? 'one-list' : 'multiple-list'">
		<view v-for="(item, index) in listData" :key="index" class="item-class"
			@click="goDetail(type=='box'?item.goods_id:item.id)">
			<view class="top-area main-center-flex">
				<!-- 由于在nvue下，u-image名称被uni-app官方占用，在nvue页面中请使用u--image名称，在vue页面中使用u--image或者u-image均可 -->
				<u--image :showLoading="true" :src="item.image" width="238rpx" height="238rpx" mode="scaleToFill">
				</u--image>
			</view>
			<view class="bottom-area">
				<view class="title line">{{ item.name }}</view>
				<view class="standard">
					<view class="specification_1">
						<text>{{ '￥' + item.price }}</text>
					</view>
					<view v-if="item.stock > 0" class="specification_2">{{ '余' + item.stock }}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			listData: {
				type: Array,
				default: () => {
					return [];
				}
			},
			type: {
				type: String,
				default: 'detail'
			}
		},
		data() {
			return {};
		},
		methods: {
			goDetail(id) {
				if (this.type == 'box') {
					uni.navigateTo({
						url: '/plugins/box-goods-detail/index?id=' + id
					})
				} else {
					uni.navigateTo({
						url: '/plugins/market-detail/index?id=' + id
					})
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
.one-list{
	justify-content: flex-start;
}
.multiple-list{
	justify-content: space-around;
}
.list-class{
	--border-radius: 10rpx;
	--border-width: 8rpx;
	--border-color: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1));

	width: 100%;
	padding: 0 15rpx;
	display: grid;
	justify-content: space-between;
	grid-gap: 20rpx 20rpx; // 行间距 列间距
	grid-template-columns: repeat(2, 316rpx); // 重复2列 每列宽316
	// grid-template-rows: repeat(auto-fill, 200px);  // auto-fill自动填充行/列 行高列/宽
	grid-template-rows: auto; // 行高/列宽由容器的大小和行中网格元素内容的大小决定

	.item-class{
		width: 316rpx;
		margin-top: 24rpx;
		background: #000000;
	}
	.top-area{
		position: relative;
		width: 100%;
		height: 316rpx;
		padding: 10rpx 10rpx 10rpx 10rpx;
		background: #000000;
		border-radius: var(--border-radius);
		opacity: 1;
		border: 8rpx solid;
		// 8表示内向偏移量，写成和边框设置的宽度一样即可
		border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 8;
		clip-path: inset(0 round 14rpx);
	}

	.bottom-area{
		height: 156rpx;
		display: flex;
		flex-direction: column;
		justify-content: space-evenly;
		padding: 6rpx 12rpx;
	}
	.title{
		font-size: 26rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
		// display: flex;
		// align-items: center;

		line-height: 42rpx;
		height: 84rpx;
	}
	.line{
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		display: -moz-box;
		-moz-line-clamp: 2;
		-moz-box-orient: vertical;
		word-wrap: break-word;
		word-break: break-all;
		white-space: normal;
		overflow: hidden;
	}
	.standard{
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.specification_1{
		text{
			font-family: PingFang SC-Semibold, PingFang SC;
			font-size: 32rpx;
			font-weight: 600;
			color: #82FF80;
		}
	}
	.sell-out{
		position: absolute;
		width: 42rpx;
		height: 92rpx;
		border: 4rpx solid #82ff80;
		z-index: 99;
		-webkit-transform: rotate(4deg);
		transform: rotate(4deg);
		color: #FFFFFF;
		font-size: 24rpx;
		text-align: center;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.specification_2{
		font-size: 20rpx;
		font-weight: 400;
		font-family: PingFang SC-Semibold, PingFang SC;
		color: #FFFFFF;
	}
}
</style>