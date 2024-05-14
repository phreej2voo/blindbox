<script>
	import $store from './store/index.js';
	import baseUrl from '@/utils/siteInfo.js';
	const globalData = {
		privacyContractName: '', //隐私协议的名字
		showPrivacy: false //控制隐私弹窗显隐
	};
	export default {
		onLaunch: function() {
			// 引入字体
			uni.loadFontFace({
				family: 'BTH',
				source: `url(${baseUrl.imgroot}static/fontFamily/BTH.ttf)`,
				global: true,
				success() {
					console.log('success loadFontFace')
				},
				fail() {
					console.log('fail loadFontFace')
				}
			});
			console.log('App Launch')
			$store.dispatch('user/USER_CONFIG')
			//获取手机上面安全距离
			$store.dispatch('phone/SET_PHONE_NAME_ACT')

			// #ifdef MP-WEIXIN
			const that = this;
			wx.getPrivacySetting({
				success(res) {
					// console.log('是否需要授权：', res.needAuthorization, '隐私协议的名称为：', res.privacyContractName);
					if (res.needAuthorization) {
						that.globalData.privacyContractName = res.privacyContractName;
						that.globalData.showPrivacy = true;
					} else {
						that.globalData.showPrivacy = false;
					}
				}
			});
			// #endif
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import '@/uni_modules/uview-ui/theme.scss';
	@import "@/uni_modules/uview-ui/index.scss";

	// 全局样式
	:not(not) {
		box-sizing: border-box;
	}
</style>
