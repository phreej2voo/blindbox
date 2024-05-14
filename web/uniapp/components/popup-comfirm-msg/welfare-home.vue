<template>
	<uni-popup ref="popupComfrim" type="center">
		<view class="popup-content-box">
			<view class="welfare-title">
				输入房间密码
			</view>
			<view class="welfare-input">
				<input type="text" v-model="password" placeholder="请输入密码" />
			</view>
			<view class="btn-cancel-confirm">
				<view class="cancel btn-item" @click="closeBtn">
					取消
				</view>
				<view class="line"></view>
				<view class="confirm btn-item" @click="confirm">
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
				password: ''
			}
		},
		props: {
			homesType: {
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
			confirm() {
				if(this.homesType){
					this.$emit('postRollJoin', this.password)
					this.closeBtn();
				}else{
					this.closeBtn();
				}
			}
		}
	}
</script>

<style scoped lang="scss">
	@import "./index.scss";
</style>