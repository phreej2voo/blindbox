<template>
	<view class="container" :style="{'padding-top': phoneHieght}">
		<u-navbar :fixed="true" bgColor="rgba(0,0,0,0)">
			<template slot="left">
				<image src="../../static/image/login-icon.png" mode="" class="image-logo"></image>
			</template>
		</u-navbar>
		<scroll-view class="body" enableFlex scroll-y @scrolltolower="getMoreData">
			<view>
				<u-swiper :list="swiperList" keyName="pic" :autoplay="true" indicatorStyle="right: 34rpx;bottom: 17rpx"
					height="294rpx" imgMode="scaleToFill" bgColor="#1D1F36"
					@change="e => currentSwiper = e.current" @click="goBuy">
					<view slot="indicator" class="indicator-dot-container">
						<view class="indicator-dot" v-for="(item, index) in swiperList" :key="index"
							:class="{'indicator-dot-active' : index === currentSwiper}">
						</view>
					</view>
				</u-swiper>
				<view class="kinds-list">
					<view class="kinds-list-item column-space-between" v-for="(item,index) in cateList" :key="index"
						@click="goCateDetail(item)">
						<view class="kinds-list-item-image main-center-flex" :style="{'background-image': 'url('+cate_bg+')'}">
							<u--image :showLoading="true" :src="item.icon" width="70rpx" height="70rpx" radius="8rpx">
							</u--image>
						</view>
						<view class="kinds-list-item-name">
							{{item.name}}
						</view>
					</view>
				</view>
				<market-goods :listData="goodsList" type="market"></market-goods>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import checkUpdate from '@/uni_modules/uni-upgrade-center-app/utils/check-update.js';
	import { mapGetters, mapState} from 'vuex';
	import { get_shop_cate, get_goods_list, slider} from '@/api/market.js';
	import MarketGoods from '@/components/market-goods/index.vue';

	export default {
		components: {
			MarketGoods
		},
		data() {
			return {
				cate_bg: this.$imgList.boxImgs.cate_bg,
				swiperList: [],
				currentSwiper: 0,
				cateList: [],
				goodsList: [],
				loadingPage: true,
				curSort: 0,
				pager: {
					page: 1,
					limit: 10,
				},
				loadStatus: 'loading',
				hasMore: false,
			}
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght',
			}),
		},
		onLoad(params) {
			const {scene, agent_user_id = ''} = params;
			// 场景值
			if(scene) {
				const info = decodeURIComponent(scene);
				const arr = info.split('&');
				console.log('arr-', arr);
				uni.setStorageSync('agent_user_id', arr[0].substring(5));
			}
			if(agent_user_id){
				uni.setStorageSync('agent_user_id', agent_user_id);
			}
			checkUpdate();
			this.getSwiper();
			this.getCateList()
			this.getGoods();
		},
		onPullDownRefresh() {
			checkUpdate();
			this.getSwiper();
			this.getCateList()
			this.getGoods();
			uni.stopPullDownRefresh();
		},
		methods: {
			goBuy(e) {
				uni.navigateTo({
					url: '/plugins/market-detail/index?id=' + this.swiperList[e].goods_id
				})
			},
			getSwiper() {
				slider().then(res => {
					if (res.code == 0) {
						this.swiperList = res.data
					}
				}).catch(err => {})
			},
			getCateList() {
				get_shop_cate().then(res => {
					if (res.code == 0) {
						this.cateList = res.data
					}
				}).catch(err => {})
			},
			goCateDetail(item) {
				uni.navigateTo({
					url: '/plugins/cate-detail/index?id=' + item.id + '&title=' + item.name
				})
			},
			getMoreData() {
				if (this.hasMore) {
					this.loadStatus = 'loading'
					this.getGoods()
				}
			},
			getGoods() {
				uni.showLoading({
					title: '加载中...', // 必须
					mask: true
				});
				get_goods_list({
					page: this.pager.page,
					limit: this.pager.limit
				}).then(res => {
					uni.hideLoading();
					const {code, data} = res;
					if (code == 0) {
						if (this.pager.page < data.last_page) {
							this.pager.page++
							this.loadStatus = 'loadmore'
							this.hasMore = true
						} else {
							this.loadStatus = 'nomore'
							this.hasMore = false
						}
						if (data.data.length) {
							const arr = [...data.data]
							for (let i = 0; i < arr.length; i++) {
								arr[i].image = arr[i].image.split(',')[0]
							}
							this.goodsList = this.goodsList.concat(arr)
						}
					}
					this.loadingPage = false
				}).catch(err => {
					this.loadingPage = false
				})
			},
			sortGoods(type) {
				switch(type) {
					case 1: break;
					case 2: break;
					case 3: break;
					case 4: 
					if(this.curSort == 2) {
						this.curSort = 0;
					} else {
						this.curSort++;
					}
					break;
				}
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
			height: 100%;
			padding-bottom: 30rpx;
			overflow-y: scroll;
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
		.tab-class{
			width: 100%;
			height: 80rpx;
			margin-top: 30rpx;
			view{
				width: 25%;
				font-size: 28rpx;
				font-family: PingFang SC-Regular, PingFang SC;
				color: #FFFFFF;
			}
		}
		.sort-class{
			height: 40rpx;
		}
		.sort-flag{
			font-size: 14rpx;
		}
		::v-deep .u-swiper {
			margin-top: 20rpx;
		}

		.kinds-list {
			width: 100%;
			margin-top: 67rpx;
			padding: 0 10rpx;
			display: flex;
			flex-wrap: wrap;
		}

		.kinds-list-item {
			width: 95rpx;
			height: 112rpx;
			margin: 0 19.5rpx;
			margin-bottom: 50rpx;
		}

		.kinds-list-item-name {
			font-size: 24rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #FFFFFF;
			white-space: nowrap;
		}

		.kinds-list-item-image {
			width: 78rpx;
			height: 78rpx;
			background-size: 100% 100%;
			padding: 8rpx;
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

		.right-logo {
			width: 250rpx;
			height: 46rpx;
		}
	}
</style>

