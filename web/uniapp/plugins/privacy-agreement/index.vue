<template>
	<view class="container">
		<scroll-view enableFlex scroll-y class="scroll-class">
			<view v-if="htmlNodes">
				<rich-text :nodes="htmlNodes"></rich-text>
			</view>
			<view v-else class="empty-content main-center-flex"> 
				<empty></empty>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import { userAgreement } from '@/api/setting.js'
	export default {
		data() {
			return {
				htmlNodes: null,
			}
		},
		onLoad() {
			this.getList()
		},
		methods: {
			getList() {
				userAgreement({
					type: 2
				}).then(res => {
					const {code, data} = res;
					if (code == 0) {
						this.htmlNodes = data ? data.content : null
					}
				})
			},
		}
	}

</script>

<style scoped>
	.container {
		width: 750rpx;
		height: 100vh;
		position: fixed;
		top: 0;
		left: 0;
		background:  #1D1F36;
		color: #fff;
		padding: 0 30rpx;
	}
	.scroll-class{
		height: calc(100% - env(safe-area-inset-bottom));
	}
	.empty-content{
		height: 100%;
	}
</style>

