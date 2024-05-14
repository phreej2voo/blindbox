<template>
	<view class="container" v-if="Object.keys(userLevelData).length">
		<view class="top-area">
			<image v-if="showLeft" class="left-btn" :style="{'background-image': 'url('+CenterChange.left+')'}"
				@click="preChange"></image>
			<image v-if="showRight" class="right-btn" :style="{'background-image': 'url('+CenterChange.right+')'}"
				@click="nextChange"></image>
			<view class="level-card" :style="{'background-image': 'url('+userLevelData.card_bg+')'}">
				<view class="card-top-area">
					<view class="top-left-area">
						<view>
							<text class="text-wrap text_1">{{ 'V' + userLevelData.level }}</text>
							<text class="text-wrap text_2">{{ userLevelData.level_title }}</text>
						</view>
						<view v-if="userLevelData.is_lock" class="text-wrap text_3">已解锁</view>
						<view v-else class="text-wrap text_3">{{`未解锁 . 成长值需达到${userLevelData.level_experience}`}}</view>
					</view>
					<image class="top-right-area" :src="userLevelData.icon"></image>
				</view>
				<view v-if="nextLevelExperience" class="card-bottom-area">{{`还需要${nextLevelExperience}成长值可升级`}}</view>
			</view>
			<view class="equity-class">
				<view class="equity-title">
					<text class="title_1">{{ 'V' + userLevelData.level }}</text>
					<text class="title_2">{{`共享${couponsList.length}项权益`}}</text>
				</view>
				<view class="uni-row-class">
					<uni-row>
						<uni-col v-for="(item,index) in couponsList" :key="index" :span="24/couponsList.length">
							<view class="col-class">
								<image :src="UserLevelRights[item.type]" class="col-img1"></image>
								<view class="col-title">{{ rightsType[item.type] }}</view>
								<view class="col-desc">{{ rightsTypeDesc[item.type] }}</view>
							</view>
						</uni-col>
					</uni-row>
				</view>
			</view>
		</view>
		<view class="empty-view"></view>
		<!-- 本月专属优惠券 -->
		<view class="bottom-area">
			<view class="title-area">
				<text class="left_area">本月专属券</text>
			</view>
			<view class="coupons-list">
				<u-empty v-if="show" text="暂无数据" mode="list"></u-empty>
				<coupons v-else :couponsList="couponsList"></coupons>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapGetters, mapState} from 'vuex';
	import Coupons from '@/components/coupons/index';
	import { getUserLevel } from '@/api/user.js';
	import { UserLevelRights, CenterChange } from '../../utils/objectValue';

	export default {
		components:{
			Coupons
		},
		data() {
			return {
				show: false,
				isNavWhite: false,
				UserLevelRights,
				CenterChange,
				rightsType: {
					coupon: '盲盒优惠券',
					prop: '重抽卡',
					blindbox: '惊喜盲盒'
				},
				rightsTypeDesc: {
					coupon: '无门槛优惠券',
					prop: '可再抽一次',
					blindbox: '必中保底盒'
				},
				curLevel: 0, // 默认传0
				userLevelData: {},
				couponsList: [],
				leftBtn: '<',
				rightBtn: '>'
			};
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght'
			}),
			showLeft() {
				return this.preLevel !== -1;
			},
			showRight() {
				return this.nextLevel !== -1;
			},
			nextLevel() {
				const {next_level = -1} = this.userLevelData;
				return next_level;
			},
			preLevel() {
				const {pre_level = -1} = this.userLevelData;
				return pre_level;
			},
			nextLevelExperience() {
				const {next_level_experience = 0} = this.userLevelData;
				return next_level_experience;
			},
		},
		onShow() {},
		onLoad(options) {
			if(options.userLevel){
				this.curLevel = options.userLevel;
			}
			this.getUserLevel(this.curLevel);
		},
		methods: {
			getUserLevel(level) {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				getUserLevel({level}).then(res => {
					const {code, data} = res;
					uni.hideLoading();
					if(code == 0) {
						this.userLevelData = {...data};
						if(data.present_list.length) {
							this.show = false;
						} else  {
							this.show = true;
						}
						this.couponsList = data.present_list;
					}
				}).catch(err => {
					
				});
			},
			preChange() {
				if(this.preLevel !== -1) {
					this.getUserLevel(this.preLevel);
				} else {
					return false;
				}
			},
			nextChange() {
				if(this.nextLevel !== -1) {
					this.getUserLevel(this.nextLevel);
				} else {
					return false;
				}
			},
		}
	}
</script>

<style lang="scss" scoped>
.container {
	width: 750rpx;
	height: 100vh;
	background: linear-gradient(to bottom, #F0EFFF, #F1F4FF);
    z-index: 1;

	.top-area {
		background-color: #48495B;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;

		.left-btn {
			width: 19px;
			height: 19px;
			position: absolute;
			left: 8px;
			top: 100px;
		}
		.right-btn {
			width: 19px;
			height: 19px;
			position: absolute;
			right: 8px;
			top: 100px;
		}
		.level-card {
			width: calc(100% - 100px);
			height: 162px;
			margin-top: 30px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			background-size: 100% 100%;
			background-repeat: no-repeat center;
			background-image: url(http://dev-hashmart.mstshop.cn/static/images/center/v1-back.png);
		}
		.card-top-area {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			padding-top: 10px;
			height: calc(100% - 25px);
		}
		.text-wrap {
			overflow-wrap: break-word;
			color: rgba(55, 41, 103, 1);
		}
		.text_1 {
			font-size: 43px;
			letter-spacing: 0.17px;
			font-family: BTH;
			font-weight: 800;
			white-space: nowrap;
			line-height: 44px;
		}
		.text_2 {
			font-size: 14px;
			letter-spacing: 0.07px;
			font-family: PingFangHK-Regular;
			font-weight: NaN;
			text-align: left;
			white-space: nowrap;
			line-height: 20px;
			margin-top: 16px;
		}
		.text_3 {
			font-size: 12px;
			letter-spacing: 0.06px;
			font-family: PingFangHK-Regular;
			font-weight: NaN;
			text-align: left;
			white-space: nowrap;
			line-height: 17px;
			margin-top: 1px;
		}
		.top-left-area {
			margin-left: 16px;
		}
		.top-right-area {
			width: 144px;
			height: 118px;
			background-size: 100% 100%;
			background-repeat: no-repeat center;
		}
		.card-bottom-area {
			color: #372967;
			height: 38px;
			text-align: left;
			line-height: 38px;
			margin-left: 16px;
			font-size: 14px;
		}
		.equity-class {
			width: 100%;
			margin-top: 20px;
		}
		.equity-title{
			padding: 0 12%;
		}
		.title_1 {
			overflow-wrap: break-word;
			color: rgba(255, 255, 255, 1);
			font-size: 38px;
			letter-spacing: 0.17px;
			font-family: BTH;
			font-weight: 800;
			white-space: nowrap;
			line-height: 44px;
		}
		.title_2 {
			overflow-wrap: break-word;
			color: rgba(255, 255, 255, 1);
			font-size: 14px;
			letter-spacing: 0.07px;
			font-family: PingFangHK-Regular;
			font-weight: NaN;
			text-align: left;
			white-space: nowrap;
			line-height: 20px;
			margin-top: 16px;
			margin-left: 10px;
		}
		.uni-row-class{
			width: 100%;
			margin-top: 10px;
		}

		.col-class {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}
		.col-img1 {
			width: 30px;
			height: 30px;
		}
		.col-img2 {
			width: 25px;
			height: 25px;
		}
		.col-img3 {
			width: 25px;
			height: 25px;
		}
		.col-title {
			margin-top: 6px;
			overflow-wrap: break-word;
			color: rgba(255, 255, 255, 1);
			font-size: 14px;
			letter-spacing: 0.07px;
			font-family: PingFangHK-Semibold;
			font-weight: 600;
			white-space: nowrap;
			align-self: center
		}
		.col-desc {
			overflow-wrap: break-word;
			color: rgba(224, 224, 224, 1);
			font-size: 12px;
			letter-spacing: 0.05999999865889549px;
			font-family: PingFangHK-Regular;
			font-weight: 500;
			white-space: nowrap;
		}
	}
	.empty-view {
		width: 100%;
		height: 70px;
		background-color: #48495B;
	}
	.bottom-area {
		width: 100%;
		margin-top: -45px;
		height: calc(100vh - 362px);
		padding-bottom: env(safe-area-inset-bottom);
		background-color: #F0EFFF;
		border-top-left-radius: 16px;
		border-top-right-radius: 16px;

		.title-area{
			display: flex;
			justify-content: space-between;
			padding: 15px 20px;
		}
		.left_area{
			overflow-wrap: break-word;
			color: rgba(72, 73, 91, 1);
			font-size: 18px;
			letter-spacing: 0.09px;
			font-family: PingFangHK-Semibold;
			font-weight: 600;
			white-space: nowrap;
			line-height: 25px;
		}
		.right_area{
			overflow-wrap: break-word;
			color: rgba(72, 73, 91, 1);
			font-size: 14px;
			letter-spacing: 0.07px;
			font-family: PingFangHK-Regular;
			font-weight: 400;
			white-space: nowrap;
			line-height: 20px;
			margin-top: 5px;
		}
		.coupons-list{
			height: calc(100% - 55px);
			padding: 0px 30px 10px;
			overflow-y: auto;
			max-height: 45vh;
		}
	}
}
</style>