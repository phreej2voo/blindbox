<template>
	<uni-popup ref="popupComfrim" type="center">
		<view class="popup-content-box">
			<view class="top-tip" v-if="isType">
				提示
			</view>
			<view class="popup-title">
				{{ msg }}
			</view>
			<view class="btn-cancel-confirm">
				<view class="cancel btn-item" @click="closeBtn">
					{{ isType ? '知道了' : '取消' }}
				</view>
				<view class="line"></view>
				<view class="go-bag btn-item confirm" @click="goBag" v-if="isType">
					去抽赏
				</view>
				<view class="confirm btn-item" @click="closeBtn" v-else>
					确认
				</view>
			</view>
		</view>
	</uni-popup>
</template>

<script>
	export default {
		data(){
			return {
			}
		},
		props: {
			msg: {
				type: String,
				default: '选中该赏数量'
			},
			isType: {
				type: Boolean,
				default: false
			}
		},
		methods: {
			//开启数量选择
			openBtn() {
				this.$refs.popupComfrim.open()
			},
			// 关闭数量选择
			closeBtn() {
				this.$refs.popupComfrim.close()
			},
			// 去抽赏
			goBag(){
				uni.switchTab({
					url: '/pages/index/index'
				})
			}
		}
	}
</script>

<style scoped lang="scss">
	@import "./index.scss";
</style>