<template>
	<view class="container" :style="{'padding-top': phoneHieght}">
		<u-navbar :fixed="true" bgColor="#1D1F36">
			<template slot="left">
				<image src="../../static/image/login-icon.png" mode="" class="image-logo"></image>
			</template>
		</u-navbar>
		<scroll-view class="body" enableFlex scroll-y @scrolltolower="getMoreData" @refresherpulling="refresherpulling">
			<u-swiper bgColor="#1D1F36" :list="swiperList" keyName="pic" :autoplay="true" indicatorStyle="right: 34rpx;bottom: 17rpx"
				height="294rpx" imgMode="scaleToFill" @change="e => currentSwiper = e.current" @click="goDetail">
				<view slot="indicator" class="indicator-dot-container">
					<view class="indicator-dot" v-for="(item, index) in swiperList" :key="index"
						:class="{'indicator-dot-active' : index == currentSwiper}">
					</view>
				</view>
			</u-swiper>
			<view class="tab-class">
				<view class="tabs">
					<view v-for="(item, index) in tabsList" :key="index"
						:style="{'background-image': 'url('+(currentTab==item.value?item.selectedImg:item.img)+')'}"
						class="item-class" :class="currentTab==item.value? 'selected-icon' : 'unSelected-icon'"
						@click="changeTab(item)">
						<view class="tab-title">{{ item.label }}</view>
					</view>
				</view>
				<view :style="{'background-image': 'url('+contactImg+')'}" class="contact-class" @click="contactGroup">
					<text>交流群</text>
				</view>
			</view>
			<view class="yfs-list">
				<template v-if="goodsList.length">
					<yfs-list :listData="goodsList" :listType="currentTab"></yfs-list>
				</template>
				<template v-else>
					<empty contextTag="暂无数据"></empty>
				</template>
			</view>
		</scroll-view>
		
		<!-- 隐私协议 -->
		<!-- #ifdef MP-WEIXIN -->
		<view class="content">
			<privacyPopup></privacyPopup>
		</view>
		<!-- #endif -->
	</view>
</template>

<script>
	import checkUpdate from '@/uni_modules/uni-upgrade-center-app/utils/check-update.js';
	import { mapGetters, mapState } from 'vuex';
	import { get_home_list, getHomeAdvertisement } from '@/api/home.js';
	import YfsList from '@/components/yfs-list';
	// #ifdef MP-WEIXIN
	import privacyPopup from '@/components/privacyPopup/privacyPopup.vue';
	// #endif

	export default {
		components: {
			YfsList,
			// #ifdef MP-WEIXIN
			privacyPopup
			// #endif
		},
		data() {
			return {
				contactImg: this.$imgList.homeImgs.contact,
				currentSwiper: 0,
				swiperList: [],
				currentTab: 1, // change事件回调中可以得到index，将其赋值给current
				tabsList: [{
                    label: '一番赏',
					value: 1,
					img: this.$imgList.homeImgs.yfsUnSelected,
					selectedImg: this.$imgList.homeImgs.yfsSelected02,
                }, {
                    label: '无限赏',
					value: 2,
					img: this.$imgList.homeImgs.wuxianUnSelected,
					selectedImg: this.$imgList.homeImgs.wuxianSelected,
                },{
                    label: '全局赏',
					value: 3,
					img: this.$imgList.homeImgs.randomUnSelected,
					selectedImg: this.$imgList.homeImgs.randomSelected,
                }],
				pager: {
					page: 1,
					limit: 10,
				},
				loadingPage: true,
				loadStatus: 'loading',
				hasMore: false,
				goodsList: [],
				refreshPage: true,
				urlObj: {
					1: '/plugins/yfs-detail/index',
					2: '/plugins/hash-detail/index',
					3: '/plugins/yfs-detail/index',
				}
			}
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght'
			})
		},
		onLoad(params) {
			const {scene, agent_user_id = ''} = params;
			// 场景值
			if(scene) {
				const info = decodeURIComponent(scene);
				const arr = info.split('&');
				uni.setStorageSync('agent_user_id', arr[0].substring(5));
			}
			if(agent_user_id){
				uni.setStorageSync('agent_user_id', agent_user_id);
			}
			checkUpdate();
			this.refreshPage = true;
			this.goodsList = [];
			this.pager.page = 1;
			this.getHomeList()
		},
		onShow() {},
		onHide() {
			this.refreshPage = false;
		},
		onUnload() {},
		// #ifdef MP
		onShareAppMessage() {
			return this.$shareAppMessage({
				path: "/pages/index/index"
			});
		},
		// #endif
		onPullDownRefresh() {
			checkUpdate();
			this.getHomeList();
		},
		methods: {
			getMoreData() {
				if (this.hasMore) {
					this.loadStatus = 'loading';
					this.getHomeList();
				}
			},
			refresherpulling() {
				this.getHomeList();
			},
			// 获取首页商品列表数据
			getHomeList() {
				uni.showLoading({
					title: '加载中...', // 必须
					mask: true
				});
				get_home_list({
					page: this.pager.page,
					limit: this.pager.limit,
					play_id: this.currentTab
				}).then(res => {
					uni.hideLoading();
					const {code, data} = res;
					if (code == 0) {
						const {goods = {}, slider = []} = data;
						this.swiperList = slider;
						if (this.pager.page < goods.last_page) {
							this.pager.page++;
							this.loadStatus = 'loadmore';
							this.hasMore = true;
						} else {
							this.hasMore = false;
						}
						if (goods.data.length) {
							this.goodsList = this.goodsList.concat(goods.data);
						}
						this.loadingPage = false;
					} else {
						this.loadingPage = false;
						uni.showToast({
							title: res.msg,
							icon: 'error',
							mask: false
						})
					}
				}).catch(err => {
					uni.hideLoading();
					this.loadingPage = false;
					uni.showToast({
						title: err.msg,
						icon: 'error',
						mask: false
					})
				})
				uni.stopPullDownRefresh();
			},
			goDetail(e) {
				const url = this.urlObj[this.currentTab] + `?id=${this.swiperList[e].blindbox_id}&type=${this.currentTab}`;
				uni.navigateTo({ url });
			},
			changeTab(item) {
				this.pager.page = 1;
				this.currentTab = item.value;
				this.goodsList = [];
				this.getHomeList();
			},
			contactGroup() {
				uni.navigateTo({
					url: '/plugins/customer/index'
				});
			}
		}
	}

</script>

<style lang="scss" scoped>
	.container {
		width: 100vw;
		/* #ifdef MP-WEIXIN || APP-PLUS */
		height: 100vh;
		/* #endif */
		/* #ifdef H5 */
		height: calc(100vh - 60px);
		/* #endif */
		padding: 0 30rpx;
		background: #1D1F36;

		.body {
			overflow-y: scroll;
			padding-bottom: 30rpx;
			height: 100%;
		}
		.tab-class{
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-top: 20rpx;
		}
		.tabs{
			width: calc(100% - 250rpx);
			display: flex;
			justify-content: space-around;
		}
		.item-class{
			height: 129rpx;
			background-size: 100% 100%;
			display: flex;
			justify-content: flex-end;
			flex-direction: column;
			align-items: center;
			.tab-title{
				font-size: 26rpx;
				font-family: BTH;
				font-weight: 400;
				color: #FFFFFF;
				padding-bottom: 10rpx;
			}
		}
		.unSelected-icon{
			width: 122rpx;
		}
		.selected-icon{
			width: 157rpx;
		}
		.contact-class{
			width: 243rpx;
			height: 90rpx;
			background-size: 100% 100%;
			display: flex;
			align-items: center;
			padding-left: 96rpx;
			text{
				font-family: BTH;
				font-size: 46rpx;
				transform: rotate(-2deg);
			}
		}
		.yfs-list{
			margin-top: 16rpx;
		}
	}

	.movable-area{
		position: fixed;
		/* #ifdef MP-WEIXIN */
		top: 182rpx;
		bottom: env(safe-area-inset-bottom);
		/* #endif */
		/* #ifdef H5 || APP-PLUS */
		top: 20px;
		bottom: 60px;
		/* #endif */
		left: 0;
		width: 100%;
		/* #ifdef MP-WEIXIN */
		height: calc(100vh - 182rpx - env(safe-area-inset-bottom));
		/* #endif */
		/* #ifdef H5 || APP-PLUS */
		height: calc(100vh - 80px);
		/* #endif */
		pointer-events: none;
		z-index: 99;
		.movable-view{
			width: 100rpx;
			height: 142rpx;
			pointer-events: auto;
			position: relative;
		}
		.free-play{
			/* #ifdef MP-WEIXIN */
			// bottom: 50rpx;
			/* #endif */
			/* #ifdef H5 || APP-PLUS */
			// bottom: 66px;
			/* #endif */
			width: 100rpx;
			.menu-img{
				width: 100rpx;
				height: 106rpx;
			}
			.menu-text{
				margin-top: -8px;
				width: 100%;
				height: 44rpx;
				background-color: #8D01E6;
				border-radius: 22px;
				opacity: 0.8;
				font-size: 22rpx;
				font-family: PingFang SC-Medium, PingFang SC;
				color: #FFFFFF;
				font-weight: 400;
				text-align: center;
				line-height: 44rpx;
			}
		}
	}

	.indicator-dot-container {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.indicator-dot {
		width: 8rpx;
		height: 8rpx;
		background: #FFFFFF;
		opacity: 0.5;
		border-radius: 50%;
		transition: width 0.2s ease;
		margin-right: 5rpx;
	}

	.indicator-dot-active {
		width: 24rpx;
		height: 10rpx;
		background: #FFFFFF;
		border-radius: 5rpx;
		opacity: 1;
	}

	::v-deep .u-swiper {
		margin-top: 20rpx;
	}

	.link-image {
		width: 100%;
		height: 140rpx;
		margin-top: 30rpx;
	}

	.link-image_1 {
		clip-path: polygon(0 0, 100% 0, calc(100% - 30rpx) 100%, 0 100%);
	}

	.link-image_2 {
		clip-path: polygon(30rpx 0, 100% 0, 100% 100%, 0 100%);
	}

	.image-logo {
		/* #ifdef MP-WEIXIN */
		width: 144rpx;
		height: 86rpx;
		/* #endif */
		/* #ifdef APP-PLUS || H5 */
		width: 110rpx;
		height: 66rpx;
		/* #endif */
	}

	.center-menu{
		width: 100%;
		padding-top: 24rpx;
		.all-back{
			width: 100%;
			height: 168rpx;
			background-size: 100% 100%;
		}
		.all-back > view {
			width: 50%;
			height: 100%;
			background-size: 100% 100%;
		}
		.daily-view {
			position: relative;
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding-left: 10px;

			.daily-title{
				display: flex;
				align-items: flex-end;
				padding-left: 8rpx;
				view{
					text-align: center;
					font-family: BTH;
					color: #FFFFFF;
				}
			}
			.d-view{
				width: 112rpx;
				height: 60rpx;
				line-height: 60rpx;
				font-size: 54rpx;
				background: #5C25C5;
				-webkit-transform: skewX(-22deg);
				transform: skewX(-22deg);
			}
			.t-view{
				width: 100rpx;
				height: 46rpx;
				font-size: 42rpx;
				line-height: 46rpx;
				background: #ED40FA;
				-webkit-transform: skewX(-22deg);
				transform: skewX(-22deg);
			}
			.progress {
				width: 68%;
				// height: 45%;
				display: flex;
				align-items: center;
				padding-top: 10rpx;
			}
			.progress-line{
				position: relative;
				width: 100%;
				height: 16px;
				padding: 3px;
				background-color: #ffffff;
				border-radius: 8px;
				.progress-inner{
					height: 100%;
					background: #693AEE;
					border-radius: 10px;
					opacity: 1;
				}
				.progress-text {
					position: absolute;
					right: 7px;
					top: 0;
					font-size: 12px;
					font-family: PingFang SC-Regular, PingFang SC;
					font-weight: 400;
					color: #b7a5e8;
					line-height: 16px;
				}
			}
			.number-percent {
				margin-left: 10rpx;
			}
		}
		.list-view {
		}
		.list-text{
			margin-left: 40%;
			height: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-direction: column;
		}
		.hl-view{
			display: flex;
			align-items: flex-end;
			view{
				text-align: center;
				font-family: BTH;
				color: #FFFFFF;
			}
		}
		.ls-view{
			display: flex;
			align-items: center;
			margin-bottom: 19rpx;
			view{
				text-align: center;
				font-family: BTH;
			}
		}
		.h-view{
			width: 112rpx;
			height: 60rpx;
			line-height: 60rpx;
			font-size: 54rpx;
			background: #2340C7;
			-webkit-transform: skewX(-22deg);
			transform: skewX(-22deg);
		}
		.l-view{
			width: 78rpx;
			height: 46rpx;
			font-size: 40rpx;
			line-height: 46rpx;
			background: #1771DB;
			-webkit-transform: skewX(-22deg);
			transform: skewX(-22deg);
		}
		.limit-view{
			font-size: 26rpx;
			color: #381878;
			background: #ffffff;
			transform: skewX(-8deg);
		}
		.sprint-view{
			font-size: 24rpx;
			color: #ffffff;
			border-bottom: 1px solid #ffffff;
		}
	}

	/deep/.u-popup__content{
		background-color: transparent !important;
	}

	.ad-container{
		width: 90vw;
		height: 84vh;
		padding-top: 60rpx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		.close{
			width: 100%;
			display: flex;
			justify-content: flex-end;
			image {
				width: 64rpx;
				height: 64rpx;
			}
			// #ifdef APP-PLUS || H5
			img{
				width: 64rpx;
				height: 64rpx;
			}
			// #endif
		}
		.ad-img{
			margin-top: 20rpx;
			width: 92%;
		}
	}
</style>