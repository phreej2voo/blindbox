<template>
	<view class="composite-progress-box">
		<view class="title">
			总需要合成值
			<text class="green">{{ conflateVal }}</text>
			<view class="smaller">(相关材料可合成总值请查看下方详细进度)</view>
		</view>
		<view class="total-progress">
			<view>总进度</view>
			<view>{{ completeVal }}<text>/{{ conflateVal }}</text></view>
		</view>
		<view class="progress-box">
			<block v-for="(el,index) in 18" :key="index">
				<view class="single-box" :key="index" :class="index + 1 <= completeNum? 'satisfaction' : ''"></view>
			</block>
		</view>
		<!-- 普通材料 -->
		<view class="ordinary-materials">
			<view class="materials">
				普通材料
				<text class="strong green">{{ completeVal }}</text>
				<text class="strong">/{{ conflateVal }}</text>
			</view>
			<view class="btn" @click="addBtn">
				<image :src="compositeBtn" mode="aspectFill"></image>
				<text>添加</text>
			</view>
		</view>
		<!-- 已选材料： -->
		<view class="selected-materials" v-if="progressList.length">
			<view class="title">
				<text>已选材料：</text>
				<text class="cancel" :class="canRemoveList.length? 'canRemove' : ''" @click="remove">删除</text>
			</view>
			<view class="selected-list-box">
				<block v-for="(item, index) in progressList" :key="index">
					<view class="selected-item">
						<image class="seleced-bck" :src="item.isSelected? selectedBox : unselectedBox" mode="aspectFill"></image>
						<image :src="decrease" mode="aspectFill" class="decrease" v-if="item.selectedNum != item.num"></image>
						<view class="selected-item-box" @click="selectedCancel(item)">
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
		</view>
		<!-- 去合成 -->
		<template v-if="progressList.length">
			<view class="to-advance" @click="confirmConflate">
				<image :src="collectedBtn" mode="aspectFill"></image>
				<text>去合成</text>
			</view>
		</template>
		
		<comfirmMsg ref="comfirmMsg" :msg="msg"></comfirmMsg>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	
	import {
		remove_conflate,
		do_conflate,
	} from '@/api/composite.js';
	
	export default {
		data(){
			return {
				selectedBox: baseUrl.imgroot + 'static/images/composite/onSelected.png',
				unselectedBox: baseUrl.imgroot + 'static/images/composite/unSelected.png',
				decrease: baseUrl.imgroot + 'static/images/composite/decrease.png',
				collectedBtn: baseUrl.imgroot + 'static/images/composite/Group001.png',
				compositeBtn: baseUrl.imgroot + 'static/images/composite/composite_btn.png',
				removeGoods: [],
				canRemoveList: [],
				msg: '',
			}
		},
		props: {
			completeNum: {
				type: [String, Number],
				default: 0
			},
			conflateVal: {
				type: [String, Number],
				default: 0
			},
			completeVal: {
				type: [String, Number],
				default: 0
			},
			progressList: [Array],
			cardsId: [String, Number],
			progressId: [String, Number],
		},
		watch: {
			progressList: {
				handler(newList, oldList) {
					this.canRemoveList = this.progressList.reduce((pre, item) => {
				    	if(item.isSelected){
				    		pre.push(item)
				    	}
				    	return pre
				    }, []);
				},
				immediate: true
			}
		},
		methods: {
			// 添加材料
			addBtn(){
				this.$emit('toggle');
			},
			// 选择待删除的数量
			selectedCancel(el){
				if(el.isSelected){
					this.$emit('clearStatus', el.goods_id, 'progress')
				}else{
					this.$emit('changeModelNum', el.num, el.goods_id, 'progress')
				}
			},
			// 移除碎片
			remove(){
				this.removeGoods = this.progressList.reduce((pre, item) => {
					if(item.isSelected){
						pre.push({
							goods_id: item.goods_id,
							num: item.selectedNum
						})
					}
					return pre
				}, []);
				if(!this.removeGoods.length){
					uni.showToast({
						title: '暂无可移除的碎片',
						icon:'none',
						mask: true
					})
					return
				}
				this.removeConflate();
			},
			async removeConflate(){
				try{
					let data = {
						goods: this.removeGoods,
						progress_id: this.progressId
					}
					let res = await remove_conflate(data);
					uni.showToast({
						title: res.msg,
						icon: 'none',
						mask: true
					})
					if(res.code == 0){
						this.$emit('getConflateProgressDetail')
					}
				}catch(e){
					//TODO handle the exception
				}
			},
			// 去合成
			confirmConflate(){
				if(Number(this.completeVal) < Number(this.conflateVal)){
					uni.showToast({
						title: "您的目前的合成进度未满",
						icon: 'none',
						mask: true
					})
					return
				}
				this.doConflate()
			},
			async doConflate(){
				try{
					let res = await do_conflate({
						progress_id: this.progressId
					});
					this.msg = res.msg;
					this.$refs.comfirmMsg.openBtn();
					if(res.code == 0){
						this.$emit('getConflateDetail', 'noneLoading')
					}
				}catch(e){
					//TODO handle the exception
				}
			}
		}
	}
</script>

<style scoped lang="scss">
@import './composite-progress.scss';
</style>