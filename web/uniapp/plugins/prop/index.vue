<template>
	<view class="container">
		<view class="head">
			<u-tabs :list="tabList" :current="currentTab" :itemStyle="tabStyle" :scrollable="false" lineColor="#8D00E6"
				lineWidth="113rpx" lineHeight="4rpx" @change="changeTab"
				:activeStyle="{fontFamily: 'Source Han Sans CN',fontWeight: '500',color:'#000000'}">
			</u-tabs>
		</view>
		<!-- @scrolltoupper="refreshList" -->
		<scroll-view class="scroll-view" scroll-y @scrolltolower="getMoreData">
			<!-- :style="{'height': `calc(100vh - ${phoneHieght} - 40px)`}" -->
			<view class="list-container">
				<view v-for="(item, index) in couponList" :key="index"
					class="list-item" :class="{'cannot-use': curValue == '2'}"
					:style="{'background-image': 'url(' + getBackImg(item) + ')'}">
					<view class="list-item-left column-center-flex">
						<!-- 满减 -->
						<view class="coupon-price" v-if="item.couponInfo.type == 1">
							<text>￥</text>
							{{ item.couponInfo.amount }}
						</view>
						<!-- 折扣 -->
						<view v-else class="coupon-price">
							{{ item.couponInfo.discount * 10 + '折' }}
						</view>
						<!-- <view class="coupon-condition">
							满200元可用
						</view> -->
					</view>
					<view class="list-item-center column-center-flex">
						<view class="coupon-name text-ellipsis">
							{{ item.couponInfo.name }}
						</view>
						<view class="coupon-range">
							{{ item.start_time.substr(0,10) + ' - ' + item.end_time.substr(0,10) }}
						</view>
					</view>
					<view class="list-item-right main-center-flex">
						<view v-if="curValue == '1'" class="btn-class"
						:class="item.couponInfo.type == 1 ? 'coupon-btn' : 'discount-btn'"
						@click="toUse">
							去使用
						</view>
						<image v-else :src="CouponsStatusObj[item.status]" class="has-use-img"></image>
					</view>
				</view>
			</view>
		</scroll-view>
		<!-- <u-loadmore :status="loadStatus" /> -->
		<!-- <view class="buy-button main-center-flex">
			<view class="buy-button-detail main-center-flex" @tap="collectCoupons">
				去领券
			</view>
		</view> -->
	</view>
</template>

<script>
	import { mapGetters, mapState } from 'vuex';
	import baseUrl from '../../utils/siteInfo';
	import { getCouponList } from '@/api/coupon.js';
	import { CouponsStatusObj } from '../../utils/objectValue';

	export default {
		data() {
			return {
				tabList: [{
						name: '可用',
						value: '1'
					},
					{
						name: '不可用',
						value: '2'
					},
				],
				currentTab: 0,
				curValue: '1',
				tabStyle: {
					width: '50%',
					height: '80rpx',
					fontSize: '28rpx',
					fontFamily: 'Source Han Sans CN',
					fontWeight: '400',
					color: '#999999',
				},
				couponList: [],
				page: 1, // 页数
				limit: 20, // 每页条目数
				total: 0,
				loadStatus: 'loading',
				hasMore: false,
				CouponsStatusObj,
				baseUrl
			}
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght'
			})
		},
		onLoad() {
			console.log('onload-ccc');
			this.getCouponList();
		},
		methods: {
			changeTab(e) {
				console.log('e----', e);
				this.currentTab = e.index;
				this.curValue = e.value;
				this.page = 1;
				this.couponList = [];
				this.getCouponList();
			},
			refreshList() {
				console.log('下拉刷新');
			},
			getMoreData() {
				console.log('上拉加载');
				if (this.hasMore) {
					this.loadStatus = 'loading';
					this.getCouponList();
				}
			},
			getCouponList() {
				getCouponList({
					page: this.page,
					limit: this.limit,
					type: this.curValue
				}).then(res => {
					const {code, data = {}} = res;
					if(code == 0) {
						const {total = 0, last_page} = data;
						this.total = total;
						if (this.page < last_page) {
							this.page++;
							this.loadStatus = 'loadmore';
							this.hasMore = true;
						} else {
							this.loadStatus = 'nomore';
							this.hasMore = false;
						}
						if (data.data.length) {
							this.couponList = this.couponList.concat(data.data);
						}
					} else {

					}
				}).catch(err => {
					uni.showToast(err.msg);
				});
			},
			getBackImg(item) {
				if(item.status === 1) {
					return CouponsStatusObj[1][item.couponInfo.type];
				} else {
					return CouponsStatusObj.hasBack;
				}
			},
			collectCoupons(e) {
				console.log('e----', e);
			},
			toUse() {
				uni.switchTab({
					url: '/pages/market/index'
				})
			}
		}
	}

</script>

<style lang="scss" scoped>
	.container {
		width: 750rpx;
		// padding-bottom: calc(130rpx + env(safe-area-inset-bottom));
		background: linear-gradient(90deg, #F2F0FF, #EDEBFF, #F3F8FF);
		z-index: 1;

		.head {
			width: 100%;
			height: 80rpx;
			background: #fff;
		}
		.scroll-view {
			height: calc(100vh - 45px);
			padding-bottom: env(safe-area-inset-bottom);
			background: linear-gradient(to bottom, #F0EFFF, #F2F6FF);
		}
		.list-container {
			width: 100%;
			height: 100%;
			padding: 30rpx;
			background: linear-gradient(to bottom, #F0EFFF, #F2F6FF);
		}

		.list-item {
			width: 100%;
			height: 200rpx;
			// background-image: var(--can_use_coupon);
			background-size: 100% 100%;
			margin-bottom: 30rpx;
			display: flex;
		}

		.cannot-use {
			filter: grayscale(1);
			opacity: 0.7;
			pointer-events: none;
		}

		.list-item-left {
			width: 188rpx;
			height: 100%;
		}

		.coupon-price {
			font-size: 32px;
			font-family: PingFang SC-Semibold;
			font-weight: Semibold;
			color: #FFFFFF;
		}

		.coupon-price>text {
			font-size: 16px;
		}

		.coupon-condition {
			font-size: 24rpx;
			font-family: PingFang SC-Regular;
			font-weight: Regular;
			color: #FFFFFF;
		}

		.list-item-center {
			// width: 390rpx;
			width: calc(100% - 188rpx - 160rpx);
			height: 100%;
			padding: 0 5rpx 0 25rpx;
		}

		.list-item-center>view {
			width: 100%;
		}

		.coupon-name {
			font-size: 14px;
			font-family: PingFang SC-Medium;
			font-weight: 600;
			color: #54575F;
		}
		.has-coupon-name{
			opacity: 55%;
		}

		.coupon-range {
			padding-top: 5px;
			font-size: 24rpx;
			font-family: PingFang SC-Regular;
			font-weight: Regular;
			color: #999999;
		}

		.coupon-time {
			font-size: 20rpx;
			font-family: Source Han Sans CN;
			font-weight: 400;
			color: #999999;
			margin-top: 20rpx;
		}

		.list-item-right {
			width: 160rpx;
			height: 100%;
			.btn-class{
				width: 60px;
				height: 22px;
				border-radius: 11px;
				text-align: center;
				font-size: 12px;
				font-family: PingFangHK-Regular;
				white-space: nowrap;
				font-weight: 500;
				line-height: 21px;
			}
			.coupon-btn {
				border: 1px solid #8F09EE;
				overflow-wrap: break-word;
				color: #8F09EC;
			}
			.discount-btn {
				border: 1px solid #EE8B34;
				overflow-wrap: break-word;
				color: #EE8C34;
			}
			.has-use-img {
				width: 68px;
				height: 56px;
			}
		}

		.buy-button {
			position: fixed;
			bottom: 0;
			left: 0;
			padding-bottom: env(safe-area-inset-bottom);
			width: 750rpx;
			height: calc(130rpx + env(safe-area-inset-bottom));
			background-color: #ffffff;
			// background: linear-gradient(99deg, #8F0CF2, #B444FE);
			// box-shadow: 1rpx -4rpx 16rpx 0 rgba(30, 30, 30, 0.15);

			.buy-button-detail {
				width: 690rpx;
				height: 98rpx;
				font-size: 38rpx;
				font-family: BTH;
				font-weight: 400;
				color: #FBF8FF;
				text-shadow: 0 2rpx 0 rgba(28, 28, 27, 0.33);
				background: linear-gradient(99deg, #8F0CF2, #B444FE);;
			}
		}
	}
</style>

