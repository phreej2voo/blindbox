<template>
	<view class="claim-benefits" :style="{'padding-top': currentTab == 2? '100rpx': '480rpx'}">
		<!-- tab栏 -->
		<tabs-list :tabList="tabList" :defaultTab="currentTab" :boxWidth="248" :textWidth="240"
		:justifyContent="'space-around'" @changeTab="changeTab"></tabs-list>
		
		<view class="cards-info-box" v-if="currentTab == 1 && currentCards">
			<view class="relative-box">
				<image :src="vipCardsBox" mode="aspectFill" class="vip-cards-box"></image>
				<view class="cards-info">
					<view class="cards-name">
						<text>{{ currentCards.name? currentCards.name : '' }}</text>
						<text class="cards-No">{{ currentCards.card_no}}</text>
					</view>
					<view class="cards-content">
						每日福利大放送
					</view>
					<view class="cards-content">
						更多权益等你来领
					</view>
					<view class="cards-time">
						<view>有效期至 {{ currentCards.end_date }}</view>
						<view>剩余 <text>{{ currentCards.valid_day }}</text> 天</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list-box">
			<template v-if="awardList && awardList.length">
				<block v-for="(item,index) in awardList" :key="index">
					<template v-if="currentTab == 1">
						<claimItem :key="index" :item="item" :index="index" @getVipCardEquity="getVipCardEquity"></claimItem>
					</template>
					<template v-else-if="currentTab == 2">
						<receiveLog :key="index" :item="item" :index="index"></receiveLog>
					</template>
				</block>
			</template>
			<template v-else>
				<empty></empty>
			</template>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import claimItem from '../components/claim-item/index.vue';
	import receiveLog from '../components/receive-log-item/receive-log-item.vue';
	import {
		vipCardLog,
		vipCardEquity,
	} from '@/api/memberBenefits.js';
	export default {
		data() {
			return {
				tabList: [{
					id: 1,
					name: '待领取',
					value: 1,
				},{
					id: 2,
					name: '已领取',
					value: 2,
				},],
				currentTab: 1,
				page: 1,
				lastPage: 1,
				vipCardsBox: baseUrl.imgroot + 'static/images/memberBenefits/vip-cards-box.png',
				awardList: [],
				currentCards: null,
			}
		},
		components: {
			claimItem,
			receiveLog
		},
		methods: {
			changeTab(e){
				uni.pageScrollTo({
					scrollTop: 0,
					duration: 0
				});
				this.currentTab = e.id;
				this.awardList = [];
				if(this.currentTab == 1){
					this.getVipCardEquity();
				}else if(this.currentTab == 2){
					this.page = 1;
					this.getVipCardLog();
				}
			},
			// 权益领取记录
			async getVipCardLog(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true,
					})
					let res = await vipCardLog({
						limit: '10',
						page: this.page
					});
					uni.hideLoading();
					if(res.code == 0){
						this.lastPage = res.data.last_page;
						let data = res.data.data;
						if(this.page == 1){
							this.awardList = data;
						}else{
							this.awardList = [...this.awardList, ...data];
						}
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			// 待领取权限
			async getVipCardEquity(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let res = await vipCardEquity();
					uni.hideLoading()
					if(res.code == 0){
						this.awardList = res.data.award;
						this.currentCards = res.data.card;
					}
					
				}catch(e){
					uni.hideLoading()
					//TODO handle the exception
				}
			}
		},
		onLoad() {
			this.getVipCardEquity();
		},
		onReachBottom() {
			if(this.currentTab == 2 && this.page < this.lastPage){
				this.page++;
				this.getVipCardLog();
			}
		}
	}
</script>

<style scoped lang="scss">
@import './claim-benefit.scss';
</style>
