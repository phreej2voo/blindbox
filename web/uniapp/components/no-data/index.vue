<template>
	<view class="no-class">
		<image class="img-class" :src="NoDataImg"></image>
		<view class="no-text">{{ noText }}</view>
	</view>
</template>

<script>
	import { NoDataImg } from '@/utils/objectValue';

	export default {
		props:{
			isLoading: {
				type: Boolean,
				default: false
			},
			noText: {
				type: String,
				default: '暂无数据'
			}
		},
		data() {
			return {
				NoDataImg
			};
		},
		methods: {

		}
	}
</script>

<style lang="scss" scoped>
.no-class {
	width: 100%;
	height: 100%;
	// margin-top: 50%;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	.img-class{
		width: 196rpx;
		height: 196rpx;
	}
	.no-text{
		margin-top: 30rpx;
		font-size: 32rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #AFAFAF;
	}
}

</style>