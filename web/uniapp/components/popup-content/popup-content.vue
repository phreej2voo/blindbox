<template>
	<uni-popup ref="popupNum" type="center">
		<view class="popup-content-box">
			<view class="popup-title">
				选中该赏数量
			</view>
			<view class="selected-num">
				<image :src="decreaseBtn" mode="aspectFill" class="decrease icon" @click="decrease"></image>
				<view class="input">
					<input type="number" class="number" :value="goodsNum" @blur="blur" @confirm="confirm">
					<text>件</text>
				</view>
				<image :src="addBtn" mode="aspectFill" class="add icon" @click="add"></image>
			</view>
			<view class="btn-cancel-confirm">
				<view class="cancel btn-item" @click="cancel">
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
	import baseUrl from '@/utils/siteInfo';
	export default {
		data(){
			return {
				addBtn: baseUrl.imgroot + 'static/images/composite/addBtn.png',
				decreaseBtn: baseUrl.imgroot + 'static/images/composite/decreaseBtn.png',
				goodsNum: 0
			}
		},
		props: {
			modelNum: {
				type: Number,
				default: 0,
			},
			maxNum: {
				type: Number,
				default: 0,
			}
		},
		watch: {
			modelNum: {
				handler(newNum, oldNum) {
				    this.goodsNum = newNum
				},
				immediate: true
			}
		},
		methods: {
			//开启数量选择
			openBtn() {
				this.$refs.popupNum.open()
			},
			// 关闭数量选择
			closeBtn() {
				this.$refs.popupNum.close()
			},
			// 增加
			add(){
				if(Number(this.maxNum) > this.goodsNum){
					this.goodsNum++;
					// this.$emit('confirmModelNum', Number(this.goodsNum));
				}else{
					this.goodsNum = this.maxNum;
					uni.showToast({
						title: '选择数量最多' + this.maxNum + '件',
						mask: true,
						icon: 'none'
					})
				}
			},
			// 减少
			decrease(){
				if(this.goodsNum > 1){
					this.goodsNum--;
					// this.$emit('confirmModelNum', Number(this.goodsNum));
				}else{
					this.goodsNum = 1;
					uni.showToast({
						title: '选择数量最低1件',
						mask: true,
						icon: 'none'
					})
				}
			},
			blur(e){
				if(Number(e.detail.value) > 0 && Number(e.detail.value) <= Number(this.maxNum)){
					this.goodsNum = Number(e.detail.value);
				}
			},
			// 取消按钮
			cancel(){
				this.goodsNum = this.maxNum;
				this.closeBtn();
			},
			// 确认按钮
			confirm(){
				if(!this.goodsNum){
					this.goodsNum = Number(this.maxNum);
				}
				this.$emit('confirmModelNum', Number(this.goodsNum));
				this.closeBtn()
			}
		}
	}
</script>

<style scoped lang="scss">
	@import "./popup-content.scss";
</style>