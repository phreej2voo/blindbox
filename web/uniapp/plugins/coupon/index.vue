<template>
	<view class="coupon-page">
		<!-- tab按钮 -->
		<view class="tab-list common-style">
			<block v-for="(item,index) in tabList" :key="index">
				<view class="tab-item common-style" :class="defaultTab == item.id? 'selectedTab' : ''"
				 :key="index" @click="changeTab(item)">
					<view class="content">
						{{ item.text }}
					</view>
				</view>
			</block>
		</view>
		<!-- 优惠券列表  -->
		<view class="coupon-list">
			<template v-if="couponList.length">
				<view class="can-be-used coupon">
					<block v-for="(item, index) in couponList" :key="index">
						<canUsedCoupon :key="index" :item="item" :defaultTab="defaultTab"
						@selectCoupon="selectCoupon" :index="index"></canUsedCoupon>
					</block>
				</view>
			</template>
			<template v-else>
				<empty :contextTag="'暂无使用优惠券~'"></empty>
			</template>
		</view>
		<!-- 确认选择 -->
		<view class="confirm-selected" v-if="couponList.length && defaultTab != 2">
			<view class="box" @click="confirmBtn">
				<image :src="confirmSelectedImg" mode="aspectFill"></image>
				<view class="text">确认选择</view>
			</view>
		</view>
		
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import { getCouponList } from '@/api/coupon.js';
	import canUsedCoupon from './components/can-used-coupon/index.vue';
	
	export default {
		data() {
			return {
				confirmSelectedImg: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
				tabList: [{
					id: '1',
					text: '可使用'
				},{
					id: '2',
					text: '不可用'
				}],
				defaultTab: '1',
				page: 1, // 页数
				limit: 20, // 每页条目数
				lastPage: 1,
				couponList: [],
			}
		},
		components: {
			canUsedCoupon,
		},
		methods: {
			confirmBtn(){
				let selectedList = this.couponList.filter(item => item.checked);
				if(!selectedList.length){
					uni.showToast({
						title: '请选择优惠券',
						icon: 'none',
						mask: true
					});
					return
				}
				this.confirmUseCoupon()
			},
			// 确认使用优惠券
			confirmUseCoupon() {
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			// 优惠券选中or不选中
			selectCoupon(i){
				this.couponList = this.couponList.reduce((pre,item, index) => {
					if(index == i){
						item.checked = !item.checked;
					}
					pre.push(item);
					return pre
				}, [])
			},
			// 切换状态栏
			changeTab(e){
				this.defaultTab = e.id;
				this.page = 1;
				this.couponList = [];
				this.getCouponListAll();
			},
			// 获取数据
			async getCouponListAll() {
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let res = await getCouponList({
						page: this.page,
						limit: this.limit,
						type: this.defaultTab
					});
					if(res.code == 0) {
						let info = res.data;
						this.lastPage = info.last_page;
						if(this.page == 1){
							this.couponList = info.data.reduce((pre,item) => {
								item.checked = false;
								pre.push(item);
								return pre;
							}, []);
						}else{
							let list = info.data.reduce((pre,item) => {
								item.checked = false;
								pre.push(item);
								return pre;
							}, []);
							this.couponList = [...this.couponList, ...list];
						}
					}
					uni.hideLoading();
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
		},
		onLoad() {
			this.getCouponListAll();
		},
		onReachBottom(){
			if(this.page < this.lastPage){
				this.page++;
				this.getCouponListAll();
			}
		}
	}

</script>

<style lang="scss" scoped>
@import "./index.scss";
</style>

