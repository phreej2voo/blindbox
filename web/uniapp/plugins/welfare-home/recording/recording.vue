<template>
	<view class="recording-page">
		<!-- <tabs-list :tabList="tabList" :defaultTab="currentTab" boxWidth="244" textWidth="236" justifyContent="space-around"
		@changeTab="changeTab"></tabs-list> -->
		<view class="recording-list">
			<template v-if="recordingLog && recordingLog.length">
				<block v-for="(item, index) in recordingLog" :key="index">
					<recordingItem :item="item" :key="index"></recordingItem>
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
		rollReward
	} from '@/api/welfare.js';
	import recordingItem from '../components/recording-item/index.vue';
	export default {
		data() {
			return {
				tabList: [{
					id: 1,
					name: '中赏结果',
					value: 1,
				},{
					id: 2,
					name: '参加记录',
					value: 2,
				}],
				currentTab: 1,
				page: 1,
				lastPage: 1,
				recordingLog: [],
			}
		},
		components: {
			recordingItem
		},
		methods: {
			changeTab(e) {
				uni.pageScrollTo({
					scrollTop: 0,
					duration: 0
				});
				this.currentTab = e.id;
				this.page = 1;
				this.recordingLog = [];
				this.getRollReward();
			},
			async getRollReward(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let { code, data } = await rollReward({
						page: this.page,
						limit: 10,
						status: this.currentTab
					});
					uni.hideLoading();
					if(code == 0){
						this.lastPage = data.last_page;
						if(this.page == 1){
							this.recordingLog = data.data;
						}else{
							this.recordingLog = [...this.recordingLog, ...data.data];
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
			this.getRollReward()
		},
		onReachBottom() {
			if(this.page < this.lastPage){
				this.page++;
				this.getRollReward()
			}
		}
	}
</script>

<style scoped lang="scss">
@import './recording.scss';
</style>
