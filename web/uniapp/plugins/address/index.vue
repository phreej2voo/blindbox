<template>
	<page-meta :page-style="`overflow: ${showVisible ? 'hidden':''}`">
	</page-meta>
	<view class="container">
		<template v-if="addressList.length">
			<radio-group @change="setDefaultAddress">
				<view v-for="(item,index) in addressList" :key="index" @click.prevent>
					<view class="address-item" @click="userAddress(item)">
						<view class="name-phone main-start-flex">
							<text>{{item.receiver}}</text>
							{{item.phone}}
						</view>
						<view class="address-detail main-start-flex">
							{{item.province_name + ' ' + item.city_name + ' ' + item.area_name  + ' ' +  item.address}}
						</view>
						<u-line color="#F8F8F8" :hairline="false" margin="10rpx 0"></u-line>
						<view class="address-operation main-space-between">
							<view class="main-start-flex">
								<radio :value="String(index)" :checked="item.default_flag == 1" color="#82FF80"
									style="transform:scale(0.8)" />
								设为默认
							</view>
							<view class="main-start-flex">
								<view @click.stop="goEditAddress(item)">编辑</view>
								<view @click.stop="deleteAddress(item.id)">删除</view>
							</view>
						</view>
					</view>
				</view>
			</radio-group>
		</template>
		<template v-else>
			<empty :contextTag="'暂无地址~'"></empty>
		</template>
		<view class="footer main-center-flex">
			<view class="add-address main-center-flex" @click="goAddressOperation">
				<image :src="addAddress" mode="aspectFill"></image>
				<text>新建地址</text>
			</view>
		</view>
		<modal ref="modal" :showVisible.sync="showVisible"></modal>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import {
		address_list,
		set_default_address,
		delete_address,
		get_address_code
	} from '@/api/address.js';
	
	export default {
		data() {
			return {
				fromType: '',
				show: false,
				addressList: [],
				showVisible: false,
				addAddress: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
				isSelAddress: false
			}
		},
		async onLoad(options) {
			if(options.fromType){
				this.fromType = options.fromType;
			}
			if(options.isSelAddress) {
				this.isSelAddress = true;
			}
			// 获取省市区地址
			const addressCodeList = uni.getStorageSync('_ADDRESS_CODE');
			if (!addressCodeList || addressCodeList.length === 0) {
				const {code, data = []} = await get_address_code();
				if (code == 0) {
					uni.setStorageSync('_ADDRESS_CODE', data);
				}
			}
		},
		onShow() {
			this.getAddressList()
		},
		methods: {
			// 切换账号
			userAddress(el){
				if(this.fromType == 'user'){
					let pages = getCurrentPages();
					pages.forEach((item) => {
						if(item.route == 'plugins/warehouse-bag/shipments/shipments'){
							item.$vm.changeAddressList = [{...el}];
							setTimeout(() => {
								uni.navigateBack()
							},10)
						}
					})
				}
				if(this.isSelAddress) {
					const pages = getCurrentPages();
					const prePage = pages[pages.length - 2];
					prePage.$vm.selAddress = true;
					prePage.$vm.default_address = el;
					uni.navigateBack();
				}
			},
			getAddressList() {
				address_list().then(res => {
					if (res.code == 0) {
						this.addressList = res.data
						if (this.addressList.length == 0) {
							this.show = true
						} else {
							this.show = false
						}
					} else {
						this.show = true
					}
				}).catch(err => {})
			},
			goAddressOperation() {
				uni.navigateTo({
					url: '/plugins/address/address-operation/index'
				})
			},
			setDefaultAddress(e) {
				set_default_address({
					id: this.addressList[e.detail.value].id
				}).then(res => {
					if (res.code == 0) {
						this.getAddressList()
					}
					uni.$u.toast(res.msg)
				}).catch(err => {
					uni.$u.toast(err.msg)
				})
			},
			goEditAddress(item) {
				item.addressArea = [item.province_name, item.city_name, item.area_name]
				item.addressCode = [item.province_code, item.city_code, item.area_code]
				uni.navigateTo({
					url: '/plugins/address/address-operation/index?editData=' + encodeURIComponent(JSON.stringify(
						item)) + '&type=edit'
				})
			},
			deleteAddress(id) {
				this.$refs.modal.showModal({
					title: '是否删除',
					content: '将删除此地址',
					confirm: () => {
						delete_address({
							id
						}).then(res => {
							if (res.code == 0) {
								this.getAddressList()
							}
							uni.$u.toast(res.msg)
						}).catch(err => {
							uni.$u.toast(err.msg)
						})
					}
				})
			}
		}
	}

</script>

<style scoped lang="scss">
	.container {
		width: 100%;
		min-height: 100vh;
		background-color: #1D1F36;
		padding: 30rpx 30rpx calc(180rpx + env(safe-area-inset-bottom)) 30rpx;
	}

	.footer {
		width: 100%;
		height: calc(150rpx + env(safe-area-inset-bottom));
		background-color: #1D1F36;
		padding-bottom: env(safe-area-inset-bottom);
		box-shadow: 1rpx -4rpx 16rpx 0 rgba(30, 30, 30, 0.15);
		position: fixed;
		bottom: 0;
		left: 0;
		z-index: 6;
	}

	.add-address {
		width: 605rpx;
		height: 106rpx;
		font-size: 40rpx;
		font-family: BTH;
		font-weight: 400;
		position: relative;
		text-align: center;
		image{
			width: 100%;
			height: 100%;
		}
		text{
			z-index: 9;
			position: absolute;
			color: #FFFFFF;
		}
	}

	.radio-group-container {
		width: 100%;
	}

	.address-item {
		width: 100%;
		min-height: 223rpx;
		background: rgba(217,217,217,0.05);
		border-radius: 8rpx 8rpx 8rpx 8rpx;
		padding: 30rpx 30rpx 20rpx 30rpx;
		margin-bottom: 30rpx;
		color: #FFF;
	}

	.name-phone {
		width: 100%;
		height: 50rpx;
		font-size: 26rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #BABABA;
	}

	.name-phone>text {
		font-size: 30rpx;
		font-family: Source Han Sans CN;
		font-weight: bold;
		color: #FFF;
		margin-right: 10rpx;
	}

	.address-detail {
		width: 100%;
		font-size: 28rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFF;
	}

	.address-operation {
		width: 100%;
		height: 60rpx;
	}

	.address-operation>view {
		height: 100%;
	}

	.address-operation>view:first-child {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #BABABA;
	}

	.address-operation>view:last-child {
		font-size: 24rpx;
		font-family: Source Han Sans CN;
		font-weight: 400;
		color: #FFF;
	}

	.address-operation>view:last-child>view:last-child {
		margin-left: 33rpx;
	}
</style>

