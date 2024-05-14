<template>
	<view class="invitations-page">
		<view class="logo-tip top">
			<image :src="logoTip" mode="aspectFill"></image>
		</view>
		<!-- 邀请内容 -->
		<view class="invite-info">
			<view class="invite-new">
				<image :src="inviteLogo" mode="widthFix" class="logo1"></image>
				<image :src="hash" mode="widthFix" class="logo2"></image>
				<image :src="inviteNew" mode="heightFix" class="logo3"></image>
			</view>
			<!-- 内容 -->
			<view class="info-box">
				<view class="info-title">
					<text>送千元好礼无限送</text>
					<text>邀请越多福利越多</text>
				</view>
				<!-- 点击生成二维码 -->
				<view class="bd-qrcode">
					<view class="share-view" @click="share">
						<image :src="qrCodeBtn" mode="aspectFill"></image>
						<text>点击生成二维码</text>
					</view>
				</view>
				<!-- 四种活动 -->
				<view class="four-events">
					<view class="event-item" v-for="(item,index) in eventsList" :key="index">
						<image :src="item.img" mode="aspectFill"></image>
						<text>{{ item.text }}</text>
					</view>
				</view>
				<!-- 下级用户 我的奖励 -->
				<view class="next-users-mine">
					<view class="next-item" @click="changeCurrent(item)"
					v-for="(item, index) in nextUserList" :key="index">
						<image :src="currentIndex == item.id? btnImg1 : btnImg2" mode="aspectFill"></image>
						<text>{{ item.text }}</text>
					</view>
				</view>
				<!-- 用户列表 -->
				<view class="user-list">
					<template v-if="shareMineList.length">
						<block v-for="(item,index) in shareMineList" :key="index">
							<inviteUser :key="index" :currentIndex="currentIndex" :item="item"></inviteUser>
						</block>
					</template>
					<template v-else>
						<empty></empty>
					</template>
				</view>
				<!-- 活动规则 -->
				<view class="rules">
					<view class="title">
						<text>活动规则</text>
					</view>
					<view class="rules-list">
						<view class="" style="margin-bottom: 20rpx;">活动规则:</view>
						<view class="">
							1、适用对象此系统上线之后注册的新用户(用户需 •扫码后24小时内注册)<br />
							2、发起人点击“好友邀请一点击生产二维码一分享 新用户一新用户注册一新用户下单一领取分享福利 ”并与其建立鄉定关系，才算参与活动成功<br />
							3、被邀请成功的好友完成相应任务，您即可获得 相应的奖励<br />
							4、邀请好友完成相应任务后需手动领取相应奖励 注：本活动所有内容”鸢鹰科技有限公司“享有最终 解释权<br />
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="logo-tip bottom">
			<image :src="logoTip" mode="aspectFill"></image>
		</view>
		
		<!-- 分享二维码 -->
		<template v-if="canvasStatus">
			<view class="qr-code" @click="closeCode">
				<view class="container" @click.stop="">
					<view class="img-list">
						<canvas class="canvas" canvas-id="myCanvas"></canvas>
					</view>
					<!-- 保存按钮 分享 -->
					<view class="save-share">
						<block v-for="(item, index) in btnList" :key="index">
							<view class="btn-item" :key="index">
								<image :src="shareBtn" mode="aspectFill"></image>
								<!-- #ifdef MP -->
								<button class="text" :open-type="item.id == 2? 'share': ''"
								@click="item.id == 1 ? shareClickBtn(item) : ''">{{ item.text }}</button>
								<!-- #endif -->
								<!-- #ifndef MP -->
								<text class="text" @click="shareClickBtn(item)">{{ item.text }}</text>
								<!-- #endif -->
							</view>
						</block>
					</view>
				</view>
			</view>
		</template>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import utils from '@/utils/canvasUtils.js';
	import { batchSave } from '@/utils/save.js';
	import { drawImg } from '@/utils/appqrCode.js';
	import inviteUser from './components/invite-user/index.vue';
	import { getQrcode, getMyReward, getMyFriends} from '@/api/invitations.js';
	import { Base64 } from '@/js_sdk/js-base64/base64.min';

	export default {
		data() {
			return {
				logoTip: baseUrl.imgroot + 'static/images/userCenter/invitePerson/logo-tip.png',
				inviteLogo: baseUrl.imgroot + 'static/images/userCenter/invitePerson/invite-logo1.png',
				hash: baseUrl.imgroot + 'static/images/userCenter/invitePerson/hash.png',
				inviteNew: baseUrl.imgroot + 'static/images/userCenter/invitePerson/invite-newperson.png',
				qrCodeBtn: baseUrl.imgroot + 'static/images/userCenter/invitePerson/qr-code-btn.png',
				eventsList: [{
					id: 1,
					text: '分享新用户',
					img: baseUrl.imgroot + 'static/images/userCenter/invitePerson/share.png'
				},{
					id: 2,
					text: '新用户注册',
					img: baseUrl.imgroot + 'static/images/userCenter/invitePerson/enroll.png'
				},{
					id: 3,
					text: '新用户下单',
					img: baseUrl.imgroot + 'static/images/userCenter/invitePerson/order.png'
				},{
					id: 4,
					text: '领分享福利',
					img: baseUrl.imgroot + 'static/images/userCenter/invitePerson/welfare.png'
				}],
				btnImg1: baseUrl.imgroot + 'static/images/userCenter/invitePerson/mine.png',
				btnImg2: baseUrl.imgroot + 'static/images/userCenter/invitePerson/next-user.png',
				nextUserList: [{
					id: 1,
					text: '下级用户'
				},{
					id: 2,
					text: '我的奖励'
				}],
				currentIndex: 1,
				canvasStatus: false,
				posterSrc: '',
				btnList: [
					{
						id: 1,
						text: '保存到相册'
					},
					// {
					// 	id: 2,
					// 	text: '分享'
					// }
				],
				shareBtn: baseUrl.imgroot + 'static/images/userCenter/invitePerson/share-btn.png',
				imgList: [
					baseUrl.imgroot + 'static/images/userCenter/invitePerson/invite_bg.png', // 背景
				],
				shareUrl: '',
				shareMineList: [],
			};
		},
		components: {
			inviteUser,
		},
		onLoad() {
			this.changeCurrent(this.nextUserList[0]);
		},
		methods: {
			// 我的奖励
			async getMineReward(){
				try{
					let res = await getMyReward();
					uni.hideLoading();
					if(res.code == 0){
						this.shareMineList = res.data.data;
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			// 我的朋友
			async getMineFriends(){
				try{
					let res = await getMyFriends();
					uni.hideLoading();
					if(res.code == 0){
						this.shareMineList = res.data.data;
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			changeCurrent(e){
				this.currentIndex = e.id;
				this.shareMineList = [];
				uni.showLoading({
					title: '加载中',
					mask: true,
				});
				if(this.currentIndex == 1){
					this.getMineFriends()
				}else if(this.currentIndex == 2){
					this.getMineReward()
				}
			},
			// 关闭二维码
			closeCode(){
				this.canvasStatus = false;
			},
			// 下载图片
			downLoadImg(){
				this.imgList = this.imgList.reduce((pre,item) => {
					uni.downloadFile({
						url: item,
						success: (info) => {
							if (info.statusCode === 200) {
								console.log(info.tempFilePath)
								pre.push(info.tempFilePath);
							}
						}
					});
					return pre;
				}, []);
			},
			// 生成海报二维码
			async share() {
				uni.showLoading({
					title: '海报生成中...'
				});
				
				// #ifdef MP
				// 小程序使用接口获取二维码     
				const {code, data, msg} = await getQrcode({
					path: 'pages/index/index',
					blindbox_id: 0 // 默认传0
				});
				if(code == 0) {
					// 先下载 然后生成
					uni.downloadFile({
						url: data,
						success: (info) => {
							if (info.statusCode === 200) {
								this.generateCanvas(info.tempFilePath);
							}
						}
					});
				} else {
					uni.hideLoading();
					setTimeout(() => {
						uni.showToast({
							title: msg,
							icon: 'error',
							mask: false
						});
					},10);
				}
				// #endif

				// #ifndef MP
				// app h5用qrcode画布生成页面
				let codeImg = this.createQrcode();
				this.generateCanvas(codeImg);
				// #endif
			},
			// app h5生成二维码
			createQrcode() {
				const userInfo = uni.getStorageSync('userInfo');
				const id1 = Base64.encode(JSON.stringify(userInfo.id));
				const id2 = Base64.encode(id1);
				this.shareUrl = window.location.protocol + '//' + window.location.host + '/#/pages/index/index?agent_user_id=' + id2;
				let imgData = drawImg(this.shareUrl, {size: 280});
				return imgData;
			},
			generateCanvas(qrcode) {
				this.canvasStatus = true;
				utils.poster2canvas({imgList: this.imgList, qrcode}, (res) => {
					uni.hideLoading();
					this.posterSrc = res;
				});
			},
			// 分享+保存到相册
			shareClickBtn(item){
				if(item.id == 1){
					// 保存到相册
					this.savePicture();
				}else{
					// #ifndef MP
					this.shareFriend();
					// #endif
				}
			},
			// 保存到本地
			savePicture() {
				// #ifdef MP
				batchSave(this.posterSrc, 'image').then(() => {
					uni.showToast({
						title: '保存成功'
					});
				});
				// #endif
				// #ifndef MP
				uni.downloadFile({
					url: this.posterSrc, //图片地址
					success: (res) => {
						if (res.statusCode === 200) {
							uni.saveImageToPhotosAlbum({
								filePath: res.tempFilePath,
								success: function() {
									uni.showToast({
										title: "保存成功",
										icon: "none"
									});
								},
								fail: function() {
									uni.showToast({
										title: "保存失败",
										icon: "none"
									});
								}
							});
						}
					}
				})
				// #endif
			},
			// 分享好友
			shareFriend(){
				uni.share({
					provider: "weixin",
					scene: "WXSceneSession",
					type: 0,
					href: "http://uniapp.dcloud.io/",
					title: "亲~快来一起玩啊！",
					imageUrl: this.posterSrc,
					success: function (res) {
						console.log("success:" + JSON.stringify(res));
					},
					fail: function (err) {
						uni.showToast({
							icon: 'none',
							title: '您的账号暂时无法分享,请联系客服',
							mask:true
						})
						console.log("fail:" + JSON.stringify(err));
					}
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
@import "./index.scss";
</style>