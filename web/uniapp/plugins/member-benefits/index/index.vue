<template>
	<view class="benefits-list-index">
		<tabs-list :tabList="tabList" :defaultTab="currentTab" :boxWidth="248" :textWidth="240"
		:justifyContent="'space-around'" @changeTab="changeTab"></tabs-list>
		<!-- 会员卡列表 -->
		<view class="vip-cards-list">
			<template v-if="cardsList.length">
				<block v-for="(item, index) in cardsList" :key="index">
					<vipCardsItem :key="index" :currentTab="currentTab" :item="item"></vipCardsItem>
				</block>
			</template>
			<template v-else>
				<empty></empty>
			</template>
		</view>
	</view>
</template>

<script>
	import vipCardsItem from '../components/vip-cards-item/index.vue';
	import {
		vipCardList,
	} from '@/api/memberBenefits.js';
	export default {
		data() {
			return {
				tabList: [{
					id: 1,
					name: '周卡',
					value: 1,
				},{
					id: 2,
					name: '月卡',
					value: 2,
				},],
				currentTab: 1,
				cardsList: [],
				page: 1,
				lastPage: 1,
			}
		},
		components: {
			vipCardsItem,
		},
		methods: {
			changeTab(e){
				uni.pageScrollTo({
					scrollTop: 0,
					duration: 0
				});
				this.page = 1;
				this.cardsList = [];
				this.currentTab = e.id;
				this.getVipCardsList();
			},
			// 可购会员卡列表
			async getVipCardsList(){
				try{
					uni.showLoading({
						title: "加载中",
						mask: true
					});
					let info = {
						page: this.page,
						limit: 10,
						type: this.currentTab
					};
					let {code , data} = await vipCardList(info);
					uni.hideLoading();
					if(code == 0){
						this.lastPage = data.last_page;
						if(this.page == 1){
							this.cardsList = data.data;
						}else{
							this.cardsList = [...this.cardsList, ...data.data];
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
			this.getVipCardsList();
		},
		onReachBottom() {
			if(this.page < this.lastPage){
				this.page++;
				this.getVipCardsList();
			}
		}
	}
</script>

<style scoped lang="scss">
@import './index.scss';
</style>
