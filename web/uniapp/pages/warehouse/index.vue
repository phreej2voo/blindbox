<template>
	<view class="warehouse-page">
		<!-- 顶部tab栏 -->
		<view class="tap-list">
			<block v-for="(item, index) in tapList" :key="index">
				<view class="tap-item" :key="index" @click="changeTab(item)">
					<image :src="defaultTab == item.id? item.slectedImage : item.unslectedIimage" mode="aspectFill">
					</image>
					<view class="tab-name" :class="defaultTab == item.id? 'selectedItem' : ''">{{ item.name }}</view>
				</view>
			</block>
		</view>
		<!-- 二级tab -->
		<view class="second-tab-list">
			<block v-for="(item, index) in secondTabList" :key="index">
				<view class="second-tab-item" :key="index" @click="changeSecondTab(item)"
					:class="defaultSecondTab == item.id? 'selected-second-item' : ''">
					<text>{{ item.name }}</text>
				</view>
			</block>
		</view>
		<!-- 列表 -->
		<view class="warehouse-content">
			<template v-if="detailList.length">
				<!-- 盒子 -->
				<template v-if="defaultTab == 0">
					<template v-if="defaultSecondTab == 1">
						<view v-for="(item, index) in detailList" :key="index" @click="goBagDetail(item)">
							<warehouseList :key="index" :item="item"></warehouseList>
						</view>
					</template>
					<template v-else>
						<view v-for="(item, index) in detailList" :key="index">
							<warehouseBoxList :key="index" @getList="getList" :item="item"
								:defaultSecondTab="defaultSecondTab"></warehouseBoxList>
						</view>
					</template>
				</template>
				<!-- 商品 -->
				<template v-else>
					<view v-for="(item, index) in detailList" :key="index">
						<warehouseGoodsList @getList="getList" :key="index" :item="item"></warehouseGoodsList>
					</view>
				</template>
			</template>
			<template v-else>
				<empty></empty>
			</template>
		</view>
		<!-- 去合成 -->
		<view class="composite-box">
			<view class="composite-btn" @click="goAdvance">
				<image :src="compositeButton" mode="aspectFill"></image>
				<text>合成</text>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import warehouseList from '@/components/warehouse-list/warehouse-list.vue';
	import warehouseGoodsList from '@/components/warehouse-goods-list/warehouse-goods-list.vue';
	import warehouseBoxList from '@/components/warehouse-goods-list/warehouse-box-list.vue';

	import {
		get_bag_box_list,
		get_bag_goods_list,
		bagBoxExchange,
		bagGoodsConfirm
	} from '@/api/warehouse.js';

	export default {
		data() {
			return {
				tapList: [{
					id: 0,
					name: '盒子',
					slectedImage: baseUrl.imgroot + 'static/images/wareHouse/selected-btn.png',
					unslectedIimage: baseUrl.imgroot + 'static/images/wareHouse/unselected-btn.png',
				}, {
					id: 1,
					name: '商品',
					slectedImage: baseUrl.imgroot + 'static/images/wareHouse/selected-btn.png',
					unslectedIimage: baseUrl.imgroot + 'static/images/wareHouse/unselected-btn.png',
				}],
				defaultTab: 0,
				defaultSecondTab: 1,
				secondTabList: [{
					id: 1,
					name: '未申请'
				}, {
					id: 2,
					name: '已提货'
				}, {
					id: 3,
					name: '已兑换'
				}],
				page: 1,
				limit: 10,
				detailList: [],
				lastPage: 1,
				isUserLogin: '',
				compositeButton: baseUrl.imgroot + 'static/images/composite/compositeButtom2.png',
			}
		},
		components: {
			warehouseList,
			warehouseGoodsList,
			warehouseBoxList,
		},
		methods: {
			// 去合成
			goAdvance(){
				uni.navigateTo({
					url: '/plugins/composite/index/index'
				})
			},
			// 跳转赏袋的详情
			goBagDetail(item) {
				uni.navigateTo({
					url: '/plugins/warehouse-bag/index/index?id=' + item.blindbox_id
				})
			},
			// 切换顶部tab栏
			changeTab(e) {
				uni.pageScrollTo({
					scrollTop: 0,
					duration: 0
				});
				if (e.id == 0) {
					this.secondTabList = [{
						id: 1,
						name: '未申请'
					}, {
						id: 2,
						name: '已提货'
					}, {
						id: 3,
						name: '已兑换'
					}]
				} else if (e.id == 1) {
					this.secondTabList = [{
						id: 2,
						name: '待发货'
					}, {
						id: 3,
						name: '待收货'
					}, {
						id: 5,
						name: '已完成'
					}, {
						id: 0,
						name: '全部'
					}]
				}
				this.page = 1;
				this.defaultTab = e.id;
				this.defaultSecondTab = this.secondTabList[0].id;
				this.detailList = [];
				this.getList();
			},
			// 切换二级tab栏
			changeSecondTab(e) {
				uni.pageScrollTo({
					scrollTop: 0,
					duration: 0
				});
				this.page = 1;
				this.defaultSecondTab = e.id;
				this.detailList = [];
				this.getList();
			},
			// 获取列表数据
			async getList(newPage) {
				try {
					uni.showLoading({
						title: '加载中',
						mask: true,
					});
					if (newPage) {
						this.page = newPage;
					}
					let info = {
						page: this.page,
						limit: this.limit,
					}
					let getListMrthod = this.defaultTab == 0 ? get_bag_box_list : get_bag_goods_list;
					if (this.defaultTab == 0) {
						info.type = this.defaultSecondTab;
					} else if (this.defaultTab == 1) {
						info.status = this.defaultSecondTab;
					}
					let res = await getListMrthod(info);
					uni.hideLoading();
					if (res.code == 0) {
						this.lastPage = res.data.last_page;
						let data = res.data.data;
						if (this.page == 1) {
							this.detailList = data;
						} else {
							this.detailList = [...this.detailList, ...data];
						}
					} else {
						this.detailList = [];
						// uni.showToast({
						// 	title: res.msg,
						// 	icon: 'none'
						// })
					}
				} catch (e) {
					uni.hideLoading();
					//TODO handle the exception
				}
			},
		},
		onLoad(options) {
			if (options.fromType) {
				if (options.fromType == 'goods') {
					this.changeTab(this.tapList[1]);
				}
			}
		},
		onShow() {
			this.getList();
		},
		onReachBottom() {
			if (this.page < this.lastPage) {
				this.page++;
				this.getList();
			}
		},
		onHide() {
			this.page = 1;
			this.detailList = [];
		},
		onUnload() {
			this.page = 1;
			this.detailList = [];
		}
	}
</script>

<style lang="scss" scoped>
	@import './warehouse.scss';
</style>