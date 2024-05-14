<template>
	<view class="container">
		<u--form labelPosition="left" :labelStyle="labelStyle" :model="addressInfo" :rules="rules" ref="form">
			<!-- 收货人 手机号 -->
			<view class="common-box">
				<u-form-item label="收货人:" prop="receiver" borderBottom labelWidth="200rpx" >
					<u-input v-model="addressInfo.receiver" border="none" placeholder="请填写收货人姓名" fontSize="28rpx"
						:color="addressInfo.receiver ? '#FFF' :'#b0b0b0'" inputAlign="right" maxlength="8"></u-input>
				</u-form-item>
				<u-form-item label="手机号码:" prop="phone" labelWidth="200rpx">
					<u--input v-model="addressInfo.phone" border="none" placeholder="请填写手机号码" fontSize="28rpx"
						:color="addressInfo.phone ? '#FFF' :'#b0b0b0'" inputAlign="right"></u--input>
				</u-form-item>
			</view>
			<!-- 地址 -->
			<view class="common-box">
				<u-form-item label="所在地区:" prop="addressArea" borderBottom labelWidth="200rpx">
					<view class="picker-container main-end-flex">
						<pickers @address="selectAddress" @close="closeSelectAddress"
							:defaultAddress="addressInfo.addressArea">
							<view class="region-select">
								<view class="region-unselect" style="color: #b0b0b0;"
								v-if="addressInfo.addressArea.length == 0">
								省/市/区
								<image :src="addressIcon" mode="aspectFill"></image>
								</view>
								<view class="" v-else style="color: #FFF;">
									<text v-for="(item,index) in addressInfo.addressArea" :key="index"
										style="margin-left: 20rpx;">{{item}}</text>
								</view>
							</view>
						</pickers>
					</view>
				</u-form-item>
				<u-form-item label="详细地址:" prop="address" labelPosition="top" labelWidth="200rpx">
					<view class="address-detail">
						<view class="address-detail-title" style="color: #b0b0b0;">
							如道路、门牌号、小区等
						</view>
						<u--input v-model="addressInfo.address" :customStyle="addressDetailStyle" border="none"
							placeholder="请填写详细地址" fontSize="28rpx" :color="addressInfo.address ? '#FFF' :'#b0b0b0'"
							inputAlign="right"></u--input>
					</view>
				</u-form-item>
			</view>
			<!-- 标签 -->
			<view class="common-box">
				<!-- <u-form-item label="标签:" borderBottom labelWidth="200rpx" >
					<view class="tab-list">
						<view class="item" :class="defaultTab == item.id? 'selectedItem' : ''"
						v-for="(item, index) in tabList" :key="index" @click="changeTab(item)">
							{{ item.text }}
						</view>
					</view>
				</u-form-item> -->
				<u-form-item label="设为默认收获地址:" prop="default_flag" labelWidth="530rpx">
					<u-switch v-model="addressInfo.default_flag" :activeValue="Number(1)" :inactiveValue="Number(2)"
						@change="setDefault" activeColor="#82FF80" size="20"></u-switch>
				</u-form-item>
			</view>
		</u--form>
		<!-- 保存地址 -->
		<view class="address-box">
			<view class="save-address main-center-flex" @click="saveAddress">
				<image :src="saveAddressImg" mode="aspectFill"></image>
				<text>保存地址</text>
			</view>
		</view>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import {
		add_address,
		edit_address
	} from '@/api/address.js'
	import Pickers from "@/components/ming-picker/ming-picker.vue"
	export default {
		data() {
			return {
				saveAddressImg: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
				addressIcon: baseUrl.imgroot + 'static/images/userCenter/coupon/address-icon.png',
				type: '',
				tabList: [{
					id: 1,
					text: '家',
				},{
					id: 2,
					text: '公司',
				},{
					id: 3,
					text: '学校',
				}],
				defaultTab: 1,
				addressInfo: {
					receiver: '',
					phone: '',
					addressArea: [],
					address: '',
					default_flag: 2,
					// default_tab: 1, // 新增标签
				},
				addressCode: [],
				rules: {
					receiver: {
						type: 'string',
						required: true,
						message: '请填写收货人姓名',
						trigger: ['blur', 'change']
					},
					phone: [{
							type: 'number',
							required: true,
							message: '请填写手机号',
							trigger: ['blur', 'change']
						},
						{
							// 此为同步验证，可以直接返回true或者false，如果是异步验证，稍微不同，见下方说明
							validator: (rule, value, callback) => {
								// 调用uView自带的js验证规则，详见：https://www.uviewui.com/js/test.html
								return uni.$u.test.mobile(value);
							},
							message: "请填写正确的手机号",
							// 触发器可以同时用blur和change，二者之间用英文逗号隔开
							trigger: ["change", "blur"],
						}
					],
					addressArea: [{
							type: 'array',
							required: true,
							message: '请选择地区',
							trigger: ['change']
						},
						{
							// 此为同步验证，可以直接返回true或者false，如果是异步验证，稍微不同，见下方说明
							validator: (rule, value, callback) => {
								// 调用uView自带的js验证规则，详见：https://www.uviewui.com/js/test.html
								return this.addressInfo.addressArea.length > 0;
							},
							message: "请选择地区",
							trigger: ["change"],
						},
					],
					address: {
						type: 'string',
						required: true,
						message: '请填写详细地址',
						trigger: ['blur', 'change']
					},
				},
				labelStyle: {
					fontSize: '28rpx',
					color: '#FFF'
				},
				addressDetailStyle: {
					marginTop: '30rpx'
				},
				isvalid: false
			}
		},
		components: {
			Pickers
		},
		watch: {
			addressInfo: {
				handler(newVal, oldVal) {
					if (newVal.receiver != '' && uni.$u.test.mobile(newVal.phone) &&
					newVal.addressArea != [] && newVal.address != '') {
						this.isvalid = true
					}
				},
				deep: true
			}
		},
		onReady() {
			//如果需要兼容微信小程序，并且校验规则中含有方法等，只能通过setRules方法设置规则。
			this.$refs.form.setRules(this.rules)
		},
		onLoad(parms) {
			this.type = parms.type
			if (parms.editData) {
				const editData = JSON.parse(decodeURIComponent(parms.editData));
				this.addressInfo = Object.assign({}, editData)
				this.addressCode = editData.addressCode
			}
		},
		methods: {
			// 标签家 学校 公司 切换
			changeTab(e){
				this.defaultTab = e.id;
				this.addressInfo.default_tab = e.id;
			},
			selectAddress(e) {
				this.addressInfo.addressArea = e.value
				this.addressCode = e.code
				this.$forceUpdate()
			},
			closeSelectAddress() {},
			setDefault(e) {
				this.addressInfo.default_flag = e
			},
			saveAddress() {
				if (!this.isvalid){
					uni.showToast({
						title: '我的收货地址请填写完整',
						icon: 'none',
						mask: true
					})
					return
				}
				this.$refs.form.validate().then(res => {
					let [province_code, city_code, area_code] = this.addressCode
					let {
						receiver,
						phone,
						address,
						default_flag: is_default
					} = this.addressInfo
					let data = {
						province_code,
						city_code,
						area_code,
						receiver,
						phone,
						address,
						is_default
					}
					if (this.type == 'edit') {
						data.id = this.addressInfo.id
						edit_address(data).then(res => {
							uni.$u.toast(res.msg)
							if (res.code == 0) {
								this.$refs.form.resetFields()
								this.$refs.form.clearValidate()
								setTimeout(() => {
									uni.navigateBack({
										delta: 1
									})
								}, 800)
							}
						}).catch(err => {
							uni.$u.toast(err)
						})
					} else {
						add_address(data).then(res => {
							uni.$u.toast(res.msg)
							if (res.code == 0) {
								this.$refs.form.resetFields()
								this.$refs.form.clearValidate()
								setTimeout(() => {
									uni.navigateBack({
										delta: 1
									})
								}, 10)

							}
						}).catch(err => {
							uni.$u.toast(err)
						})
					}
				}).catch(errors => {
					uni.$u.toast(errors)
				})
			},
		}
	}

</script>

<style scoped lang="scss">
@import './index.scss';
</style>

