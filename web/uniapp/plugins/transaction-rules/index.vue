<template>
</template>

<script>
</script>

<style>
</style><template>
	<view class="container">
		<rich-text :nodes="htmlNodes"></rich-text>
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
					if (res.code == 0) {
						this.htmlNodes = res.data.content
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
		background: #1D1F36;
	}

</style>

