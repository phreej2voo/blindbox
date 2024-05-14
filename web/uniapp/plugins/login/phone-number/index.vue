<template>
	<view class="container column-start-flex">
		<view class="backto-page">
			<view class="back-text" @click="backPage">返回商城</view>
		</view>
		<view class="" v-if="!weixin">
			<view class="head">
				<view class="head-title_1">
					{{pageType == 'password' ? '密码登录' : pageType == 'retrieve' ? '找回密码' : '手机号快捷登录' }}
				</view>
				<view v-if="pageType == 'phone'" class="head-title_2">未注册过的手机号将自动创建账号</view>
			</view>
			<view class="body">
				<u--form labelPosition="left" :model="loginModel" :rules="rules" ref="form">
					<u-form-item prop="phone" borderBottom>
						<view class="form-item main-start-flex">
							<u--image :src="img1" width="36rpx" height="42rpx">
							</u--image>
							<u--input v-model="loginModel.phone" color="#FFFFFF" border="none" placeholder="请输入手机号"></u--input>
						</view>
					</u-form-item>
					<u-form-item v-if="pageType != 'password'" prop="code" borderBottom>
						<view class="form-item main-start-flex">
							<u--image :src="img2" width="38rpx" height="45rpx">
							</u--image>
							<u--input v-model="loginModel.code" color="#FFFFFF" border="none" placeholder="请输入正确的验证码"></u--input>
							<view class="get-code main-center-flex">
								<text v-if="!isGetCode" @click="getCode">获取验证码</text>
								<text v-else>{{codeTime}}s</text>
							</view>
						</view>
					</u-form-item>
					<u-form-item prop="password" borderBottom v-if="pageType != 'phone'">
						<view class="form-item main-start-flex">
							<u--image :src="img3" width="38rpx" height="45rpx">
							</u--image>
							<u--input v-model="loginModel.password" color="#FFFFFF" border="none" placeholder="请输入密码"
								password></u--input>
						</view>
					</u-form-item>
					<u-form-item prop="checked" borderBottom v-if="pageType != 'retrieve'">
						<view class="form-item main-start-flex">
							<u-checkbox-group placement="row" @change="loginModel.checked = !loginModel.checked">
								<u-checkbox shape="circle" activeColor="#82FF80" :checked="loginModel.checked"></u-checkbox>
							</u-checkbox-group>
							<view class="agreement" @click="loginModel.checked = !loginModel.checked">
								我已阅读并同意哈希玛特
								<text @click.stop="goToRule('0')">《用户协议》</text>
							</view>
						</view>
					</u-form-item>
					<!-- <view class="dev-text">演示环境请填写任意6位验证码，即可登录</view> -->
				</u--form>
			</view>
			<view class="main-center-flex">
				<view class="login-button main-center-flex color-btn" @click="login">
					{{pageType == 'password' ? '立即登录' : pageType == 'retrieve' ? '提交并确认' : '立即登录/注册' }}
				</view>
			</view>
			<!-- #ifndef MP-WEIXIN -->
			<view class="password-login">
				<view @click="changeLoginPage('password')" v-if="pageType == 'phone'">
					账号密码登录
				</view>
				<view class="main-center-flex" style="position: relative;" v-if="pageType == 'password'">
					<view class="" @click="changeLoginPage('phone')">快捷登录</view>
					<view class="line"></view>
					<view class="" style="margin-left: 80rpx;" @click="changeLoginPage('retrieve')">忘记密码</view>
				</view>
			</view>
			<!-- #endif -->
			<!-- #ifdef MP-WEIXIN -->
			<view class="other-login column-center-flex" v-if="pageType != 'retrieve' && false">
				<view class="">
					其他方式登录
				</view>
				<view class="main-center-flex">
					<view class="login-image" v-for="(item,index) in 1" :key="index">
						<u--image :src="img4" width="64rpx" height="64rpx"
							@click="weixin=!weixin">
						</u--image>
					</view>
				</view>
			</view>
			<!-- #endif -->
		</view>
		<!-- #ifdef MP-WEIXIN -->
		<view class="weixin" v-if="weixin">
			<view class="head-icon icon-img">
				<image :src="loginLogo" mode="aspectFill" class="icon-img"></image>
			</view>
			<view class="rules">
				<u-checkbox-group class="checkbox-class" @change="checkedChange(item)">
					<u-checkbox shape="circle" :checked="checked" activeColor="#82FF80">
					</u-checkbox>
					<view @click="checkedChange(item)" class="agreement-rules">
						阅读并同意哈希玛特 <text @click="goToRule('0')">《用户协议》</text>、<text @click="goToRule('1')">《隐私政策》</text>
					</view>
				</u-checkbox-group>
			</view>
			<view class="wlogin">
				<view class="others-login login-botton color-btn"
				v-if="!checked" @click="clickInfo">
					手机号快捷登录
				</view>
				<button type="default" open-type="getPhoneNumber"
				@getphonenumber="decryptPhoneNumber" class="login-botton others-login color-btn" v-else>
					手机号快捷登录
				</button>
				<view class="others-login" @click="weixin=!weixin">
					其他方式登录
				</view>
			</view>
		</view>
		<!-- #endif -->
	</view>
</template>

<script>
	import {
		mapGetters,
		mapState
	} from 'vuex';
	import {
		get_login_code,
		login_by_sms,
		login_by_account,
		login_forget,
		mini_wx_login,
		getPhone
	} from '@/api/login.js';
	import { get_user_info } from '@/api/my.js';
	import baseUrl from '@/utils/siteInfo.js';

	export default {
		data() {
			return {
				loginLogo: baseUrl.imgroot + 'static/images/login/login-icon.png',
				checked: false,
				// #ifdef MP-WEIXIN
				weixin: true,
				// #endif
				// #ifdef APP-PLUS || H5
				weixin: false,
				// #endif
				pageType: 'phone',
				loginModel: {
					phone: '',
					code: '',
					checked: false,
					password: ''
				},
				rules: {
					phone: [{
							type: 'number',
							required: true,
							message: '请填写手机号',
							trigger: ['blur', 'change']
						},
						{
							// 此为同步验证，可以直接返回true或者false，如果是异步验证，稍微不同，见下方说明
							validator: (rule, value, callback) => {
								// 调用uView自带的js验证规则，详见：https://www.uviewui.com/js/test.html
								return uni.$u.test.mobile(value);
							},
							message: "请填写正确的手机号",
							// 触发器可以同时用blur和change，二者之间用英文逗号隔开
							trigger: ["change", "blur"],
						}
					],
					code: [{
							type: 'number',
							required: true,
							message: '请填写任意6位验证码',
							trigger: ['blur', 'change']
						},
						{
							// 此为同步验证，可以直接返回true或者false，如果是异步验证，稍微不同，见下方说明
							validator: (rule, value, callback) => {
								// 调用uView自带的js验证规则，详见：https://www.uviewui.com/js/test.html
								return uni.$u.test.code(value, 6);
							},
							message: "请填写正确的验证码",
							// 触发器可以同时用blur和change，二者之间用英文逗号隔开
							trigger: ["change", "blur"],
						}
					],
					password: {
						type: 'string',
						required: true,
						message: '请填写密码',
						trigger: ['blur', 'change']
					}
				},
				loginMethod: {
					phone: login_by_sms, // 短信登录
					password: login_by_account, // 账号密码登录
					retrieve: login_forget // 忘记密码
				},
				isGetCode: false,
				codeTime: 60,
				codeInterval: null,
				img1: baseUrl.imgroot + 'static/images/login/phone.png',
				img2: baseUrl.imgroot + '/static/images/login/captcha.png',
				img3: baseUrl.imgroot + 'static/images/login/keyswords.png',
				img4: baseUrl.imgroot + '/static/images/kitego/login-wx.png',
			}
		},
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght',
			}),
		},
		onReady() {
			// 如果需要兼容微信小程序，并且校验规则中含有方法等，只能通过setRules方法设置规则
			this.$refs.form.setRules(this.rules)
		},
		onLoad(params) {
			const {type} = params;
			this.pageType = type;
		},
		methods: {
			backPage() {
				uni.switchTab({
					url: '/pages/index/index'
				});
			},
			goToRule(val) {
				if (val == 0) {
					uni.navigateTo({
						url: '/plugins/user-agreement/index'
					})
				} else {
					uni.navigateTo({
						url: '/plugins/privacy-agreement/index'
					})
				}
			},
			clickInfo() {
				uni.$u.toast('请阅读并勾选上方协议')
			},
			checkedChange(item) {
				this.checked = !this.checked
			},
			decryptPhoneNumber(e) {
				const storageKey = '_USER_ACCESS_TOKEN';
				const self = this
				uni.login({
					scopes: 'auth_base',
					success(result) {
						if (result.errMsg === 'login:ok') {
							self.code = result.code;
							console.log('try-', uni.getStorageSync('agent_user_id'));
							mini_wx_login({
								code: result.code,
								phone_code: e.detail.code,
								agent_user_id: uni.getStorageSync('agent_user_id') || ''
							}).then(response => {
								if (response.code === 0) {
									uni.showToast({
										icon: 'none',
										title: '登录成功'
									})
									uni.setStorageSync(storageKey, response.data.token);
									self.$store.dispatch('user/USER_CONFIG')
									self.getUserInfo();
									uni.switchTab({
										url: '/pages/index/index'
									})
								} else {
									uni.showToast({
										icon: 'none',
										title: response.msg
									})

								}
							})
						}
					}
				})
			},
			// 获取用户信息
			async getUserInfo() {
				const {code, data} = await get_user_info();
				if (code == 0) {
					uni.setStorageSync('userInfo', data);
				}
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			login() {
				
				if (!this.loginModel.checked) {
					uni.showToast({
						icon: 'error',
						title: '请同意用户协议'
					})
					return
				}
				
				this.$refs.form.validate().then(valid => {
					if(valid) {
						uni.showLoading({
							title: '登录中',
							mask: true
						});
						const {phone, code, password} = this.loginModel
						const params = {
							phone, 
							agent_user_id: uni.getStorageSync('agent_user_id') || ''
						};
						if(this.pageType == 'phone') {
							params.code = code;
						}else if(this.pageType == 'password') {
							params.password = password;
						}else {
							delete params.agent_user_id;
							params.code = code;
							params.password = password;
						}
						params.wx_code = ''
						
						// #ifdef MP-WEIXIN
						uni.login({
							 provider: 'weixin',
							success: (loginRes) => {
								params.wx_code = loginRes.code
								this.doLogin(params)
							}
						})
						// #endif
						
						// #ifndef MP-WEIXIN
						this.doLogin(params)
						// #endif
					} else {
						uni.$u.toast('请正确填写以上内容')
					}
				}).catch(errors => {})
			},
			// 处理登陆
			doLogin(params) {
				this.loginMethod[this.pageType](params).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						uni.setStorageSync('_USER_ACCESS_TOKEN', res.data.token);
						uni.showToast({
							icon: 'none',
							title: '登录成功'
						})
						this.getUserInfo();
						uni.switchTab({
							url: '/pages/my/index'
						})
					}else{
						setTimeout(() => {
							uni.showToast({
								icon: 'none',
								title: res.msg
							})
						}, 10)
					}
					uni.$u.toast(res.msg)
				}).catch(err => {
					uni.hideLoading();
					uni.$u.toast(err.msg)
				})
			},
			changeLoginPage(type) {
				this.loginModel = {
					phone: '',
					code: '',
					checked: true,
					password: ''
				}

				uni.navigateTo({
					url: '/plugins/login/phone-number/index?type=' + type
				})
			},
			getCode() {
				if(!this.loginModel.phone) {
					uni.$u.toast('手机号不能为空');
					return;
				}
				clearInterval(this.codeInterval)
				this.$refs.form.validateField('phone')
				get_login_code({
					phone: this.loginModel.phone,
					type: 'login_sms_code'
				}).then(res => {
					uni.$u.toast(res.msg)
					if (res.code == 0) {
						this.isGetCode = true
						this.codeInterval = setInterval(() => {
							if (this.codeTime > 0) {
								this.codeTime--
							} else {
								clearInterval(this.codeInterval)
								this.isGetCode = false
							}
						}, 1000)
					}
				}).catch(err => {
					uni.$u.toast(err.msg)
				})

			}
		}
	}

</script>

<style lang="scss" scoped>
	.container {
		width: 750rpx;
		height: 100vh;
		background: #1D1F36;
		position: relative;
	}

	.backto-page{
		width: 100%;
		display: flex;
		justify-content: flex-end;
		margin-top: 15rpx;
		.back-text{
			width: 150rpx;
			color: #FFF;
			font-size: 24rpx;
			display: flex;
			justify-content: center;
			align-items: center;
		}
	}

	.head {
		width: 100%;
		/* padding-left: 50rpx; */
		margin-top: 80rpx;
	}

	.head-title_1 {
		font-size: 42rpx;
		font-family: PingFangSC;
		font-weight: 500;
		color: #FFF;
	}

	.head-title_2 {
		font-size: 24rpx;
		font-family: PingFang SC;
		font-weight: 500;
		color: #999999;
		margin-top: 30rpx;
	}

	::v-deep .u-form {
		margin-top: 100rpx;
	}

	.form-item {
		width: 640rpx;
		height: 100rpx;
	}
	.dev-text{
		text-align: left;
		color: #f36464;
		font-size: 22rpx;
		padding-top: 15rpx;
	}

	::v-deep .u-form-item__body {
		padding: 0 !important;
	}

	::v-deep input {
		padding-left: 30rpx;
	}

	.get-code {
		height: 100%;
		font-size: 28rpx;
		font-family: PingFang SC;
		font-weight: 400;
		color: #82FF80;
	}
	.agreement {
		font-size: 24rpx;
		font-family: PingFang SC;
		font-weight: 400;
		color: #999999;
	}

	.agreement text {
		// color: #6565E7;
		color: #82FF80;
	}

	.login-button {
		width: 100%;
		height: 92rpx;
		background: #4F4F52;
		font-size: 34rpx;
		font-family: PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
		margin-top: 100rpx;
	}

	.password-login {
		font-size: 26rpx;
		font-family: PingFang SC;
		font-weight: 400;
		color: #999999;
		margin-top: 28rpx;
	}

	.other-login {
		position: absolute;
		bottom: calc(30rpx + env(safe-area-inset-bottom));
		font-size: 24rpx;
		font-family: PingFang SC;
		font-weight: 400;
		color: #999999;
		width: 100%;
		left: 0;
		right: 0;
		margin: auto;
	}

	.other-login>view:last-child {
		margin-top: 30rpx;
	}

	.login-image {
		margin: 0 5rpx;
	}

	.line {
		height: 100%;
		width: 2rpx;
		background: #E1DFDF;
		position: absolute;
	}

	.login-botton {
		background-color: none;
	}

	.icon-img {
		width: 349rpx;
		height: 208rpx;
	}

	.weixin {
		padding: 50rpx;

	}

	.head-icon {
		left: 0;
		right: 0;
		margin: auto;
		margin-top: 30%;
		margin-bottom: 20%;
	}

	.rules {
		color: #999;
		font-size: 24rpx;
		text-align: center;
		display: flex;
	}

	.wlogin {
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}

	.login-botton {
		margin-bottom: 50rpx;
		background: #8D8C8F;
		color: #fff;
		margin-top: 10%;


	}

	.others-login {
		font-size: 34rpx;
		width: 590rpx;
		height: 92rpx;
		background: #fff;
		text-align: center;
		line-height: 92rpx;
		border-radius: 5px;
	}
	.agreement-rules{
		color: #FFF;
	}
	.color-btn{
		background: linear-gradient(180deg, #56FCD8 0%, #76FE98 100%);
		color: #1D1F36;
	}
</style>