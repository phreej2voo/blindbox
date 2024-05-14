<template>
	<view class="container-mine">
		<!-- 用户头像 -->
		<view class="user-info">
			<view class="back-img">
				<image :src="backImage" mode="aspectFill"></image>
			</view>
			<view class="user-name-avater common-style">
				<template v-if="userInfo && Object.keys(userInfo).length">
					<view class="avater common-style" @click="goItemPage(serviceList[3])">
						<image :src="userInfo.avatar" mode="aspectFill"></image>
					</view>
					<view class="user-name">
						<view class="">
							{{ userInfo.nickname }}
						</view>
						<view class="user-level">
							<view class="">
								<image :src="userLevelInfo.icon" mode="aspectFill"></image>
								<text class="level-title">{{ userLevelInfo.level_title }}</text>
							</view>
							<view class="">
								<text>ID: {{ userInfo.id }}</text>
							</view>
						</view>
					</view>
				</template>
				<template v-else>
					<view class="user-name unlogin" @click="goLogin">立即登录</view>
				</template>
			</view>
			<view class="benefit-cards common-style">
				<image :src="benefitsIcon" mode="aspectFill"></image>
				<view class="benefit-info-box">
					<image :src="benefitsIconBox" mode="aspectFill"></image>
					<view class="benefit-info common-style">
						<text class="benefit-words">开通可享更多权益</text>
						<text v-if="isHasCards" class="benefit-btn" @click="goClaimBenefit">查看详情</text>
						<text v-else class="benefit-btn" @click="goBenefits">立即开通</text>
					</view>
				</view>
			</view>
			<view class="user-balance-coin common-style">
				<view class="item" v-for="(item, index) in userBalance" :key="index" @click="goAccount(item)">
					<view class="type-name">
						{{ item.text }}
					</view>
					<view class="number">
						{{ item.amount }}
					</view>
				</view>
			</view>
		</view>
		<!-- 用户的等级 权益 -->
		<view class="user-benefit-level common-style common-back-box" @click="goLevel(userLevelInfo.level)">
			<view class="user-level">
				<view class="growth-value">
					LV{{ userLevelInfo.level || 0 }} 成长值：{{userLevelInfo.user_experience || 0}}
				</view>
				<view class="progress-box common-style">
					<view class="progress-value" :style="{'width': userLevelInfo.next_level_experience == 0? '100%' : percentExperience,}">
					</view>
				</view>
				<view class="need-value" v-if="userLevelInfo.next_level_experience > 0">
					{{ userLevelInfo.user_experience || 0 }}/{{ userLevelInfo.next_level_experience || 0 }}距离下一级还需{{ nextExperience || 0 }}
				</view>
				<view class="need-value" v-else>
					您已达最高等级
				</view>
			</view>
			<view class="user-benefit common-style">
				查看权益
			</view>
		</view>
		<!-- 我的订单 -->
		<view class="mine-orders common-back-box">
			<view class="title">
				我的订单
			</view>
			<view class="order-list common-style">
				<view class="order-item common-style" v-for="(item, index) in orderList" :key="index"
					@click="goOrderDetail(item.value)">
					<image :src="item.image" mode="heightFix"></image>
					<text>{{ item.name }}</text>
				</view>
			</view>
		</view>
		<!-- 我的服务 -->
		<view class="mine-service common-back-box">
			<view class="title">
				我的服务
			</view>
			<view class="service-list">
				<view class="service-item common-style" v-for="(item, index) in serviceList" :key="index"
					@click="goItemPage(item)">
					<image :src="item.image" mode="heightFix"></image>
					<view class="text" :class="item.name.length > 5? 'newText' : ''"><text>{{ item.name }}</text></view>
				</view>
			</view>
		</view>
		<!-- 修改昵称的提示框 -->
		<modal ref="modal"></modal>
		
		<!-- 领取权益 -->
		<!-- <movable-area>
			<movable-view x="550rpx" y="830rpx" direction="all">
				<image :src="claimBenefits" mode="aspectFill" @click="goClaimBenefit"></image>
			</movable-view>
		</movable-area> -->
	</view>
</template>

<script>
	import baseUrl from '../../utils/siteInfo';
	import {
		mapGetters,
		mapState
	} from 'vuex';
	import {
		get_user_info
	} from '@/api/my.js';
	import {
		getUserLevel,
		getLevelStatus,
		getMyEquity,
	} from '@/api/user.js';
	import {
		vipCardEquity,
	} from '@/api/memberBenefits.js';

	export default {
		data() {
			return {
				backImage: baseUrl.imgroot + 'static/images/userCenter/page-background-image.png',
				userBalance: [{
					text: '我的余额',
					amount: 0,
					name: 'balance'
				}, {
					text: '哈希币',
					amount: 0,
					name: 'integral'
				}],
				// 我的订单
				orderList: [{
					name: '未申请',
					image: baseUrl.imgroot + 'static/images/userCenter/pending-payments.png',
					value: '1',
				}, {
					name: '已提货',
					image: baseUrl.imgroot + 'static/images/userCenter/to-be-shipped.png',
					value: '2',
				}, {
					name: '已兑换',
					image: baseUrl.imgroot + 'static/images/userCenter/to-be-received.png',
					value: '3',
				}],
				// 我的服务
				serviceList: [{
					name: '优惠券',
					image: baseUrl.imgroot + 'static/images/userCenter/coupon.png',
					rootUrl: '/plugins/coupon/index',
				}, {
					name: '地址管理',
					image: baseUrl.imgroot + 'static/images/userCenter/address.png',
					rootUrl: '/plugins/address/index',
				}, {
					name: '联系客服',
					image: baseUrl.imgroot + 'static/images/userCenter/customer.png',
					rootUrl: '/plugins/customer/index',
				}, {
					name: '设置',
					image: baseUrl.imgroot + 'static/images/userCenter/set-up.png',
					rootUrl: '/plugins/setting/index',
				}, {
					name: '充值订单',
					image: baseUrl.imgroot + 'static/images/userCenter/balance-recording.png',
					rootUrl: '/plugins/recharge-balance/index',
				}, {
					name: '邀请有奖',
					image: baseUrl.imgroot + 'static/images/userCenter/invite-goods.png',
					rootUrl: '/plugins/invitations-prized/index',
				},{
					name: '合成',
					image: baseUrl.imgroot + 'static/images/userCenter/composite-icon.png',
					rootUrl: '/plugins/composite/index/index',
				}
				,{
					name: '会员卡',
					image: baseUrl.imgroot + 'static/images/userCenter/vip-cards.png',
					rootUrl: '/plugins/member-benefits/index/index',
				},
				],
				userInfo: '',
				isUserLogin: '',
				isOpenLevel: '',
				userLevelInfo: '',
				claimBenefits: baseUrl.imgroot + 'static/images/userCenter/claim-benefits.png',
				benefitsIcon: baseUrl.imgroot + 'static/images/userCenter/benefits-icon.png',
				benefitsIconBox: baseUrl.imgroot + 'static/images/userCenter/benefits-icon-box.png',
				isHasCards: false,
			}
		},
		computed: {
			percentExperience() {
				if (this.userLevelInfo && this.nextExperience) {
					return ((this.userLevelInfo.user_experience / this.userLevelInfo.next_level_experience) * 100).toFixed(2) + '%'
				}else{
					return '0%'
				}
			},
			nextExperience() {
				return Number(this.userLevelInfo.next_level_experience) - Number(this.userLevelInfo.user_experience);
			},
		},
		onShow() {
			uni.showLoading({
				title: '加载中',
				mask: true,
			});
			this.getVipCardEquity();
			this.getUserInfo();
			this.changeNikeName();
			this.getUserLevelInfo();
		},
		methods: {
			// 待领取权限
			async getVipCardEquity(){
				try{
					let res = await vipCardEquity();
					if(res.code == 0 && res.data && res.data.card){
						this.isHasCards = true;
					}
					console.log(this.isHasCards)
				}catch(e){
					uni.hideLoading()
					//TODO handle the exception
				}
			},
			goBenefits(){
				uni.navigateTo({
					url: '/plugins/member-benefits/index/index'
				})
			},
			// 跳转到领取权益页面
			goClaimBenefit(){
				uni.navigateTo({
					url: '/plugins/member-benefits/claim-benefits/claim-benefits'
				})
			},
			// 跳转用户等级
			goLevel(level) {
				uni.navigateTo({
					url: '/plugins/user-level/index?userLevel=' + level
				})
			},
			// 跳转余额页面
			goAccount(e) {
				if (e.name == 'balance') {
					uni.navigateTo({
						url: '/plugins/account-balance/index'
					})
				}
			},
			// 跳转赏袋的详情
			goBagDetail(item) {
				console.log(item)
				uni.navigateTo({
					url: '/plugins/warehouse-bag/index/index?id=' + item.blindbox_id
				})
			},
			// 我的订单
			goOrderDetail(index) {
				uni.navigateTo({
					url: '/plugins/order-detail/index?currentTab=' + index
				})
			},
			// 我的服务的item跳转
			goItemPage(e) {
				uni.navigateTo({
					url: e.rootUrl
				});
			},
			// 立即登录
			goLogin() {
				uni.navigateTo({
					url: '/plugins/login/phone-number/index?type=' + 'phone'
				})
			},
			// 修改昵称
			changeNikeName() {
				const hasEditName = uni.getStorageSync('hasEditName');
				let firstShow = uni.getStorageSync('FIRST_SHOW');
				if (this.userInfo && this.userInfo.nickname == '微信用户' && !hasEditName && !firstShow) {
					this.$refs.modal.showModal({
						title: '温馨提示',
						content: '需要更换一个更酷的用户名哦~',
						btnText: '去编辑',
						confirm: (e) => {
							uni.setStorageSync('FIRST_SHOW', 1);
							uni.navigateTo({
								url: `/plugins/setting/index?isEdit=${true}`
							});
						},
						complete: (err) => {
							uni.setStorageSync('FIRST_SHOW', 1)
						}
					});
				}
			},
			async getUserInfo() {
				try {
					const {
						code,
						data
					} = await get_user_info();
					if (code == 0) {
						this.userInfo = data;
						this.userBalance = this.userBalance.reduce((pre, item) => {
							if (item.name == 'balance') {
								item.amount = data.balance
							}
							if (item.name == 'integral') {
								item.amount = data.integral
							}
							pre.push(item);
							return pre
						}, [])
						uni.setStorageSync('userInfo', data);
						this.userInfo.integral = parseFloat(this.userInfo.integral).toFixed(2)
					}
				} catch (e) {
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			async getUserLevelInfo() {
				try {
					const {
						code
					} = await getLevelStatus();
					uni.hideLoading();
					if (code == 0) {
						this.isOpenLevel = true;
						this.getUserLevel();
					} else {
						this.isOpenLevel = false;
					}
				} catch (e) {
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			async getUserLevel() {
				try {
					let {
						code,
						data = {}
					} = await getUserLevel();
					if (code == 0) {
						this.userLevelInfo = data;
					}
				} catch (e) {
					//TODO handle the exception
				}
			},
		}
	}
</script>

<style lang="scss" scoped>
	@import './index.scss';
</style>