
<template>
	<view class="privacy" v-if="showPrivacy">
		<view class="content">
			<view class="title">隐私保护指引</view>
			<view class="des">
				在使用当前小程序服务之前，请仔细阅读
				<text class="link" @click="openPrivacyContract">{{ privacyContractName }}</text>
				。如果你同意{{ privacyContractName }}，请点击“同意”开始使用。
			</view>
			<view class="btns">
				<button class="item reject" @click="exitMiniProgram">拒绝</button>
				<button id="agree-btn" class="item agree" open-type="agreePrivacyAuthorization" @agreeprivacyauthorization="handleAgreePrivacyAuthorization">同意</button>
			</view>
		</view>
	</view>
</template>
 
<script>
export default {
	name: 'privacyPopup',
	data() {
		return {
			privacyContractName: '',
			showPrivacy: false
		};
	},
	created() {
		setTimeout(() => {
			this.showPrivacy = getApp().globalData.showPrivacy;
			this.privacyContractName = getApp().globalData.privacyContractName;
		}, 500);
	},
	methods: {
		// 同意隐私协议
		handleAgreePrivacyAuthorization() {
			const that = this;
			wx.requirePrivacyAuthorize({
				success: res => {
					that.showPrivacy = false;
					getApp().globalData.showPrivacy = false;
				}
			});
		},
		// 拒绝隐私协议
		exitMiniProgram() {
			const that = this;
			uni.showModal({
				content: '如果拒绝,我们将无法获取您的信息, 包括手机号、位置信息、相册等该小程序十分重要的功能,您确定要拒绝吗?',
				success: res => {
					if (res.confirm) {
						that.showPrivacy = false;
						uni.exitMiniProgram({
							success: () => {
								console.log('退出小程序成功');
							}
						});
					}
				}
			});
		},
		// 跳转协议页面  
        // 在真机上点击高亮的协议名字会自动跳转页面 微信封装好的不用操作
		openPrivacyContract() {
			wx.openPrivacyContract({
				fail: () => {
					uni.showToast({
						title: '网络错误',
						icon: 'error'
					});
				}
			});
		}
	}
};
</script>
 
<style lang="scss" scoped>
.privacy {
	position: fixed;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	background: rgba(0, 0, 0, 0.5);
	z-index: 9999999;
	display: flex;
	align-items: center;
	justify-content: center;
	.content {
		width: 85vw;
		padding: 50rpx;
		box-sizing: border-box;
		background: #fff;
		border-radius: 16rpx;
		.title {
			text-align: center;
			color: #333;
			font-weight: bold;
			font-size: 34rpx;
		}
		.des {
			font-size: 26rpx;
			color: #666;
			margin-top: 40rpx;
			text-align: justify;
			line-height: 1.6;
			.link {
				color: #07c160;
				text-decoration: underline;
			}
		}
		.btns {
			margin-top: 60rpx;
			display: flex;
			justify-content: space-between;
			.item {
				justify-content: space-between;
				width: 244rpx;
				height: 80rpx;
				display: flex;
				align-items: center;
				justify-content: center;
				border-radius: 16rpx;
				box-sizing: border-box;
				border: none;
			}
			.reject {
				background: #f4f4f5;
				color: #909399;
			}
			.agree {
				background: #07c160;
				color: #fff;
			}
		}
	}
}
</style>