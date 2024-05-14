<template>
	<view class="list-class">
		<view v-for="(item, index) in listData" :key="index" class="item-class" @click="toDrawDetail(item)">
			<view class="top-area main-center-flex">
				<!-- 由于在nvue下，u-image名称被uni-app官方占用，在nvue页面中请使用u--image名称，在vue页面中使用u--image或者u-image均可 -->
				<u--image :showLoading="true" :src="item.pic" width="238rpx" height="238rpx" mode="scaleToFill">
				</u--image>
				<view class="tag-title">{{ titleObj[listType] }}</view>
			</view>
			<view class="bottom-area">
				<view class="title">{{ item.name }}</view>
				<view class="standard">
					<view class="specification_1">
						<!-- 一个箱子有多少件 -->
						<text>{{ item.one_box_num? item.one_box_num : 0 }}</text>
						<text>/件</text>
					</view>
					<!-- 当前箱子/箱子数 -->
					<view class="specification_2">{{ item.now_box_no? item.now_box_no : 0}}{{ '/' + item.box_num + '箱' }}</view>
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
			listType: {
				type: Number | String,
				default: 1
			}
		},
		data() {
			return {
				aaa: '',
				urlObj: {
					1: '/plugins/yfs-detail/index',
					2: '/plugins/hash-detail/index',
					3: '/plugins/yfs-detail/index',
				},
				titleObj: {
					1: '一番赏',
					2: '无限赏',
					3: '全局赏',
				}
			};
		},
		methods: {
			toDrawDetail(item) {
				const url = this.urlObj[this.listType] + `?id=${item.id}&type=${this.listType}`;
				uni.navigateTo({ url });
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
		border-radius: 10rpx;
		opacity: 1;
		border: 8rpx solid;
		// 8表示内向偏移量，写成和边框设置的宽度一样即可
		border-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1)) 8;
		clip-path: inset(0 round 14rpx);

		// position: relative;
		// width: 100%;
		// height: 316rpx;
		// padding: 10rpx 10rpx 10rpx 10rpx;
		// box-sizing: border-box;
		// background: #000000;
		// border-radius: 12rpx;
		// background-clip: content-box;
		// overflow: hidden;
		// border: 8rpx solid;
		// &:before {
		// 	position: absolute;
		// 	content: "";
		// 	width: 100%;
		// 	height: 100%;
		// 	top: 0%;
		// 	left: 0%;
		// 	z-index: -1;
		// 	background-image: linear-gradient(180deg, rgba(130, 255, 128, 1), rgba(178, 108, 255, 1));
		// }
	}
	.tag-title{
		position: absolute;
		top: -2rpx;
		right: -2rpx;
		width: 122rpx;
		height: 50rpx;
		background: linear-gradient(185deg, #82FF80 0%, #4AFCF1 100%);
		transform: skewX(6deg);
		font-size: 36rpx;
		font-family: BTH;
		font-weight: 400;
		color: #171717;
		text-align: center;
		line-height: 50rpx;
	}

	.bottom-area{
		min-height: 124rpx;
		display: flex;
		flex-direction: column;
		justify-content: space-evenly;
	}
	.title{
		padding-left: 16rpx;
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
		display: flex;
		align-items: center;
	}
	.standard{
		padding-left: 16rpx;
		padding-right: 16rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.specification_1{
		text{
			font-family: PingFang SC-Semibold, PingFang SC;
			color: #FFFFFF;
		}
		text:first-child{
			font-size: 32rpx;
			font-weight: 600;
		}
		text:last-child{
			font-size: 20rpx;
			font-weight: 400;
			padding-left: 10rpx;
		}
	}
	.specification_2{
		font-size: 20rpx;
		font-weight: 400;
		font-family: PingFang SC-Semibold, PingFang SC;
		color: #FFFFFF;
	}
}
</style>