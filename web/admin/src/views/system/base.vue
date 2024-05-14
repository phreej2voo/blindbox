<template>
	<el-main>
		<el-card shadow="never">
			<el-tabs v-model="activeName">
				<el-tab-pane label="基础配置" name="first">
					<el-form ref="form" :model="form" label-width="160px">
						<el-form-item label="站点是否开启" style="width: 500px">
							<el-radio label="1" v-model="form.web_open"
								>开启</el-radio
							>
							<el-radio label="2" v-model="form.web_open"
								>关闭</el-radio
							>
						</el-form-item>
						<el-form-item label="哈希币比例" style="width: 500px">
							<el-input
								v-model="form.change_ratio"
								type="number"
							></el-input>
							<span
								style="
									color: #f56c6c;
									font-size: 12px;
									margin-left: 10px;
								"
								>1 元 = {{ form.change_ratio }} 哈希币</span
							>
						</el-form-item>
						<el-form-item label="用户等级开启" style="width: 500px">
							<el-radio label="1" v-model="form.level_open"
								>开启</el-radio
							>
							<el-radio label="2" v-model="form.level_open"
								>关闭</el-radio
							>
						</el-form-item>
						<el-form-item label="经验值比例" style="width: 500px">
							<el-input
								v-model="form.experience_ratio"
								type="number"
							></el-input>
							<span
								style="
									color: #f56c6c;
									font-size: 12px;
									margin-left: 10px;
								"
								>1 元 = {{ form.experience_ratio }} 经验</span
							>
						</el-form-item>
						<el-form-item label="直播配置" style="width: 500px">
							<el-input
								v-model="form.zhibo_config"
							></el-input>
						</el-form-item>
						<el-form-item label="一级分销" style="width: 500px">
							<el-input
								v-model="form.one_promotion"
							></el-input>
						</el-form-item>
						<el-form-item label="二级分销" style="width: 500px">
							<el-input
								v-model="form.two_promotion"
							></el-input>
						</el-form-item>
						<el-form-item label="客服微信" style="width: 500px">
							<el-image
								:src="form.kefu_wechat"
								style="width: 100px; height: 100px"
							></el-image>
							<el-button
								style="margin-left: 50px"
								@click="showAttachment"
								>更改</el-button
							>
						</el-form-item>
						<el-form-item style="margin-top: 50px">
							<el-button
								type="primary"
								@click="Submit"
								style="width: 200px"
								>保存</el-button
							>
						</el-form-item>
					</el-form>
				</el-tab-pane>
			</el-tabs>
		</el-card>

		<el-dialog
			title="选择资源"
			v-model="dialogShow"
			:width="1200"
			destroy-on-close
		>
			<Attachment
				:select-num="1"
				@selectedFiles="selectedBatchImg"
			></Attachment>
		</el-dialog>
	</el-main>
</template>

<script>
import Attachment from "@/components/attachment";

export default {
	name: "appletSettingIndex",
	components: {
		Attachment,
	},
	data() {
		return {
			activeName: "first",
			form: {
				change_ratio: "",
				kefu_wechat: "",
				web_open: "",
				level_open: 1,
				experience_ratio: 1,
				zhibo_config: "",
				one_promotion: "",
				two_promotion: "",
			},
			dialogShow: false,
		};
	},
	mounted() {
		this.getBaseConf();
	},
	methods: {
		async getBaseConf() {
			let res = await this.$API.system.base.get();
			this.form = res.data;
		},
		// 保存
		async Submit() {
			let res = await this.$API.system.baseSave.post(this.form);
			if (res.code == 0) {
				this.$message.success(res.msg);
			} else {
				this.$message.error(res.msg);
			}
		},
		// 显示图片选择组件
		showAttachment() {
			this.dialogShow = true;
		},
		selectedBatchImg(img) {
			this.form.kefu_wechat = img[0].url;

			this.dialogShow = false;
		},
	},
};
</script>
