<template>
	<el-dialog
		:title="titleMap[mode]"
		v-model="visible"
		:width="800"
		destroy-on-close
		@closed="$emit('closed')"
		:close-on-click-modal="false"
	>
		<el-form
			:model="form"
			label-width="120px"
			:rules="rules"
			ref="ruleForm"
			:disabled="mode == 'show'"
		>
			<el-form-item label="优惠券名称" prop="name">
				<el-input v-model="form.name" style="width: 500px"></el-input>
			</el-form-item>
			<el-form-item label="优惠券类型" prop="type">
				<el-radio :label="1" v-model="form.type">满减券</el-radio>
				<el-radio :label="2" v-model="form.type">折扣券</el-radio>
				<el-radio :label="3" v-model="form.type">邮费抵扣券</el-radio>
			</el-form-item>
			<el-form-item
				label="优惠券面额"
				prop="amount"
				v-if="form.type == 1"
			>
				<el-input
					v-model="form.amount"
					style="width: 500px"
					type="number"
				></el-input>
				&nbsp;&nbsp; 元
			</el-form-item>
			<el-form-item
				label="优惠折扣"
				prop="discount"
				v-if="form.type == 2"
			>
				<el-input
					v-model="form.discount"
					style="width: 500px"
					type="number"
					placeholder="例如：0.8 或者 0.95"
				></el-input>
				&nbsp;&nbsp;
				<span style="font-size: 12px">例如:8折填0.8</span>
			</el-form-item>
			<el-form-item
				label="最多优惠"
				prop="max_discount_amount"
				v-if="form.type == 2"
			>
				<el-input
					v-model="form.max_discount_amount"
					style="width: 500px"
					type="number"
				></el-input>
				&nbsp;&nbsp; 元
				<span style="color: #f56c6c">(数值大于0则为设置有效)</span>
			</el-form-item>
			<el-form-item label="发放数量限制" prop="is_limit_num">
				<el-radio :label="1" v-model="form.is_limit_num">限量</el-radio>
				<el-radio :label="2" v-model="form.is_limit_num"
					>不限量</el-radio
				>
			</el-form-item>
			<el-form-item label="发放数量" v-if="form.is_limit_num == 1">
				<el-input
					v-model="form.total_num"
					style="width: 500px"
					type="number"
				></el-input>
			</el-form-item>
			<el-form-item label="最多领取数量" prop="max_receive_num">
				<el-input
					v-model="form.max_receive_num"
					style="width: 500px"
					type="number"
				></el-input>
			</el-form-item>
			<el-form-item label="使用门槛" prop="is_threshold">
				<el-radio :label="1" v-model="form.is_threshold"
					>有门槛</el-radio
				>
				<el-radio :label="2" v-model="form.is_threshold"
					>无门槛</el-radio
				>
			</el-form-item>
			<el-form-item label="订单满" v-if="form.is_threshold == 1">
				<el-input
					v-model="form.threshold_amount"
					style="width: 500px"
					type="number"
				></el-input>
				&nbsp;&nbsp; 元可以使用
			</el-form-item>
			<el-form-item label="有效期类型" prop="validity_type">
				<el-radio :label="1" v-model="form.validity_type"
					>固定时间</el-radio
				>
				<el-radio :label="2" v-model="form.validity_type"
					>领取之日起</el-radio
				>
			</el-form-item>
			<el-form-item
				label="有效期至"
				v-if="form.validity_type == 1"
				style="width: 620px"
			>
				<el-date-picker
					v-model="form.datetime_range"
					type="datetimerange"
					range-separator="至"
					start-placeholder="开始日期"
					end-placeholder="结束日期"
					value-format="YYYY-MM-DD hh:mm:ss"
				>
				</el-date-picker>
			</el-form-item>
			<el-form-item label="领取之日起" v-if="form.validity_type == 2">
				<el-input
					v-model="form.receive_useful_day"
					style="width: 500px"
					type="number"
				></el-input>
				&nbsp;&nbsp; 天内有效
			</el-form-item>
			<!--<el-form-item label="活动盲盒" prop="join_blindbox">
				<el-radio :label="1" v-model="form.join_blindbox"
					>全部盲盒参与</el-radio
				>
				<el-radio :label="2" v-model="form.join_blindbox"
					>指定盲盒参与</el-radio
				>
			</el-form-item>-->
			<el-form-item label="活动盲盒" v-if="form.join_blindbox == 2">
				<el-button
					type="primary"
					icon="el-icon-plus"
					size="small"
					@click="ckBlindbox"
					>选择盲盒</el-button
				>
				<el-table :data="form.selectedGoods" style="width: 100%">
					<el-table-column
						prop="name"
						width="250px"
						:show-overflow-tooltip="true"
						label="盲盒名"
					>
					</el-table-column>
					<el-table-column prop="price" label="价格">
					</el-table-column>
					<el-table-column fixed="right" label="操作">
						<template #default="scope">
							<el-button
								@click="handleDel(scope.$index)"
								type="text"
								size="small"
								>删除</el-button
							>
						</template>
					</el-table-column>
				</el-table>
			</el-form-item>
			<el-form-item>
				<el-button
					type="primary"
					:loading="isSaveing"
					@click="submit('ruleForm')"
					>立即创建</el-button
				>
			</el-form-item>
		</el-form>
	</el-dialog>

	<el-dialog
		title="盲盒选择"
		v-model="dialogVisible"
		destroy-on-close
		width="1000px"
	>
		<blindbox
			:selectedgoods="selectedGoods"
			@handleSelected="handleSelected"
			:goods-type="2"
		></blindbox>
	</el-dialog>
</template>

<script>
import blindbox from "@/components/multiBlindbox";

export default {
	emits: ["success", "closed"],
	components: {
		blindbox,
	},
	data() {
		return {
			mode: "add",
			titleMap: {
				add: "添加优惠券",
				edit: "编辑优惠券",
				show: "查看",
			},
			isSaveing: false,
			dialogVisible: false,
			visible: false,
			allreadySelected: [], // 已经选择的商品id
			form: {
				name: "",
				type: 1,
				discount: 0,
				amount: 0,
				max_discount_amount: -1,
				is_limit_num: 2,
				total_num: 0,
				max_receive_num: 0,
				is_threshold: 2,
				threshold_amount: 0,
				validity_type: 2,
				datetime_range: "",
				join_blindbox: 1,
				receive_useful_day: 7,
				selectedGoods: [], // 已经选择的商品
			},
			rules: {
				name: [
					{
						required: true,
						message: "请输入优惠券名称",
						trigger: "blur",
					},
				],
				type: [
					{
						required: true,
						message: "请选择优惠券类型",
						trigger: "blur",
					},
				],
				amount: [
					{
						required: true,
						message: "请输入优惠券面额",
						trigger: "blur",
					},
				],
				is_limit_num: [
					{
						required: true,
						message: "请输入发放数量限制",
						trigger: "blur",
					},
				],
				max_receive_num: [
					{
						required: true,
						message: "请输入最多领取张数",
						trigger: "blur",
					},
				],
				is_threshold: [
					{
						required: true,
						message: "请输入使用门槛",
						trigger: "blur",
					},
				],
				validity_type: [
					{
						required: true,
						message: "请输入有效期类型",
						trigger: "blur",
					},
				],
				join_blindbox: [
					{
						required: true,
						message: "请输入活动商品",
						trigger: "blur",
					},
				],
				discount: [
					{ required: true, message: "请输入折扣", trigger: "blur" },
				],
			},
		};
	},
	mounted() {},
	methods: {
		// 显示
		open(mode = "add") {
			this.mode = mode;
			this.visible = true;
			return this;
		},
		// 表单提交方法
		submit() {
			this.$refs.ruleForm.validate(async (valid) => {
				if (valid) {
					this.isSaveing = true;
					var res;
					if (this.mode == "add") {
						res = await this.$API.coupon.add.post(this.form);
					} else {
						res = await this.$API.coupon.edit.post(this.form);
					}
					this.isSaveing = false;
					if (res.code == 0) {
						this.$emit("success", this.form, this.mode);
						this.visible = false;
						this.$message.success(res.msg);
					} else {
						this.$message.error(res.msg);
					}
				} else {
					return false;
				}
			});
		},
		// 表单注入数据
		setData(data) {
			this.form.name = data.name;
			this.form.type = data.type;
			this.form.discount = data.discount;
			this.form.amount = data.amount;
			this.form.max_discount_amount = data.max_discount_amount;
			this.form.is_limit_num = data.is_limit_num;
			this.form.total_num = data.total_num;
			this.form.max_receive_num = data.max_receive_num;
			this.form.is_threshold = data.is_threshold;
			this.form.threshold_amount = data.threshold_amount;
			this.form.validity_type = data.validity_type;
			this.form.datetime_range = [data.start_time, data.end_time];
			this.form.join_blindbox = data.join_blindbox;
			this.form.receive_useful_day = data.receive_useful_day;
			data.blindbox.forEach((item) => {
				this.form.selectedGoods.push(JSON.parse(item.blindbox_info));
			});
		},
		// 确认选择盲盒
		handleSelected(newSelected) {
			// 如果已经选择了,则需要去重
			if (this.form.selectedGoods.length > 0) {
				newSelected.forEach((item) => {
					if (this.allreadySelected.indexOf(item.id) == -1) {
						this.form.selectedGoods.push({
							id: item.id,
							price: item.price,
							name: item.name,
						});

						this.allreadySelected.push(item.id);
					}
				});
			} else {
				let data = [];
				newSelected.forEach((item) => {
					data.push({
						id: item.id,
						price: item.price,
						name: item.name,
					});

					this.allreadySelected.push(item.id);
				});

				this.form.selectedGoods = data;
			}

			this.dialogVisible = false;
		},
		// 选择盲盒
		ckBlindbox() {
			this.dialogVisible = true;
		},
		// 删除选择的盲盒
		handleDel(index) {
			this.form.selectedGoods.splice(index, 1);
			this.allreadySelected.splice(index, 1);
		},
	},
};
</script>

<style scoped></style>
