<template>
	<u-popup :show="recordVisible" mode="bottom" :safeAreaInsetBottom="false" @close="closePopup">
		<view class="record-container">
			<view class="head-title">
				<image :src="titleImg" class="title-img"></image>
				<view class="order-title">中奖记录</view>
			</view>

			<scroll-view v-if="showRecord" class="scroll-view" enableFlex scroll-y @scrolltolower="getMoreData">
				<view class="list-content">
					<view v-for="(item, index) in recordList" :key="index" class="item-class">
						<view class="avatar main-center-flex">
							<image :src="item.user_avatar" mode="aspectFill"></image>
						</view>
						<view class="content">
							<view class="desc-text">{{ item.user_name }}</view>
							<view class="desc-text">{{ item.create_time }}</view>
						</view>
						<view class="tag desc-text">
							<view class="tag-img" :class="tagBg[item.tag_name]">
								{{ item.tag_name }}
							</view>
						</view>
					</view>
				</view>
			</scroll-view>
			<view v-if="showEmpty">
				<empty contextTag="暂无数据"></empty>
			</view>
		</view>
	</u-popup>
</template>

<script>
	import {rewardRecords} from '@/api/box.js';

	export default {
		props: {
			recordVisible: {
				type: Boolean,
				default: false
			},
			// recordList: {
			// 	type: Array,
			// 	default: () => {
			// 		return [];
			// 	}
			// },
			recordType: {
				type: [String, Number],
				default: 1
			}
		},
		data() {
			return {
				titleImg: this.$imgList.homeImgs.titleImg,
				tagImg: {
					A: this.$imgList.homeImgs.a_tag,
					B: this.$imgList.homeImgs.b_tag,
					C: this.$imgList.homeImgs.c_tag,
					SP: this.$imgList.homeImgs.sp_tag,
					LAST: this.$imgList.homeImgs.sp_tag
				},
				tagBg: {
					A: 'tag-a',
					B: 'tag-b',
					C: 'tag-c',
					SP: 'tag-sp',
					LAST: 'tag-last'
				},
				blindboxInfo: {
					blindbox_id: '',
					box_id: ''
				},
				hasMore: false, // 是否可用加载更多
				pager: {
					page: 1,
					limit: 10
				},
				recordList: [],
				showEmpty: false
			}
		},
		computed: {
			showRecord() {
				return this.recordList && this.recordList.length;
			}
		},
		methods: {
			closePopup() {
				this.$emit('closeOrder');
			},
			initRecordList(params) {
				const {blindbox_id, box_id} = params;
				this.blindboxInfo = {
					blindbox_id,
					box_id
				};
				this.pager.page = 1;
				this.recordList = [];
				this.getRewardList();
			},
			getMoreData() {
				if(this.hasMore) {
					this.getRewardList();
				}
			},
			async getRewardList() {
				uni.showLoading({
					title: '加载中', // 必须
					mask: true
				});
				const params = {
					blindbox_id: this.blindboxInfo.blindbox_id,
					box_id: this.blindboxInfo.box_id,
					page: this.pager.page,
					limit: this.pager.limit
				};
				// const {code, data, msg} = {
				// 	"code": 0,
				// 	"data": {
				// 		"total": 24, // 总数
				// 		"per_page": this.pager.limit, // 每页数据量
				// 		"current_page": 1, // 当前页数
				// 		"last_page": 3, // 总页数
				// 		"data": [
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "B"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "C"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "SP"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			},
				// 			{
				// 				"user_name": "测试的",
				// 				"user_avatar": "https://dev-hashmart.mstshop.cn/static/images/avatar-1.png",
				// 				"create_time": "2023-09-14 09:21:35",
				// 				"tag_id": 1,
				// 				"tag_name": "A"
				// 			}
				// 		]
				// 	},
				// 	"msg": "success"
				// }
				const {code, data} = await rewardRecords(params);
				uni.hideLoading();
				if(code == 0) {
					if (this.pager.page < data.last_page) {
						this.pager.page++;
						this.hasMore = true;
					} else {
						this.hasMore = false;
					}
					if (data.data.length) {
						this.recordList = this.recordList.concat(data.data);
					}
					if(!this.recordList.length) {
						this.showEmpty = true;
					}
				} else {
					uni.showToast({
						title: msg,
						icon: 'error',
						mask: false
					});
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
.record-container{
	width: 100vw;
	height: 70vh;
	padding-bottom: env(safe-area-inset-bottom);
	background-color: #1D1F36;
	.head-title{
		width: 100%;
		.title-img{
			width: 100%;
			height: 21rpx;
		}
		.order-title{
			text-align: center;
			padding-top: 20rpx;
			color: #FFFFFF;
			font-family: BTH;
			font-size: 40rpx;
		}
	}
	.scroll-view{
		// #ifdef MP-WEIXIN
		height: calc(100% - 61rpx - env(safe-area-inset-bottom));
		// #endif
		// #ifdef APP-PLUS || H5
		height: calc(100% - 95px);
		// #endif
		padding: 30rpx;
		padding-bottom: 50rpx;
	}
	.list-content{
		display: flex;
		flex-wrap: wrap;
		padding-left: 30rpx;
		padding-right: 30rpx;
	}
	.item-class{
		width: 100%;
		height: 130rpx;
		display: flex;
		align-items: center;
		margin-top: 20rpx;
	}
	.avatar{
		width: 100rpx;
		height: 100rpx;
		background: linear-gradient(180deg, #82FF80 0%, #B26CFF 100%);
		border-radius: 50rpx;
		padding: 8rpx;
		image{
			width: 100%;
			height: 100%;
			background: #B3B3B3;
			box-shadow: inset 10rpx 0rpx 0rpx 0rpx rgba(35,35,35,0.25);
			border-radius: 60rpx;
			border: 2rpx solid #FFFFFF;
		}
	}
	.content{
		width: calc(100% - 240rpx);
		display: flex;
		flex-direction: column;
		margin-left: 36rpx;
		view:last-child{
			padding-top: 10rpx;
		}
	}
	.tag{
		width: 120rpx;
		padding-left: 24rpx;
		display: flex;
		justify-content: flex-end;
		align-items: center;
		text-align: right;
	}
	.tag-img{
		width: 84rpx;
		height: 84rpx;
		text-align: center;
		line-height: 84rpx;
		clip-path: polygon(50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%,0 25%);
	}
	.tag-a{
		background: linear-gradient(to top, rgba(26,255,246,1), rgba(26,255,246,0.12));
	}
	.tag-b{
		background: linear-gradient(to top, rgba(219,96,192,1), rgba(242,116,200,0.12));
	}
	.tag-c{
		background: linear-gradient(to top, rgba(67,53,143,1), rgba(129,99,255,0.12));
	}
	.tag-sp{
		background: linear-gradient(to top, rgba(255,213,0,1), rgba(255,228,94,0.12));
	}
	.tag-last{
		background: linear-gradient(to top, rgba(255,213,0,1), rgba(255,228,94,0.12));
	}
	.desc-text{
		font-size: 28rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #FFFFFF;
	}
}
</style>