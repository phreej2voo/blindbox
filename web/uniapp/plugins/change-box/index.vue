<template>
	<view class="container">
		<view class="box-range">
			<view :style="{'background-image': 'url('+box_range+')'}">
				{{ boxInfo.min + '~' + boxInfo.max + '箱'}}
			</view>
		</view>
		<view class="tabs-class">
			<view v-for="(item, index) in tabsList" :key="index"
				class="item-class"
				@click="changeTab(item)">
				<view :class="curSort === item.value ? 'tab-active' : 'tab-inactive'">{{ item.label }}</view>
				<image :src="curSort === item.value ? sort_active : sort_inactive"></image>
			</view>
		</view>

		<scroll-view v-if="boxList.length" enableFlex scroll-y class="body">
			<view class="list-content">
				<view v-for="(item,index) in boxList" :key="index" class="child-class" @click="toBoxDetail(item)">
					<view v-if="item.status !== 1" class="sell-out-view main-center-flex">
						<view class="sell-out">售罄</view>
					</view>
					<view class="left" :style="{'background-image': 'url('+box_item_bg+')'}">
						<view>
							<text class="text_1">{{ item.box_no }}</text>
							<text class="text_2">&nbsp; 箱</text>
						</view>
						<view>
							<text class="text_2">剩&nbsp; </text>
							<text class="text_1">{{ item.stock }}</text>
							<text class="text_2">&nbsp; 张</text>
						</view>
					</view>
					<view class="right">
						<view v-for="(child, jndex) in item.detail" :key="jndex" class="tag-class">
							<text>{{ child.tag_name }}</text>
							<text>{{ child.stock + '/' + (child.stock + child.sales) }}</text>
						</view>
					</view>
				</view>
			</view>
		</scroll-view>
		<view v-else class="empty-view">
			<empty></empty>
		</view>
	</view>
</template>

<script>
	import { blindboxes } from '@/api/box.js';

	export default {
		data() {
			return {
				box_range: this.$imgList.boxImgs.box_range,
				box_item_bg: this.$imgList.boxImgs.box_item_bg,
				sort_active: this.$imgList.boxImgs.sort_active,
				sort_inactive: this.$imgList.boxImgs.sort_inactive,
				tabsList: [
					{
						label: '余量',
						value: 'stock',
					},
					{
						label: '箱号',
						value: 'box_no',
					},
				],
				blindbox_id: '', // 系列id 下面有多个box box有多个detail
				curSort: 'stock', // stock: 余量  box_no: 箱号
				boxInfo: {
					min: 0,
					max: 0,
				},
				boxList: [],
			}
		},
		computed: {

		},
		onLoad(params) {
			const {id, sort} = params;
			this.blindbox_id = id;
			this.curSort = sort;
			this.getBoxData();
		},
		methods: {
			async getBoxData() {
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					const params = {
						id: this.blindbox_id,
						sort: this.curSort
					};
					let res = await blindboxes(params);
					uni.hideLoading();
					const {code, data, msg} = res;
					if(code == 0) {
						this.boxList = [...data];
						let boxNoList = this.boxList.reduce((pre,item) => {
							pre.push(item.box_no);
							return pre
						}, []).sort((a,b) => a - b);
						console.log(boxNoList)
						if(boxNoList.length) {
							this.boxInfo.min = boxNoList[0];
							this.boxInfo.max = boxNoList[boxNoList.length - 1];
						}
					} else{
						uni.showToast({
							title: msg, // 必须
							icon: 'fail', // 默认success  error fail loading
							mask: false // 是否显示透明蒙层，防止触摸穿透，默认：false
						})
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			changeTab(item) {
				if(item.value === this.curSort) {
					return;
				}
				this.curSort = item.value;
				this.getBoxData();
			},
			toBoxDetail(item) {
				// console.log('item-', item);
				let pages = getCurrentPages();
				let prePage = pages[pages.length - 2];
				prePage.$vm.seriesInfo.now_box = item.box_no;
				prePage.$vm.changeBoxId = true;
				uni.navigateBack();
			}
		}
	}
</script>

<style lang="scss" scoped>
.container{
	width: 100vw;
	/* #ifdef MP-WEIXIN || APP-PLUS */
	height: 100vh;
	/* #endif */
	/* #ifdef H5 */
	height: calc(100vh - 60px);
	/* #endif */
	background: #1D1F36;
	padding: 0 30rpx;
	// padding-bottom: env(safe-area-inset-bottom);
	.box-range{
		width: 100%;
		height: 100rpx;
		padding: 30rpx;
		display: flex;
		align-items: center;
	}
	.box-range>view{
		width: 202rpx;
		height: 76rpx;
		background-size: 100% 100%;
		font-size: 30rpx;
		font-family: PingFang SC-Medium, PingFang SC;
		color: #1D1F36;
		text-align: center;
		line-height: 76rpx;
	}
	.tabs-class{
		width: calc(100% - 250rpx);
		display: flex;
		justify-content: flex-start;
		.item-class{
			width: 150rpx;
			height: 80rpx;
			display: flex;
			justify-content: center;
			align-items: center;
			.tab-active{
				font-size: 28rpx;
				font-family: PingFang SC-Regular, PingFang SC;
				font-weight: 400;
				color: #6AF885;
			}
			.tab-inactive{
				font-size: 28rpx;
				font-family: PingFang SC-Regular, PingFang SC;
				font-weight: 400;
				color: #FFFFFF;
			}
			image {
				width: 18rpx;
				height: 18rpx;
			}
		}
	}
	.body{
		width: 100%;
		height: calc(100% - 200rpx);
		padding: 30rpx;
		padding-bottom: env(safe-area-inset-bottom);
	}
	.list-content{
		width: 100%;
		display: flex;
		flex-wrap: wrap;
	}
	.child-class{
		width: 100%;
		height: 152rpx;
		margin-top: 30rpx;
		display: flex;
		align-items: center;
		position: relative;
		.sell-out-view{
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 99;
			background-color: $uni-bg-color-mask;
			.sell-out{
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
		}
		.left{
			width: 204rpx;
			height: 148rpx;
			display: flex;
			flex-direction: column;
			align-items: center;
			background-size: 100% 100%;

			view:first-child{
				height: 50%;
				display: flex;
				justify-content: flex-end;
				align-items: flex-end;
			}
			view:last-child{
				height: 50%;
				display: flex;
				justify-content: center;
				align-items: center;
			}
		}
		.text_1{
			font-size: 28rpx;
			font-family: PingFang SC-Medium, PingFang SC;
			font-weight: 500;
			color: #79FE93;
		}
		.text_2{
			font-size: 28rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #FFFFFF;
		}
		.right{
			width: calc(100% - 204rpx);
			height: 100%;
			display: flex;
			flex-wrap: wrap;
			padding: 10rpx 0 10rpx 30rpx;
		}
		.tag-class{
			display: flex;
			align-items: center;
			margin-left: 24rpx;
			text{
				font-size: 28rpx;
				font-family: PingFang SC-Regular, PingFang SC;
				font-weight: 400;
				color: #FFFFFF;
			}
			text:last-child{
				padding-left: 8rpx;
			}
		}
	}
	.empty-view{
		padding-top: 35%;
	}
}
</style>