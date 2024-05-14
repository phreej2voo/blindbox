<template>
	<view class="homes-history-page">
		<tabs-list :tabList="tabList" :defaultTab="currentTab" :boxWidth="158" :textWidth="150"
		@changeTab="changeTab"></tabs-list>
		<view class="homes-list">
			<template v-if="welfareLog && welfareLog.length">
				<block v-for="(item, index) in welfareLog" :key="index">
					<homesItem :item="item" :key="index"></homesItem>
				</block>
			</template>
			<template v-else>
				<empty></empty>
			</template>
		</view>
	</view>
</template>

<script>
	import {
		rollLog
	} from '@/api/welfare.js';
	import homesItem from '../components/homes-item/index.vue';
	export default {
		data() {
			return {
				tabList: [{
					id: 0,
					name: '全部',
					value: 0,
				},{
					id: 1,
					name: '进行中',
					value: 1,
				},{
					id: 2,
					name: '已结束',
					value: 2,
				},{
					id: 3,
					name: '未通过',
					value: 3,
				}],
				currentTab: 0,
				page: 1,
				lastPage: 1,
				welfareLog: [],
			}
		},
		components: {
			homesItem,
		},
		methods: {
			changeTab(e) {
				uni.pageScrollTo({
					scrollTop: 0,
					duration: 0
				});
				this.currentTab = e.id;
				this.page = 1;
				this.welfareLog = [];
				this.getRollLog()
			},
			// 参与记录
			async getRollLog(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let { code, data } = await rollLog({
						page: this.page,
						limit: 10,
						status: this.currentTab
					});
					uni.hideLoading();
					if(code == 0){
						this.lastPage = data.last_page;
						if(this.page == 1){
							this.welfareLog = data.data;
						}else{
							this.welfareLog = [...this.welfareLog, ...data.data];
						}
					}else{
						setTimeout(() => {
							uni.showToast({
								title: msg,
								icon: 'none',
								mask: true,
							});
						}, 50)
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
		},
		onLoad() {
			this.getRollLog()
		},
		onReachBottom() {
			if(this.page < this.lastPage){
				this.page++;
				this.getRollLog()
			}
		}
	}
</script>

<style scoped lang="scss">
@import './homes-history.scss';
</style>
