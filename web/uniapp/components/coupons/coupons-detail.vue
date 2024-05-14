<template>
	<view>
		<view class="list-item">
			<view class="left-area">
				<view class="left-top-area">优惠券</view>
				<view class="left-bottom-area">
					<text v-if="detailObj.type === 1" class="num_1">¥</text>
					<text class="num_2">{{ detailObj.type === 1 ? Math.floor(detailObj.amount) : detailObj.discount * 10 + '折' }}</text>
				</view>
				<!-- <view class="num_3">满60元可用</view> -->
			</view>
			<!-- <view class="middle-area"></view> -->
			<view class="right-area">
				<view class="">
					<text class="text_1">{{ detailObj.name }}</text>
					<!-- <text class="text_1">30元全场通用券</text> -->
				</view>
				<!-- <view class="right-bottom-area">
					无门槛适用于所有盲盒
				</view> -->
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props:{
			detailData: {
				type: Object,
				default: () => {
					return {};
				}
			}
		},
		data() {
			return {
			};
		},
		computed: {
			detailObj() {
				return {...this.detailData};
			}
		},
		methods: {
			// 解锁
			unlock() {}
		}
	}
</script>

<style lang="scss" scoped>
.list-item{
	width: 100%;
    height: 100px;
	margin-top: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #ffffff;
	background-size: cover;
	background-repeat: no-repeat center;
	border-radius: 5px;
	// background-image: url(http://dev-hashmart.mstshop.cn/static/images/coupons-back.png);

	.left-area{
		width: 32%;
		height: 100%;
		display: flex;
		flex-direction: column;
		// align-items: center;
		// justify-content: center;
		background: radial-gradient(circle at top right, #FDDBF1 10px, transparent 0) top right,
					radial-gradient(circle at bottom right, #FDDBF1 10px, transparent 0) bottom right;
		background-size: 50% 50%;
		background-repeat: no-repeat;
	}
	.left-top-area {
		width: 45px;
		height: 25px;
		text-align: center;
		overflow-wrap: break-word;
		color: white;
		background-color: #EB872E;
		font-size: 12px;
		font-family: PingFangHK-Regular;
		white-space: nowrap;
		line-height: 25px;
		border-top-left-radius: 5px;
		border-bottom-right-radius: 5px;
	}
	.left-bottom-area{
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		line-height: 52px;
		border-right: 1px dashed #EA6E7A;
	}
	.num_1 {
		overflow-wrap: break-word;
		color: #2C0A0B;
		font-size: 18px;
		font-family: PingFangHK-Semibold;
		font-weight: 600;
		white-space: nowrap;
	}
	.num_2 {
		overflow-wrap: break-word;
		color: #2C0A0B;
		font-size: 32px;
		font-family: PingFangHK-Semibold;
		font-weight: 600;
		white-space: nowrap;
	}
	.middle-area {
		width: 2px;
		height: 95%;
	}
	.right-area{
		width: 68%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		background: radial-gradient(circle at top left, #FDDBF1 10px, transparent 0) top left,
					radial-gradient(circle at bottom left, #FDDBF1 10px, transparent 0) bottom left;
	}
	.right-top-area {
		
	}
	.right-bottom-area {
		overflow-wrap: break-word;
		color: rgba(179, 120, 120, 1);
		font-size: 14px;
		font-family: PingFangHK-Regular;
		font-weight: NaN;
		text-align: left;
		white-space: nowrap;
		line-height: 20px;
		margin: 4px 29px 0 8px;
	}
	.text_1 {
		overflow-wrap: break-word;
		color: rgba(70, 40, 40, 1);
		font-size: 14px;
		font-family: PingFangHK-Medium;
		font-weight: 500;
		white-space: nowrap;
		line-height: 20px;
	}

	.text_2 {
		overflow-wrap: break-word;
		color: rgba(179, 120, 120, 1);
		font-size: 14px;
		font-family: PingFangHK-Regular;
		font-weight: NaN;
		white-space: nowrap;
		line-height: 20px;
		margin: 4px 29px 0 8px;
	}
}
</style>