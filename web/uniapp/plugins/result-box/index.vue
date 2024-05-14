<template>  
	<view class="wrap" :style="{'background-image': 'url('+GoodsPoolImg.lotteryBack+')'}">
		<view class="luckbox">
			<view class="head">
				<view :style="{'background-image': 'url('+GoodsPoolImg.rewardBg+')'}" class="head-image">恭喜获得</view>
			</view>
			<view class="ranks" v-if="num != 1">
				<view class="list" v-for="(item,index) in num" :key="index">
					<text>{{index+1}}</text>
					<image v-if="idx > index" :src="winResultList[index].goods_image"></image>
				</view>
			</view>
			<view class="cbox1" :style="{'margin-top': num > 5 ? '180rpx' : '90rpx'}">
				<view class="item_contents" :style="{'background-image': 'url('+GoodsPoolImg.poolBack+')'}">
					<view class="cons_title">商品池</view>
					<view class="cons_list">
						<scroll-view scroll-x="true" class="scroll_list">
							<view class="lists_cons" :style="{transform: 'translateX('+moveX+'rpx)',transition: miao+'s ease-in-out'}">
								<block v-for="(item,index) in goodsDetails" :key="index">
									<view class="detalis" :style="{'background-image':'url('+PoolsImg[item.boxTag.id]+')'}">
										<view class="goods-type">
											<!-- <text :style="{'-webkit-text-stroke': stroke[item.boxTag.id]}">
												{{item.boxTag.name}}
											</text> -->
											<view class="goods-title" :style="{'background-image': 'url('+PoolsResultFont[item.boxTag.id]+')'}">
												{{item.boxTag.name}}
											</view>
										</view>
										<view class="goods-img">
											<image :src="item.goods_image" lazy-load></image>
										</view>
										<view class="goods-detail">
											<view class="goods-name">
												{{item.goods_name}}
											</view>
											<view class="goods-price">
												{{item.price ? '￥' + Number(item.price) : ''}}
											</view>
										</view>
									</view>
									<!-- <view :id="item.goods_id" class="detalis"
										:style="{'background-image':item.boxTag.name=='普通款'?'url('+common+')':'url('+rare+')'}">
										<view class="goods-type"
											:style="{'color':item.boxTag.name=='普通款'?colorObj.common_color:item.boxTag.name=='稀有款'?colorObj.rare_color:item.boxTag.name=='传说款'?colorObj.lore_color:colorObj.epic_color}">
											{{item.boxTag.name}}
										</view>
										<view class="goods-img"
											:style="{'background':item.goods_image.indexOf('.png')!=-1?'#fff':''}">
											<u--image :showLoading="true" :src="item.goods_image" width="170rpx" height="170rpx">
											</u--image>
										</view>
										<view class="goods-detail">
											<view class="goods-name">
												{{item.goods_name}}
											</view>
											<view class="goods-price">
												￥{{item.price}}
											</view>
										</view>
									</view> -->
								</block>
							</view>
						</scroll-view>
					</view>
					<view class="top-rect">
						<view class="top"></view>
					</view>
					<!-- <view class="cons_list_mask"></view> -->
				</view>
			</view>

			<view class="count-class">
				<view class="count-view">
					<text>{{daojishi + '停止滚动'}}</text>
				</view>
			</view>
			<view class="kai" @click="oneTouch">一键开启</view>
		</view>

		<!-- 保底popup -->
		<u-popup :show="guarantShow" mode="center" round="14px" bgColor="rgba(0, 0, 0, 0.5)"
			:safeAreaInsetBottom="false" @close="closeGuarant">
			<view class="out-guarant_box">
				<view class="guarant_box" :style="{'background-image':'url('+MinGuarantee.back+')'}">
					<view class="inner_box">
						<view class="top-area main-center-flex">
							<view class="star-class"></view>
							<view class="guarant-title">触发保底</view>
							<view class="star-class"></view>
						</view>
						<view class="res-area">
							<view v-for="(item,index) in guarantList" :class="guarantList.length <=3 ? 'normal' : 'normal2'"
								:style="{'background-image':'url('+PoolsImgChinese[item.tag_name]+')'}"
								:key="index">
								<!-- :style="{'-webkit-text-stroke': stroke[item.boxTag.id]}" -->
								<view class="goods-type">
									<view class="goods-title" :style="{'background-image': 'url('+PoolsResultFont[item.tag_name]+')'}">
										<text>{{item.tag_name}}</text>
									</view>
								</view>
								<view class="goods-img">
									<image :src="item.goods_image"></image>
								</view>
								<view class="goods-detail">
									<view class="goods-name">
										{{item.goods_name}}
									</view>
									<view class="goods-price">
										{{item.goods_price ? '￥' + Number(item.goods_price) : ''}}
									</view>
								</view>
							</view>
						</view>
						<view class="bottom-area main-center-flex">
							恭喜获得<view>{{guarantList.length ? guarantList[0].tag_name : ''}}</view>商品
						</view>
					</view>
				</view>
			</view>
		</u-popup>

		<!-- 结果popup -->
		<uni-popup ref="popup" type="center" @change="changeState">
			<view class="result" :style="{'background-image': 'url('+PoolsResultBack.back+')'}">
				<view class="rbox">
					<view class="tit" :style="{'background-image': 'url('+PoolsResultBack.title+')'}">
						恭喜获得
						<image :src="GoodsShareClose" class="close-img" mode="aspectFill" @click="close"></image>
					</view>
					<view class="goodsBox">
						<view v-if="resultList.length <= 5" class="cbox" :class="cboxHeight[resultList.length]">
							<view v-for="(item,index) in resultList1" :class="resultList.length <=3 ? 'normal' : 'normal2'"
								:style="{'background-image':'url('+PoolsImgChinese[item.tag_name]+')'}"
								:key="index">
								<!-- :style="{'-webkit-text-stroke': stroke[item.boxTag.id]}" -->
								<view class="goods-type">
									<view v-if="item.source == 2" class="guarant-log"
										:style="{'background-image': 'url('+GitLog+')'}">
										<view>赠</view>
									</view>
									<view v-else class="empty-view"></view>
									<view class="goods-title" :style="{'background-image': 'url('+PoolsResultFont[item.tag_name]+')'}">
										{{item.tag_name}}
									</view>
								</view>
								<view class="goods-img">
									<image :src="item.goods_image"></image>
								</view>
								<view class="goods-detail">
									<view class="goods-name">
										{{item.goods_name}}
									</view>
									<view class="goods-price">
										{{item.goods_price ? '￥' + Number(item.goods_price) : ''}}
									</view>
								</view>
							</view>
						</view>
						<view v-else class="cbox2">
							<view class="cbox2-item">
								<view v-for="(item,index) in resultList1" class="normal2"
									:style="{'background-image':'url('+PoolsImgChinese[item.tag_name]+')'}"
									:key="index">
									<view class="goods-type">
										<view v-if="item.source == 2" class="guarant-log"
											:style="{'background-image': 'url('+GitLog+')'}">
											<view>赠</view>
										</view>
										<view v-else class="empty-view"></view>
										<view class="goods-title" :style="{'background-image': 'url('+PoolsResultFont[item.tag_name]+')'}">
											{{item.tag_name}}
										</view>
									</view>
									<view class="goods-img">
										<image :src="item.goods_image"></image>
									</view>
									<view class="goods-detail">
										<view class="goods-name">
											{{item.goods_name}}
										</view>
										<view class="goods-price">
											{{item.goods_price ? '￥' + Number(item.goods_price) : ''}}
										</view>
									</view>
								</view>
							</view>
							<view class="cbox2-item2">
								<view v-for="(item,index) in resultList2" class="normal2"
									:style="{'background-image':'url('+PoolsImgChinese[item.tag_name]+')'}"
									:key="index">
									<view class="goods-type">
										<view v-if="item.source == 2" class="guarant-log"
											:style="{'background-image': 'url('+GitLog+')'}">
											<view>赠</view>
										</view>
										<view v-else class="empty-view"></view>
										<view class="goods-title" :style="{'background-image': 'url('+PoolsResultFont[item.tag_name]+')'}">
											{{item.tag_name}}
										</view>
									</view>
									<view class="goods-img">
										<image :src="item.goods_image"></image>
									</view>
									<view class="goods-detail">
										<view class="goods-name">
											{{item.goods_name}}
										</view>
										<view class="goods-price">
											{{item.goods_price ? '￥' + Number(item.goods_price) : ''}}
										</view>
									</view>
								</view>
							</view>
							<view class="cbox2-item2">
								<view v-for="(item,index) in resultList3" class="normal2"
									:style="{'background-image':'url('+PoolsImgChinese[item.tag_name]+')'}"
									:key="index">
									<view class="goods-type">
										<view v-if="item.source == 2" class="guarant-log"
											:style="{'background-image': 'url('+GitLog+')'}">
											<view>赠</view>
										</view>
										<view v-else class="empty-view"></view>
										<view class="goods-title" :style="{'background-image': 'url('+PoolsResultFont[item.tag_name]+')'}">
											{{item.tag_name}}
										</view>
									</view>
									<view class="goods-img">
										<image :src="item.goods_image"></image>
									</view>
									<view class="goods-detail">
										<view class="goods-name">
											{{item.goods_name}}
										</view>
										<view class="goods-price">
											{{item.goods_price ? '￥' + Number(item.goods_price) : ''}}
										</view>
									</view>
								</view>
							</view>
						</view>
					</view>
					<view class="footer">
						<view v-if="!isPlay" class="receive common" @click="goToWarehouse">
							去仓库查收
						</view>
						<view v-if="!isNewer" class="once-more common" @click="onceMore">
							再来一次
						</view>
					</view>
					<!-- <view class="bottom" v-if="!isPay">
						<view class="btit" v-if="!isPay">*试玩结果仅供参考</view>
						<view class="btn" @click="jixu" v-if="istwo">
							继续开盒 x10
						</view>
						<view class="btn" @click="$.to('/pages/home/kaixiang?id='+boxId)" v-else>
							来发真的试试
						</view>
					</view>

					<view class="bottom" v-if="isPay">
						<view class="btit" v-if="!isPay">*试玩结果仅供参考</view>
						<view class="btn" @click="jixu" v-if="istwo">
							继续开盒 x10
						</view>
						<view class="btn" @click="shou" v-else>
							全部收下
						</view>
					</view> -->
				</view>
			</view>
		</uni-popup>
	</view>
</template>  
  
<script>
	import baseUrl from '@/utils/siteInfo.js';
	import { mapGetters, mapState } from 'vuex';
	import { GoodsPoolImg, PoolsImg, PoolsImgChinese, PoolsResultBack, GoodsShareClose, PoolsResultFont, MinGuarantee, GitLog } from '../../utils/objectValue';
	import {get_blind_detail} from '@/api/goods.js';

	export default {  
		data() {  
			return {
				GoodsPoolImg,
				PoolsImgChinese,
				PoolsImg,
				PoolsResultBack,
				GoodsShareClose,
				PoolsResultFont,
				MinGuarantee,
				GitLog,
				// 1-普通 2-传说 3-史诗 4-稀有
				stroke: {
					1: '1.2px #1D5DED',
					2: '1.2px #841DC1',
					3: '1.2px #7A26F0',
					4: '1.2px #004785',
				},
				common: baseUrl.imgroot + '/front/border.png',
				rare: baseUrl.imgroot + '/front/border-little.png',
				goodsDetails: [],
				winningId: '', //中奖id
				count: 5,
				winningIndex: 0, //中奖索引
				moveX: 0,
				miao: 5,
				isResultShow: false, // 是否开启结果popup
				boxId: '',
				num: '', //几抽
				idx: 0, //当前抽的第几发
				cboxHeight: {
					1: 'cbox-1',
					2: 'cbox-1',
					3: 'cbox-3',
					4: 'cbox-3',
					5: 'cbox-5'
				},
				winResultList: [], // 中奖结果 source=1
				resultList: [], // 抽中的全部商品
				resultList1: [],
				resultList2: [],
				resultList3: [],
				yichou: [], //当前已经抽到的商品
				daojishi: 5, //倒计时
				ding: '', //倒计时定时器
				isKai: false, //是否一键开启
				istwo: false, //是否有二抽
				isPay: false, //是否正式抽
				ooid: '',
				twoPay: {}, //支付二十抽全部数据
				endTime: '',
				colorObj: {
					common_color: '',
					rare_color: '',
					lore_color: '',
					epic_color: ''
				},
				isNewer: false,
				isPlay: false,
				guarantShow: false,
				hasGuarant: false,
				guarantList: []
			}  
		},
		/*watch: {
			goodsDetails: {
				handler(news) {
					if(news.length) {
						this.preMove();
					}
				},
				immediate: true
			}
		},*/
		computed: {
			...mapGetters('phone', {
				phoneHieght: 'phoneHieght'
			})
		},
		onLoad(option) {
			console.log('option-', option, this.$goods.getPayParams());
			const {id, num, isnew = false, isPlay = false} = option;
			this.isNewer = isnew;
			this.isPlay = Boolean(isPlay);
			this.boxId = id; // 盲盒id
			if (num == 20) {
				this.num = 10
				this.istwo = true
			} else {
				this.num = Number(num)
			}
			if(!Boolean(isPlay)) {
				const data1 = this.$store.getters['goods/getPoolsDetails'];
				const {goods_list = [], guarantRes} = this.$store.getters['goods/getGoodsDetails'];
				this.winResultList = goods_list.filter(item => {
					return item.source == 1;
				});
				if(guarantRes && guarantRes.length) {
					this.hasGuarant = true;
					this.guarantList = guarantRes;
				} else {
					this.hasGuarant = false;
				}
				this.getBox(data1);
				this.getData(goods_list);
			} else {
				// 试玩
				this.freePlay(id);
			}
		},
		methods: {
			// 奖池
			getBox(data) {
				const {poolList = [], probabilityList = []} = data;
				this.processingStyles(probabilityList);
				let arr = [...poolList]
				let jia = []
				arr.forEach((item, index) => {
					jia.push({
						...item,
						goods_id: 10000 + index
					})
				})
				let add = jia.concat()
				jia = jia.concat(arr)
				if (jia.length < 40) {
					for (let i = 0; i < 40; i++) {
						if (jia.length >= 40) {
							break
						}
						jia.unshift(...add)
					}
				}
				jia = jia.concat(add, add)
				console.log('result-', jia);
				this.goodsDetails = jia;
			},
			// 获取抽奖结果
			getData(data) {
				// 保证h5下奖池数据渲染完成后进行抽奖
				this.$nextTick(() => {
					this.changeBg([...data])
				})
			},
			changeBg(data) {
				this.resultList = data;
				if(data.length <= 5) {
					this.resultList1 = data;
				} else {
					const last = (data.length - 2) % 3;
					// 第一行两个 后面三个每行
					const arr1 = data.slice(0, 2);
					const arr2 = data.slice(2, data.length - last);
					this.resultList1 = arr1;
					this.resultList2 = arr2;
					if(last > 0) {
						const arr3 = data.slice(data.length - last);
						this.resultList3 = arr3;
					}
				}
				this.state();
			},
			// 开始抽奖
			state() {
				console.log('开始抽奖-', this.idx, this.winResultList[this.idx]);
				this.winningId = this.winResultList[this.idx].goods_id || '';
				console.log('抽中的id-', this.winningId);
				this.daojishi = 5
				this.preMove()
				this.ding = setInterval(() => {
					this.daojishi -= 1
					if (this.daojishi <= 0) {
						this.daojishi = 0
					}
				}, 1000)
				this.end()
			},
			end() {
				this.endTime = setTimeout(() => {
					clearInterval(this.ding)
					this.yichou = this.winResultList[this.idx]
					this.idx++
					if (this.idx >= this.winResultList.length) {
						if(this.hasGuarant) {
							this.openGuarant();
							setTimeout(() => {
								this.closeGuarant();
								this.openPopup()
							}, 2000)
						} else {
							this.openPopup()
						}
						return
					}
					setTimeout(() => {
						this.miao = 0
						this.moveX = 0
					}, 900)
					if (!this.isKai) {
						setTimeout(() => {
							this.miao = 5
							this.state()
						}, 1000)
					}
				}, 5000)
			},
			async freePlay(id) {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				const {code, data} = await get_blind_detail({id});
				uni.hideLoading();
				if (code == 0) {
					const {list = [], probability = []} = data;
					const temp = [...list];

					this.getBox({poolList: list, probabilityList: probability});
					if(temp.length < this.num) {
						const gapNum = this.num - temp.length;
						const obj = temp[temp.length - 1];
						for(let i = 0; i < gapNum; i++) {
							temp.push({
								...obj,
								goods_id: obj.goods_id + 100
							});
						}
					}
					const arr = temp.slice(0, this.num);
					const resList = arr.map(item => {
						return {
							goods_id: item.goods_id,
							goods_image: item.goods_image,
							goods_name: item.goods_name,
							goods_price: item.price,
							tag_name: item.boxTag.name
						};
					});
					this.winResultList = [...resList];
					this.getData(resList);
				}
			},
			openPopup() {
				this.$refs.popup.open();
			},
			// 拿到数据之后执行 需要保证中奖数据不在最后5条中
			preMove() {
				this.winningIndex = this.goodsDetails.findIndex(item => item.goods_id == this.winningId)
				const randomNum = Math.floor(Math.random() * (260 - 120 + 1)) + 120; // 随机滚动补差数据确保滚动在中奖条目范围内
				this.moveX = (-340 * this.winningIndex - 1) + randomNum + 50 //282 宽度258+右边距24 318+22
				console.log('this.winningIndex----', this.winningIndex, this.moveX, this.miao)
			},
			processingStyles(styleList) {
				for (let i = 0; i < styleList.length; i++) {
					if (styleList[i].tag == '稀有款') {
						this.colorObj.rare_color = styleList[i].color
					} else if (styleList[i].tag == '史诗款') {
						this.colorObj.epic_color = styleList[i].color
					} else if (styleList[i].tag == '传说款') {
						this.colorObj.lore_color = styleList[i].color
					} else {
						this.colorObj.common_color = styleList[i].color
					}
				}
			},
			changeState(e) {
				const {show = false} = e;
			},
			// 一键开启
			oneTouch() {
				this.isKai = true
				this.daojishi = 0
				this.miao = 0
				this.moveX = 0
				this.$forceUpdate()
				if(this.hasGuarant) {
					this.openGuarant();
					setTimeout(() => {
						this.closeGuarant();
						this.openPopup();
					}, 2000)
				} else {
					this.openPopup();
				}
			},
			openGuarant() {
				this.guarantShow = true;
			},
			closeGuarant() {
				this.guarantShow = false;
			},
			close() {
				uni.navigateBack({
					delta: 1, // 返回层数，2则上上页
					success: () => {
						const page = getCurrentPages().pop();
						console.log('page-', page);
						if (page) {
							page.onLoad(page.options); // 执行上个页面的方法
						};
					}
				});
			},
			// 跳转仓库
			goToWarehouse() {
				this.$store.commit('param/setType', 'box')
				this.$store.commit('param/setStatus', '1')
				uni.switchTab({
					url: '/pages/warehouse/index',
					success: function(res) {
						getApp().globalData.paramsData = {
							type: 'box',
							currentIndex: 0
						};
					}
				})
			},
			// 再来一次 返回上一级并携带参数
			onceMore(){
				const pages = getCurrentPages();
				console.log('pages-', pages);
				console.log('payParams-', this.$goods.getPayParams());
				const prevPage = pages[pages.length - 2];
				const params = {
					val: '1',
					isPlay: this.isPlay
				};
				this.$goods.setIsPlayBack(true);

				// #ifdef H5
				prevPage.$vm.testdata = params;
				// #endif

				// #ifdef MP-WEIXIN
				prevPage.setData(params);
				// #endif

				console.log('prevPage-', prevPage);
				uni.navigateBack({
					delta: 1 // 返回层数，2则上上页
				})
			}
		}
	}  
</script>  
  
<style lang="scss" scoped>
	.wrap {
		width: 100%;
		height: 100%;
		background-size: 100% 100%;
	}
	.luckbox {
		width: 100vw;
		height: 100vh;
		overflow: hidden;
		background-size: 100% 100%;
	}

	.head {
		width: 100%;
		text-align: center;
		background-position: center;
		background-size: 564rpx 84rpx;
		display: flex;
		justify-content: center;
		/* #ifdef MP-WEIXIN || APP-PLUS */
		margin-top: 200rpx;
		/* #endif */
	}
	.head-image {
		/* #ifdef MP-WEIXIN */
		width: 564rpx;
		height: 84rpx;
		position: relative;
		/* #endif */
		/* #ifdef APP-PLUS */
		display: inline-block;
		width: 564rpx;
		height: 42rpx !important;
		position: relative;
		/* #endif */
		background-size: 100% 100%;
		text-align: center;
		color: #fff;
		font-size: 90rpx;
		line-height: 90rpx;
		font-family: BTH;
	}

	.luckbox .cbox1 {
		// margin-top: 60rpx;
	}

	.ranks {
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
		/* #ifdef MP-WEIXIN */
		margin-top: 28rpx;
		/* #endif */
		/* #ifdef APP-PLUS */
		margin-top: 40px;
		/* #endif */
		padding: 0 20rpx;
		width: 100%;
		height: 106rpx;
	}

	.ranks .list {
		width: 94rpx;
		height: 96rpx;
		border-radius: 6px;
		opacity: 1;
		border: 1px solid #F7DAFF;
		// border-radius: 16rpx;
		// width: 52rpx;
		// height: 52rpx;
		// border: 1rpx solid #00E1DB;
		position: relative;
		padding: 10rpx;
		margin: 10rpx;
	}

	.ranks .list image {
		width: 100%;
		height: 100%;
	}

	.ranks .list text {
		position: absolute;
		right: -6rpx;
		top: -32rpx;
		color: #F7DAFF;
		font-family: BTH;
		font-size: 32rpx;
	}

	.di {
		margin-top: 100rpx;
	}

	.di .stoppic {
		text-align: center;
		position: relative;
	}

	.di .stoppic image {
		width: 600rpx;
	}

	.di .stoppic text {
		color: #fff;
		font-size: 100rpx;
		font-family: 'myfont';
		position: absolute;
		left: 230rpx;
		top: 4rpx;
	}

	.kai {
		padding: 10rpx 20rpx;
		border-radius: 16rpx;
		// margin-top: 40rpx;
		border: 1rpx solid #fff;
		color: #fff;
		display: inline-block;
		margin-left: 30rpx;
		font-size: 16px;
	}

	.item_contents {
		width: 100%;
		// height: 654rpx;
		height: 674rpx;
		// padding-top: 52rpx;
		background-size: 100% 100%;
		box-sizing: border-box;
		position: relative;
		overflow: hidden;
	}
	.cons_title{
		font-size: 32px;
		font-family: BTH;
		font-weight: 400;
		color: #FFFFFF;
		text-align: center;
		height: 55px;
		line-height: 55px;
	}
	.cons_list {
		display: inline-block;
		// height: calc(100% - 55px - 50px);
		height: 510rpx;
		// padding-left: 32rpx;
		box-sizing: border-box;
		padding-top: 40rpx;
	}

	.scroll_list {
		width: 100%;
		height: 100%;
	}
	.top-rect{
		width: 100%;
		// height: 50px;
		display: flex;
		justify-content: center;
	}
	.top {
		height: 100%;
		border: 10px solid transparent;
		border-bottom: 12px solid #ffffff;
	}
	.lists_cons {
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
	}

	.count-class{
		width: 100%;
		height: 50px;
		margin-top: 20px;
		display: flex;
		justify-content: center;
		align-items: center;
		.count-view{
			width: 140px;
			height: 100%;
			text-align: center;
			font-size: 26px;
			color: #ffffff;
			font-family: BTH;
			background: linear-gradient(260deg, rgba(162,39,244,0.31) 0%, #BC58FF 100%);
			border-radius: 10px;
			opacity: 1;
			border: 1px solid #FFFFFF;
			display: flex;
			justify-content: center;
			align-items: center;
		}
	}
	.receive {
		border: 2rpx solid #fff;
	}
	.once-more {
		background: linear-gradient(to right, #8F09E6, #B546FF);
	}
	.common {
		width: 290rpx;
		height: 100rpx;
		line-height: 100rpx;
		color: #FBF8FF;
		font-size: 28rpx;
		text-align: center;
	}
	.detalis {
		// width: 258rpx;
		// height: 374rpx;
		// width: 230rpx;
		// height: 342rpx;
		width: 318rpx;
		height: 440rpx;
		// margin-right: 24rpx;
		margin-left: 22rpx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		position: relative;
		background-size: 100% 100% !important;
		background-repeat: no-repeat;
		text-align: center;
		.tit {
			display: -webkit-box;
			text-overflow: ellipsis;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			margin-top: 10rpx;
			color: #fff;
			font-size: 24rpx;
			padding: 0 16rpx;
		}
		.goods-type {
			width: 100%;
			height: 80rpx;
			padding: 10rpx 20rpx;
			display: flex;
			justify-content: flex-end;
		}
		.goods-title{
			width: 131rpx;
			height: 45rpx;
			background-size: 100% 100%;
			font-size: 46rpx;
			font-family: BTH;
			text-align: right;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 43rpx;
			// text-shadow: 2px 4px 4px #693AEE;
		}
		.goods-img {
			width: 100%;
			padding: 20rpx;
			height: 240rpx;
			display: flex;
			justify-content: center;
			align-items: center;
			background-color: transparent;
			image{
				width: 200rpx;
				height: 200rpx;
			}
		}
		.goods-detail {
			color: #242424;
			font-size: 24rpx;
			width: 100%;
			height: calc(100% - 80rpx - 240rpx);
			text-align: center;
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 0 24rpx;
		}
		.goods-name {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			text-align: center;
			font-family: Source Han Sans CN;
			font-size: 12px;
			font-family: PingFang SC-Regular, PingFang SC;
			color: #FFFFFF;
		}
		.goods-price {
			color: #FFFFFF;
			margin-top: 10rpx;
			font-size: 20px;
			font-family: BTH;
			font-weight: 400;
		}
	}
	.guarant-res{
		width: 500rpx;
		height: 600rpx;
		// box-shadow: 0px 0px 70px 0px rgba(231, 121, 252, 1);
		box-shadow: 0px 0px 70px 0px #e779fc;
		background-color: rgba(124, 78, 255, 0.37);
		border-radius: 10px;
		// background: radial-gradient(circle at top left, transparent 10px, rgba(124, 78, 255, 0.37) 0) top left;
		// border: 1px solid rgba(247, 207, 255, 1);
		border: 1px solid #f7cfff;
		display: flex;
		flex-direction: column;
		justify-content: center;
		.top-area{
			width: 100%;
			height: 80rpx;
			display: flex;
			justify-content: space-evenly;
			align-items: center;
		}
		.star-class {
			width: 17rpx;
			height: 18rpx;
			background-color: rgba(255, 255, 255, 0.7);
			clip-path: polygon(48% 0, 58% 30%, 98% 35%, 58% 45%, 49% 77%, 38% 44%, 2% 35%, 38% 30%);
		}
		.guarant-title{
			font-family: BTH;
			font-size: 22px;
			color: #ffffff;
		}
		.res-area{
			width: 100%;
			height: calc(100% - 80rpx);
		}
	}
	.out-guarant_box{
		width: 100vw;
		max-height: 100vh;
		background-color: rgba(0, 0, 0, 0.5);
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.guarant_box{
		width: 90vw;
		max-height: 60vh;
		padding: 10rpx 20rpx;
		background-size: 100% 100%;
		-webkit-filter: drop-shadow(0px 0px 70px #e779fc);
		filter: drop-shadow(0px 0px 70px #e779fc);

		.inner_box{
			width: 100%;
			height: 100%;
			// border: 1px solid #f7cfff;
			// box-shadow: 0px 0px 70px 0px #e779fc;
			// background-color: rgba(124, 78, 255, 0.37);
			// -webkit-clip-path: polygon(23% 1%, 1% 17%, 0% 99%, 100% 100%, 100% 17%, 76% 0%);
			// clip-path: polygon(23% 1%, 1% 17%, 0% 99%, 100% 100%, 100% 17%, 76% 0%);
			// border-radius: 50rpx;
			// background: 
			// radial-gradient(circle at top left, transparent 50px, rgba(124, 78, 255, 0.37) 0) top left, 
			// radial-gradient(circle at top right, transparent 50px, rgba(124, 78, 255, 0.37) 0) top right, 
			// radial-gradient(circle at bottom right, transparent 0px, rgba(124, 78, 255, 0.37) 0) bottom right, 
			// radial-gradient(circle at bottom left, transparent 0px, rgba(124, 78, 255, 0.37) 0) bottom left;
			// background-size: 50% 50%;
			// background-repeat: no-repeat;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
		.top-area{
			width: 100%;
			height: 80rpx;
		}
		.star-class {
			width: 17rpx;
			height: 18rpx;
			background-color: rgba(255, 255, 255, 0.7);
			clip-path: polygon(48% 0, 58% 30%, 98% 35%, 58% 45%, 49% 77%, 38% 44%, 2% 35%, 38% 30%);
		}
		.guarant-title{
			width: 220rpx;
			text-align: center;
			font-family: BTH;
			font-size: 26px;
			color: #ffffff;
		}
		.res-area{
			width: 100%;
			height: calc(100% - 200rpx);
			margin-top: 40rpx;
			overflow-y: auto;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-wrap: wrap;
			// height: calc(100% - 80rpx);
		}
		.bottom-area{
			height: 80rpx;
			// padding: 20rpx 0;
			text-align: center;
			line-height: 60rpx;
			font-size: 16px;
			color: #ffffff;
			view{
				padding: 0 4rpx;
				font-size: 26px;
				font-family: BTH;
			}
		}
		.normal {
			// width: 276rpx;
			// height: 410rpx;
			margin-bottom: 20rpx;
			margin-right: 20rpx;
			width: 238rpx;
			height: 330rpx;
			text-align: center;
			background-size: 100% 100% !important;
			.goods-img{
				width: 100%;
				padding: 20rpx;
				height: 160rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: transparent;
				image{
					width: 140rpx;
					height: 140rpx;
				}
			}
			.goods-detail{
				width: 100%;
				height: calc(100% - 70rpx - 160rpx);
				color: #242424;
				font-size: 24rpx;
				text-align: center;
				display: flex;
				flex-direction: column;
				justify-content: center;
				padding: 0 24rpx;
			}
		}
		.normal2 {
			margin-bottom: 20rpx;
			margin-right: 20rpx;
			width: 214rpx;
			height: 297rpx;
			text-align: center;
			background-size: 100% 100% !important;
			.goods-img{
				width: 100%;
				padding: 20rpx;
				height: 130rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: transparent;
				image{
					width: 110rpx;
					height: 110rpx;
				}
			}
			.goods-detail{
				width: 100%;
				height: calc(100% - 70rpx - 130rpx);
				color: #242424;
				font-size: 24rpx;
				text-align: center;
				display: flex;
				flex-direction: column;
				justify-content: center;
				padding: 0 24rpx;
			}
		}
		.goods-type{
			width: 100%;
			height: 70rpx;
			padding: 10rpx 20rpx;
			display: flex;
			justify-content: flex-end;
		}
		.goods-title{
			width: 131rpx;
			height: 45rpx;
			background-size: 100% 100%;
			font-size: 46rpx;
			font-family: BTH;
			text-align: right;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 48rpx;
		}
		.goods-name{
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			text-align: center;
			font-family: Source Han Sans CN;
			font-size: 12px;
			font-family: PingFang SC-Regular, PingFang SC;
			color: #FFFFFF;
		}
		.goods-price{
			color: #FFFFFF;
			margin-top: 10rpx;
			font-size: 20px;
			font-family: BTH;
			font-weight: 400;
		}
	}
	.result {
		width: 100vw;
		height: 100vh;
		background-size: 100% 100%;

		.rbox {
			position: relative;
			display: flex;
			// align-items: space-around;
			justify-content: center;
			flex-direction: column;
			padding-top: 180rpx;
			height: calc(100% - 120rpx);
		}
		.tit {
			position: relative;
			width: 100%;
			height: 100rpx;
			// margin-top: 50px;
			background-size: 100% 100%;
			text-align: center;
			font-size: 28px;
			font-family: BTH;
			font-weight: 400;
			color: #FFFFFF;
			// line-height: 89px;
			text-shadow: 0px 0px 28px rgba(244,190,255,0.72);
			.close-img{
				position: absolute;
				width: 24px;
				height: 24px;
				right: 23px;
				top: 5px;
			}
		}

		.goodsBox {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 750rpx;
			height: calc(100% - 40rpx);
			overflow-y: auto;
			padding: 40rpx 0;
			box-sizing: border-box;
		}
		.footer {
			width: 100%;
			height: 100rpx;
			margin-top: 50px;
			display: flex;
			justify-content: space-around;
		}
		.cbox {
			width: 100%;
			// height: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-wrap: wrap;
		}
		.cbox-1{
			height: 100%;
		}
		.cbox-3{
			height: 715rpx;
		}
		.cbox-5{
			height: 670rpx;
		}
		.cbox2 {
			width: 100%;
			height: 100%;
		}
		.cbox2-item {
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.cbox2-item2 {
			display: flex;
			justify-content: center;
			align-items: center;
			flex-wrap: wrap;
		}
		.normal {
			// width: 276rpx;
			// height: 410rpx;
			margin-bottom: 20rpx;
			margin-right: 20rpx;
			width: 238rpx;
			height: 330rpx;
			text-align: center;
			background-size: 100% 100% !important;
			.goods-img{
				width: 100%;
				padding: 20rpx;
				height: 160rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: transparent;
				image{
					width: 140rpx;
					height: 140rpx;
				}
			}
			.goods-detail{
				width: 100%;
				height: calc(100% - 70rpx - 160rpx);
				color: #242424;
				font-size: 24rpx;
				text-align: center;
				display: flex;
				flex-direction: column;
				justify-content: center;
				padding: 0 24rpx;
			}
		}
		.normal2 {
			margin-bottom: 20rpx;
			margin-right: 20rpx;
			width: 214rpx;
			height: 297rpx;
			text-align: center;
			background-size: 100% 100% !important;
			.goods-img{
				width: 100%;
				padding: 20rpx;
				height: 130rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: transparent;
				image{
					width: 110rpx;
					height: 110rpx;
				}
			}
			.goods-detail{
				width: 100%;
				height: calc(100% - 70rpx - 130rpx);
				color: #242424;
				font-size: 24rpx;
				text-align: center;
				display: flex;
				flex-direction: column;
				justify-content: center;
				padding: 0 24rpx;
			}
		}
		.guarant-log{
			width: 64rpx;
			height: 64rpx;
			background-size: 100% 100%;
			display: flex;
			// justify-content: center;
			// background:: linear-gradient(-45deg, transparent 50%, rgba(132, 50, 246, 0.5) 50%, rgba(132, 50, 246, 0.5) 100%);
			// background: linear-gradient(to top left, transparent 0%, #fff 49.9%, #8432F6 50%, #8432F6 100%);
			view{
				padding-left: 2px;
				font-size: 11px;
				font-family: PingFang SC-Semibold, PingFang SC;
				color: #FFFFFF;
			}
		}
		.empty-view{
			width: 20%;
			height: 100%;
		}
		.goods-type{
			width: 100%;
			height: 70rpx;
			padding: 2rpx 20rpx 10rpx 8rpx;
			display: flex;
			justify-content: space-between;
		}
		.goods-title{
			width: 131rpx;
			height: 45rpx;
			margin-top: 8rpx;
			background-size: 100% 100%;
			font-size: 46rpx;
			font-family: BTH;
			text-align: right;
			font-weight: 400;
			color: #FFFFFF;
			line-height: 48rpx;
		}
		.goods-name{
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			text-align: center;
			font-family: Source Han Sans CN;
			font-size: 12px;
			font-family: PingFang SC-Regular, PingFang SC;
			color: #FFFFFF;
		}
		.goods-price{
			color: #FFFFFF;
			margin-top: 10rpx;
			font-size: 20px;
			font-family: BTH;
			font-weight: 400;
		}
		.bottom {
			width: 750rpx;
			font-family: 'myfont';
			text-align: center;
			position: absolute;
			left: 0;
			bottom: 40rpx;

			.btit {
				color: #fff;
				font-size: 36rpx;
			}
			.btn {
				padding: 20rpx 60rpx;
				background-color: #fff;
				border-radius: 20rpx;
				font-size: 38rpx;
				display: inline-block;
				margin-top: 20rpx;
			}
		}
	}
	/* ::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	} */

	.cons_list_mask {
		width: 100%;
		height: 662rpx;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 99999;
		background-color: rgba(0, 0, 0, 0);
	}
</style>