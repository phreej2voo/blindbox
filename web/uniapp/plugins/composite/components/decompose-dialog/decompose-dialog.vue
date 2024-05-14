<template>
	<view class="container-dialog">
		<view class="box">
			<image :src="collectedPopUps" mode="aspectFill" class="dialog-bck img"></image>
			<image :src="closeImg" mode="widthFix" @click="close" class="colse img"></image>
			<scroll-view class="content-dialog" scroll-y="true">
				<template v-if="myGoods.length">
					<view class="selected-list-box">
						<block v-for="(item, index) in myGoods" :key="index">
							<view class="selected-item" @click="openSelectedNum(item)">
								<image class="seleced-bck" :src="item.isSelected? selectedBox : unselectedBox" mode="aspectFill"></image>
								<view class="selected-item-box">
									<image :src="item.goods_image" mode="aspectFill" class="cards-item"></image>
									<view class="cards-title">
										{{ item.goods_name }}
									</view>
									<view class="selected-num" :class="item.selectedNum? '' : 'unselected-num'">
										<text>{{ item.selectedNum?  `已选${item.selectedNum}件` : `${item.num}件`}}</text>
									</view>
								</view>
							</view>
						</block>
					</view>
				</template>
				<template v-else>
					<empty></empty>
				</template>
			</scroll-view>
			<view class="confirm">
				<view class="progress">
					当前材料进度：
					<text class="strong green">{{ currentCompleteVal }}</text>
					<text class="strong">/{{ conflateVal }}</text>
				</view>
				<view class="collected-btn" @click="confirmAdd">
					<image :src="collectedBtn" mode="aspectFill"></image>
					<text>确认添加</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import {
		get_conflate_putIn,
	} from '@/api/composite.js';
	export default {
		data() {
			return {
				closeImg: baseUrl.imgroot + 'static/images/wareHouse/close.png',
				collectedBtn: baseUrl.imgroot + 'static/images/composite/Group001.png',
				collectedPopUps: baseUrl.imgroot + 'static/images/composite/collected-pop-ups.png',
				selectedBox: baseUrl.imgroot + 'static/images/composite/onSelected.png',
				unselectedBox: baseUrl.imgroot + 'static/images/composite/unSelected.png',
				collectedGoods: [],
			};
		},
		props: {
			myGoods: [Array, String],
			cardsId: [String, Number],
			conflateVal: {
				type: [String, Number],
				default: 0
			},
			currentCompleteVal: {
				type: [String, Number],
				default: 0
			},
		},
		methods: {
			// 开启选择数量
			openSelectedNum(el){
				if(el.isSelected){
					this.$emit('clearStatus', el.goods_id, 'dialog')
				}else{
					this.$emit('changeModelNum', el.num, el.goods_id, 'dialog')
				}
			},
			// 关闭弹窗
			close(){
				this.$emit('closeDialog')
			},
			// 确认添加
			confirmAdd(){
				if(!this.myGoods.length){
					uni.showToast({
						title: '暂无可添加的碎片',
						icon:'none',
						mask: true
					})
					return
				}
				this.collectedGoods = this.myGoods.reduce((pre, item) => {
					if(item.isSelected){
						pre.push({
							goods_id: item.goods_id,
							num: item.selectedNum
						})
					}
					return pre
				}, []);
				if(!this.collectedGoods.length){
					uni.showToast({
						title: '暂无可添加的碎片',
						icon:'none',
						mask: true
					})
					return
				}
				this.getConflatePutIn();
			},
			// 确认放入碎片
			async getConflatePutIn(list){
				try{
					let data = {
						goods: this.collectedGoods,
						conflate_id: this.cardsId
					}
					let res = await get_conflate_putIn(data);
					uni.showToast({
						title: res.msg,
						icon: 'none',
						mask: true
					})
					if(res.code == 0){
						this.$emit('getConflateProgressDetail')
						this.close()
					}
				}catch(e){
					//TODO handle the exception
				}
			}
		},
	}
</script>

<style scoped lang="scss">
@import './decompose-dialog.scss';
</style>