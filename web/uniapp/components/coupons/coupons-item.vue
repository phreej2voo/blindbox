<template>
	<view>
		<radio-group @change="radioChange">
			<label v-for="(item, index) in couponListData" :key="item.id_code"
				class="list-item" :style="{'background-image': 'url(' + getBackImg(item) + ')'}">
				<view class="list-item-left column-center-flex">
					<!-- 满减 -->
					<view class="coupon-price" v-if="item.coupon_info.type == 1">
						<text>￥</text>
						{{ item.coupon_info.amount }}
					</view>
					<!-- 折扣 -->
					<view v-else class="coupon-price">
						{{ item.coupon_info.discount * 10 + '折' }}
					</view>
					<!-- <view class="coupon-condition">
						满200元可用
					</view> -->
				</view>
				<view class="list-item-center column-center-flex">
					<view class="coupon-name text-ellipsis">
						{{ item.coupon_name }}
					</view>
					<view class="coupon-range">
						{{ item.start_time.substr(0,10) + ' - ' + item.end_time.substr(0,10) }}
					</view>
				</view>
				<view class="list-item-right main-center-flex">
					<radio :value="item.id_code" :checked="index === current" />
				</view>
			</label>
		</radio-group>
	</view>
</template>

<script>
	import { CouponsStatusObj } from '@/utils/objectValue';

	export default {
		props: {
			couponList: {
				type: Array,
				default: () => {
					return [];
				}
			},
			isNoUse: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				CouponsStatusObj,
				current: -1
			};
		},
		watch: {
			isNoUse: {
				handler(newVal) {
					if (newVal) {
						this.couponListData.forEach((item, i) => {
							this.current = -1;
						});
					}
				}
			}
		},
		computed: {
			couponListData() {
				if(this.couponList && this.couponList.length) {
					return [...this.couponList];
				} else {
					return [];
				}
			}
		},
		methods: {
			radioChange(e) {
				console.log('e-', e);
				let obj = {};
				for (let i = 0; i < this.couponListData.length; i++) {
					if (this.couponListData[i].id_code === e.detail.value) {
						this.current = i;
						obj = this.couponListData[i];
						break;
					}
				}
				this.$emit('selCoupon', {value: e.detail.value, item: obj});
			},
			getBackImg(item) {
				return item.status === 1 ? CouponsStatusObj[1][item.coupon_info.type] : CouponsStatusObj.hasBack;
			},
		}
	}
</script>

<style lang="scss" scoped>
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
		width: calc(100% - 188rpx - 80rpx);
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
		width: 80rpx;
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
	/deep/ .uni-checkbox-input {
		// width: 36upx;
		// height: 36upx;
		border-radius: 50%;
		// margin-right: 0;
		border: 1px solid #888888;
	}
}
</style>