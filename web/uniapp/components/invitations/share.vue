<template>
	<uni-popup ref="popup" type="bottom" background-color="transparent" class="share-popup"
		:safe-area="false" @change="changeState">
		<view class="popup-content" :style="{'background-image': 'url('+ShareImg.back+')'}">
			<view class="view-title main-space-around">
				<image :src="ShareImg.left" class="left-1"></image>
				<view class="share-text">分享</view>
				<image :src="ShareImg.right" class="left-1"></image>
			</view>
			<view class="view-container">
				<view class="column-center-flex">
					<button plain class="share-btn" type="default" data-name="shareBtn" open-type="share"> 
						<image :src="ShareImg.wechat" class="img-class" mode="aspectFit"></image>
					</button>
					<!-- <image :src="ShareImg.wechat" class="img-class" @tap="share('wechat')"></image> -->
					<text class="text-class">微信</text>
				</view>
				<view class="column-center-flex">
					<image :src="ShareImg.posters" class="img-class" @tap="share"></image>
					<text class="text-class special">生成海报</text>
				</view>
			</view>
		</view>

		<uni-popup ref="posters" type="center" background-color="transparent" class="posters-popup-class">
			<view class="column-center-flex">
				<view class="posters-content" :style="{'background-image': 'url('+SharePostersImg.back+')'}">
					<image :src="qrCodeImg" class="code-img"></image>
				</view>
				<view class="uni-share-button-box" @tap="saveToPhone">
					<!-- <button class="uni-share-button" @click="close">{{cancelText}}</button> -->
					保存到手机
				</view>
			</view>
		</uni-popup>
	</uni-popup>
</template>

<script>
	import { ShareImg, SharePostersImg } from '../../utils/objectValue';
	import { getQrcode } from '@/api/invitations.js';

	export default {
		data() {
			return {
				ShareImg,
				SharePostersImg,
				qrCodeImg: ''
			};
		},
		onLoad() {},
		onShow() {},
		methods: {
			open() {
				this.$refs.popup.open();
			},
			close() {
				this.$refs.popup.close();
			},
			changeState(e) {
				const {show = false} = e;
				if(!show) {
					this.$refs.posters.close();
				}
			},
			share() {
				getQrcode({
					path: 'pages/index/index',
					blindbox_id: 0 // 默认传0
				}).then(res => {
					if(res.code == 0) {
						this.qrCodeImg = res.data;
						this.$refs.posters.open();
					} else {
						uni.$u.toast(res.msg);
					}
				}).catch(err => {
					uni.$u.toast(err.msg);
				});
			},
			// shareFriend() {
			// 	uni.share({
			// 		provider: "weixin",
			// 		scene: "WXSceneTimeline",
			// 		type: 1,
			// 		summary: "哈希玛特-邀新有奖",
			// 		success: function (res) {
			// 			console.log("success:" + JSON.stringify(res));
			// 		},
			// 		fail: function (err) {
			// 			console.log("fail:" + JSON.stringify(err));
			// 		}
			// 	});
			// },
			saveToPhone() {
				uni.downloadFile({
					url: this.qrCodeImg,
					success: (res) => {
						if (res.statusCode === 200) {
							console.log('下载成功');
							uni.$u.toast('下载成功');
							this.$refs.posters.close();
						}
					}
				});
				// downloadTask.abort();
			}
		}
	}
</script>

<style lang="scss" scoped>
.share-popup {
	width: 100%;
	.popup-content {
		width: 100%;
		height: 260px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		background-size: 100% 100%;
		background-repeat: no-repeat center;
	}
	.view-title {
		width: 100%;
		height: 60px;
	}
	.share-btn{
		border: 0 !important;
	}
	.share-text {
		font-size: 30px;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
		line-height: 35px;
	}
	.left-1 {
		width: 98px;
		height: 15px;
	}
	.right-1 {
		width: 44px;
		height: 19px;
	}
	.view-container {
		display: flex;
		width: 100%;
		height: calc(100% - 60px);
	}
	.view-container > view {
		width: calc(100% / 2);

	}
	.img-class {
		width: 60px;
		height: 60px;
	}
	.text-class {
		margin-top: 5px;
		font-size: 16px;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
		line-height: 19px;
	}
	.special{
		padding-top: 15px;
	}
	.posters-popup-class{
		width: 100%;
		.content-view{
			width: 100%;
		}
		.posters-content{
			position: relative;
			width: 297px;
			height: 416px;
			background-size: 100% 100%;
			.code-img {
				width: 80px;
				height: 80px;
				position: absolute;
				right: 0;
				bottom: 0;
			}
		}
		.uni-share-button-box{
			width: 297px;
			height: 48px;
			background: linear-gradient(270deg, #8F0CF2 0%, #AF3DFC 100%);
			border-radius: 4px 4px 4px 4px;
			opacity: 1;
			font-size: 16px;
			font-family: PingFangHK-Medium-Regular, PingFangHK-Medium;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 48px;
			margin-top: 20px;
			margin-bottom: 100%;
			text-align: center;
		}
	}
}
</style>