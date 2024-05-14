<template>
	<view class="composite-index">
		<!-- 返回箭头 -->
		<view class="img-back" :style="{'padding-top': paddingTop + 'rpx'}">
			<image @click="goBack" src="../../../static/image/icon/back.png" mode="widthFix"></image>
		</view>
		<view class="content" :style="{'background': `url(${topBg})`}">
			<view class="">
				<!-- 造梦重构 -->
				<view class="title">
					<image :src="thunderLeft" mode="" class="thunder"></image>
					<view class="placeholder">
						<text class="text">造梦重构</text>
					</view>
					<image :src="thunderRight" mode="" class="thunder"></image>
				</view>
				<!-- 合成记录 玩法规则 -->
				<view class="all-btns">
					<view class="item-btn" v-for="(item, index) in btnsList" :key="index" @click.stop="goOtherPage(item)">
						<image :src="item.img" mode="aspectFill"></image>
						<text :class="index == 1? 'rigth' : 'left'">{{ item.text }}</text>
					</view>
				</view>
			</view>
		</view>
		<!-- 可合成的列表 -->
		<scroll-view class="composite-list" scroll-y="true" @scrolltolower="scrolltolower">
			<block v-for="(item, index) in cardsList" :key="index">
				<cards-item :item="item" :key="index"></cards-item>
			</block>
		</scroll-view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import { get_conflate_list } from '@/api/composite.js';
	import cardsItem from '../components/cards-item/cards-item.vue';
	export default {
		data() {
			return {
				topBg: baseUrl.imgroot + 'static/images/composite/top_bg.png',
				thunderLeft: baseUrl.imgroot + 'static/images/composite/thunder_left.png',
				thunderRight: baseUrl.imgroot + 'static/images/composite/thunder_right.png',
				btnsList: [{
					text: '合成记录',
					img: baseUrl.imgroot + 'static/images/composite/btn_left.png',
					url: '/plugins/composite/recording/recording'
				},{
					text: '玩法规则',
					img: baseUrl.imgroot + 'static/images/composite/btn_right.png',
					url: '/plugins/composite/rules-play/rules-play'
				}],
				page: 1,
				lastPage: 1,
				cardsList: [],
				paddingTop: 14,
			}
		},
		components: {
			cardsItem
		},
		methods: {
			// 滚动触底事件
			scrolltolower(){
				console.log('触底了')
				if(this.page < this.lastPage){
					this.page++;
					this.getConflateList();
				}
			},
			// 顶部返回箭头
			goBack(){
				uni.navigateBack()
			},
			// 跳转合成记录 玩法规则 
			goOtherPage(el){
				uni.navigateTo({
					url: el.url
				})
			},
			// 获取列表
			async getConflateList(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let res = await get_conflate_list({
						page: this.page,
						limit: 10
						
						,
					});
					if(res.code == 0){
						if(this.page == 1){
							this.lastPage = res.data.last_page;
							this.cardsList = res.data.data;
						}else{
							this.cardsList = [...this.cardsList, ...res.data.data];
						}
					}
					uni.hideLoading()
				}catch(e){
					uni.hideLoading()
					//TODO handle the exception
				}
			}
		},
		onLoad() {
			// #ifdef MP
			let menuButtonInfo = uni.getMenuButtonBoundingClientRect();
			this.paddingTop = (menuButtonInfo.top + menuButtonInfo.height / 2) * 2 - 21;
			// #endif
			// #ifdef APP-PLUS
			let menuButtonInfo = uni.getSystemInfoSync();
			this.paddingTop = (menuButtonInfo.safeArea.top) * 2;
			// #endif
		},
		onShow() {
			this.page = 1;
			this.getConflateList();
		},
		onReachBottom() {
			if(this.page < this.lastPage){
				this.page++;
				this.getConflateList();
			}
		}
	}
</script>

<style scoped lang="scss">
@import "./index.scss";
</style>
