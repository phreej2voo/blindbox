<template>
	<view class="container" :style="{'padding-top': phoneHieght}">
		<u-navbar :fixed="true" :autoBack="true" title="今日限定"
			bgColor="rgba(237,235,255,0.51)">
			<!-- <template slot="center">
				<view class="navbar-title">今日限定</view>
			</template> -->
		</u-navbar>
		<!-- bgColor="linear-gradient(to bottom, #F3F7FF 100%, #EDEBFF 100%, #F2F0FF 100%)" -->
		<scroll-view enableFlex scroll-y class="body"
			:style="{'background-image': 'url('+WeeklyCommonImg.weekly_bg+')'}">
			<view v-if="showWeekly" class="inner">
				<view class="title-area">
					<view class="title" :style="{'background-image': 'url('+WeeklyTitleBg[curDay]+')'}">
						{{ WeeklyTitle[curDay] }}
					</view>
					<view class="title-bottom">
						<image :src="WeeklyCommonImg.weeklyIcon"></image>
						<view class="desc-area">
							<view class="title_1" :style="{'background-image': weeklyTextColor[curDay]}">
								{{ weeklyInfo.title || '' }}
							</view>
							<view class="title_2">{{ weeklyInfo.sub_title || '' }}</view>
							<view class="count main-center-flex">
								<view class="count-title">距结束: </view>
								<view class="time character main-center-flex">{{ hours }}</view>
								<view class="character">:</view>
								<view class="time character main-center-flex">{{ minutes }}</view>
								<view class="character">:</view>
								<view class="time character main-center-flex">{{ seconds }}</view>
							</view>
						</view>
					</view>
				</view>
				<u-tabs :list="tabsList" lineColor="url('')" :itemStyle="tabStyle" :current="currentTab"
					:activeStyle="activeStyle" :inactiveStyle="inactiveStyle" @change="changeTab">
				</u-tabs>
				<view class="detail-area">
					<view v-for="(item,index) in detailList" :key="index" class="list-item" @click="toDetail(item)">
						<view :style="{'background-image': 'url('+(index == 0?WeeklyDetailBack[curDay]:WeeklyCommonImg.common_back)+')'}"
							class="detail-item">
							<view class="top">
								<view class="limit-num">
									{{ '限购 ' + item.buy_limit_num + ' 次'}}
								</view>
							</view>
							<view class="middle">
								<view class="left">
									<image :src="WeeklyCommonImg.weeklyIcon"></image>
								</view>
								<view class="right">
									<view class="right-title_1">{{ item.title }}</view>
									<view class="right-title_2">{{ item.sub_title }}</view>
									<view class="right-title_3">{{ '￥' + item.blindbox.price }}</view>
									<view class="right-btn" :style="{'box-shadow': boxShadowColor[curDay]}">
										<view class="buy-limit">{{ '已抽' + item.buy_num + '/' + item.buy_limit_num }}</view>
										<view class="btn-title">立即开盒</view>
										<view class="btn-countdown">
											<view>{{'距结束: ' }}</view>
											<view>{{countDown}}</view>
										</view>
									</view>
								</view>
							</view>
							<view class="bottom">
								<u--image v-for="(child,jndex) in item.blindbox.detail" :src="child.goods_image" :key="jndex"
									:showLoading="true" width="100rpx" height="100rpx" class="goods-item">
								</u--image>
							</view>
						</view>
					</view>
				</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import { mapGetters, mapState } from 'vuex';
	import { WeeklyTitle,WeeklyTitleColor,WeeklyDescColor, WeeklyCommonImg,WeeklyTitleBg, WeeklyDetailBack } from '../../utils/objectValue';
	import { countDown, countDownHous, countDownMiuntes, countDownSeconds } from '../../utils/common';
	import { getWeeklyInfo } from '@/api/home.js';

	export default {
		data() {
			return {
				WeeklyTitle,WeeklyTitleColor,WeeklyDescColor,WeeklyCommonImg,WeeklyTitleBg,WeeklyDetailBack,
				timer1: null,
				timer2: null,
				countDown: '',
				hours: '',
				minutes: '',
				seconds: '',
				currentTab: '',
				curValue: '1',
				// 菜单item的样式
				tabStyle: {
					width: '14.28%',
					height: '80rpx',
					fontSize: '28rpx',
					fontFamily: 'PingFang SC-Regular, PingFang SC',
					fontWeight: '400',
					padding: '0rpx',
					color: '#ffffff',
					opacity: 0.89
				},
				// 菜单选择中时的样式
				activeStyle: {},
				// 菜单非选中时的样式
				inactiveStyle: {
					color: 'rgba(255,255,255,0.89)'
				},
				tabsList: [{
                    name: '周一',
					value: 1,
					disabled: true
                }, {
                    name: '周二',
					value: 2,
					disabled: true
                }, {
                    name: '周三',
					value: 3,
					disabled: true
                }, {
                    name: '周四',
					value: 4,
					disabled: true
                }, {
                    name: '周五',
					value: 5,
					disabled: true
                }, {
                    name: '周六',
					value: 6,
					disabled: true
                }, {
                    name: '周日',
					value: 7,
					disabled: true
                }],
				weeklyInfo: {},
				weeklyDetail: {},
				weeklyTextColor: {
					1: 'linear-gradient(180deg, #89E3F9 0%, #B89FFF 100%)',
					2: 'linear-gradient(180deg, #FFFAFB 0%, #FF9DE3 100%)',
					3: 'linear-gradient(180deg, #FFFAFB 0%, #79F0FF 100%)',
					4: 'linear-gradient(180deg, #FFFAFB 0%, #F8BB16 100%)', 
					5: 'linear-gradient(180deg, #FFFAFB 0%, #9CE97B 100%)',
					6: 'linear-gradient(180deg, #FFFAFB 0%, #FEE086 100%)',
					7: 'linear-gradient(180deg, #FFFAFB 0%, #D09BFF 100%)',
				},
				tabActiveColor: {
					1: 'linear-gradient(202deg, #7EFFF7 0%, #AC8FFF 100%)',
					2: 'linear-gradient(202deg, #FF9DE3 0%, #FEC2C7 100%)',
					3: 'linear-gradient(202deg, #2AB2FF 0%, #8CF6FF 98%)',
					4: 'linear-gradient(202deg, #FBE29F 0%, #FF9E58 98%)', 
					5: 'linear-gradient(202deg, #40D7FF 0%, #92E779 98%)',
					6: 'linear-gradient(213deg, #FFE816 0%, #FFBB4F 98%)',
					7: 'linear-gradient(37deg, #B486FF 0%, #FFE0FA 91%)',
				},
				boxShadowColor: {
					1: '10rpx -8rpx 6rpx 6rpx #B89FFF',
					2: '10rpx -8rpx 6rpx 6rpx #FF9DE3',
					3: '10rpx -8rpx 6rpx 6rpx #79F0FF',
					4: '10rpx -8rpx 6rpx 6rpx #F8BB16',
					5: '10rpx -8rpx 6rpx 6rpx #9CE97B',
					6: '10rpx -8rpx 6rpx 6rpx #FEE086',
					7: '10rpx -8rpx 6rpx 6rpx #D09BFF',
				}
			}
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght'
			}),
			showWeekly() {
				return this.weeklyDetail && Object.keys(this.weeklyDetail).length;
			},
			curDay() {
				const {day = ''} = this.weeklyDetail;
				return day;
			},
			detailList() {
				const {list = []} = this.weeklyDetail;
				return list;
			}
		},
		onShow() {},
		onLoad() {
			this.weeklyInfo = this.$store.getters['goods/getWeeklyInfo'];
			this.timer1 = setInterval(() => {
				this.hours = countDownHous();
				this.minutes = countDownMiuntes();
				this.seconds = countDownSeconds();
			}, 1000);
			this.timer2 = setInterval(() => {
				this.countDown = countDown();
			}, 1000);
			this.getWeeklyInfo();
		},
		onUnload() {
			console.log('aaa');
			clearInterval(this.timer1);
			this.timer1 = null;
			clearInterval(this.timer2);
			this.timer2 = null;
		},
		onHide() {
			
		},
		methods: {
			changeTab(index, item){
				console.log('index, item-', index, item);
			},
			async getWeeklyInfo() {
				uni.showLoading({
					title: '',
					mask: true
				});
				const {code, data} = await getWeeklyInfo();
				uni.hideLoading();
				if(code == 0) {
					this.weeklyDetail = {...data};
					this.currentTab = Number(data.day) - 1;
					this.tabsList.forEach(item => {
						if(item.value == data.day) {
							item.disabled = false;
						} else {
							item.disabled = true;
						}
					});
					this.activeStyle = {
						width: '100%',
						height: '100%',
						'text-align': 'center',
						background: this.tabActiveColor[data.day],
						border: '1px solid #FFFFFF',
						'font-size': '28rpx',
						'font-family': 'PingFang SC-Medium, PingFang SC',
						color: '#463F5A',
						'line-height': '80rpx'
					}
				}
			},
			toDetail(item) {
				const {blindbox_id, id} = item;
				this.$store.commit('goods/setWeeklyDetail', item);
				uni.navigateTo({
					url: `/plugins/weekly-goods-detail/index?id=${id}&blindId=${blindbox_id}&day=${this.curDay}`
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
.container{
	width: 100vw;
	height: 100vh;
	.navbar-title{
		color: #FFFFFF;
	}
	.body{
		width: 100%;
		height: calc(100%);
		padding-bottom: env(safe-area-inset-bottom);
		background-size: 100% 100%;
		overflow-y: scroll;
	}
	.inner{
		width: 100%;
		overflow: hidden;
	}
	.title-area{
		height: 460rpx;
		display: flex;
		flex-direction: column;
		justify-content: space-evenly;
	}
	.title{
		width: 664rpx;
		height: 80rpx;
		background-size: 100% 100%;
		font-family: BTH;
		font-size: 54rpx;
		line-height: 80rpx;
		padding-left: 42rpx;
		color: #3B3451;
	}
	.title-bottom{
		display: flex;
		align-items: center;
		justify-content: space-evenly;
		image{
			width: 334rpx;
			height: 308rpx;
		}
	}
	.title_1{
		font-size: 44rpx;
		font-family: PingFang SC-Semibold, PingFang SC;
		font-weight: 600;
		background-clip: text;
		-webkit-text-fill-color: transparent;
	}
	.title_2{
		padding-top: 10rpx;
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
	}
	.count{
		padding-top: 24rpx;
	}
	.count-title{
		font-size: 24rpx;
		color: #FFFFFF;
	}
	.character{
		margin-left: 10rpx;
		color: #FFFFFF;
	}
	.time{
		width: 44rpx;
		height: 50rpx;
		background: #FF717A;
		border-radius: 6px 6px 6px 6px;
		color: #FFFFFF;
		font-size: 28rpx;
	}
	::v-deep .u-tabs{
		background-color: rgba(255,255,255,0.06);
	}
	.tab-area{

	}
	.detail-area{
		display: flex;
		flex-direction: column;
		flex-wrap: wrap;
		padding: 0 30rpx 30rpx 30rpx;
	}
	.list-item{
		width: 100%;
		padding-top: 30rpx;
	}
	.detail-item{
		width: 100%;
		height: 504rpx;
		display: flex;
		flex-direction: column;
		align-items: center;
		background-size: 100% 100%;
	}
	.top{
		width: 100%;
		padding-top: 20rpx;
		display: flex;
		justify-content: flex-start;
	}
	.limit-num{
		width: 190rpx;
		font-size: 28rpx;
		font-family: PingFang SC-Medium, PingFang SC;
		font-weight: 500;
		color: #FFFFFF;
		padding: 8rpx 0 8rpx 10rpx;
		//                     控制切角方向 从哪里切及背景色 外部背景色
		background: linear-gradient(110deg, #463F5A 90%, transparent 0) top right;
		background-size: 100% 100%;
		background-repeat: no-repeat;
		// box-shadow: 8rpx -8rpx 6rpx 6rpx #8CFF89;
		opacity: 1;
	}
	.middle{
		width: 100%;
		margin-top: 10rpx;
		display: flex;
		justify-content: center;
	}
	.bottom{
		width: 100%;
		margin-top: 15rpx;
		display: flex;
    	justify-content: space-evenly;
	}
	.left{
		width: 50%;
		image{
			width: 342rpx;
			height: 294rpx;
		}
	}
	.right{
		width: 50%;
		display: flex;
		flex-direction: column;
		justify-content: space-around;
	}
	.right-title_1{
		color: #1C344E;
		font-size: 44rpx;
		font-family: PingFang SC-Semibold, PingFang SC;
		font-weight: 600;
	}
	.right-title_2{
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #1C344E;
	}
	.right-title_3{
		font-size: 48rpx;
		font-family: PingFang SC-Semibold, PingFang SC;
		font-weight: 600;
		color: #F9606F;
	}
	.right-btn{
		width: 90%;
		height: 102rpx;
		margin-top: 10rpx;
		background: #463F5A;
		opacity: 1;
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding-left: 44rpx;
		position: relative;
	}
	.buy-limit{
		position: absolute;
		right: 6rpx;
		top: 8rpx;
		font-size: 20rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		color: #EEE9FF;
	}
	.btn-title{
		font-size: 38rpx;
		font-family: PingFang SC-Semibold, PingFang SC;
		font-weight: 600;
		letter-spacing: 1px;
		color: #FFFFFF;
		// background: linear-gradient(180deg, #40D7FF 0%, #88E476 100%);
		// -webkit-background-clip: text;
		// -webkit-text-fill-color: transparent
	}
	.btn-countdown{
		display: flex;
		padding-top: 10rpx;
		view:first-child{
			font-size: 22rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			color: #FFFFFF;
		}
		view:last-child{
			padding-left: 8rpx;
			font-size: 22rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			color: #DEB4FF;
		}
	}
}
</style>