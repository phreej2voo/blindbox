<template>
	<view class="composite-advance" v-if="cardsInfo">
		<image :src="backImg" mode="widthFix" class="back-img"></image>
		<view class="content-box">
			<!-- 顶部导航栏 -->
			<view class="top-nav" :style="{'padding-top': paddingTop + 'rpx'}">
				<view class="img-back" @click="goBack">
					<image src="../../../static/image/icon/back.png" mode="widthFix"></image>
				</view>
				<view>造梦重构器</view>
			</view>
		</view>
		<!-- 合成的详情内容 -->
		<view class="advance-detail">
			<view class="space-block"></view>
			<scroll-view class="advance-content" scroll-y="true" @scrolltolower="scrolltolower">
				<!-- 活动时间 -->
				<view class="activity-time">
					<image :src="timeBtn" mode="aspectFill"></image>
					<view class="text">活动时间{{ cardsInfo.start_time }} 至 {{ cardsInfo.end_time }}</view>
				</view>
				<!-- 商品图片+信息 -->
				<view class="goods-info">
					<view class="goods-img">
						<image :src="rectangle" mode="aspectFill" class="bck-img"></image>
						<image :src="cardsInfo.image" mode="aspectFill" class="cards-img"></image>
					</view>
					<!-- 商品名称 -->
					<view class="goods-name">
						{{ cardsInfo.goods_name }}
					</view>
					<!-- 商品价格+数量 -->
					<view class="goods-price-num">
						<view class="goods-price">
							￥<text>{{ cardsInfo.price }}</text>
						</view>
						<view class="goods-num">
							剩余<text class="green">{{ cardsInfo.stock }}个</text>/<text class="purple">{{ cardsInfo.sales }}个</text>已合成
						</view>
					</view>
					
					<view class="composite-container">
						<!-- 需要合成值 -->
						<compositeProgress :completeNum="completeNum" :conflateVal="conflateVal" :completeVal="completeVal"
						:cardsId="cardsId" :progressId="progressId" :progressList="progressList"
						@getConflateDetail="getConflateDetail" @clearStatus="clearStatus" @getConflateProgressDetail="getConflateProgressDetail"
						@toggle="toggle" @openSelected="openSelected" @changeModelNum="changeModelNum"></compositeProgress>
						<!-- 材料图鉴 -->
						<view class="materials-list-box">
							<view class="mianTitle common">
								材料图鉴
							</view>
							<view class="subheading common">普通材料</view>
							<view class="materials-list">
								<block v-for="(item, index) in materialList" :key="index">
									<materialsItem :item="item" :key="index"></materialsItem>
								</block>
							</view>
						</view>
					</view>
				</view>
			</scroll-view>
			<!-- 去收集材料 -->
			<view class="collected-box">
				<view class="collected-btn" @click="goIndex">
					<image :src="collectedBtn" mode="aspectFill"></image>
					<text>去收集材料</text>
				</view>
			</view>
		</view>
		<!-- 加入碎片 -->
		<uni-popup ref="popup" type="bottom">
			<decomposeDialog :myGoods="myGoods" :conflateVal="conflateVal" :currentCompleteVal="currentCompleteVal"
			:cardsId="cardsId" @clearStatus="clearStatus" @getConflateProgressDetail="getConflateProgressDetail"
			@closeDialog="closeDialog" @openSelected="openSelected" @changeModelNum="changeModelNum"></decomposeDialog>
		</uni-popup>
		<!-- 数量选择 -->
		<popupContent ref="popupContent" :modelNum="modelNum" :maxNum="maxNum"
		@confirmModelNum="confirmModelNum"></popupContent>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import decomposeDialog from '../components/decompose-dialog/decompose-dialog.vue';
	import compositeProgress from '../components/composite-progress/composite-progress.vue';
	import materialsItem from '../components/materials-item/materials-item.vue';
	import popupContent from '@/components/popup-content/popup-content.vue';
	
	import {
		get_conflate_detail,
		get_conflate_myGoods,
		get_conflate_progressDetail,
	} from '@/api/composite.js';
	
	export default {
		data() {
			return {
				backImg: baseUrl.imgroot + 'static/images/composite/main-box-bg.png',
				timeBtn: baseUrl.imgroot + 'static/images/composite/time_btn.png',
				rectangle: baseUrl.imgroot + 'static/images/composite/Rectangle001.png',
				compositeBtn: baseUrl.imgroot + 'static/images/composite/composite_btn.png',
				collectedBtn: baseUrl.imgroot + 'static/images/composite/Group001.png',
				paddingTop: 14,
				cardsId: '',
				cardsInfo: null,
				conflateVal: 0,
				completeVal: 0,
				currentCompleteVal: 0,
				materialList: [],
				myGoods: [],
				progressList: [],
				modelNum: 0,
				maxNum: 0,
				currentId: '',
				type: '',
				progressId: '',
			}
		},
		components: {
			compositeProgress,
			materialsItem,
			decomposeDialog,
			popupContent
		},
		computed: {
			completeNum(){
				let num = 0;
				if(this.conflateVal){
					num = parseInt((this.completeVal / this.conflateVal) * 18);
				}
				return num
			}
		},
		methods: {
			// 去收集材料
			goIndex(){
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			// 顶部返回箭头
			goBack(){
				uni.navigateBack()
			},
			// 滚动触底事件
			scrolltolower(){
				console.log('触底了~')
			},
			// 清除选中的状态
			clearStatus(id, type){
				if(type == 'dialog'){
					this.myGoods = this.myGoods.reduce((pre,item) => {
						if(item.goods_id == id){
							item.isSelected = false;
							item.selectedNum = 0;
						}
						pre.push(item);
						return pre
					}, []);
				}else if(type == 'progress'){
					this.progressList = this.progressList.reduce((pre,item) => {
						if(item.goods_id == id){
							item.isSelected = false;
							item.selectedNum = 0;
						}
						pre.push(item);
						return pre
					}, []);
				}
			},
			// 单个材料数量弹窗的最大件数
			changeModelNum(num, id, type){
				this.maxNum = Number(num);
				this.modelNum = Number(num);
				this.currentId = id;
				this.type = type;
				if(num == 1){
					this.confirmModelNum(num)
				}else{
					this.openSelected();
				}
			},
			// 关闭弹窗
			closeDialog(){
				this.$refs.popup.close();
			},
			// 添加
			toggle() {
				this.getConflateMyGoods();
				this.$refs.popup.open();
			},
			//开启数量选择
			openSelected() {
				this.$refs.popupContent.openBtn()
			},
			// 关闭数量选择
			closeSelected() {
				this.$refs.popupContent.closeBtn()
			},
			// 修改数量选择
			confirmModelNum(num){
				this.modelNum = Number(num);
				if(this.type == 'dialog'){
					this.myGoods = this.myGoods.reduce((pre,item) => {
						if(item.goods_id == this.currentId){
							item.isSelected = true;
							item.selectedNum = this.modelNum;
						}
						pre.push(item);
						return pre
					}, []);
				}else if(this.type == 'progress'){
					this.progressList = this.progressList.reduce((pre,item) => {
						if(item.goods_id == this.currentId){
							item.isSelected = true;
							item.selectedNum = this.modelNum;
						}
						pre.push(item);
						return pre
					}, []);
				}
			},
			// 获取我的材料
			async getConflateMyGoods(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true,
					})
					let info = await get_conflate_myGoods({
						id: this.cardsId
					});
					uni.hideLoading()
					if(info.code == 0){
						this.myGoods = info.data.reduce((pre,item) => {
							item.isSelected = false;
							item.selectedNum = 0;
							pre.push(item);
							return pre
						}, []);
					}
				}catch(e){
					uni.hideLoading()
					//TODO handle the exception
				}
			},
			// 获取进度详情
			async getConflateProgressDetail(type){
				try{
					let info = await get_conflate_progressDetail({
						id: this.cardsId
					});
					if(type != 'noneLoading'){
						uni.hideLoading()
					}
					if(info.code == 0){
						this.conflateVal = info.data.total_conflate;
						this.completeVal = info.data.now_conflate;
						this.currentCompleteVal = info.data.now_conflate;
						this.progressId = info.data.progress_id;
						this.progressList = info.data.goods.reduce((pre,item) => {
							item.isSelected = false;
							item.selectedNum = 0;
							pre.push(item);
							return pre
						}, []);
					}
				}catch(e){
					uni.hideLoading()
					//TODO handle the exception
				}
			},
			// 合成活动详情
			async getConflateDetail(type){
				try{
					if(type != 'noneLoading'){
						uni.showLoading({
							title: '加载中',
							mask: true
						});
					}
					let res = await get_conflate_detail({
						id: this.cardsId
					});
					if(res.code == 0){
						let data = res.data;
						this.materialList = [...data.material];
						if(type == 'first'){
							this.cardsInfo = {
								start_time: data.start_time,
								end_time: data.end_time,
								goods_name: data.goods_name,
								image: data.image,
								price: data.price,
								stock: data.stock,
								sales: data.sales
							};
						}else{
							this.cardsInfo.stock = data.stock;
							this.cardsInfo.sales = data.sales;
						}
						this.getConflateProgressDetail(type);
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			}
		},
		onLoad(options) {
			// #ifdef MP
			let menuButtonInfo = uni.getMenuButtonBoundingClientRect();
			this.paddingTop = (menuButtonInfo.top + menuButtonInfo.height / 2) * 2 - 21;
			// #endif
			// #ifdef APP-PLUS
			let menuButtonInfo = uni.getSystemInfoSync();
			this.paddingTop = (menuButtonInfo.safeArea.top) * 2;
			// #endif
			if(options.id){
				this.cardsId = options.id;
				this.getConflateDetail('first');
			}
		},
	}
</script>

<style scoped lang="scss">
@import "./advance.scss";
</style>
