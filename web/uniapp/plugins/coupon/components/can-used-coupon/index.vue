<template>
	<view class="can-be-used" :class="defaultTab == 1? '' : 'can-not-be-used'">
		<view class="image">
			<image :src="defaultTab == 1? available : notAvailable" mode="aspectFill"></image>
		</view>
		<view class="box">
			<view class="left" :class="defaultTab == 1? '' : 'unavailable'">
				<view class="discount">
					{{ discount }}
				</view>
				<view class="expired" v-if="defaultTab != 1">
					已过期
				</view>
			</view>
			<view class="center">
				<view class="coupon-info">
					<text v-if="item.couponInfo">{{ item.couponInfo.name }}</text>
					<text v-else>无</text>
				</view>
				<view class="coupon-expiration-date">
					{{ expirationDate }}
				</view>
			</view>
			<view class="rigth" v-if="defaultTab == 1" @click="changeStatus">
				<image :class="item.checked? '' : 'selected-btn'" :src="item.checked? selectedImg : ''" mode="aspectFill"></image>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	export default {
		data(){
			return {
				notAvailable: baseUrl.imgroot + 'static/images/userCenter/coupon/not-available-coupon.png',
				available: baseUrl.imgroot + 'static/images/userCenter/coupon/use-coupon.png',
				selectedImg: baseUrl.imgroot + 'static/images/userCenter/coupon/selected-btn.png',
			}
		},
		props: {
			defaultTab: {
				type: [String, Number, Boolean]
			},
			item: [Object],
			index: [Number, String],
		},
		computed: {
			discount(){
				let name;
				if(this.item.couponInfo){
					if(this.item.couponInfo.type == 1){
						name = '满减券'
					}else if(this.item.couponInfo.type == 2){
						name = '折扣券'
					}
				}
				return name
			},
			expirationDate(){
				let endTime = this.item.end_time.substring(0,10);
				let startTime = this.item.start_time.substring(0,10);
				return `${startTime} 至 ${endTime}`
			}
		},
		methods: {
			changeStatus(){
				this.$emit('selectCoupon', this.index);
			}
		}
	}
</script>

<style scoped lang="scss">
@import './index.scss';
</style>