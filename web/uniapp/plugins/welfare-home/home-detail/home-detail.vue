<template>
	<view class="home-detail-page">
		<view class="deyail-pic">
			<image :src="benefitPic" mode="widthFix"></image>
		</view>
		
		<template v-if="welfareInfo">
			<view class="activity-box">
				<image :src="activityImg" mode="widthFix"></image>
				<view class="activity-info">
					<view class="activity-title">
						<view class="welfare-img">
							<image :src="welfareInfo.avatar" mode="aspectFill"></image>
						</view>
						<view class="welfare-name">
							{{ welfareInfo.title }}
						</view>
						<view class="reward-time">
							{{ rewardTime }} 开赏
						</view>
					</view>
					<view class="discription">
						{{ welfareInfo.desc }}
					</view>
				</view>
			</view>
			<!-- 商品池 -->
			<view class="all-goods">
				<view class="tip-name">
					<image :src="allgoodsTip" mode="widthFix" class="allgoodsImg"></image>
					<text>商品池</text>
				</view>
				<view class="goods-list">
					<view class="goods-item-box" v-for="(item ,index) in goodsList" :key="index">
						<image :src="goodsItemBox" mode="aspectFill" class="box-img"></image>
						<view class="goods-item-info">
							<view class="goods-name">
								{{ item.goods_name}}
							</view>
							<view class="goods-img">
								<image :src="item.image" mode="aspectFill"></image>
							</view>
							<view class="good-price">
								<text>{{ item.recovery_price }}</text>哈希币
							</view>
						</view>
					</view>
				</view>
				<!-- 全部商品 -->
				<view class="total-value">
					<view class="">
						共<text style="color: #82FF80;">{{ goodsList.length }}</text>个<br/>
						价值<text style="color: #82FF80;">{{ allGoodsValue }}</text>哈希币
					</view>
					<!-- <view class="all-goods-btn">
						<image :src="bckImg" mode="aspectFill"></image>
						<text>全部商品</text>
					</view> -->
				</view>
			</view>
			<!-- 参与热度 -->
			<view class="all-goods" v-if="userList && userList.length">
				<view class="tip-name">
					<image :src="allgoodsTip" mode="widthFix" class="allgoodsImg"></image>
					<text>参与热度</text>
				</view>
				<view class="join-person">
					<view class="join-item" v-for="(item,index) in userList" :key="index">
						<image :src="item.avatar" mode="aspectFill"></image>
						<text>{{ item.username }}</text>
					</view>
				</view>
			</view>
			<!-- 距离开赏还有 -->
			<view class="bottom-fixed" v-if="welfareInfo">
				<view class="reward-time" v-if="isOpenReward">
					已开赏 <text>{{ rewardTime }}</text>
				</view>
				<view class="reward-time" v-else>
					距开赏<text>{{countDownDay}}天{{countDownHours|time_filter_count_down}}时{{countDownMinutes|time_filter_count_down}}分{{countDownSeconds|time_filter_count_down}}秒</text>
				</view>
				<view class="btn">
					<image :src="selectedImg" mode="widthFix" class="selected-img"></image>
					<view class="password-icon">
						<image :src="passwordIcon" mode="aspectFill" v-if="welfareInfo.type == 2"></image>
						<text @click="openHome">{{ welfareInfo.type == 2 ? '输入密码' : '进入房间'}}</text>
					</view>
				</view>
			</view>
		</template>
		
		<comfirmMsg ref="homes" :isType="true" :msg="msg"></comfirmMsg>
		<welfareHome ref="welfare" :homesType="true" @postRollJoin="postRollJoin"></welfareHome>
	</view>
</template>

<script>
	import baseUrl from '@/utils/siteInfo';
	import {
		rollInfo,
		rollJoin
	} from '@/api/welfare.js';
	import welfareHome from '@/components/popup-comfirm-msg/welfare-home.vue';
	export default {
		data() {
			return {
				benefitPic: baseUrl.imgroot + 'static/images/welfare/benefit-pic.png',
				activityImg: baseUrl.imgroot + 'static/images/welfare/activity-img.png',
				allgoodsTip: baseUrl.imgroot + 'static/images/welfare/all-goods-tip.png',
				goodsItemBox: baseUrl.imgroot + 'static/images/welfare/goods-item-box.png',
				bckImg: baseUrl.imgroot + 'static/images/welfare/btn2.png',
				selectedImg: baseUrl.imgroot + 'static/images/wareHouse/btn-bck.png',
				passwordIcon: baseUrl.imgroot + 'static/images/welfare/password-icon.png',
				homeId: '',
				welfareInfo: null,
				rewardTime: '',
				goodsList: [],
				allGoodsValue: 0,
				userList: [],
				countDownInterval: '',
				countDownDay: 0,
				countDownHours: 0,
				countDownMinutes: 0,
				countDownSeconds: 0,
				isOpenReward: '',
				msg: '',
			}
		},
		components: {
			welfareHome
		},
		watch: {
			rewardTime: {
				handler(newVal) {
					this.countDown()
				},
				deep: true,
				immediate: true
			}
		},
		filters: {
			time_filter_count_down(value) {
				if (value < 10 && value >= 0) {
					return '0' + value
				}
				return value
			}
		},
		methods: {
			openHome(){
				if(this.welfareInfo.type == 2){
					this.$refs.welfare.openBtn(); // 输入房间号
				}else if(this.welfareInfo.type == 1){
					// this.$refs.homes.openBtn(); // 直接加入
					this.postRollJoin('');
				}
			},
			countDown() {
				let time = (+new Date(this.rewardTime) - +new Date()) / 1000;
				clearInterval(this.countDownInterval)
				if(time < 0){
					this.isOpenReward = true;
				}else{
					this.countDownInterval = setInterval(() => {
						this.countDownDay = parseInt(time / 60 / 60 / 24)
						this.countDownHours = parseInt(time / 60 / 60 % 24)
						this.countDownMinutes = parseInt(time / 60 % 60)
						this.countDownSeconds = parseInt(time % 60 % 60)
						if (time > 0) {
							time--
						} else {
							clearInterval(this.countDownInterval)
						}
					}, 1000)
				}
			},
			// rollJoin 加入福利房
			async postRollJoin(value){
				try{
					uni.showLoading({
						title: '申请加入中',
						mask: true
					});
					let res = await rollJoin({
						id: this.homeId,
						password: value
					});
					uni.hideLoading();
					if(res.code == 0){
						this.getRollInfo();
					}
					setTimeout(() => {
						this.msg = res.msg;
						this.$refs.homes.openBtn()
					}, 50)
				}catch(e){
					uni.hideLoading()
					//TODO handle the exception
				}
			},
			// 福利房详情
			async getRollInfo(){
				try{
					uni.showLoading({
						title: '加载中',
						mask: true
					});
					let { code, data } = await rollInfo({
						id: this.homeId
					});
					uni.hideLoading()
					if(code == 0){
						this.welfareInfo = {
							type: data.type,
							username: data.username,
							avatar: data.avatar,
							title: data.title,
							hot: data.hot,
							desc: data.desc
						};
						this.rewardTime = data.reward_time;
						this.goodsList = data.goods;
						this.userList = data.user;
						this.allGoodsValue = this.goodsList.reduce((pre, item) => {
							pre += parseInt(Number(item.recovery_price) * 100);
							return pre
						}, 0) / 100;
					}
				}catch(e){
					uni.hideLoading()
					//TODO handle the exception
				}
			}
		},
		onLoad(options) {
			const params = JSON.parse(decodeURIComponent(options.params));
			this.homeId = params.id;
			this.getRollInfo();
		}
	}
</script>

<style scoped lang="scss">
@import './home-detail.scss';
</style>
