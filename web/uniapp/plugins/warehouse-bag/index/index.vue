<template>
	<view class="container">
		<view class="goods-title-name">
			<view class="goods-name">
				赏种系列
			</view>
			<view class="goods-total">
				共计{{ goodsTotalNum }}个赏品
			</view>
		</view>
		<!-- 赏种列表 -->
		<view class="goods-list">
			<block v-for="(item,index) in cardsList" :key="index">
				<cardsItem :key="index" :index="index" :item="item" @checkedItem="checkedItem"></cardsItem>
			</block>
		</view>
		<!-- 底部按钮 -->
		<view class="bottom-btn">
			<view class="all-select" @click="checkedAll">
				<image :src="allChecked? allSelected : ''" mode="aspectFill" :class="allChecked? '' : 'unChecked'"></image>
				<text>全选</text>
			</view>
			<view class="two-btn">
				<view class="item-btn" v-for="(item, index) in btnList" :key="index" @click="changeType(item)">
					<image :src="defaultChecked == item.id? selectedImg : ''" mode="widthFix"></image>
					<text class="text" :class="defaultChecked == item.id? '' : 'unChecked'">{{ item.text }}</text>
				</view>
			</view>
		</view>
		<!-- 分解弹窗 -->
		<view>
			<uni-popup ref="popup" background-color="#1D1F36">
				<decomposeDialog @closeDialog="closeDialog" @comfirmDecompose="comfirmDecompose"
				:decomposeTotal="decomposeTotal" :goodsTotal="goodsTotal"></decomposeDialog>
			</uni-popup>
		</view>
		<!-- 弹窗 -->
		<modal ref="modal"></modal>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import cardsItem from '../components/cards-item/index.vue';
	import decomposeDialog from '@/components/decompose-dialog/decompose-dialog.vue';
	import {
		getBagBoxDetail,
		bagBoxExchange,
	} from '@/api/warehouse.js';
	export default {
		data() {
			return {
				goodsTotalNum: 0,
				goodsTotal: 0,
				selectedImg: baseUrl.imgroot + 'static/images/wareHouse/btn-bck.png',
				allSelected: baseUrl.imgroot + 'static/images/userCenter/coupon/selected-btn.png',
				btnList: [{
					id: 0,
					text: '选择发货',
					checked: false,
				},{
					id: 1,
					text: '分解哈希币',
					checked: false,
				}],
				defaultChecked: -1,
				allChecked: false,
				cardsList: [],
				id: '',
				totalMoney: 0,
				decomposeTotal: 0,
			}
		},
		components: {
			cardsItem,
			decomposeDialog,
		},
		methods: {
			toggle(type) {
				this.type = type;
				this.$refs.popup.open(type)
			},
			// 分解
			comfirmDecompose(){
				this.$refs.modal.showModal({
					title: '是否确认分解？',
					confirm: () => {
						this.getBagBoxExchange();
					}
				})
			},
			// 盲盒兑换
			async getBagBoxExchange(){
				try{
					uni.showLoading({
						title: '分解中',
						mask: true
					});
					let goodsId = this.cardsList.reduce((pre, item) => {
						if(item.checked){
							pre.push(item.goods_id)
						};
						return pre;
					}, []);
					let res = await bagBoxExchange({
						goods_id: goodsId,
						blindbox_id: this.id
					});
					uni.hideLoading();
					if(res.code == 0){
						let pages = getCurrentPages();
						pages.forEach(item => {
							if(item.route == 'pages/warehouse/index'){
								item.$vm.defaultSecondTab = 3;
							}
						});
						uni.switchTab({
							url: '/pages/warehouse/index',
						})
					}else{
						uni.showToast({
							title: res.msg,
							icon: 'none',
							mask: true
						})
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			// 全选按钮
			checkedAll(){
				this.allChecked = !this.allChecked;
				this.cardsList = this.cardsList.reduce((pre, item) => {
					item.checked = this.allChecked;
					if(item.checked){
						item.selectedNum = item.t_total;
					}else{
						item.selectedNum = 0;
					}
					pre.push(item);
					return pre;
				}, []);
				this.judgmentAll()
			},
			// 判断是否全选
			judgmentAll(){
				let checkedList = this.cardsList.filter(item => item.checked);
				if(checkedList.length == this.cardsList.length){
					this.allChecked = true;
				}else{
					this.allChecked = false;
				}
				this.statistics()
			},
			// 统计选中的数量
			statistics(){
				let total = 0;
				this.goodsTotal = this.cardsList.reduce((pre, item) => {
					let num = Number(item.selectedNum)? Number(item.selectedNum) : 0;
					pre += num;
					total = total + num * Number(item.recovery_price) * 100;
					return pre;
				}, 0);
				this.decomposeTotal = parseInt(total) / 100;
			},
			// 切换按钮 btnList 发货 or 分解
			changeType(e) {
				if(!this.goodsTotal){
					uni.showToast({
						title: '请先选择商品~',
						icon: 'none',
						mask: true
					});
					return
				}
				this.defaultChecked = e.id;
				if(this.defaultChecked == 0){
					let params = {
						id: this.id,
						strArr: this.cardsList.filter(item => item.checked)
					}
					uni.navigateTo({
						url: '/plugins/warehouse-bag/shipments/shipments?params=' + encodeURIComponent(JSON.stringify(params))
					})
				}else if(this.defaultChecked == 1){
					this.toggle('bottom');
				}
			},
			closeDialog(){
				this.$refs.popup.close();
			},
			// 单选
			checkedItem(e, index){
				this.cardsList = this.cardsList.reduce((pre, item, i) => {
					if(i == index){
						item.checked = !item.checked;
						if(item.checked){
							item.selectedNum = item.t_total;
						}else{
							item.selectedNum = 0;
						}
					}
					pre.push(item);
					return pre;
				}, []);
				this.judgmentAll(); // 判断全选
			},
			// 未申请盲盒详情
			async getOrderDetail(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					})
					let res = await getBagBoxDetail({
						blindbox_id: this.id
					});
					uni.hideLoading();
					if(res.code == 0){
						this.cardsList = res.data.reduce((pre,item) => {
							item.checked = false;
							this.goodsTotalNum += item.t_total;
							pre.push(item);
							return pre;
						}, []);
						this.judgmentAll(); // 判断全选
					}else{
						uni.showToast({
							title: res.msg,
							mask: true,
							icon: 'none'
						});
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			}
		},
		onLoad(options) {
			if(options.id){
				this.id = options.id;
				this.getOrderDetail();
			}
		}
	}
</script>

<style scoped lang="scss">
@import "./index.scss";
</style>
