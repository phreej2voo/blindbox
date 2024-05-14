<template>
	<view class="welfare-container">
		<view class="fixed-top">
			<!-- 输入房间口令 输入框 -->
			<!-- <view class="input-box">
				<input placeholder="输入房间口令" placeholder-class="placeholder" type="text" @confirm="confirmInput" confirm-type="search">
				<uni-icons type="search" color="#71E594" size="27" @click="confirmInput"></uni-icons>
			</view> -->
			<!-- 手办免费送 -->
			<view class="activity-pic">
				<image :src="benefitPic" mode="widthFix"></image>
			</view>
		</view>
		<!-- 福利房列表 -->
		<view class="welfare-content" v-if="welfareList && welfareList.length">
			<block v-for="(item, index) in welfareList" :key="index">
				<view class="welfare-home-item" :key="index">
					<image :src="welfareHomeBox" mode="aspectFill" class="welfare-home-img"></image>
					<view class="welfare-home-box">
						<view class="welfare-img">
							<image :src="item.avatar" mode="aspectFill"></image>
						</view>
						<view class="welfare-name">
							{{ item.title }}
						</view>
						<view class="welfare-material">
							<block v-for="(el, i) in item.award" :key="i">
								<image :src="el" mode="aspectFill" :key="i"></image>
							</block>
						</view>
						<view class="welfare-num-accoumt">
							<view class="item">
								<view>件数</view>
								<view>{{ item.num }}</view>
							</view>
							<view class="item">
								<view>哈希币</view>
								<view>{{ item.recovery_price }}</view>
							</view>
						</view>
						<view class="go-welfare-home" @click="goDetail(item)">
							<text>进入房间</text>
						</view>
					</view>
				</view>
			</block>
		</view>
		<view class="" v-else>
			<empty contextTag="暂无福利房~"></empty>
		</view>
		<!-- 创建房间 -->
		<view class="created-home-btn" @click="createdHome">
			<image :src="createdHomeBtn" mode="aspectFill"></image>
			<text>创建</text>
		</view>
		<!-- 底部按钮 -->
		<view class="bottom-btn">
			<view class="btn-item" v-for="(item,index) in bottomBtns" :key="index" :class="item.id == 1? 'created' : ''" @click="clickBtns(item)">
				<image :src="item.bckImg" mode="aspectFill"></image>
				<text>{{ item.text }}</text>
			</view>
		</view>
		<!-- 说明弹窗 -->
		<popupIllustrate ref="illustrate"></popupIllustrate>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import {
		rollList,
	} from '@/api/welfare.js';
	import popupIllustrate from '@/components/popup-illustrate/index.vue';
	export default {
		data(){
			return {
				benefitPic: baseUrl.imgroot + 'static/images/welfare/benefit-pic.png',
				welfareHomeBox: baseUrl.imgroot + 'static/images/welfare/welfare-home-box.png',
				bottomBtns: [{
					id: 1,
					text: '参与记录',
					bckImg: baseUrl.imgroot + 'static/images/welfare/btn1.png',
					url: '/plugins/welfare-home/homes-history/homes-history'
				},{
					id: 2,
					text: '中赏记录',
					bckImg: baseUrl.imgroot + 'static/images/welfare/btn2.png',
					url: '/plugins/welfare-home/recording/recording'
				},{
					id: 3,
					text: '活动介绍',
					bckImg: baseUrl.imgroot + 'static/images/welfare/btn3.png',
					url: ''
				}],
				createdHomeBtn: baseUrl.imgroot + 'static/images/welfare/created-home-btn.png',
				page: 1,
				lastPage: 1,
				welfareList: [],
			}
		},
		components: {
			popupIllustrate,
		},
		methods: {
			// 搜索框
			confirmInput(){
				console.log('搜索')
			},
			// 创建房间
			createdHome(){
				uni.navigateTo({
					url: '/plugins/welfare-home/created-home/created-home'
				})
			},
			clickBtns(item){
				if(item.id == 3){
					this.$refs.illustrate.open();
				}else{
					uni.navigateTo({
						url: item.url
					})
				}
			},
			// 进入房间
			goDetail(item){
				let params = {
					id: item.id,
					type: item.type
				};
				uni.navigateTo({
					url: '/plugins/welfare-home/home-detail/home-detail?params=' + encodeURIComponent(JSON.stringify(params))
				})
			},
			// 福利房列表
			async getRollList(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let { code, data } = await rollList({
						page: this.page,
						limit: 10
					});
					uni.hideLoading();
					if(code == 0){
						this.lastPage = data.last_page;
						if(this.page == 1){
							this.welfareList = data.data;
						}else{
							this.welfareList = [...this.welfareList, ...data.data];
						}
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			}
		},
		onShow() {
			this.page = 1;
			this.getRollList();
		},
		onReachBottom() {
			if(this.page < this.lastPage){
				this.page++;
				this.getRollList();
			}
		}
	}
</script>

<style scoped lang="scss">
	@import "./index.scss";
</style>