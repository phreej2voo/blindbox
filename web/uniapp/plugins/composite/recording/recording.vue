<template>
	<view class="composite-recording">
		<view class="tab-list">
			<block v-for="item in tabList" :key="item.id">
				<view @click="changeCurrentTab(item)" class="tab-item" :key="item.id" :class="currentTab == item.id? 'current' : ''">
					<text>{{ item.text }}</text>
				</view>
			</block>
		</view>
		<!-- 合成记录列表 -->
		<view class="recording-box">
			<template v-if="recodingList.length">
				<block v-for="(item, index) in recodingList" :key="index">
					<recordingItem :key="index" :item="item"></recordingItem>
				</block>
			</template>
			<template v-else>
				<empty></empty>
			</template>
		</view>
	</view>
</template>

<script>
	import recordingItem from '../components/recording-item/recording-item.vue';
	
	import {
		get_conflate_log,
	} from "@/api/composite.js";
	export default {
		data() {
			return {
				tabList: [{
					id: 0,
					text: '全部',
				},{
					id: 1,
					text: '进行中',
				},{
					id: 2,
					text: '已合成',
				}],
				currentTab: 0,
				page: 1,
				lastPage: 1,
				recodingList: [],
			}
		},
		components: {
			recordingItem,
		},
		methods: {
			// 切换currentTab
			changeCurrentTab(el){
				this.page = 1;
				this.recodingList = [];
				this.currentTab = el.id;
				this.getConflateLog();
			},
			// 合成记录
			async getConflateLog(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					})
					let res = await get_conflate_log({
						page: this.page,
						status: this.currentTab,
						limit: 10
					});
					uni.hideLoading();
					if(res.code == 0){
						this.lastPage = res.data.last_page;
						if(this.page == 1){
							this.recodingList = res.data.data;
						}else{
							this.recodingList = [...this.recodingList, ...res.data.data]
						}
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			}
		},
		onLoad() {
			this.getConflateLog()
		},
		onReachBottom() {
			if(this.page < this.lastPage){
				this.page++;
				this.getConflateLog()
			}
		}
	}
</script>

<style scoped lang="scss">
@import "./recording.scss";
</style>
