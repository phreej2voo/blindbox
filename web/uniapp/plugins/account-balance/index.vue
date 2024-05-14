<template>
	<app-layout>
		<view class="container">
			<view class="head" :style="{'background-image':'url('+bgUrl+')'}">
				<view class="head-title">
					<view class="banlance">
						账户余额（元）
					</view>
					<view class="recharge" @click="recharge">
						<image :src="rechargeBtn" mode="aspectFill"></image>
						<text>充值</text>
					</view>
				</view>
				<view class="head-content">
					<view class="banlan-num">
						{{banlan_money}}
					</view>
				</view>
				<view class="head-title">
					<view class="banlance total-rech">
						<view class="">
							累计充值（元 ）
						</view>
						<view class="banlan-num">
							{{total_money}}
						</view>
					</view>
					<view class="banlance total-rech">
						<view class="">
							累计消费(元)
						</view>
						<view class="banlan-num">
							{{consume}}
						</view>
					</view>
				</view>
			</view>
			<view class="history common">
				<view class="his-log">
					历史记录
				</view>
				<view class="common date" @click="show =true">
					<text>{{date_times}}</text>
					<u-datetime-picker :show="show" v-model="date_time" mode="year-month" @confirm="confirm"
						@cancel="show=false"></u-datetime-picker>
					<u-icon name="arrow-right"></u-icon>
				</view>
			</view>
			<view class="tab">
				<view class="tab-list">
					<view class="tab-all com" v-for="(item,index) in list" :key="index"
						:class="{activeAll:item.isSelect}" @click="tabChange(item.val,index)">
						{{item.name}}
					</view>
				</view>
			</view>
			<view class="list">
				<view class="list-item" v-for="(item,index) in banlance_list" :key="index">
					<view class="list-item common">
						<view class="item-left">
							<view class="opera">
								{{item.username}}
							</view>
							<view class="time">
								{{item.create_time}}
							</view>
						</view>
						<view class="item-right">
							<text class="del-num" v-if="item.balance.indexOf('-')!=-1">{{item.balance}}</text>
							<text class="add-num" v-if="item.balance.indexOf('-')==-1">+{{item.balance}}</text>
						</view>
					</view>
					<u-line color="#D8D8D8"></u-line>
				</view>
			</view>
		</view>
	</app-layout>
</template>

<script>
	import { balance_info, balance_list } from '@/api/my.js'
	import baseUrl from '@/utils/siteInfo.js';
	export default {
		data() {
			return {
				bgUrl: baseUrl.imgroot + '/static/images/userCenter/balance/balance-pic.png',
				rechargeBtn: baseUrl.imgroot + '/static/images/userCenter/balance/recharge-btn.png',
				isSelectAll: true,
				date_time: Number(new Date()),
				date_times: '',
				show: false,
				mode: 'single',
				banlan_money: 0,
				total_money: 0,
				consume: 0,
				list: [{
					name: '全部',
					val: '0',
					isSelect: true
				}, {
					name: '充值',
					val: '2',
					isSelect: false
				}, {
					name: '消费',
					val: '1',
					isSelect: false
				}],
				banlance_list: [],
				args: false,
				load: false,
				page: 1,
				type: 0,
			}
		},
		onShow() {
			this.getCurrentTime()
			this.initData()
		},
		onReachBottom: function() {
			const self = this;
			if (self.args || self.load)
				return;
			self.load = true;
			let page = self.page + 1;
			balance_list({
				limit: '10',
				page: page,
				type: self.type,
				month: this.date_times
			}).then(res => {
				if (res.code === 0) {
					[self.page, self.args, self.banlance_list] = [page, res.data.rows.length === 0, self
						.banlance_list.concat(
							res.data.rows)
					];
				}
				self.load = false;
			});
		},
		methods: {
			getCurrentTime() {
				let date = new Date()
				let year = date.getFullYear()
				let month = date.getMonth() + 1
				this.date_times = year + '-' + (month < 10 ? '0' + month : month)
			},
			confirm(e) {
				this.date_times = uni.$u.timeFormat(e.value, 'yyyy-mm')
				this.show = false
				this.getList()
			},
			tabChange(val, index) {
				this.page = 1
				this.banlance_list = []
				this.args = false
				this.load = false
				for (var i = 0; i < this.list.length; i++) {
					this.list[i].isSelect = false
					if (i == index) {
						this.list[i].isSelect = true
					}
				}
				this.type = val
				this.getList()
			},
			getList() {
				this.banlance_list = []
				this.page = 1
				balance_list({
					limit: '10',
					page: this.page,
					type: this.type,
					month: this.date_times
				}).then(res => {
					if (res.code == 0) {
						this.banlance_list = res.data.rows
					}
				})
			},
			recharge() {
				uni.navigateTo({
					url: '/plugins/account-rech/index?banlan_money=' + this.banlan_money
				})
			},
			initData() {
				balance_info().then(res => {
					if (res.code == 0) {
						this.banlan_money = res.data.balance
						this.total_money = res.data.totalRecharge
						this.consume = Math.abs(res.data.totalSpend)
					}
				})
				this.getList()
			}
		}
	}

</script>

<style scoped lang="scss">
	.container {
		width: 100%;
		min-height: 100vh;
		padding: 30rpx;
		background-color: #1D1F36;
		color: #FFF;
	}

	.head {
		width: 682rpx;
		height: 356rpx;
		padding: 40rpx 30rpx 30rpx 54rpx;
		border-radius: 20rpx;
		background-repeat: no-repeat;
		background-size: 100% 100%;
		font-size: 28rpx;
		font-family: PingFang SC-Medium, PingFang SC;
		font-weight: 500;
		color: #C5FFE4;
	}

	.head-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.head-left {
		color: #fff;
		width: 456rpx;
	}

	.banlance {
		font-size: 24rpx;
	}

	.banlan-num {
		margin-top: 6rpx;
		font-size: 44rpx;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
		text-shadow: 0rpx 2rpx 16rpx #49FFAF;
	}

	.total-rech {
		margin-top: 24rpx;
	}

	.all-con {
		position: absolute;
		/* #ifdef MP-WEIXIN */
		top: 72rpx;
		/* #endif */
		/* #ifdef APP-PLUS */
		top: 50rpx;
		/* #endif */
		left: -98rpx;

	}

	.recharge {
		width: 203rpx;
		height: 70rpx;
		font-size: 28rpx;
		font-family: PingFang SC-Medium, PingFang SC;
		font-weight: 500;
		color: #59FFB0;
		position: relative;
		image{
			width: 100%;
			height: 100%;
		}
		text{
			display: inline-block;
			width: 100%;
			height: 100%;
			text-align: center;
			line-height: 70rpx;
			position: absolute;
			z-index: 9;
			top: 0;
			left: 0;
		}
	}



	.total-con {
		margin-top: 92rpx;
	}

	.total-cons {
		margin-top: 110rpx;
	}

	.history {
		margin-top: 48rpx;
		padding: 0 24rpx;
	}

	.his-log {
		font-size: 32rpx;
	}

	.date {
		font-size: 28rpx;
	}

	.common {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.head-right {
		color: #fff;
		position: relative;
		right: 14rpx;
		width: 234rpx;
	}

	.tab {
		margin-top: 24rpx;
	}

	.tab-list {
		display: flex;
		margin: 0 auto;
		width: 660rpx;
		height: 68rpx;
		background: #1D1F36;
		border: 2rpx solid #59FFB0;
		font-size: 28rpx;
		font-family: PingFang SC-Medium, PingFang SC;
		font-weight: 500;
		color: rgba(197,255,228,0.47);
	}

	.com {
		width: 220rpx;
		height: 68rpx;
		text-align: center;
		line-height: 68rpx;
	}

	.activeAll {
		background: rgba(89,255,176,0.28);
		color: #39FFA4;
	}


	.list-item {
		width: 656rpx;
		margin: 0 auto;
		height: 150rpx;
	}

	.opera {
		font-size: 28rpx;
		height: 60rpx;
		line-height: 60rpx;
	}

	.time {
		font-size: 24rpx;
	}

	.item-right {
		font-size: 32rpx;
	}

	.del-num {
		color: #22AC38;
	}

	.add-num {
		color: #E60012;
	}

</style>

