<template>
	<view class="container">
		<view v-if="showUserInfo" class="list-content">
			<view class="" v-for="(item,index) in cellList" :key="index">
				<view class="list-items" v-if="item.label=='avatar'" @click="operateUserInfo(item)">
					<text>{{item.title}}</text>
					<view class="avatar">
						<image :src="userInfo.avatar" mode="" class="head-img"></image>
					</view>
				</view>

				<view class="list-items" v-else-if="item.label=='nickname'" @click="operateUserInfo(item)">
					<text>{{item.title}}</text>
					<text>{{userInfo.nickname}}</text>
				</view>
				<view class="list-items" v-else-if="item.label=='id'">
					<text>{{item.title}}</text>
					<text>{{userInfo.id}}</text>
				</view>
				<view class="list-items-last" v-else-if="item.label=='logout'" @click="logout">
					<text>{{item.title}}</text>
				</view>
				<!-- #ifdef APP-PLUS -->
				<view class="list-items" v-else @click="operateUserInfo(item)">
				<!-- #endif -->
				<!-- #ifdef MP-WEIXIN || H5-->
				<view class="list-items" v-else>
				<!-- #endif -->
						<text>{{item.title}}</text>
						<view class="list-button" @click="operateUserInfo(item)">
							<!-- #ifdef APP-PLUS -->
							<text @click="operateUserInfo(item)">{{userInfo.phone}}</text>
							<!-- #endif -->
							<!-- #ifdef MP-WEIXIN || H5-->
							<button open-type="getPhoneNumber" @getphonenumber.stop="getphonenumber"
								class="list-button clear-button">
								<cover-view class="choose-avatar main-end-flex"
									style="font-size: 28rpx;color: #FFF;">
									{{userInfo.phone}}
								</cover-view>
							</button>
							<!-- #endif -->
						</view>
					</view>
				</view>
			</view>
			<view class="ugroup">
				<view class="cell-container" :class="index + 1 != cellList2.length? 'underLineColor' : ''"
				v-for="(item,index) in cellList2" :key="index" @click="goDetail(item)">
					<text>{{item.title}}</text>
					<image :src="rightPointer" mode="heightFix"></image>
				</view>
			</view>
			<!-- #ifdef APP-PLUS -->
			<view class="login-out main-center-flex" @click="loginOut">
				退出登录
			</view>
			<!-- #endif -->

			<!-- #ifdef MP-WEIXIN || H5-->
			<u-popup :show="avatar_chose" mode="bottom" @close="avatar_chose = !avatar_chose" ref="avatarPop">
			<!-- #endif -->
				<!-- #ifdef APP-PLUS -->
				<view class="footer" v-show="avatar_chose">
				<!-- #endif -->
					<view class="avatar-chose">
						<view class="">
							推荐使用头像
						</view>
						<view class="avatar-list">
							<view class="avatar-list list-item" v-for="(item,index) in avatarList" :key="index"
								@click="chosedAvatar=item" :style="{'background':item==chosedAvatar?'#EC872E':'gray'}">
								<u-avatar size="68rpx" :src="item">
								</u-avatar>
							</view>
						</view>
						<view class="avatar-sure" @click="sureChose">
							确定
						</view>
					</view>
				<!-- #ifdef APP-PLUS -->
				</view>
				<!-- #endif -->

			<!-- #ifdef MP-WEIXIN || H5-->
			</u-popup>
			<!-- #endif -->
			<modal ref="modal"></modal>

			<view class="dialog" v-if="showVisible">
				<!-- #ifdef APP-PLUS -->
				<view class="containers">
					<view class="main">
						<view class="close" @click="showVisible=false"></view>
						<image class="head" src="../../static/image/modal-head.png" mode="scaleToFill" @click="showVisible=false"></image>
						<view class="body main-center-flex">
							<view class="content">
								<view class="title main-center-flex">
									{{dialogTitle}}
								</view>
								<view class="content-detail">
									<u--input v-model="inputModel" border="none" placeholder="请填写昵称" fontSize="28rpx"
										color="#b0b0b0" inputAlign="center">
									</u--input>
								</view>
								<view class="edit-icon">
									<u-icon name="edit-pen"></u-icon>
								</view>
								<view class="button main-center-flex">
									<view class="button_1 main-center-flex" @click="confirm">
										确定
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
				<!-- #endif -->
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import {
		get_user_info,
		set_user_info,
		getAvatar
	} from '@/api/my.js';
	export default {
		data() {
			return {
				rightPointer: baseUrl.imgroot + 'static/images/userCenter/coupon/right.png',
				currentType: '',
				isInput: false,
				content: '',
				inputModel: '',
				dialogTitle: '',
				showVisible: false,
				chosedAvatar: null,
				avatarList: [],
				avatar_chose: false,
				userInfo: null,
				cellList: [{
						title: '头像',
						isLink: true,
						label: 'avatar',
					},
					{
						title: '昵称',
						isLink: true,
						label: 'nickname',
					},
					{
						title: '手机号',
						isLink: false,
						label: 'phone',
					},
					{
						title: '用户ID',
						isLink: false,
						label: 'id'
					},
					{
						title: '退出登录(清空本地缓存)',
						isLink: false,
						label: 'logout'
					}
				],
				cellList2: [{
						title: '隐私协议',
						isLink: true,
						adress: '/plugins/privacy-agreement/index'
					},
					{
						title: '平台交易规则',
						isLink: true,
						adress: '/plugins/transaction-rules/index'
					}
				]
			}
		},
		computed: {
			showUserInfo() {
				return this.userInfo && Object.keys(this.userInfo).length;
			}
		},
		onLoad(params) {
			const {isEdit} = params;
			this.getUserInfo()
			if(isEdit) {
				setTimeout(() => {
					this.$nextTick(() => {
						this.operateUserInfo(this.cellList[1]);
					})
				}, 1000);
			}
		},
		methods: {
			logout() {
				uni.clearStorageSync();
				// #ifdef MP-WEIXIN || H5
				uni.reLaunch({url: '/plugins/login/phone-number/index?type=phone'});
				// #endif
				// #ifdef APP-PLUS
				this.$login.appLogin();
				// #endif
			},
			sureChose() {
				set_user_info({
					avatar: this.chosedAvatar,
					nickname: this.userInfo.nickname,
					phone: this.userInfo.phone
				}).then(res => {
					if (res.code == 0) {
						this.getUserInfo()
					}
					this.avatar_chose = false
					uni.$u.toast(res.msg)
				}).catch(err => {
					uni.$u.toast(err.msg)
				})
			},
			goDetail(item) {
				if (item.adress) {
					uni.navigateTo({
						url: item.adress
					})
				}
			},
			loginOut() {
				uni.setStorageSync('_USER_ACCESS_TOKEN', '')
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			getUserInfo() {
				uni.showLoading({
					title: '',
					mask: true
				});
				get_user_info().then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						this.userInfo = {...res.data}
						this.chosedAvatar = res.data.avatar
					}
				}).catch(err => {
					uni.hideLoading();
				})
				getAvatar().then(res => {
					if (res.code == 0) {
						this.avatarList = res.data
					}
				})
			},
			changeAvatar(e) {
				console.log('1416-0', e)
			},
			getphonenumber(e) {
				console.log('1416-2', e)
			},
			changeUserInfo(type) {
				const {
					avatar,
					phone,
					nickname
				} = this.userInfo
				set_user_info({
					avatar,
					nickname,
					phone
				}).then(res => {
					if (res.code == 0) {
						if(type === 'nickname') {
							uni.setStorageSync('hasEditName', true);
						}
						this.getUserInfo()
						this.avatar_chose = false
					}
					uni.$u.toast(res.msg)
				}).catch(err => {
					uni.$u.toast(err.msg)
				})
			},
			operateUserInfo(e) {
				// #ifdef APP-PLUS
				console.log('app');
				this.currentType = e.label
				if (e.label == 'nickname') {
					this.showVisible = true
					this.$nextTick(() => {
						this.dialogTitle = '修改昵称'
						this.inputModel = this.userInfo.nickname
					})
				} else if (e.label == 'avatar') {
					this.avatar_chose = true
				} else if (e.label == 'phone') {
					this.showVisible = true
					this.$nextTick(() => {
						this.dialogTitle = '修改手机号'
						this.inputModel = this.userInfo.phone
					});
				}
				// #endif

				// #ifdef MP-WEIXIN || H5
				console.log('mp-weixin||H5', e);
				if (e.label == "avatar") {
					this.avatar_chose = true
					return
				}
				if(e.label == 'nickname') {
					this.$refs.modal.showModal({
						title: '修改昵称',
						isInput: true,
						inputModel: this.userInfo.nickname,
						confirm: (e) => {
							this.userInfo.nickname = e
							this.changeUserInfo('nickname')
						}
					})
				}
				// #endif
			},
			confirm() {
				if (this.currentType == "nickname") {
					this.userInfo.nickname = this.inputModel
					this.changeUserInfo('nickname')
				} else {
					this.userInfo.phone = this.inputModel
					this.changeUserInfo()
				}
				this.showVisible = false
			},
		}
	}

</script>

<style scoped lang="scss">
	@import './index.scss';
</style>

