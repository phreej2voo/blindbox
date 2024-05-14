
<template>
	<view>
		<view class="coupons-class" v-for="(item, i) in spendAmountProgress" :key="i">
			<view class="title-view">
				<image :src="StarPng" class="star-class"></image>
				<view class="title-view-class">
					<text class="title-class">达到</text>
					<text class="title-class amount-class">{{ Math.floor(item) }}</text>
					<text class="title-class">元领取</text>
				</view>
				<image :src="StarPng" class="star-class"></image>
			</view>
			<view class="coupons-view-content">
				<view v-for="(child, j) in couponLists[i]" :key="j" class="coupons-item">
					<image :src="CouponsImgObj[child.type]" class="img-class" mode="aspectFit"></image>
					<view class="coupons-text">
						<!-- 余额用value 其余用num -->
						<text class="coupons-text1">{{ child.name + '*' +  (child.type === 'balance' ? child.value : child.num)}}</text>
						<text v-if="child.type === 'coupon'"
							class="coupons-detail" @click="toDetail(item, child)">查看详情></text>
					</view>
					<view class="lock-img"
					:class="child.status === 3 || child.status === 4 || child.status === 5 ? 'has-get' : child.status === 1 ? 'lock-class other-img' : 'unlock-class other-img'"
						:style="{'background-image': 'url('+CouponsStatusImg[child.status]+')'}"
						@click="toGetReward(child, i, j)">
						{{ CouponsStatus[child.status] }}
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
import { CouponsObj, CouponsImgObj, CouponsStatus, CouponsStatusImg, StarPng } from '../../utils/objectValue';

export default {
	props: {
		// 任务进度 与 下方券列表长度一致
		spendAmountProgress: {
			type: Array,
			default: () => {
				return [];
			}
		},
		// 二维数组
		couponLists: {
			type: Array,
			default: () => {
				return [[]];
			}
		}
	},
	data() {
		return {
			CouponsObj,
			CouponsImgObj,
			CouponsStatus,
			CouponsStatusImg,
			StarPng
		};
	},
	// watch: {
	// 	items: {
	// 		handler(newVal) {
	// 		},
	// 		immediate: true
	// 	}
	// },
	computed: {
		isShowCoupons() {
			return this.items && Object.keys(this.lists).length;
		},
		couponsData() {
			return this.items && Object.keys(this.items).length ? {...this.items} : {};
		}
	},
	onLoad() {
	},
	methods: {
		toDetail(item, child) {
			this.$emit('toDetail', {curTask: item, ...child});
		},
		// 领取
		toGetReward(child, i, j) {
			// 2: '去领取'
			if(child.status === 2) {
				this.$emit('toGetReward', {groupId: i, rewardId: j});
			} else {
				return false;
			}
		},
		getStatusBack(status) {
			// 1:未解锁 2:去领取 3:去使用 4:已使用 5:已过期
			let cla = '';
			switch(status) {
				case '1': 
					cla = 'lock-class other-img';
					break;
				case '2': 
					cla = 'unlock-class other-img';
					break;
				case '3': 
					cla = 'has-get';
					break;
				case '4': 
					cla = 'lock-class other-img';
					break;
				case '5': 
					cla = 'lock-class other-img';
					break;
			}
			return cla;
		},
	}
}
</script>

<style lang="scss" scoped>
.coupons-class {
	width: 100%;
	margin-bottom: 15px;
	display: flex;
	flex-direction: column;
	align-items: center;
	background-size: cover;
	// background-size: 100% 100%;
	box-sizing: border-box;
	border-radius: 10px;
	background: linear-gradient(90deg, #FEDBF1 0%, #EBDDFE 100%);
	// background-image: url(http://dev-hashmart.mstshop.cn/static/images/background.png);

	.title-view {
		width: 44%;
		margin-right: 56%;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.star-class {
		width: 7px;
		height: 9px;
	}
	.title-view-class {
		padding: 0 3px;
	}
	.title-class {
		color: #821D18;
		overflow-wrap: break-word;
		font-size: 32rpx;
		font-family: PingFangHK-Semibold;
		font-weight: 600;
		text-align: left;
		white-space: nowrap;
		line-height: 30px;
	}
	.amount-class {
		color: #FF2625;
	}
	.coupons-view-content {
		width: 100%;
		padding-bottom: 15px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	.coupons-item {
		width: 90%;
		// width: 311px;
		height: 82px;
		// background-color: #ffffff;
		background-image: url(http://dev-hashmart.mstshop.cn/static/images/daily-coupon-back.png);
		background-size: 100% 100%;
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		border-radius: 8px;
		margin-top: 15px;
		padding: 10px 6px;
	}
	.img-class {
		width: 77px;
		height: 55px;
	}
	.coupons-text {
		width: 50%;
		display: flex;
		flex-direction: column;
		justify-content: flex-start;
		overflow-wrap: break-word;
		white-space: nowrap;
	}
	.has-get{
		width: 75px;
    	height: 60px;
	}
	.other-img{
		width: 50px;
		height: 50px;
	}
	.lock-img {
		background-size: cover;
		background-repeat: no-repeat center;
		display: flex;
		justify-content: center;
		align-items: center;

		overflow-wrap: break-word;
		font-size: 24rpx;
		font-family: PingFangHK-Semibold, PingFangHK;
		font-weight: 600;
		text-align: left;
		white-space: nowrap;
		line-height: 17px;
	}
	.lock-class {
		color: #858484;
	}
	.unlock-class {
		color: #FFFFFF;
	}
	.coupons-text1 {
		color: #2C0A0B;
		font-size: 34rpx;
		font-family: PingFangHK-Semibold;
		font-weight: 600;
		line-height: 25px;
		// white-space: nowrap;
		// overflow: hidden;
		// text-overflow: ellipsis;
	}
	.coupons-text2 {
		color: rgba(70, 40, 40, 1);
		font-size: 24rpx;
		font-family: PingFangHK-Regular;
		font-weight: 400;
		line-height: 17px;
	}
	.coupons-detail {
		width: 75px;
		color: rgba(255, 90, 89, 1);
		padding-top: 5px;
		font-size: 24rpx;
		font-family: PingFangHK-Medium;
		font-weight: 500;
		line-height: 17px;
	}
}
</style>