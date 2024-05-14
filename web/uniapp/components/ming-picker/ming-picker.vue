<template>
	<view>
		<picker class="address-picker" mode="multiSelector" :range="[province, city, area]" :value="value"
			@change="changeHandler" @cancel="close" @columnchange="columnchange">
			<slot></slot>
		</picker>
	</view>
</template>

<script>
	import {
		get_address_code
	} from '@/api/address.js';
	import addressDatajs from '@/utils/area.js';
	// const addressData = uni.getStorageSync('_ADDRESS_CODE');//addressDatajs; 
	export default {
		props: {
			defaultAddress: {
				type: Array,
				default: () => []
			}
		},
		data() {
			return {
				province: [],
				city: [],
				area: [],
				value: [0, 0, 0],
				addressData:[],
			};
		},
		created() {
			this.onAttached();
		},
		methods: {
			async onAttached() {
				
				this.addressData = uni.getStorageSync('_ADDRESS_CODE');//addressDatajs;
				if (!this.addressData || this.addressData.length === 0) {
					const {code, data = []} = await get_address_code();
					if (code == 0) {
						uni.setStorageSync('_ADDRESS_CODE', data);
						this.addressData =data;
					}
				}
				this.province= this.addressData.map(it => it.label)
				this.city=this.addressData[0].children.map(it => it.label)
				this.area= this.addressData[0].children[0].children.map(it => it.label)
				const address = this.defaultAddress;
				if (address[0]) {
					// 如果有初始值，则需要初始地址
					const filter = (index) => (index > -1 ? index : 0);
					const currentProvince = filter(this.addressData.findIndex(it => it.label === address[0]));
					const currentCity = filter(this.addressData[currentProvince].children.findIndex(it => it.label === address[
						1]));
					const currentArea = filter(this.addressData[currentProvince].children[currentCity].children.findIndex(it =>
						it.label === address[2]));
					const city = this.addressData[currentProvince].children;
					const area = this.addressData[currentProvince].children[currentCity].children;
					this.value = [currentProvince, currentCity, currentArea];
					this.city = city.map(it => it.label);
					this.area = area.map(it => it.label);
					this.address = [this.addressData[currentProvince].label, city[currentCity].label, area[currentArea].label];
				}
			},
			changeHandler() {
				let value = this.getAddress(...this.value),
					code = this.getCode(...this.value)
				this.$emit("address", {
					value,
					code,
					data: {
						[code[0]]: value[0],
						[code[1]]: value[1],
						[code[2]]: value[2],
					}
				})
			},
			close() {
				this.$emit("close", {})
			},
			getAddress(p, c, a) {
				const {
					province,
					city = [],
					area = []
				} = this;
				return [province[p], city[c] || '', area[a] || ''];
			},
			getCode(p, c, a) {
				let province = this.addressData.map(it => it.value),
					city = this.addressData[p].children.map(it => it.value),
					area = this.addressData[p].children[c].children.map(it => it.value)
				return [province[p], city[c] || 0, area[a] || 0];
			},
			columnchange(e) {
				// wx.showLoading({ mask: true });
				const {
					column,
					value: index
				} = e.detail;
				if (column === 0) {
					// 省份变了
					this.city = this.addressData[index].children.map(it => it.label);
					this.area = this.addressData[index].children[0].children.map(it => it.label);
					this.value = [index, 0, 0];
				} else if (column === 1) {
					// 城市变了
					const currentProvince = this.value[0];
					this.area = this.addressData[currentProvince].children[index].children.map(it => it.label);
					this.value = [currentProvince, index, 0];
				} else {
					const value = this.value;
					value[2] = index;
					this.value = value;
				}
			}
		}
	}
</script>

<style scoped>
</style>