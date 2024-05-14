<template>
	<view class="order-detail-page">
		<tabs-list :tabList="tabList" :defaultTab="currentTab"
		@changeTab="changeTab"></tabs-list>
		<!-- 列表数据 -->
		<view class="goods-list">
			<template v-if="detailList.length">
				<template v-if="currentTab == 1">
					<view v-for="(item, index) in detailList" :key="index" @click="goBagDetail(item)">
						<warehouseList :key="index" :item="item"></warehouseList>
					</view>
				</template>
				<template v-else>
					<view v-for="(item, index) in detailList" :key="index">
						<warehouseBoxList :key="index" :item="item" :defaultSecondTab="currentTab"></warehouseBoxList>
					</view>
				</template>
			</template>
			<template v-else>
				<empty></empty>
			</template>
		</view>
	</view>
</template>

<script>
	import {
		mapGetters,
		mapState
	} from 'vuex';
	import {
		get_bag_box_list
	} from '@/api/warehouse.js';
	import warehouseList from '@/components/warehouse-list/warehouse-list.vue';
	import warehouseBoxList from '@/components/warehouse-goods-list/warehouse-box-list.vue';
	export default {
		data() {
			return {
				status: null,
				show: false,
				tabList: [{
					id: 1,
					name: '未申请',
					value: 1,
				},{
					id: 2,
					name: '已提货',
					value: 2,
				},{
					id: 3,
					name: '已兑换',
					value: 3,
				}],
				currentTab: 1,
				detailList: [],
				limit: 10,
				page: 1,
				lastPage: 1,
			}
		},
		components: {
			warehouseList,
			warehouseBoxList,
		},
		onLoad(parms) {
			if (parms) {
				this.currentTab = this.status = parms.currentTab
			}
			this.getOrderList()
		},
		onReachBottom() {
			if(this.page < this.lastPage){
				this.page++;
				this.getOrderList();
			}
		},
		methods: {
			// 跳转赏袋的详情
			goBagDetail(item){
				console.log(item)
				uni.navigateTo({
					url: '/plugins/warehouse-bag/index/index?id='+ item.blindbox_id
				})
			},
			changeTab(e) {
				uni.pageScrollTo({
					scrollTop: 0,
					duration: 0
				});
				this.page = 1;
				this.detailList = [];
				this.currentTab = e.id;
				this.status = e.value;
				this.getOrderList()
			},
			// 获取数据
			async getOrderList(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let res = await get_bag_box_list({
						page: this.page,
						limit: this.limit,
						type: this.status
					});
					uni.hideLoading();
					if (res.code == 0) {
						this.lastPage = res.data.last_page;
						let data = res.data.data;
						if(this.page == 1){
							this.detailList = data;
						}else{
							this.detailList = [...this.detailList, ...data];
						}
					} else {
						this.detailList = [];
						uni.showToast({
							title: res.msg,
							icon: 'none',
							mask: true
						})
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
		}
	}

</script>

<style scoped lang="scss">
	@import "./index.scss";
</style>

