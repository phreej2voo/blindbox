<template>
	<view>
		<view v-if="showPoster" class="content">
			<button style="margin-top: 20px;" @click="share">分享</button>

			<view class="poster-pop">
				<image src="/static/poster-close.png" class="close" @click="closePoster"></image>
				<image :src="posterSrc"></image>
				<!-- #ifndef H5  -->
				<view class="save-poster" @click="savePosterPath">保存到手机</view>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view class="keep">长按图片可以保存到手机</view>
				<!-- #endif -->
			</view>
			<view class="mask"></view>

			<canvas class="canvas" canvas-id="myCanvas" v-show="canvasStatus"></canvas>
		</view>
		<uni-popup ref="posters" type="center" background-color="transparent" class="posters-popup-class">
			<view class="column-center-flex">
				<view class="posters-content" :style="{'background-image': 'url('+GoodsShareBack+')'}">
					<image :src="GoodsShareClose" class="close-img" mode="aspectFill" @click="close"></image>
					<view class="inner-content">
						<view class="content-i">
							<view v-if="probabilityData" class="content-1">
								<view class="left-text">{电脑玩家必备盲盒商}</view>
								<view class="tag-text" :style="{'color': probabilityData.color}">
									{{probabilityData.tag}}
								</view>
							</view>
							<view v-if="listData" class="img-view column-center-flex">
								<image :src="listData.goods_image" class=""></image>
								<view class="price">{{ '¥' + Math.floor(listData.price) }}</view>
							</view>
							<view class="text-flex">
								<text class="text_1">扫码抽盲盒</text>
								<text class="text_2">保存分享给好友</text>
								<text class="text_3">赢更多福利</text>
							</view>
							<image :src="qrCodeImg" class="code-img"></image>
						</view>
					</view>
				</view>
				<view v-if="qrCodeImg" class="uni-share-button-box" @click="savePosterPath">
					保存到手机
				</view>
			</view>
		</uni-popup>
	</view>

</template>

<script>
	import { ShareImg, SharePostersImg, GoodsShareBack, GoodsShareClose } from '../../utils/objectValue';
	import { getQrcode } from '@/api/invitations.js';
	import canvasUtils from '@/utils/canvasUtils.js';

	export default {
		props: {
			postersData: {
				type: Object,
				default: () => {
					return {};
				}
			},
			blindId: {
				type: String,
				default: ''
			}
		},
		data() {
			return {
				ShareImg,
				SharePostersImg,
				GoodsShareBack,
				GoodsShareClose,
				qrCodeImg: '',

				showPoster: false,
				canvasStatus: false,
				posterSrc: ''
			};
		},
		computed: {
			listData(){
				const {list = []} = this.postersData;
				return list.length ? list[0] : null;
			},
			probabilityData(){
				const {probability = []} = this.postersData;
				return probability.length ? probability[0] : null;
			}
		},
		onLoad() {},
		onShow() {},
		methods: {
			async open(posterObj) {
				// console.log('posterObj-', posterObj);
				// const {back, postersData} = posterObj;
				// const title = postersData.info.name;
				// const tag = postersData.probability[0].tag;
				// const tagColor = postersData.probability[0].color;
				// const coverImg = postersData.list[0].goods_image;
				// const price = postersData.list[0].price;
				// // uni.showLoading({
				// // 	title: '海报生成中...'
				// // });
				const {code, data, msg} = await getQrcode({
					path: 'plugins/goods-detail/index',
					blindbox_id: this.blindId
				});
				if(code == 0) {
					this.qrCodeImg = data;
				} else {
					uni.showToast({
						icon: 'none',
						title: msg
					});
				}
				// this.canvasStatus = true;
				// // imgs, 'Redmi Note 12T Pro', 1599, 1699
				// canvasUtils.poster2canvas({back,title,tag,tagColor,coverImg,price,qrCodeImg: this.qrCodeImg}, (res) => {
				// 	uni.hideLoading();
				// 	this.showPoster = true;
				// 	this.canvasStatus = false;
				// 	this.posterSrc = res;
				// });

				this.$refs.posters.open();
				this.share();
			},
			close() {
				this.$refs.posters.close();
			},
			changeState(e) {
				const {show = false} = e;
				if(!show) {
					this.$refs.posters.close();
				}
			},
			share() {},

			closePoster() {
				this.showPoster = false
			},
			/*
			 * 保存到手机相册
			 */
			// #ifdef MP-WEIXIN
			savePosterPath() {
				const that = this;
				uni.getSetting({
					success(res) {
						console.log('res1-', res);
						if (!res.authSetting['scope.writePhotosAlbum']) {
							uni.authorize({
								scope: 'scope.writePhotosAlbum',
								success() {
									uni.saveImageToPhotosAlbum({
										filePath: that.qrCodeImg,
										success: function(res) {
											console.log('su-res1-', res);
											that.close();
											uni.$u.toast('保存成功');
										},
										fail: function(res) {
											console.log('err-res1-', res);
											uni.$u.toast('保存失败');
										}
									})
								}
							})
						} else {
							uni.saveImageToPhotosAlbum({
								filePath: that.qrCodeImg,
								success: function(res) {
									console.log('su-res1-', res);
									that.close();
									uni.$u.toast('保存成功');
								},
								fail: function(res) {
									console.log('err-res1-', res);
									uni.$u.toast('保存失败');
								}
							})
						}
					}
				});
			},
			// #endif
			// #ifdef APP-PLUS
			savePosterPath() {
				const that = this;
				uni.saveImageToPhotosAlbum({
					filePath: this.qrCodeImg,
					success: function(res) {
						that.close();
						uni.$u.toast('保存成功');
					},
					fail: function(res) {
						uni.$u.toast('保存失败');
					}
				});
			}
			// #endif
		}
	}
</script>

<style lang="scss" scoped>
	.canvas {
		width: 100%;
		height: 1190px;
		top: -9999999999999rpx;
	}
	.poster-pop {
		width: 450rpx;
		height: 714rpx;
		position: fixed;
		left: 50%;
		transform: translateX(-50%);
		z-index: 399;
		top: 50%;
		margin-top: -377rpx;
	}
	.poster-pop image {
		width: 100%;
		height: 100%;
		display: block;
	}
	.poster-pop .close {
		width: 46rpx;
		height: 75rpx;
		position: fixed;
		right: 0;
		top: -73rpx;
		display: block;
	}
	.save-poster {
		background-color: #e93323;
		font-size: ：22rpx;
		color: #fff;
		text-align: center;
		height: 76rpx;
		line-height: 76rpx;
		width: 100%;
	}
	.keep {
		color: #fff;
		text-align: center;
		font-size: 25rpx;
		margin-top: 10rpx;
		background: #ff6700;
		padding: 10px 20px;
	}
	.mask {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0, 0, 0, 0.6);
		z-index: 9;
	}

.posters-popup-class{
	width: 90%;
	.posters-content{
		position: relative;
		// width: 290px;
		width: calc(100vw - 120px);
		height: 360px;
		background-size: 100% 100%;
		.close-img{
			position: absolute;
			width: 24px;
			height: 24px;
			right: 0;
			top: 0;
		}
		.code-img {
			width: 80px;
			height: 80px;
			position: absolute;
			right: 0;
			bottom: 0;
		}
	}
	.inner-content{
		display: flex;
		flex-direction: column;
		justify-content: center;
		margin-top: 36px;
    	padding: 10px;
		height: calc(100% - 36px);
	}
	.content-i{
		position: relative;
		background: #FFFFFF;
		border: 1px solid #101010;
		border-radius: 10px;
	}
	.content-1 {
		padding-top: 5px;
		display: flex;
		justify-content: space-around;
		align-items: center;
	}
	.left-text{
		font-family: PingFangHK-Medium, PingFangHK;
		font-weight: 600;
		color: #363636;
	}
	.tag-text{
		width: 54px;
		height: 23px;
		border: 1px solid #DF7499;
		-webkit-backdrop-filter: blur(0.66467px);
		backdrop-filter: blur(0.66467px);
		font-size: 14px;
		font-family: YouSheBiaoTiHei;
		// color: #DF7499;
		text-align: center;
		border-radius: 5px;
	}
	.img-view{
		width: 100%;
		height: 200px;
		image{
			width: 65%;
		}
	}
	.price {
		font-size: 24px;
		font-family: HarmonyOS_Sans_Bold;
		color: #363636;
		font-weight: 700;
	}
	.text-flex{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: flex-start;
		padding-left: 10px;
		padding-bottom: 5px;
		.text_1{
			font-family: BTH;
			font-size: 25px;
		}
		.text_2{
			font-size: 14px;
			font-family: PingFangHK-Medium, PingFangHK;
			// font-weight: 500;
			color: #363636;
		}
		.text_3{
			font-size: 14px;
			font-family: PingFangHK-Medium, PingFangHK;
			// font-weight: 500;
			color: #363636;
		}
	}
	.uni-share-button-box{
		// width: 297px;
		width: 100%;
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
		text-align: center;
	}
}
</style>