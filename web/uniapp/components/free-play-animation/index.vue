<template>
	<u-popup :show="showPopup" mode="center" round="14px" bgColor="transparent"
		:safeAreaInsetBottom="false" :closeOnClickOverlay="false">
		<view class="free-container">
			<view class="img-animation">
				<!-- <image :src="FreeGif[numType]"></image> -->
				<u--image :showLoading="true" :src="FreeGif[numType]" width="100vw" height="100vh">
				</u--image>
			</view>
		</view>
	</u-popup>
</template>

<script>
	import { FreeGif } from '@/utils/objectValue';

	export default {
		props: {
			showPlay: {
				type: Boolean,
				default: false
			},
			numType: {
				type: String,
				default: '1'
			}
		},
		data() {
			return {
				FreeGif,
				showPopup: false
			};
		},
		watch: {
			showPlay(newVal) {
				if(newVal) {
					setTimeout(() => {
						this.showPopup = true;
						this.$nextTick(() => {
							setTimeout(() => {
								this.showPopup = false;
								this.$emit('closePlay');
							}, 8500);
						});
					}, 300);
				}
			}
		},
		methods: {
		}
	}
</script>

<style lang="scss" scoped>
.free-container{
	width: 100vw;
	height: 100vh;
	background-color: $uni-bg-color-mask;
	display: flex;
	justify-content: center;
	align-items: center;
	.img-animation{
		width: 100%;
		height: 100%;
		// image {
		// 	width: 100%;
		// 	height: 100%;
		// }
	}
}
</style>