<template>
	<view class="created-home-page">
		<view class="reward-condition">
			开赏时房间人数需<text class="green">大于9人</text>，否则活动无效，赏品自动退回
		</view>
		<!-- 表单 -->
		<view class="created-box">
			<view class="vali-form-data">
				<view class="label">创建人:</view>
				<view class="input-content">
					<view class="welfare-img">
						<image :src="avatar" mode="aspectFill"></image>
					</view>
					<input class="founder" type="text" v-model="founder" disabled='true'>
				</view>
			</view>
			<view class="vali-form-data">
				<view class="label">房间名:</view>
				<view class="input-content">
					<input type="text" maxlength="15" placeholder-class="placeholder" v-model="valiFormData.title" placeholder="输入房间名，15字以内">
				</view>
			</view>
			<view class="vali-form-data">
				<view class="label">活动介绍:</view>
				<view class="input-content">
					<textarea maxlength="50" placeholder-class="placeholder" v-model="valiFormData.desc" placeholder="输入活动介绍，最多50个汉字"/>
				</view>
			</view>
			<view class="vali-form-data">
				<view class="label">进房口令:</view>
				<view class="input-content">
					<input type="text" maxlength="15" placeholder-class="placeholder" v-model="valiFormData.password" placeholder="搜索该口令进入房间，15字以内">
				</view>
			</view>
			<view class="vali-form-data">
				<view class="label">开奖时间:</view>
				<view class="input-content">
					<uni-datetime-picker type="datetime" placeholder-class="placeholder" v-model="valiFormData.reward_time" @change="changeLog" :border="false" />
				</view>
			</view>
			<view class="vali-form-data" style="border-bottom: none; align-items: flex-start;">
				<view class="label goods-label">商品:</view>
				<view class="input-content" style="flex-wrap: wrap;">
					<view class="add-goods" @click="addGoods">
						<uni-icons type="plusempty" size="30" color="#FFF"></uni-icons>
						<text>点击选择商品</text>
					</view>
					<view class="goods-list" v-if="prepareGoodsList.length">
						<view class="goods-item" v-for="(el, i) in prepareGoodsList" :key="i">
							<view class="icons" @click="clearStatus(el.goods_id, 'decrease')">
								<uni-icons type="minus-filled" size="26" color="#82FF80"></uni-icons>
							</view>
							<view class="goods-img">
								<image :src="el.goods_image" mode="aspectFill" class="main"></image>
								<text :class="el.selectedNum > 9? 'text1': 'text2'">x{{ el.selectedNum }}</text>
							</view>
							<view class="goods-name">
								{{ el.goods_name }}
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 确认创建 -->
		<view class="created-fixed">
			<view class="created-btn" @click="confirmCreated">
				<image :src="confirmSelectedImg" mode="aspectFill"></image>
				<text>确认创建</text>
			</view>
		</view>
		
		<popAddGoods ref="popGoods" @checkedItem="checkedItem" @changeModelNum="changeModelNum"
		@clearStatus="clearStatus" :ownMaterials="ownMaterials" @makeSure="makeSure"
		:totalSlectedNum="totalSlectedNum" :totalSlectedPrice="totalSlectedPrice"></popAddGoods>
		<!-- 数量选择 -->
		<popupContent ref="popupContent" :modelNum="modelNum" :maxNum="maxNum"
		@confirmModelNum="confirmModelNum"></popupContent>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	import {
		get_user_info
	} from '@/api/my.js';
	import {
		rollGoods,
		rollCreate,
	} from '@/api/welfare.js';
	import popAddGoods from '../components/pop-add-goods/index.vue';
	import popupContent from '@/components/popup-content/popup-content.vue';
	export default {
		data() {
			return {
				// 表单数据
				founder: '',
				valiFormData: {
					title: '',
					desc: '',
					password: '',
					reward_time: '',
					award: [],
				},
				prepareGoodsList: [],
				avatar: '',
				confirmSelectedImg: baseUrl.imgroot + 'static/images/userCenter/coupon/confirm-selected-btn.png',
				ownMaterials: [],
				copyOwnMaterials: [],
				totalSlectedNum: 0,
				totalSlectedPrice: 0,
				modelNum: 0,
				maxNum: 0,
				currentId: '',
			}
		},
		components: {
			popAddGoods,
			popupContent
		},
		methods: {
			changeLog(e) {
				console.log('change事件:', e);
			},
			// 确认创建
			confirmCreated(){
				this.valiFormData.award = this.copyOwnMaterials.reduce((pre, item) => {
					if(item.checked){
						pre.push({
							goods_id: item.goods_id,
							num: item.selectedNum
						})
					}
					return pre;
				}, [])
				let finalData = this.valiFormData;
				let msg = '';
				if(!finalData.title){
					msg = '房间名';
				}else if(!finalData.desc){
					msg = '活动介绍';
				}else if(!finalData.password){
					msg = '进房口令';
				}else if(!finalData.reward_time){
					msg = '开奖时间';
				}else if(!finalData.award.length){
					msg = '商品';
				};
				if(msg){
					uni.showToast({
						title: '请设置' + msg,
						icon: 'none',
						mask: true,
					});
					return
				}
				this.postRollCreate(finalData);
			},
			async postRollCreate(info){
				try{
					uni.showLoading({
						title: '创建中',
						mask: true
					});
					let res = await rollCreate(info);
					uni.hideLoading();
					if(res.code == 0){
						uni.switchTab({
							url: '/pages/welfare/index'
						});
					}else{
						setTimeout(() => {
							uni.showToast({
								title: res.msg,
								icon: 'none',
								mask: true
							})
						}, 50)
					}
				}catch(e){
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			// 弹窗确认选中
			makeSure(){
				this.copyOwnMaterials =  JSON.parse(JSON.stringify(this.ownMaterials));
				this.prepareGoodsList = this.copyOwnMaterials.reduce((pre, item) => {
					if(item.checked){
						pre.push(item)
					}
					return pre;
				}, [])
			},
			// 添加商品
			addGoods(){
				this.ownMaterials = JSON.parse(JSON.stringify(this.copyOwnMaterials));
				this.getAllChecked();
				this.$refs.popGoods.open();
			},
			// 用户信息
			async getUserInfo() {
				try {
					const {
						code,
						data
					} = await get_user_info();
					if (code == 0) {
						this.founder = data.nickname;
						this.avatar = data.avatar;
					}
				} catch (e) {
					uni.hideLoading();
					//TODO handle the exception
				}
			},
			// 清除选中的状态
			clearStatus(id, type){
				this.ownMaterials = this.ownMaterials.reduce((pre,item) => {
					if(item.goods_id == id){
						item.checked = false;
						item.selectedNum = 0;
					}
					pre.push(item);
					return pre
				}, []);
				if(type == 'decrease'){
					this.makeSure()
				}
			},
			confirmModelNum(num){
				this.modelNum = Number(num);
				this.ownMaterials = this.ownMaterials.reduce((pre,item) => {
					if(item.goods_id == this.currentId){
						item.checked = true;
						item.selectedNum = this.modelNum;
					}
					pre.push(item);
					return pre
				}, []);
			},
			// 单个材料数量弹窗的最大件数
			changeModelNum(num, id){
				this.maxNum = Number(num);
				this.modelNum = Number(num);
				this.currentId = id;
				if(num == 1){
					this.confirmModelNum(num)
				}else{
					this.openSelected();
				}
			},
			//开启数量选择
			openSelected() {
				this.$refs.popupContent.openBtn()
			},
			// 统计选中的数量+价值
			getAllChecked(){
				this.totalSlectedNum = 0;
				this.totalSlectedPrice = 0;
				let currentPrice = 0;
				this.ownMaterials.forEach((item) => {
					if(item.checked){
						this.totalSlectedNum += Number(item.selectedNum);
						let itemPrice = Number(item.selectedNum) * Number(item.recovery_price) * 100
						currentPrice += parseInt(itemPrice);
					}
				})
				this.totalSlectedPrice = currentPrice / 100;
			},
			// 获取我的奖品
			async getRollGoods(){
				try{
					let { code, data} = await rollGoods();
					if(code == 0){
						this.ownMaterials = data.reduce((pre, item) => {
							item.checked = false;
							item.selectedNum = 0;
							pre.push(item);
							return pre
						}, []);
						this.copyOwnMaterials = JSON.parse(JSON.stringify(this.ownMaterials))
					}
				}catch(e){
					//TODO handle the exception
				}
			}
		},
		onLoad() {
			this.getUserInfo();
			this.getRollGoods();
		}
	}
</script>

<style scoped lang="scss">
@import './created-home.scss';
.uni-datetime-picker{
	
}
</style>
