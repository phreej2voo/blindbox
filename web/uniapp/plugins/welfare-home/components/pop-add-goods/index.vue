<template>
	<uni-popup ref="popAddGoods" type="center">
		<view class="pop-add-goods">
			<view class="add-title">
				<text>选择商品</text>
				<uni-icons class="icons" @click="close" type="close" size="30" color="#82FF80"></uni-icons>
			</view>
			<view class="card-item-box" v-if="ownMaterials && ownMaterials.length">
				<block v-for="(item, index) in ownMaterials" :key="index">
					<view class="card-item" :class="item.checked? 'selected-item' : ''" @click="selectedItem(item)">
						<!-- <view class="goods-kinds">
							A赏
						</view> -->
						<view class="crads-img">
							<image :src="item.goods_image" mode="aspectFill"></image>
						</view>
						<view class="cards-info">
							<view class="cards-kinds">
								<view class="checked-btn">
									<text v-if="item.checked"></text>
								</view>
								<view class="name">{{ item.goods_name }}</view>
							</view>
							<view class="cards-value">
								<view class="decompose">
									{{ item.recovery_price }}哈希币
								</view>
								<view class="slected-num">
									选中{{ item.checked? item.selectedNum : '0'}}个
								</view>
							</view>
						</view>
					</view>
				</block>
			</view>
			<view class="none-material" v-else>
				<empty></empty>
			</view>
			<!-- 总共选中 -->
			<view class="total-selected">
				选中 <text>{{ totalSlectedNum }}</text>个 <br />
				价值 <text>{{ totalSlectedPrice }}</text> 哈希币
			</view>
			<view class="confirm-selected" @click="verification">
				<image :src="confirmBtn" mode="aspectFill"></image>
				<text>确认</text>
			</view>
		</view>
	</uni-popup>
</template>

<script>
	import baseUrl from '@/utils/siteInfo.js';
	export default {
		data(){
			return {
				confirmBtn: baseUrl.imgroot + 'static/images/welfare/confirm-btn.png',
			}
		},
		props: {
			ownMaterials: [Array, String],
			totalSlectedNum: {
				type: [Number, String],
				default: 0
			},
			totalSlectedPrice: {
				type: [Number, String],
				default: 0
			}
		},
		methods: {
			open(){
				this.$refs.popAddGoods.open()
			},
			close() {
				this.$refs.popAddGoods.close()
			},
			selectedItem(el){
				if(el.checked){
					this.$emit('clearStatus', el.goods_id)
				}else{
					this.$emit('changeModelNum', el.num, el.goods_id)
				}
			},
			// 确认
			verification(){
				this.$emit('makeSure');
				this.close();
			}
		}
	}
</script>

<style scoped lang="scss">
@import './index.scss';
</style>